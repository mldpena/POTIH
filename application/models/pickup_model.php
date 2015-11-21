<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pickup_Model extends CI_Model {

	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('UNABLE_TO_GENERATE_SUMMARY' => 'Unable to generate summary!',
									'UNABLE_TO_DELETE' => 'Unable to delete summary!',
									'UNABLE_TO_SELECT_HEAD' => 'Unable to get summary head details!',
									'UNABLE_TO_SELECT_DETAILS' => 'Unable to get summary details!',
									'SUMMARY_FOR_TODAY_EXISTS' => 'Summary for today already exists!');

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() 
	{
		parent::__construct();

		$this->load->constant('pickup_const');
		
		$this->_current_branch_id 	= (int)$this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= (int)$this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date('Y-m-d H:i:s');
	}

	public function get_pickup_summary_list($param)
	{
		extract($param);

		$response = array();
		$query_data = array();

		$response['rowcnt'] = 0;

		$conditions = "";
		

		if ($branch != \Constants\PICKUP_CONST::ALL_OPTION) 
		{
			$conditions .= " AND PS.`branch_id` = ?";
			array_push($query_data, $branch);
		}

		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT('PS',PS.`reference_number`) LIKE ?";
			array_push($query_data, '%'.$search_string.'%');
		}

		$query = "SELECT PS.`id`, COALESCE(B.`name`, '') AS 'location', 
					CONCAT('PS',PS.`reference_number`) AS 'reference_number', DATE(PS.`entry_date`) AS 'entry_date'
					FROM pickup_summary_head AS PS
					LEFT JOIN branch AS B ON B.`id` = PS.`branch_id` AND B.`is_show` = ".\Constants\PICKUP_CONST::ACTIVE."
					WHERE PS.`is_show` = ".\Constants\PICKUP_CONST::ACTIVE." $conditions";

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($i+1);
				$response['data'][$i][] = array($row->location);
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array(date('m-d-Y', strtotime($row->entry_date)));
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();

		return $response;
	}

	public function generate_pickup_summary()
	{
		$response = array();
		$response['error'] = '';

		$result_summary_for_today = $this->_check_if_summary_exist_today();

		if ($result_summary_for_today->num_rows() > 0)
			throw new Exception($this->_error_message['SUMMARY_FOR_TODAY_EXISTS']);
			
		$result_summary_for_today->free_result();

		$result_reference_number = get_next_number('pickup_summary_head','reference_number',array('entry_date' => $this->_current_date));
		$summary_head_id = $this->encrypt->decode($result_reference_number['id']);

		$query_data = array($summary_head_id, date("Y-m-d", strtotime($this->_current_date)));

		$query = "INSERT INTO pickup_summary_detail(`headid`, `release_head_id`)
					SELECT ?, `id` FROM release_head WHERE DATE(`entry_date`) = ? AND `branch_id` = ".$this->_current_branch_id." AND `is_show` = ".\Constants\PICKUP_CONST::ACTIVE;

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'])
			throw new Exception($this->_error_message['UNABLE_TO_GENERATE_SUMMARY']);	

		return $response;	
	}

	public function delete_pickup_summary($param)
	{
		extract($param);

		$summary_head_id = $this->encrypt->decode($head_id);

		$query_data = array($this->_current_date,$this->_current_user,$summary_head_id);

		$query 	= "UPDATE `pickup_summary_head` 
					SET 
					`is_show` = ".\Constants\PICKUP_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE']);
	}

	public function check_if_summary_has_product($summary_head_id)
	{
		$total_detail_count = 0;

		$summary_head_id = $this->encrypt->decode($summary_head_id);

		$query = "SELECT COUNT(*) AS 'rowcount' 
					FROM pickup_summary_head AS PH
					LEFT JOIN pickup_summary_detail AS PD ON PD.`headid` = PH.`id`
					LEFT JOIN release_head AS RH ON RH.`id` = PD.`release_head_id`
					LEFT JOIN release_detail AS RD ON RD.`headid` = RH.`id`
					WHERE PH.`is_show` = ".\Constants\PICKUP_CONST::ACTIVE." AND PH.`id` = ? 
					AND RH.`is_show` = ".\Constants\PICKUP_CONST::ACTIVE." AND RH.`is_used` = ".\Constants\PICKUP_CONST::USED;

		$result = $this->db->query($query, $summary_head_id);

		$row = $result->row();

		$total_detail_count = $row->rowcount;

		$result->free_result();

		return $total_detail_count;
	}

	public function get_pickup_printout_details()
	{
		$response = array();

		$response['error'] = '';

		$summary_head_id = $this->encrypt->decode($this->session->userdata('pickup_summary'));

		$query_head = "SELECT CONCAT('PS',`reference_number`) AS 'reference_number', DATE(`entry_date`) AS 'entry_date'
					FROM pickup_summary_head
					WHERE `is_show` = ".\Constants\PICKUP_CONST::ACTIVE." AND `id` = ?";

		$result_head = $this->db->query($query_head, $summary_head_id);

		if ($result_head->num_rows() == 1) 
		{
			$row = $result_head->row();

			$response['reference_number'] = $row->reference_number;
			$response['entry_date'] = $row->entry_date;
		}
		else
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);

		$result_head->free_result();

		$query_detail = "SELECT RD.`quantity`, COALESCE(P.`description`,'-') AS 'product', 
							COALESCE(ROD.`description`,'') AS 'description', COALESCE(P.`material_code`,'-') AS 'item_code', 
							COALESCE(ROD.`memo`,'') AS 'memo', COALESCE(ROH.`customer`, '') AS 'customer', 
							COALESCE(CONCAT('WR',RH.`reference_number`), '') AS 'reference_number',
							CASE
								WHEN P.`uom` = 1 THEN 'PCS'
								WHEN P.`uom` = 2 THEN 'KGS'
								WHEN P.`uom` = 3 THEN 'ROLL'
							END AS 'uom'
							FROM pickup_summary_head AS PH
							LEFT JOIN pickup_summary_detail AS PD ON PD.`headid` = PH.`id`
							LEFT JOIN release_head AS RH ON RH.`id` = PD.`release_head_id`
							LEFT JOIN release_detail AS RD ON RD.`headid` = RH.`id`
							LEFT JOIN product AS P ON P.`id` = RD.`product_id` AND P.`is_show` = ".\Constants\PICKUP_CONST::ACTIVE."
							LEFT JOIN release_order_detail AS ROD ON ROD.`id` = RD.`release_order_detail_id`
							LEFT JOIN release_order_head AS ROH ON ROH.`id` = ROD.`headid`
							WHERE PH.`is_show` = ".\Constants\PICKUP_CONST::ACTIVE." AND PH.`id` = ? 
							AND RH.`is_show` = ".\Constants\PICKUP_CONST::ACTIVE." AND RH.`is_used` = ".\Constants\PICKUP_CONST::USED;

		$result_detail = $this->db->query($query_detail, $summary_head_id);

		if ($result_detail->num_rows() > 0) 
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				foreach ($row as $key => $value) 
					$response['detail'][$i][$key] = $value;

				$i++;
			}
		}
		else
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_DETAILS']);

		$result_detail->free_result();

		return $response;
	}

	private function _check_if_summary_exist_today()
	{
		$this->db->select("*")
				->from("pickup_summary_head")
				->where("DATE(entry_date)", $this->_current_date)
				->where("`is_show`", \Constants\PICKUP_CONST::ACTIVE);

		$result = $this->db->get();

		return $result;
	}
}
