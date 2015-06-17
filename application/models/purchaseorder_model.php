<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PurchaseOrder_Model extends CI_Model {

	private $_purchase_head_id = 0;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() 
	{
		$this->load->library('encrypt');
		$this->load->file(CONSTANTS.'purchase_const.php');
		$this->load->library('sql');
		$this->load->helper('cookie');

		$this->_purchase_head_id 	= $this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");

		parent::__construct();
	}

	public function get_purchaseorder_details()
	{
		$response 		= array();

		$branch_id 		= 0;

		$response['head_error'] 	= '';
		$response['detail_error'] 	= ''; 

		$query_head = "SELECT CONCAT('PO',PH.`reference_number`) AS 'reference_number', COALESCE(DATE(PH.`entry_date`),'') AS 'entry_date', 
					PH.`memo`, PH.`branch_id`, PH.`supplier`, PH.`for_branchid`, SUM(PD.`recv_quantity`) AS 'total_qty', PH.`is_imported`
					FROM `purchase_head` AS PH
					LEFT JOIN purchase_detail AS PD ON PD.`headid` = PH.`id`
					WHERE PH.`is_show` = ".PURCHASE_CONST::ACTIVE." AND PH.`id` = ?
					GROUP BY PH.`id`";

		$result_head = $this->db->query($query_head,$this->_purchase_head_id);

		if ($result_head->num_rows() != 1) 
			$response['head_error'] = 'Unable to get purchase head details!';
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= $row->entry_date;
			$response['memo'] 				= $row->memo;
			$response['supplier_name'] 		= $row->supplier;
			$response['orderfor'] 			= $row->for_branchid;
			$response['is_imported'] 		= $row->is_imported;
			$response['is_editable'] 		= $row->total_qty == 0 ? TRUE : FALSE;
			$branch_id = $row->for_branchid;
		}

		$query_detail_data = array($branch_id,$this->_purchase_head_id);

		$query_detail = "SELECT PD.`id`, PD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', PD.`quantity`, PD.`memo`, 
						COALESCE(PBI.`inventory`,0) AS 'inventory'
					FROM `purchase_detail` AS PD
					LEFT JOIN `purchase_head` AS PH ON PD.`headid` = PH.`id` AND PH.`is_show` = ".PURCHASE_CONST::ACTIVE."
					LEFT JOIN `product` AS P ON P.`id` = PD.`product_id` AND P.`is_show` = ".PURCHASE_CONST::ACTIVE."
					LEFT JOIN `product_branch_inventory` AS PBI ON PBI.`product_id` = P.`id` AND PBI.`branch_id` = ? 
					WHERE PD.`headid` = ?";

		$result_detail = $this->db->query($query_detail,$query_detail_data);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = 'No purchase details found!';
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
		$query_data 		= array($this->_purchase_head_id,$qty,$product_id,$memo);

		$query = "INSERT INTO `purchase_detail`
					(`headid`,
					`quantity`,
					`product_id`,
					`memo`)
					VALUES
					(?,?,?,?);";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to insert purchase order detail!';
		else
			$response['id'] = $result['id'];

		return $response;
	}

	public function update_purchaseorder_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$purchase_detail_id = $this->encrypt->decode($detail_id);
		$query_data 		= array($qty,$product_id,$memo,$purchase_detail_id);

		$query = "UPDATE `purchase_detail`
					SET
					`quantity` = ?,
					`product_id` = ?,
					`memo` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to update purchase order detail!';

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
			$response['error'] = 'Unable to delete purchase detail!';

		return $response;

	}

	public function update_purchaseorder_head($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$entry_date 		= $entry_date.' '.date('h:i:s');
		$query_data 		= array($entry_date,$memo,$supplier_name,$orderfor,$is_imported,$this->_current_user,$this->_current_date,$this->_purchase_head_id);

		$query = "UPDATE `purchase_head`
					SET
					`entry_date` = ?,
					`memo` = ?,
					`supplier` = ?,
					`for_branchid` = ?,
					`is_imported` = ?,
					`is_used` = ".PURCHASE_CONST::USED.",
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to update purchase head!';

		return $response;
	}

	public function search_purchaseorder_list($param)
	{
		extract($param);

		$conditions		= "";
		$order_field 	= "";
		$having 		= "";

		$response 	= array();
		$query_data = array();

		$response['rowcnt'] = 0;
		
		
		if (!empty($date_from))
		{
			$conditions .= " AND PH.`entry_date` >= ?";
			array_push($query_data,$date_from.' 00:00:00');
		}

		if (!empty($date_to))
		{
			$conditions .= " AND PH.`entry_date` <= ?";
			array_push($query_data,$date_to.' 23:59:59');
		}

		if ($branch != PURCHASE_CONST::ALL_OPTION) 
		{
			$conditions .= " AND PH.`branch_id` = ?";
			array_push($query_data,$branch);
		}

		if ($for_branch != PURCHASE_CONST::ALL_OPTION) 
		{
			$conditions .= " AND PH.`for_branchid` = ?";
			array_push($query_data,$for_branch);
		}
	
		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT('PO',PH.`reference_number`,' ',PH.`memo`,' ',PH.`supplier`) LIKE ?";
			array_push($query_data,'%'.$search_string.'%');
		}

		if ($type != PURCHASE_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case PURCHASE_CONST::IMPORTED:
					$conditions .= " AND PH.`is_imported` = ".PURCHASE_CONST::IMPORTED;
					break;
				
				case PURCHASE_CONST::LOCAL:
					$conditions .= " AND PH.`is_imported` = ".PURCHASE_CONST::LOCAL;
					break;
			}
		}

		switch ($order_by) 
		{
			case PURCHASE_CONST::ORDER_BY_REFERENCE:
				$order_field = "PH.`reference_number`";
				break;
			
			case PURCHASE_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case PURCHASE_CONST::ORDER_BY_DATE:
				$order_field = "PH.`entry_date`";
				break;

			case PURCHASE_CONST::ORDER_BY_SUPPLIER:
				$order_field = "PH.`supplier`";
				break;
		}

		if ($status != PURCHASE_CONST::ALL_OPTION) 
		{
			switch ($status) 
			{
				case PURCHASE_CONST::INCOMPLETE:
					$having = "HAVING remaining_qty < total_qty AND remaining_qty <> 0";
					break;
				
				case PURCHASE_CONST::COMPLETE:
					$having = "HAVING remaining_qty <= 0";
					break;

				case PURCHASE_CONST::NO_RECEIVED:
					$having = "HAVING remaining_qty = total_qty";
					break;
			}
		}

		$query = "SELECT PH.`id`, COALESCE(B.`name`,'') AS 'location', COALESCE(B2.`name`,'') AS 'forbranch', 
					CONCAT('PO',PH.`reference_number`) AS 'reference_number', PH.`supplier`,
					COALESCE(DATE(PH.`entry_date`),'') AS 'entry_date', IF(PH.`is_used` = 0, 'Unused', PH.`memo`) AS 'memo',
					SUM(PD.`quantity`) AS 'total_qty', SUM(PD.`quantity` - PD.`recv_quantity`) AS 'remaining_qty',
					CASE 
						WHEN SUM(PD.`quantity` - PD.`recv_quantity`) < SUM(PD.`quantity`) AND SUM(PD.`quantity` - PD.`recv_quantity`) <> 0 THEN 'Incomplete'
						WHEN SUM(PD.`quantity` - PD.`recv_quantity`) <= 0 THEN 'Complete'
						WHEN SUM(PD.`quantity` - PD.`recv_quantity`) = SUM(PD.`quantity`) THEN 'No Received'
					END AS 'status'
					FROM purchase_head AS PH
					LEFT JOIN purchase_detail AS PD ON PD.`headid` = PH.`id`
					LEFT JOIN branch AS B ON B.`id` = PH.`branch_id` AND B.`is_show` = ".PURCHASE_CONST::ACTIVE."
					LEFT JOIN branch AS B2 ON B2.`id` = PH.`for_branchid` AND B2.`is_show` = ".PURCHASE_CONST::ACTIVE."
					WHERE PH.`is_show` = ".PURCHASE_CONST::ACTIVE." $conditions
					GROUP BY PH.`id`
					$having
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
				$response['data'][$i][] = array($row->total_qty);
				$response['data'][$i][] = array($row->remaining_qty);
				$response['data'][$i][] = array($row->status);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}
	
	public function delete_purchaseorder_head($param)
	{
		extract($param);

		$purchase_head_id = $this->encrypt->decode($purchase_id);

		$response = array();
		$response['error'] = '';

		$query_data = array($this->_current_date,$this->_current_user,$purchase_head_id);
		$query 	= "UPDATE `purchase_head` 
					SET 
					`is_show` = ".PURCHASE_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to delete Purchase head!';

		return $response;
	}


}
