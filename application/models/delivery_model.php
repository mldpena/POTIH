<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delivery_Model extends CI_Model {

	private $_delivery_head_id = 0;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() 
	{
		$this->load->library('encrypt');
		$this->load->file(CONSTANTS.'delivery_const.php');
		$this->load->library('sql');
		$this->load->helper('cookie');

		$this->_delivery_head_id 	= $this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");

		parent::__construct();
	}

	public function get_stock_delivery_details()
	{
		$response 		= array();
		$branch_id 		= 0;

		$response['head_error'] 	= '';
		$response['detail_error'] 	= ''; 

		$query_head = "SELECT CONCAT('SD',SH.`reference_number`) AS 'reference_number', COALESCE(DATE(SH.`entry_date`),'') AS 'entry_date', 
					SH.`memo`, SH.`branch_id`, SH.`to_branchid`, SUM(SD.`recv_quantity`) AS 'total_qty', SH.`delivery_type`
					FROM `stock_delivery_head` AS SH
					LEFT JOIN stock_delivery_detail AS SD ON SD.`headid` = SH.`id`
					WHERE SH.`is_show` = ".DELIVERY_CONST::ACTIVE." AND SH.`id` = ?
					GROUP BY SH.`id`";

		$result_head = $this->db->query($query_head,$this->_delivery_head_id);

		if ($result_head->num_rows() != 1) 
			$response['head_error'] = 'Unable to get stock delivery head details!';
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= $row->entry_date;
			$response['memo'] 				= $row->memo;
			$response['to_branchid'] 		= $row->to_branchid;
			$response['delivery_type'] 		= $row->delivery_type;
			$response['is_editable'] 		= $row->total_qty == 0 ? TRUE : FALSE;
			$branch_id = $row->branch_id;
		}

		$query_detail_data = array($branch_id,$this->_delivery_head_id);

		$query_detail = "SELECT SD.`id`, SD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', SD.`quantity`, SD.`memo`, SD.`is_for_branch`, 
						COALESCE(PBI.`inventory`,0) AS 'inventory', SD.`recv_quantity` AS 'receiveqty'
					FROM `stock_delivery_detail` AS SD
					LEFT JOIN `stock_delivery_head` AS SH ON SD.`headid` = SH.`id` AND SH.`is_show` = ".DELIVERY_CONST::ACTIVE."
					LEFT JOIN `product` AS P ON P.`id` = SD.`product_id` AND P.`is_show` = ".DELIVERY_CONST::ACTIVE."
					LEFT JOIN `product_branch_inventory` AS PBI ON PBI.`product_id` = P.`id` AND PBI.`branch_id` = ? 
					WHERE SD.`headid` = ?";

		$result_detail = $this->db->query($query_detail,$query_detail_data);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = 'No stock delivery details found!';
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$response['detail'][$i][] = array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array($row->is_for_branch);
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->inventory);
				$response['detail'][$i][] = array($row->receiveqty);
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

	public function insert_stock_delivery_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$query_data 		= array($this->_delivery_head_id,$qty,$product_id,$memo,$istransfer);

		$query = "INSERT INTO `stock_delivery_detail`
					(`headid`,
					`quantity`,
					`product_id`,
					`memo`,
					`is_for_branch`)
					VALUES
					(?,?,?,?,?);";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to insert stock delivery detail!';
		else
			$response['id'] = $result['id'];

		return $response;
	}

	public function update_stock_delivery_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$delivery_detail_id = $this->encrypt->decode($detail_id);
		$query_data 		= array($qty,$product_id,$memo,$istransfer,$delivery_detail_id);

		$query = "UPDATE `stock_delivery_detail`
					SET
					`quantity` = ?,
					`product_id` = ?,
					`memo` = ?,
					`is_for_branch` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to update stock delivery detail!';

		return $response;
	}
	
	public function delete_stock_delivery_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$delivery_detail_id 	= $this->encrypt->decode($detail_id);

		$query = "DELETE FROM `stock_delivery_detail` WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$delivery_detail_id);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to delete stock delivery detail!';

		return $response;

	}

	public function update_stock_delivery_head($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$entry_date 		= $entry_date.' '.date('h:i:s');

		$query_data 		= array();
		$query 				= array();

		$query_delivery_head_data = array($entry_date,$entry_date,$entry_date,$memo,$to_branch,$type,$this->_current_user,$this->_current_date,$this->_delivery_head_id); 

		$query_delivery_head = "UPDATE `stock_delivery_head`
					SET
					`entry_date` = ?,
					`delivery_receive_date` = ?,
					`customer_receive_date` = ?,
					`memo` = ?,
					`to_branchid` = ?,
					`delivery_type` = ?,
					`is_used` = ".DELIVERY_CONST::USED.",
					`last_modified_by` = ?,
					`last_modified_date` = ?
					WHERE `id` = ?;";

		array_push($query,$query_delivery_head);
		array_push($query_data,$query_delivery_head_data);

		if ($type != DELIVERY_CONST::BOTH) 
		{
			$type = $type == DELIVERY_CONST::SALES ? 0 : 1;

			$query_delivery_detail_data = array($type,$this->_delivery_head_id);
			$query_delivery_detail = "UPDATE `stock_delivery_detail`
										SET `is_for_branch` = ?
										WHERE `headid` = ?";

			array_push($query,$query_delivery_detail);
			array_push($query_data,$query_delivery_detail_data);
		}

		$result = $this->sql->execute_transaction($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to update stock delivery head!';

		return $response;
	}

	public function search_stock_delivery_list($param)
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
			$conditions .= " AND SH.`entry_date` >= ?";
			array_push($query_data,$date_from.' 00:00:00');
		}

		if (!empty($date_to))
		{
			$conditions .= " AND SH.`entry_date` <= ?";
			array_push($query_data,$date_to.' 23:59:59');
		}

		if ($from_branch != DELIVERY_CONST::ALL_OPTION) 
		{
			$conditions .= " AND SH.`branch_id` = ?";
			array_push($query_data,$from_branch);
		}

		if ($to_branch != DELIVERY_CONST::ALL_OPTION) 
		{
			$conditions .= " AND SH.`to_branchid` = ?";
			array_push($query_data,$to_branch);
		}
	
		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT(SH.`reference_number`,' ',SH.`memo`) LIKE ?";
			array_push($query_data,'%'.$search_string.'%');
		}

		if ($type != DELIVERY_CONST::ALL_OPTION) 
		{
			switch ($type) 
			{
				case DELIVERY_CONST::BOTH:
					$conditions .= " AND SH.`delivery_type` = ".DELIVERY_CONST::BOTH;
					break;
				
				case DELIVERY_CONST::SALES:
					$conditions .= " AND SH.`delivery_type` = ".DELIVERY_CONST::SALES;
					break;

				case DELIVERY_CONST::TRANSFER:
					$conditions .= " AND SH.`delivery_type` = ".DELIVERY_CONST::TRANSFER;
					break;
			}
		}

		switch ($order_by) 
		{
			case DELIVERY_CONST::ORDER_BY_REFERENCE:
				$order_field = "SH.`reference_number`";
				break;

			case DELIVERY_CONST::ORDER_BY_DATE:
				$order_field = "SH.`entry_date`";
				break;
		}

		if ($status != DELIVERY_CONST::ALL_OPTION) 
		{
			switch ($status) 
			{
				case DELIVERY_CONST::INCOMPLETE:
					$having = "HAVING remaining_qty < total_qty AND remaining_qty <> 0";
					break;
				
				case DELIVERY_CONST::COMPLETE:
					$having = "HAVING remaining_qty = 0";
					break;

				case DELIVERY_CONST::NO_RECEIVED:
					$having = "HAVING remaining_qty = total_qty";
					break;
			}
		}

		$query = "SELECT SH.`id`, COALESCE(B.`name`,'') AS 'from_branch', COALESCE(B2.`name`,'-') AS 'to_branch', 
					CONCAT('SD',SH.`reference_number`) AS 'reference_number',
					COALESCE(DATE(SH.`entry_date`),'') AS 'entry_date', IF(SH.`is_used` = 0, 'Unused', SH.`memo`) AS 'memo',
					SUM(SD.`quantity`) AS 'total_qty', SUM(SD.`quantity` - SD.`recv_quantity`) AS 'remaining_qty',
					CASE 
						WHEN `delivery_type` = ".DELIVERY_CONST::BOTH." THEN 'Both'
						WHEN `delivery_type` = ".DELIVERY_CONST::SALES." THEN 'Sales'
						WHEN `delivery_type` = ".DELIVERY_CONST::TRANSFER." THEN 'Transfer'
						ELSE 'Unused'
					END AS 'delivery_type',
					CASE
						WHEN SUM(SD.`quantity` - SD.`recv_quantity`) < SUM(SD.`quantity`) AND SUM(SD.`quantity` - SD.`recv_quantity`) <> 0 THEN 'Incomplete'
						WHEN SUM(SD.`quantity` - SD.`recv_quantity`) = 0 THEN 'Complete'
						WHEN SUM(SD.`quantity` - SD.`recv_quantity`) = SUM(SD.`quantity`) THEN 'No Received'
					END AS 'status'
					FROM stock_delivery_head AS SH
					LEFT JOIN stock_delivery_detail AS SD ON SD.`headid` = SH.`id`
					LEFT JOIN branch AS B ON B.`id` = SH.`branch_id` AND B.`is_show` = ".DELIVERY_CONST::ACTIVE."
					LEFT JOIN branch AS B2 ON B2.`id` = SH.`to_branchid` AND B2.`is_show` = ".DELIVERY_CONST::ACTIVE."
					WHERE SH.`is_show` = ".DELIVERY_CONST::ACTIVE." $conditions
					GROUP BY SH.`id`
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
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array($row->from_branch);
				$response['data'][$i][] = array($row->to_branch);
				$response['data'][$i][] = array($row->entry_date);
				$response['data'][$i][] = array($row->delivery_type);
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array($row->total_qty);
				$response['data'][$i][] = array($row->status);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();

		return $response;
	}
	
	public function delete_stock_delivery_head($param)
	{
		extract($param);

		$delivery_head_id = $this->encrypt->decode($delivery_head_id);

		$response = array();
		$response['error'] = '';

		$query_data = array($this->_current_date,$this->_current_user,$delivery_head_id);
		$query 	= "UPDATE `stock_delivery_head` 
					SET 
					`is_show` = ".DELIVERY_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to delete stock delivery head!';

		return $response;
	}

	public function search_stock_receive_list($param)
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
			$conditions .= " AND SH.`entry_date` >= ?";
			array_push($query_data,$date_from.' 00:00:00');
		}

		if (!empty($date_to))
		{
			$conditions .= " AND SH.`entry_date` <= ?";
			array_push($query_data,$date_to.' 23:59:59');
		}

		if ($from_branch != DELIVERY_CONST::ALL_OPTION) 
		{
			$conditions .= " AND SH.`branch_id` = ?";
			array_push($query_data,$from_branch);
		}

		if ($to_branch != DELIVERY_CONST::ALL_OPTION) 
		{
			$conditions .= " AND SH.`to_branchid` = ?";
			array_push($query_data,$to_branch);
		}
	
		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT(SH.`reference_number`,' ',SH.`memo`) LIKE ?";
			array_push($query_data,'%'.$search_string.'%');
		}

		switch ($order_by) 
		{
			case DELIVERY_CONST::ORDER_BY_REFERENCE:
				$order_field = "SH.`reference_number`";
				break;

			case DELIVERY_CONST::ORDER_BY_DATE:
				$order_field = "SH.`entry_date`";
				break;
		}

		$query = "SELECT SH.`id`, COALESCE(B.`name`,'') AS 'from_branch', COALESCE(B2.`name`,'-') AS 'to_branch', 
					CONCAT('SD',SH.`reference_number`) AS 'reference_number',
					COALESCE(DATE(SH.`entry_date`),'') AS 'entry_date', SH.`memo`,
					SUM(SD.`quantity`) AS 'total_qty'
					FROM stock_delivery_head AS SH
					LEFT JOIN stock_delivery_detail AS SD ON SD.`headid` = SH.`id`
					LEFT JOIN branch AS B ON B.`id` = SH.`branch_id` AND B.`is_show` = ".DELIVERY_CONST::ACTIVE."
					LEFT JOIN branch AS B2 ON B2.`id` = SH.`to_branchid` AND B2.`is_show` = ".DELIVERY_CONST::ACTIVE."
					WHERE SH.`is_show` = ".DELIVERY_CONST::ACTIVE." AND SH.`is_used` = ".DELIVERY_CONST::USED." 
						AND SH.`delivery_type` IN(".DELIVERY_CONST::TRANSFER.",".DELIVERY_CONST::BOTH.") $conditions
						AND SD.`is_for_branch` = 1
					GROUP BY SH.`id`
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
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array($row->from_branch);
				$response['data'][$i][] = array($row->to_branch);
				$response['data'][$i][] = array($row->entry_date);
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array($row->total_qty);
				$i++;
			}
		}

		$result->free_result();

		return $response;
	}

	public function get_stock_receive_details()
	{
		$response 		= array();
		$branch_id 		= 0;

		$response['head_error'] 	= '';
		$response['detail_error'] 	= ''; 

		$query_head = "SELECT CONCAT('SD',SH.`reference_number`) AS 'reference_number', COALESCE(DATE(SH.`entry_date`),'') AS 'entry_date', 
					SH.`memo`, SH.`branch_id`, SH.`to_branchid`, SH.`delivery_type`, DATE(SH.`delivery_receive_date`) AS 'receive_date'
					FROM `stock_delivery_head` AS SH
					WHERE SH.`is_show` = ".DELIVERY_CONST::ACTIVE." AND SH.`id` = ?
					GROUP BY SH.`id`";

		$result_head = $this->db->query($query_head,$this->_delivery_head_id);

		if ($result_head->num_rows() != 1) 
			$response['head_error'] = 'Unable to get stock receive head details!';
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= $row->entry_date;
			$response['memo'] 				= $row->memo;
			$response['to_branchid'] 		= $row->to_branchid;
			$response['delivery_type'] 		= $row->delivery_type;
			$response['receive_date'] 		= $row->receive_date;
			$branch_id = $row->branch_id;
		}

		$query_detail_data = array($branch_id,$this->_delivery_head_id);

		$query_detail = "SELECT SD.`id`, SD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', SD.`quantity`, SD.`memo`, SD.`is_for_branch`, 
						COALESCE(PBI.`inventory`,0) AS 'inventory', SD.`recv_quantity`
					FROM `stock_delivery_detail` AS SD
					LEFT JOIN `stock_delivery_head` AS SH ON SD.`headid` = SH.`id` AND SH.`is_show` = ".DELIVERY_CONST::ACTIVE."
					LEFT JOIN `product` AS P ON P.`id` = SD.`product_id` AND P.`is_show` = ".DELIVERY_CONST::ACTIVE."
					LEFT JOIN `product_branch_inventory` AS PBI ON PBI.`product_id` = P.`id` AND PBI.`branch_id` = ? 
					WHERE SD.`headid` = ? AND SD.`is_for_branch` = 1";

		$result_detail = $this->db->query($query_detail,$query_detail_data);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = 'No stock receive details found!';
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
				$response['detail'][$i][] = array($row->recv_quantity);
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$result_head->free_result();
		$result_detail->free_result();

		return $response;
	}

	public function update_stock_receive_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$delivery_detail_id = $this->encrypt->decode($detail_id);
		$query_data 		= array($receiveqty,$delivery_detail_id);

		$query = "UPDATE `stock_delivery_detail`
					SET
					`recv_quantity` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to update stock delivery detail!';

		return $response;
	}

	public function search_customer_receive_list($param)
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
			$conditions .= " AND SH.`entry_date` >= ?";
			array_push($query_data,$date_from.' 00:00:00');
		}

		if (!empty($date_to))
		{
			$conditions .= " AND SH.`entry_date` <= ?";
			array_push($query_data,$date_to.' 23:59:59');
		}

		if ($from_branch != DELIVERY_CONST::ALL_OPTION) 
		{
			$conditions .= " AND SH.`branch_id` = ?";
			array_push($query_data,$from_branch);
		}

		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT(SH.`reference_number`,' ',SH.`memo`) LIKE ?";
			array_push($query_data,'%'.$search_string.'%');
		}

		switch ($order_by) 
		{
			case DELIVERY_CONST::ORDER_BY_REFERENCE:
				$order_field = "SH.`reference_number`";
				break;

			case DELIVERY_CONST::ORDER_BY_DATE:
				$order_field = "SH.`entry_date`";
				break;
		}

		$query = "SELECT SH.`id`, COALESCE(B.`name`,'') AS 'from_branch', COALESCE(B2.`name`,'-') AS 'to_branch', 
					CONCAT('SD',SH.`reference_number`) AS 'reference_number',
					COALESCE(DATE(SH.`entry_date`),'') AS 'entry_date', SH.`memo`,
					SUM(SD.`quantity`) AS 'total_qty'
					FROM stock_delivery_head AS SH
					LEFT JOIN stock_delivery_detail AS SD ON SD.`headid` = SH.`id`
					LEFT JOIN branch AS B ON B.`id` = SH.`branch_id` AND B.`is_show` = ".DELIVERY_CONST::ACTIVE."
					LEFT JOIN branch AS B2 ON B2.`id` = SH.`to_branchid` AND B2.`is_show` = ".DELIVERY_CONST::ACTIVE."
					WHERE SH.`is_show` = ".DELIVERY_CONST::ACTIVE." AND SH.`is_used` = ".DELIVERY_CONST::USED." 
						AND SH.`delivery_type` IN(".DELIVERY_CONST::SALES.",".DELIVERY_CONST::BOTH.") $conditions
						AND SD.`is_for_branch` = 0
					GROUP BY SH.`id`
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
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array($row->from_branch);
				$response['data'][$i][] = array($row->entry_date);
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array($row->total_qty);
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function get_customer_receive_details()
	{
		$response 		= array();
		$branch_id 		= 0;

		$response['head_error'] 	= '';
		$response['detail_error'] 	= ''; 

		$query_head = "SELECT CONCAT('SD',SH.`reference_number`) AS 'reference_number', COALESCE(DATE(SH.`entry_date`),'') AS 'entry_date', 
					SH.`memo`, SH.`branch_id`, SH.`delivery_type`, DATE(SH.`customer_receive_date`) AS 'receive_date'
					FROM `stock_delivery_head` AS SH
					WHERE SH.`is_show` = ".DELIVERY_CONST::ACTIVE." AND SH.`id` = ?
					GROUP BY SH.`id`";

		$result_head = $this->db->query($query_head,$this->_delivery_head_id);

		if ($result_head->num_rows() != 1) 
			$response['head_error'] = 'Unable to get customer receive head details!';
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= $row->entry_date;
			$response['memo'] 				= $row->memo;
			$response['delivery_type'] 		= $row->delivery_type;
			$response['receive_date'] 		= $row->receive_date;
			$branch_id = $row->branch_id;
		}

		$query_detail_data = array($branch_id,$this->_delivery_head_id);

		$query_detail = "SELECT SD.`id`, SD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', SD.`quantity`, SD.`memo`, SD.`is_for_branch`, 
						COALESCE(PBI.`inventory`,0) AS 'inventory', SD.`recv_quantity`
					FROM `stock_delivery_detail` AS SD
					LEFT JOIN `stock_delivery_head` AS SH ON SD.`headid` = SH.`id` AND SH.`is_show` = ".DELIVERY_CONST::ACTIVE."
					LEFT JOIN `product` AS P ON P.`id` = SD.`product_id` AND P.`is_show` = ".DELIVERY_CONST::ACTIVE."
					LEFT JOIN `product_branch_inventory` AS PBI ON PBI.`product_id` = P.`id` AND PBI.`branch_id` = ? 
					WHERE SD.`headid` = ? AND SD.`is_for_branch` = 0";

		$result_detail = $this->db->query($query_detail,$query_detail_data);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = 'No customer receive details found!';
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
				$response['detail'][$i][] = array($row->recv_quantity);
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$result_head->free_result();
		$result_detail->free_result();

		return $response;
	}

	public function check_current_inventory($param)
	{
		extract($param);

		$response = array();
		$response['is_insufficient'] = 0;

		$query_data = array($product_id,$this->_current_branch_id);

		$query = "SELECT `inventory` AS 'current_inventory', `min_inv` FROM product_branch_inventory WHERE `product_id` = ? AND `branch_id` = ?";
		
		$result = $this->db->query($query,$query_data);

		$row = $result->row();


		if (($row->current_inventory - $qty) < 0) 
			$response['is_insufficient'] = DELIVERY_CONST::NEGATIVE_INV;
		elseif (($row->current_inventory - $qty) > 0 && ($row->current_inventory - $qty) <= $row->min_inv)
			$response['is_insufficient'] = DELIVERY_CONST::MINIMUM;

		$response['current_inventory'] = $row->current_inventory;

		$result->free_result();

		return $response;
	}

}
