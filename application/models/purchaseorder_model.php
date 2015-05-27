<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchaseorder_Model extends CI_Model {

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		$this->load->library('encrypt');
		$this->load->library('constants/purchase_const');
		$this->load->library('sql');
		$this->load->helper('cookie');
		parent::__construct();
	}

	public function get_purchaseorder_details()
	{
		$response 		= array();
		$purchase_head_id = $this->encrypt->decode($this->uri->segment(3));
		$branch_id 		= 0;

		$response['head_error'] 	= '';
		$response['detail_error'] 	= ''; 

		$query_head = "SELECT `reference_number`, COALESCE(DATE(`entry_date`),'') AS 'entry_date', `memo`,`branch_id`,`supplier`,`for_branchid`
					FROM `purchase_head`
					WHERE `is_show` = ".PURCHASE_CONST::ACTIVE." AND `id` = ?";

		$result_head = $this->db->query($query_head,$purchase_head_id);

		if ($result_head->num_rows() != 1) 
		{
			$response['head_error'] = 'Unable to get purchase head details!';
		}
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= $row->entry_date;
			$response['memo'] 				= $row->memo;
			$response['supplier_name'] 		= $row->supplier;
			$response['orderfor'] 		= $row->for_branchid;
		
		


			$branch_id = $row->branch_id;
		}

		$query_detail_data = array($branch_id,$purchase_head_id);

		$query_detail = "SELECT PD.`id`, PD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', PD.`quantity`, PD.`memo`, 
						COALESCE(PBI.`inventory`,0) AS 'inventory'
					FROM `purchase_head` AS PH
					LEFT JOIN `purchase_detail` AS PD ON PD.`headid` = PH.`id`
					LEFT JOIN `product` AS P ON P.`id` = PD.`product_id` AND P.`is_show` = ".PURCHASE_CONST::ACTIVE."
					LEFT JOIN `product_branch_inventory` AS PBI ON PBI.`product_id` = P.`id` AND PBI.`branch_id` = ? 
					WHERE PH.`is_show` = ".PURCHASE_CONST::ACTIVE." AND PD.`headid` = ?";

		$result_detail = $this->db->query($query_detail,$query_detail_data);

		if ($result_detail->num_rows() == 0) 
		{
			$response['detail_error'] = 'No purchase details found!';
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

	public function insert_purchaseorder_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$purchase_head_id 	= $this->encrypt->decode($this->uri->segment(3));
		$query_data 		= array($purchase_head_id,$qty,$product_id,$memo);

		$query = "INSERT INTO `purchase_detail`
					(`headid`,
					`quantity`,
					`product_id`,
					`memo`)
					VALUES
					(?,?,?,?);";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
		{
			$response['error'] = 'Unable to insert purchase order detail!';
		}
		else
		{
			$response['id'] = $result['id'];
		}

		return $response;
	}
	public function update_purchaseorder_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$purchase_detail_id 	= $this->encrypt->decode($detail_id);
		$query_data 		= array($qty,$product_id,$memo,$purchase_detail_id);

		$query = "UPDATE `purchase_detail`
					SET
					`quantity` = ?,
					`product_id` = ?,
					`memo` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
		{
			$response['error'] = 'Unable to update purchase order detail!';
		}

		return $response;
	}
	public function delete_purchaseorder_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$purchase_detail_id 	= $this->encrypt->decode($detail_id);

		$query = "DELETE FROM `purchase_detail` WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$purchase_detail_id);

		if ($result['error'] != '') 
		{
			$response['error'] = 'Unable to delete purchase detail!';
		}

		return $response;

	}
	public function update_purchaseorder_head($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$date_today 		= date('Y-m-d h:i:s');
		$entry_date 		= $entry_date.' '.date('h:i:s');
		$purchase_head_id 	= $this->encrypt->decode($this->uri->segment(3));
		$user_id 			= $this->encrypt->decode(get_cookie('temp'));
		$query_data 		= array($entry_date,$memo,$supplier_name,$orderfor,$user_id,$date_today,$purchase_head_id);

		$query = "UPDATE `purchase_head`
					SET
					`entry_date` = ?,
					`memo` = ?,
					`supplier` = ?,
					`for_branchid`=?,
					`is_used` = ".PURCHASE_CONST::USED.",
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
		{
			$response['error'] = 'Unable to update purchase head!';
		}

		return $response;
	}
	public function search_purchaseorder_list($param)
	{
		extract($param);

		$conditions		= "";
		$order_field 	= "";

		$response 	= array();
		$query_data = array();

		$response['rowcnt'] = 0;
		
		
		if (!empty($date_from))
		{
			$conditions .= " AND PD.`date_created` >= ?";
			array_push($query_data,$date_from.' 00:00:00');
		}

		if (!empty($date_to))
		{
			$conditions .= " AND PD.`date_created` <= ?";
			array_push($query_data,$date_to.' 23:59:59');
		}

		if ($branch != PURCHASE_CONST::ALL_OPTION) 
		{
			$conditions .= " AND PD.`branch_id` = ?";
			array_push($query_data,$branch);
		}

		if ($for_branch != PURCHASE_CONST::ALL_OPTION) 
		{
			$conditions .= " AND PD.`for_branchid` = ?";
			array_push($query_data,$for_branch);
		}
	
		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT('PD',PD.`reference_number`,' ',PD.`memo`,' ',PD.`supplier`) LIKE ?";
			array_push($query_data,'%'.$search_string.'%');
		}

		switch ($order_by) 
		{
			case PURCHASE_CONST::ORDER_BY_REFERENCE:
				$order_field = "PD.`reference_number`";
				break;
			
			case PURCHASE_CONST::ORDER_BY_LOCATION:
				$order_field = "PD.`memo`";
				break;

			case PURCHASE_CONST::ORDER_BY_DATE:
				$order_field = "PD.`supplier`";
				break;
		}


		$query = "SELECT PD.`id`, COALESCE(B.`name`,'') AS 'location', COALESCE(B2.`name`,'') AS 'forbranch', CONCAT('PD',PD.`reference_number`) AS 'reference_number',
					COALESCE(DATE(`entry_date`),'') AS 'entry_date', IF(PD.`is_used` = 0, 'Unused', PD.`memo`) AS 'memo', PD.`supplier`
					FROM purchase_head AS PD
					LEFT JOIN branch AS B ON B.`id` = PD.`branch_id` AND B.`is_show` = ".PURCHASE_CONST::ACTIVE."
					LEFT JOIN branch AS B2 ON B2.`id` = PD.`for_branchid` AND B.`is_show` = ".PURCHASE_CONST::ACTIVE."
					WHERE PD.`is_show` = ".PURCHASE_CONST::ACTIVE." $conditions
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
				$response['data'][$i][] = array($row->forbranch);
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array($row->entry_date);
				$response['data'][$i][] = array($row->supplier);
				$response['data'][$i][] = array($row->memo);
		
				$response['data'][$i][] = array('');
				$response['data'][$i][] = array('');
				$response['data'][$i][] = array('');
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		return $response;
	}
	public function delete_purchaseorder_head($param)
	{
		extract($param);

		$date_today 	= date('Y-m-d h:i:s');
		$user_id		= $this->encrypt->decode(get_cookie('temp'));
		$purchase_head_id = $this->encrypt->decode($purchase_id);

		$response = array();
		$response['error'] = '';

		$query_data = array($date_today,$user_id,$purchase_head_id);
		$query 	= "UPDATE `purchase_head` 
					SET 
					`is_show` = ".PURCHASE_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
		{
			$response['error'] = 'Unable to delete Purchase head!';
		}

		return $response;
	}


}
