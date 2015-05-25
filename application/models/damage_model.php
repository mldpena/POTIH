<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Damage_Model extends CI_Model {

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		$this->load->library('encrypt');
		$this->load->library('constants/damage_const');
		$this->load->library('sql');
		$this->load->helper('cookie');
		parent::__construct();
	}

	public function get_damage_details()
	{
		$response 		= array();
		$damage_head_id = $this->encrypt->decode($this->uri->segment(3));
		$branch_id 		= 0;

		$response['head_error'] 	= '';
		$response['detail_error'] 	= ''; 

		$query_head = "SELECT `reference_number`, COALESCE(DATE(`entry_date`),'') AS 'entry_date', `memo`, `branch_id`
					FROM `damage_head`
					WHERE `is_show` = ".DAMAGE_CONST::ACTIVE." AND `id` = ?";

		$result_head = $this->db->query($query_head,$damage_head_id);

		if ($result_head->num_rows() != 1) 
		{
			$response['head_error'] = 'Unable to get damage head details!';
		}
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= $row->entry_date;
			$response['memo'] 				= $row->memo;

			$branch_id = $row->branch_id;
		}

		$query_detail_data = array($branch_id,$damage_head_id);

		$query_detail = "SELECT DD.`id`, DD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', DD.`quantity`, DD.`memo`, 
						COALESCE(PBI.`inventory`,0) AS 'inventory'
					FROM `damage_head` AS DH
					LEFT JOIN `damage_detail` AS DD ON DD.`headid` = DH.`id`
					LEFT JOIN `product` AS P ON P.`id` = DD.`product_id` AND P.`is_show` = ".DAMAGE_CONST::ACTIVE."
					LEFT JOIN `product_branch_inventory` AS PBI ON PBI.`product_id` = P.`id` AND PBI.`branch_id` = ? 
					WHERE DH.`is_show` = ".DAMAGE_CONST::ACTIVE." AND DD.`headid` = ?";

		$result_detail = $this->db->query($query_detail,$query_detail_data);

		if ($result_detail->num_rows() == 0) 
		{
			$response['detail_error'] = 'No damage details found!';
		}
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$response['detail'][$i][] = array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->inventory);
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
		$damage_head_id 	= $this->encrypt->decode($this->uri->segment(3));
		$query_data 		= array($damage_head_id,$qty,$product_id,$memo);

		$query = "INSERT INTO `damage_detail`
					(`headid`,
					`quantity`,
					`product_id`,
					`memo`)
					VALUES
					(?,?,?,?);";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
		{
			$response['error'] = 'Unable to insert damage detail!';
		}
		else
		{
			$response['id'] = $result['id'];
		}

		return $response;
	}

	public function update_damage_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$damage_detail_id 	= $this->encrypt->decode($detail_id);
		$query_data 		= array($qty,$product_id,$memo,$damage_detail_id);

		$query = "UPDATE `damage_detail`
					SET
					`quantity` = ?,
					`product_id` = ?,
					`memo` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
		{
			$response['error'] = 'Unable to update damage detail!';
		}

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
		{
			$response['error'] = 'Unable to delete damage detail!';
		}

		return $response;

	}

	public function update_damage_head($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$date_today 		= date('Y-m-d h:i:s');
		$entry_date 		= $entry_date.' '.date('h:i:s');
		$damage_head_id 	= $this->encrypt->decode($this->uri->segment(3));
		$user_id 			= $this->encrypt->decode(get_cookie('temp'));
		$query_data 		= array($entry_date,$memo,$user_id,$date_today,$damage_head_id);

		$query = "UPDATE `damage_head`
					SET
					`entry_date` = ?,
					`memo` = ?,
					`is_used` = ".DAMAGE_CONST::USED.",
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
		{
			$response['error'] = 'Unable to update damage head!';
		}

		return $response;
	}

	public function search_damage_list($param)
	{
		extract($param);

		$conditions		= "";
		$order_field 	= "";

		$response 	= array();
		$query_data = array();

		$response['rowcnt'] = 0;
		
		
		if (!empty($date_from))
		{
			$conditions .= " AND D.`date_created` >= ?";
			array_push($query_data,$date_from.' 00:00:00');
		}

		if (!empty($date_to))
		{
			$conditions .= " AND D.`date_created` <= ?";
			array_push($query_data,$date_to.' 23:59:59');
		}

		if ($branch != DAMAGE_CONST::ALL_OPTION) 
		{
			$conditions .= " AND D.`branch_id` = ?";
			array_push($query_data,$branch);
		}

		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT(D.`reference_number`,' ',D.`memo`) LIKE ?";
			array_push($query_data,'%'.$search_string.'%');
		}

		switch ($order_by) 
		{
			case DAMAGE_CONST::ORDER_BY_REFERENCE:
				$order_field = "D.`reference_number`";
				break;
			
			case DAMAGE_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case DAMAGE_CONST::ORDER_BY_DATE:
				$order_field = "D.`entry_date`";
				break;
		}


		$query = "SELECT D.`id`, COALESCE(B.`name`,'') AS 'location', CONCAT('DD',D.`reference_number`) AS 'reference_number',
					COALESCE(DATE(`entry_date`),'') AS 'entry_date', IF(D.`is_used` = 0, 'Unused', D.`memo`) AS 'memo'
					FROM damage_head AS D
					LEFT JOIN branch AS B ON B.`id` = D.`branch_id` AND B.`is_show` = ".DAMAGE_CONST::ACTIVE."
					WHERE D.`is_show` = ".DAMAGE_CONST::ACTIVE." $conditions
					ORDER BY $order_field $order_type";

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
				$response['data'][$i][] = array($row->entry_date);
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		return $response;
	}

	public function delete_damage_head($param)
	{
		extract($param);

		$date_today 	= date('Y-m-d h:i:s');
		$user_id		= $this->encrypt->decode(get_cookie('temp'));
		$damage_head_id = $this->encrypt->decode($damage_id);

		$response = array();
		$response['error'] = '';

		$query_data = array($date_today,$user_id,$damage_head_id);
		$query 	= "UPDATE `damage_head` 
					SET 
					`is_show` = ".DAMAGE_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
		{
			$response['error'] = 'Unable to delete damage head!';
		}

		return $response;
	}

}
