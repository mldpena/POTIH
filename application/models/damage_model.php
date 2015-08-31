<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Damage_Model extends CI_Model {

	private $_damage_head_id = 0;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('UNABLE_TO_INSERT' => 'Unable to insert damage detail!',
									'UNABLE_TO_UPDATE' => 'Unable to update damage detail!',
									'UNABLE_TO_UPDATE_HEAD' => 'Unable to update damage head!',
									'UNABLE_TO_SELECT_HEAD' => 'Unable to get damage head details!',
									'UNABLE_TO_SELECT_DETAILS' => 'Unable to get damage details!',
									'UNABLE_TO_DELETE' => 'Unable to delete damage detail!',
									'UNABLE_TO_DELETE_HEAD' => 'Unable to delete damage head!',
									'NOT_OWN_BRANCH' => 'Cannot delete damage entry of other branches!');
	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		parent::__construct();

		$this->load->constant('damage_const');

		$this->_damage_head_id 		= $this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");
	}

	public function get_damage_details()
	{
		$response 		= array();
		$branch_id 		= 0;

		$response['error'] 	= '';
		$response['detail_error'] 	= ''; 

		$query_head = "SELECT CONCAT('DD',`reference_number`) AS 'reference_number', COALESCE(DATE(`entry_date`),'') AS 'entry_date', `memo`, `branch_id`, `is_used`
					FROM `damage_head`
					WHERE `is_show` = ".\Constants\DAMAGE_CONST::ACTIVE." AND `id` = ?";

		$result_head = $this->db->query($query_head,$this->_damage_head_id);

		if ($result_head->num_rows() != 1) 
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= date('m-d-Y', strtotime($row->entry_date));
			$response['memo'] 				= $row->memo;
			$response['is_editable']		= $row->branch_id == $this->_current_branch_id ? TRUE : FALSE;
			$response['is_saved'] 			= $row->is_used == 1 ? TRUE : FALSE;
		}

		$query_detail = "SELECT DD.`id`, DD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', P.`uom`, DD.`quantity`, DD.`memo`, DD.`description`, P.`type`
					FROM `damage_detail` AS DD
					LEFT JOIN `damage_head` AS DH ON DD.`headid` = DH.`id` AND DH.`is_show` = ".\Constants\DAMAGE_CONST::ACTIVE."
					LEFT JOIN `product` AS P ON P.`id` = DD.`product_id` AND P.`is_show` = ".\Constants\DAMAGE_CONST::ACTIVE."
					WHERE DD.`headid` = ?";

		$result_detail = $this->db->query($query_detail,$this->_damage_head_id);
		
		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = 'No damage details found!';
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$break_line = $row->type == \Constants\DAMAGE_CONST::STOCK ? '' : '<br/>';
				$response['detail'][$i][] = array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line, $row->description);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->uom);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->memo);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$result_head->free_result();
		$result_detail->free_result();

		return $response;
	}

	public function insert_damage_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$query_data 		= array($this->_damage_head_id,$qty,$product_id,$memo,$description);

		$query = "INSERT INTO `damage_detail`
					(`headid`,
					`quantity`,
					`product_id`,
					`memo`,
					`description`)
					VALUES
					(?,?,?,?,?);";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_INSERT']);
		else
			$response['id'] = $result['id'];

		return $response;
	}

	public function update_damage_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$damage_detail_id 	= $this->encrypt->decode($detail_id);
		$query_data 		= array($qty,$product_id,$memo,$description,$damage_detail_id);

		$query = "UPDATE `damage_detail`
					SET
					`quantity` = ?,
					`product_id` = ?,
					`memo` = ?,
					`description` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE']);

		return $response;
	}

	public function delete_damage_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$damage_detail_id 	= $this->encrypt->decode($detail_id);

		$query = "DELETE FROM `damage_detail` WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$damage_detail_id);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE']);

		return $response;

	}

	public function update_damage_head($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$entry_date 		= $entry_date.' '.date('h:i:s');
		$query_data 		= array($entry_date,$memo,$this->_current_user,$this->_current_date,$this->_damage_head_id);

		$query = "UPDATE `damage_head`
					SET
					`entry_date` = ?,
					`memo` = ?,
					`is_used` = ".\Constants\DAMAGE_CONST::USED.",
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE_HEAD']);

		return $response;
	}

	public function search_damage_list($param, $with_limit = TRUE)
	{
		extract($param);

		$response['rowcnt'] = 0;

		$this->db->select(" D.`id`, COALESCE(B.`name`,'') AS 'location', CONCAT('DD',D.`reference_number`) AS 'reference_number',
					COALESCE(DATE(`entry_date`),'') AS 'entry_date', IF(D.`is_used` = 0, 'Unused', D.`memo`) AS 'memo'")
				->from("damage_head AS D")
				->join("branch AS B", "B.`id` = D.`branch_id` AND B.`is_show` = ".\Constants\DAMAGE_CONST::ACTIVE, "left")
				->where("D.`is_show`", \Constants\DAMAGE_CONST::ACTIVE);

		if (!empty($date_from))
			$this->db->where("D.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("D.`entry_date` <=", $date_to." 23:59:59");

		if ($branch != \Constants\DAMAGE_CONST::ALL_OPTION) 
			$this->db->where("D.`branch_id`", (int)$branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('DD',D.`reference_number`,' ',D.`memo`)", $search_string, "both");

		switch ($order_by) 
		{
			case \Constants\DAMAGE_CONST::ORDER_BY_REFERENCE:
				$order_field = "D.`reference_number`";
				break;
			
			case \Constants\DAMAGE_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case \Constants\DAMAGE_CONST::ORDER_BY_DATE:
				$order_field = "D.`entry_date`";
				break;
		}

		$this->db->order_by($order_field, $order_type);

		if ($with_limit) 
		{
			$limit = $row_end - $row_start + 1;
			$this->db->limit((int)$limit, (int)$row_start);
		}

		$result = $this->db->get();

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($row_start + $i + 1);
				$response['data'][$i][] = array($row->location);
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array(date('m-d-Y', strtotime($row->entry_date)));
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		return $response;
	}

	public function get_damage_list_count_by_filter($param)
	{
		extract($param);

		$this->db->from("damage_head AS D")
				->where("D.`is_show`", \Constants\DAMAGE_CONST::ACTIVE);

		if (!empty($date_from))
			$this->db->where("D.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("D.`entry_date` <=", $date_to." 23:59:59");

		if ($branch != \Constants\DAMAGE_CONST::ALL_OPTION) 
			$this->db->where("D.`branch_id`", (int)$branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('DD',D.`reference_number`,' ',D.`memo`)", $search_string, "both");

		return $this->db->count_all_results();
	}

	public function delete_damage_head($param)
	{
		extract($param);

		$damage_head_id = $this->encrypt->decode($head_id);

		$response = array();
		$response['error'] = '';

		$query 	= "SELECT `branch_id` FROM damage_head WHERE id = ?";
		$result = $this->db->query($query,$damage_head_id);
		$row 	= $result->row();

		if ($row->branch_id != $this->_current_branch_id) {
			throw new Exception($this->_error_message['NOT_OWN_BRANCH']);
		}

		$result->free_result();

		$query_data = array($this->_current_date,$this->_current_user,$damage_head_id);
		$query 	= "UPDATE `damage_head` 
					SET 
					`is_show` = ".\Constants\DAMAGE_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE_HEAD']);

		return $response;
	}

	public function get_damage_printout_details()
	{
		$response = array();

		$response['error'] = '';

		$damage_id = $this->encrypt->decode($this->session->userdata('damage_entry'));

		$query_head = "SELECT CONCAT('DD',H.`reference_number`) AS 'reference_number', 
						DATE(H.`entry_date`) AS 'entry_date', H.`memo`
					FROM damage_head AS H
					WHERE H.`id` = ?";

		$result_head = $this->db->query($query_head,$damage_id);
		
		if ($result_head->num_rows() == 1) 
		{
			$row = $result_head->row();

			foreach ($row as $key => $value)
				$response[$key] = $value;
		}
		else
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
			
		$result_head->free_result();

		$query_detail = "SELECT D.`quantity` AS 'quantity', COALESCE(P.`description`,'-') AS 'product', 
							D.`description`, COALESCE(P.`material_code`,'-') AS 'item_code', D.`memo`
							FROM damage_head AS H
							LEFT JOIN damage_detail AS D ON D.`headid` = H.`id`
							LEFT JOIN product AS P ON P.`id` = D.`product_id`
							WHERE H.`id` = ?";

		$result_detail = $this->db->query($query_detail,$damage_id);

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

	public function check_if_transaction_has_product()
	{
		$this->db->select("D.*")
				->from("damage_detail AS D")
				->join("damage_head AS H", "H.`id` = D.`headid`", "left")
				->where("H.`is_show`", \Constants\DAMAGE_CONST::ACTIVE)
				->where("H.`id`", $this->_damage_head_id);

		$result = $this->db->get();

		return $result;
	}

	public function get_damage_by_transaction($param)
	{
		extract($param);
		
		$this->db->select("H.`id`, COALESCE(B.`name`,'') AS 'location', CONCAT('DD',H.`reference_number`) AS 'reference_number',
							COALESCE(DATE(`entry_date`),'') AS 'entry_date', IF(H.`is_used` = 0, 'Unused', H.`memo`) AS 'memo'")
				->from("damage_head AS H")
				->join("branch AS B", "B.`id` = H.`branch_id` AND B.`is_show` = ".\Constants\DAMAGE_CONST::ACTIVE, "left")
				->where("H.`is_show`", \Constants\DAMAGE_CONST::ACTIVE)
				->where("H.`is_used`", \Constants\DAMAGE_CONST::USED);

		if (!empty($date_from))
			$this->db->where("H.`entry_date` >=", $date_from.' 00:00:00');

		if (!empty($date_to))
			$this->db->where("H.`entry_date` <=", $date_to.' 23:59:59');

		if ($branch != \Constants\DAMAGE_CONST::ALL_OPTION) 
			$this->db->where("H.`branch_id`", $branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('DD',H.`reference_number`,' ',H.`memo`)", $search_string, "both");

		switch ($order_by) 
		{
			case \Constants\DAMAGE_CONST::ORDER_BY_REFERENCE:
				$order_field = "H.`reference_number`";
				break;
			
			case \Constants\DAMAGE_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case \Constants\DAMAGE_CONST::ORDER_BY_DATE:
				$order_field = "H.`entry_date`";
				break;
		}

		$this->db->order_by($order_field, $order_type);
		
		$result = $this->db->get();

		return $result;
	}
}
