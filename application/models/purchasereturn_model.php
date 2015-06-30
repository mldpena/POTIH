<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PurchaseReturn_Model extends CI_Model {

	private $_purchase_return_head_id = 0;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('UNABLE_TO_INSERT' => 'Unable to insert purchase return detail!',
									'UNABLE_TO_UPDATE' => 'Unable to update purchase return detail!',
									'UNABLE_TO_UPDATE_HEAD' => 'Unable to update purchase return head!',
									'UNABLE_TO_SELECT_HEAD' => 'Unable to get purchase return head details!',
									'UNABLE_TO_SELECT_DETAILS' => 'Unable to get purchase return details!',
									'UNABLE_TO_DELETE' => 'Unable to delete purchase return detail!',
									'UNABLE_TO_DELETE_HEAD' => 'Unable to delete purchase return head!');

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() 
	{
		$this->load->library('encrypt');
		$this->load->file(CONSTANTS.'purchase_return_const.php');
		$this->load->library('sql');
		$this->load->helper('cookie');

		$this->_purchase_return_head_id = $this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");

		parent::__construct();
	}

	public function get_purchasereturn_details()
	{
		$response 		= array();

		$branch_id 		= 0;

		$response['error'] 	= '';
		$response['detail_error'] 	= ''; 

		$query_head = "SELECT CONCAT('PR',PH.`reference_number`) AS 'reference_number', COALESCE(DATE(PH.`entry_date`),'') AS 'entry_date', 
					PH.`memo`, PH.`branch_id`, PH.`supplier`
					FROM `purchase_return_head` AS PH
					LEFT JOIN purchase_return_detail AS PD ON PD.`headid` = PH.`id`
					WHERE PH.`is_show` = ".PURCHASE_RETURN_CONST::ACTIVE." AND PH.`id` = ?
					GROUP BY PH.`id`";

		$result_head = $this->db->query($query_head,$this->_purchase_return_head_id);

		if ($result_head->num_rows() != 1) 
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= $row->entry_date;
			$response['memo'] 				= $row->memo;
			$response['supplier_name'] 		= $row->supplier;
			$branch_id = $row->branch_id;
		}

		$query_detail = "SELECT PD.`id`, PD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', PD.`quantity`, PD.`memo`, PD.`description`
					FROM `purchase_return_detail` AS PD
					LEFT JOIN `purchase_return_head` AS PH ON PD.`headid` = PH.`id` AND PH.`is_show` = ".PURCHASE_RETURN_CONST::ACTIVE."
					LEFT JOIN `product` AS P ON P.`id` = PD.`product_id` AND P.`is_show` = ".PURCHASE_RETURN_CONST::ACTIVE."
					WHERE PD.`headid` = ?";

		$result_detail = $this->db->query($query_detail,$this->_purchase_return_head_id);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = 'No purchase return details found!';
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$break_line = empty($row->description) ? '' : '<br/>';
				$response['detail'][$i][] = array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id,$break_line,$row->description);
				$response['detail'][$i][] = array($row->material_code);
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

	public function insert_purchasereturn_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$query_data 		= array($this->_purchase_return_head_id,$qty,$product_id,$memo,$description);

		$query = "INSERT INTO `purchase_return_detail`
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

	public function update_purchasereturn_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$purchase_detail_id = $this->encrypt->decode($detail_id);
		$query_data 		= array($qty,$product_id,$memo,$description,$purchase_detail_id);

		$query = "UPDATE `purchase_return_detail`
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
	
	public function delete_purchasereturn_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$purchase_detail_id 	= $this->encrypt->decode($detail_id);

		$query = "DELETE FROM `purchase_return_detail` WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$purchase_detail_id);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE']);

		return $response;

	}

	public function update_purchasereturn_head($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$entry_date 		= $entry_date.' '.date('h:i:s');
		$query_data 		= array($entry_date,$memo,$supplier_name,$this->_current_user,$this->_current_date,$this->_purchase_return_head_id);

		$query = "UPDATE `purchase_return_head`
					SET
					`entry_date` = ?,
					`memo` = ?,
					`supplier` = ?,
					`is_used` = ".PURCHASE_RETURN_CONST::USED.",
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE_HEAD']);

		return $response;
	}

	public function search_purchasereturn_list($param)
	{
		extract($param);

		$conditions		= "";
		$order_field 	= "";

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

		if ($branch != PURCHASE_RETURN_CONST::ALL_OPTION) 
		{
			$conditions .= " AND PH.`branch_id` = ?";
			array_push($query_data,$branch);
		}
	
		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT('PR',PH.`reference_number`,' ',PH.`memo`,' ',PH.`supplier`) LIKE ?";
			array_push($query_data,'%'.$search_string.'%');
		}

		switch ($order_by) 
		{
			case PURCHASE_RETURN_CONST::ORDER_BY_REFERENCE:
				$order_field = "PH.`reference_number`";
				break;
			
			case PURCHASE_RETURN_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case PURCHASE_RETURN_CONST::ORDER_BY_DATE:
				$order_field = "PH.`entry_date`";
				break;

			case PURCHASE_RETURN_CONST::ORDER_BY_SUPPLIER:
				$order_field = "PH.`supplier`";
				break;
		}

		$query = "SELECT PH.`id`, COALESCE(B.`name`,'') AS 'location',
					CONCAT('PR',PH.`reference_number`) AS 'reference_number', PH.`supplier`,
					COALESCE(DATE(PH.`entry_date`),'') AS 'entry_date', IF(PH.`is_used` = 0, 'Unused', PH.`memo`) AS 'memo',
					COALESCE(SUM(PD.`quantity`),'') AS 'total_qty'
					FROM purchase_return_head AS PH
					LEFT JOIN purchase_return_detail AS PD ON PD.`headid` = PH.`id`
					LEFT JOIN branch AS B ON B.`id` = PH.`branch_id` AND B.`is_show` = ".PURCHASE_RETURN_CONST::ACTIVE."
					WHERE PH.`is_show` = ".PURCHASE_RETURN_CONST::ACTIVE." $conditions
					GROUP BY PH.`id`
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
				$response['data'][$i][] = array($row->supplier);
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array($row->total_qty);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}
	
	public function delete_purchasereturn_head($param)
	{
		extract($param);

		$purchase_return_id = $this->encrypt->decode($head_id);

		$response = array();
		$response['error'] = '';

		$query_data = array($this->_current_date,$this->_current_user,$purchase_return_id);
		$query 	= "UPDATE `purchase_return_head` 
					SET 
					`is_show` = ".PURCHASE_RETURN_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE_HEAD']);

		return $response;
	}


}
