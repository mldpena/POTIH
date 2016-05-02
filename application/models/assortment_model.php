<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assortment_Model extends CI_Model {

	private $_assortment_head_id = 0;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('UNABLE_TO_INSERT' => 'Unable to insert assortment detail!',
									'UNABLE_TO_UPDATE' => 'Unable to update assortment detail!',
									'UNABLE_TO_UPDATE_HEAD' => 'Unable to update assortment head!',
									'UNABLE_TO_SELECT_HEAD' => 'Unable to get assortment head details!',
									'UNABLE_TO_SELECT_DETAILS' => 'Unable to get assortment details!',
									'UNABLE_TO_DELETE' => 'Unable to delete assortment detail!',
									'UNABLE_TO_DELETE_HEAD' => 'Unable to delete assortment head!',
									'HAS_RELEASED' => 'Pick-Up Assortment can only be deleted if assortment status is no received!',
									'SALES_NOT_FOUND' => 'No sales invoice found!',
									'NOT_OWN_BRANCH' => 'Cannot delete assortment order entry of other branches!');

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() 
	{
		parent::__construct();

		$this->load->constant('assortment_const');

		$this->_assortment_head_id 	= $this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d H:i:s");
	}

	public function get_assortment_details()
	{
		$response 		= array();
		$response['error'] = '';
		$response['detail_error'] = '';

		$query_head = "SELECT 
							CONCAT('PA',RH.`reference_number`) AS 'reference_number', 
							COALESCE(DATE(RH.`entry_date`),'') AS 'entry_date', 
							RH.`memo`, 
							RH.`branch_id`, 
							RH.`customer`, 
							RH.`customer_id`,
							SUM(RD.`qty_released`) AS 'qty_released', 
							RH.`is_used`,
							SUM(IF(RD.`quantity` - RD.`qty_released` < 0, 0, RD.`quantity` - RD.`qty_released`)) AS 'remaining_qty'
						FROM 
							release_order_head AS RH
						LEFT JOIN 
							release_order_detail AS RD ON RD.`headid` = RH.`id`
						LEFT JOIN
							customer AS C ON C.`id` = RH.`customer_id`
						WHERE 
							RH.`is_show` = ".\Constants\ASSORTMENT_CONST::ACTIVE." AND 
							RH.`id` = ?
						GROUP BY RH.`id`";

		$result_head = $this->db->query($query_head, $this->_assortment_head_id);

		if ($result_head->num_rows() != 1) 
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= date('m-d-Y', strtotime($row->entry_date));
			$response['memo'] 				= $row->memo;
			$response['customer_name'] 		= $row->customer;
			$response['customer_id'] 		= $row->customer_id;
			$response['is_editable'] 		= $row->qty_released == 0 ? (($row->branch_id == $this->_current_branch_id) ? TRUE : FALSE) : FALSE;
			$response['is_saved'] 			= $row->is_used == 1 ? TRUE : FALSE;
			$response['is_incomplete'] 		= $row->remaining_qty > 0 && $row->qty_released > 0 ? TRUE : FALSE;
			$response['own_branch'] 		= $this->_current_branch_id;
			$response['transaction_branch'] = $row->branch_id;
		}

		$query_detail = "SELECT 
							RD.`id`, 
							RD.`product_id`, 
							COALESCE(P.`material_code`,'') AS 'material_code', 
							COALESCE(CONCAT(P.`description`, IF(P.`is_show` = 0, '(Product Deleted)', '')),'') AS 'product',
							COALESCE(P.`is_show`, 0) AS 'is_deleted',
							CASE
								WHEN P.`uom` = ".\Constants\ASSORTMENT_CONST::PCS." THEN 'PCS'
								WHEN P.`uom` = ".\Constants\ASSORTMENT_CONST::KG." THEN 'KGS'
								WHEN P.`uom` = ".\Constants\ASSORTMENT_CONST::ROLL." THEN 'ROLL'
								ELSE ''
							END AS 'uom', 
							RD.`quantity`, 
							RD.`memo`, 
							RD.`description`, 
							COALESCE(P.`type`, '') AS 'type', 
							RD.`qty_released`,
							(IF((RD.`quantity` - RD.`qty_released`) < 0, 0, RD.`quantity` - RD.`qty_released`)) AS 'qty_remaining',
							RD.`sales_detail_id`,
							COALESCE(CONCAT('SI', SH.`reference_number`), '') AS 'sales_reference'
						FROM 
							`release_order_detail` AS RD
						LEFT JOIN 
							`release_order_head` AS RH ON RD.`headid` = RH.`id` AND RH.`is_show` = ".\Constants\ASSORTMENT_CONST::ACTIVE."
						LEFT JOIN 
							`sales_detail` AS SD ON SD.`id` = RD.`sales_detail_id`
						LEFT JOIN 
							`sales_head` AS SH ON SH.`id` = SD.`headid` AND SH.`is_show` = ".\Constants\ASSORTMENT_CONST::ACTIVE." AND SH.`is_used` = ".\Constants\ASSORTMENT_CONST::USED."
						LEFT JOIN 
							`product` AS P ON P.`id` = RD.`product_id`
						WHERE 
							RD.`headid` = ?";

		$result_detail = $this->db->query($query_detail,(int)$this->_assortment_head_id);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = $this->_error_message['UNABLE_TO_SELECT_DETAILS'];
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$break_line = ($row->type == \Constants\ASSORTMENT_CONST::NON_STOCK || !empty($row->description)) ? '<br/>' : '';
				$response['detail'][$i][] = array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array($this->encrypt->encode($row->sales_detail_id));
				$response['detail'][$i][] = array($row->sales_reference);
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line, $row->description, $row->is_deleted);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->uom);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->qty_released);
				$response['detail'][$i][] = array($row->qty_remaining);
				$response['detail'][$i][] = array($row->memo);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$response = $this->get_customer_sales_list($response['customer_id'], $response['transaction_branch'], $response);
		
		$result_head->free_result();
		$result_detail->free_result();

		return $response;
	}

	public function insert_assortment_detail($param)
	{
		extract($param);

		$response = array();
		$response['error'] = '';

		$sales_detail_id = $this->encrypt->decode($sales_detail_id);

		$query_data = array($this->_assortment_head_id, $qty, $product_id, $memo, $description, $sales_detail_id);

		$query = "INSERT INTO `release_order_detail`
					(`headid`,
					`quantity`,
					`product_id`,
					`memo`,
					`description`,
					`sales_detail_id`)
					VALUES
					(?,?,?,?,?,?);";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_INSERT']);
		else
			$response['id'] = $result['id'];

		return $response;
	}

	public function update_assortment_detail($param)
	{
		extract($param);

		$response = array();
		$response['error'] = '';

		$assortment_detail_id = $this->encrypt->decode($detail_id);
		$sales_detail_id = $this->encrypt->decode($sales_detail_id);
		$query_data = array($qty, $product_id, $memo, $description, $sales_detail_id, $assortment_detail_id);

		$query = "UPDATE `release_order_detail`
					SET
						`quantity` = ?,
						`product_id` = ?,
						`memo` = ?,
						`description` = ?,
						`sales_detail_id` = ?
					WHERE 
						`id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE']);

		return $response;
	}
	
	public function delete_assortment_detail($param)
	{
		extract($param);

		$response = array();
		$response['error'] 	= '';

		$assortment_detail_id 	= $this->encrypt->decode($detail_id);

		$query = "DELETE FROM `release_order_detail` WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$assortment_detail_id);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE']);

		return $response;

	}

	public function update_assortment_head($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$entry_date 		= $entry_date.' '.date('H:i:s');
		$query_data 		= array($entry_date,$memo,$customer_name,$this->_current_user,$this->_current_date, $customer_id, $this->_assortment_head_id);

		$query = "UPDATE `release_order_head`
					SET
						`entry_date` = ?,
						`memo` = ?,
						`customer` = ?,
						`is_used` = ".\Constants\ASSORTMENT_CONST::USED.",
						`last_modified_by` = ?,
						`last_modified_date` = ?,
						`customer_id` = ?
					WHERE 
						`id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE_HEAD']);

		return $response;
	}

	public function search_assortment_list($param, $with_limit = TRUE)
	{
		extract($param);

		$response['rowcnt'] = 0;

		$this->db->select("PH.`id`, COALESCE(B.`name`,'') AS 'location',
							CONCAT('PA',PH.`reference_number`) AS 'reference_number', 
							COALESCE(C.`company_name`, PH.`customer`) AS 'customer',
							COALESCE(DATE(PH.`entry_date`),'') AS 'entry_date', IF(PH.`is_used` = 0, 'Unused', PH.`memo`) AS 'memo',
							COALESCE(SUM(PD.`quantity`),0) AS 'total_qty', PH.`is_used`,
							IF(PH.`is_used` = ".\Constants\ASSORTMENT_CONST::ACTIVE.",
								COALESCE(CASE 
									WHEN SUM(COALESCE(PD.`qty_released`,0)) = 0 THEN 'No Received'
									WHEN SUM(IF(PD.`quantity` - PD.`qty_released` < 0, 0, PD.`quantity` - PD.`qty_released`)) > 0 THEN 'Incomplete'
									WHEN SUM(PD.`quantity`) - SUM(PD.`qty_released`) = 0 THEN 'Complete'
									ELSE 'Excess'
								END,'') 
							, '') AS 'status',
							IF(PH.`is_used` = ".\Constants\ASSORTMENT_CONST::ACTIVE.",
								COALESCE(CASE 
									WHEN SUM(COALESCE(PD.`qty_released`,0)) = 0 THEN ".\Constants\ASSORTMENT_CONST::NO_RECEIVED."
									WHEN SUM(IF(PD.`quantity` - PD.`qty_released` < 0, 0, PD.`quantity` - PD.`qty_released`)) > 0 THEN ".\Constants\ASSORTMENT_CONST::INCOMPLETE."
									WHEN SUM(PD.`quantity`) - SUM(PD.`qty_released`) = 0 THEN ".\Constants\ASSORTMENT_CONST::COMPLETE."
									ELSE ".\Constants\ASSORTMENT_CONST::EXCESS."
								END,'') 
							, 0) AS 'status_code'")
				->from("release_order_head AS PH")
				->join("customer AS C", "C.`id` = PH.`customer_id`", "left")
				->join("release_order_detail AS PD", "PD.`headid` = PH.`id`", "left")
				->join("branch AS B", "B.`id` = PH.`branch_id` AND B.`is_show` = ".\Constants\ASSORTMENT_CONST::ACTIVE, "left")
				->where("PH.`is_show`", \Constants\ASSORTMENT_CONST::ACTIVE);

		if (!empty($date_from))
			$this->db->where("PH.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("PH.`entry_date` <=", $date_to." 23:59:59");

		if ($branch != \Constants\ASSORTMENT_CONST::ALL_OPTION) 
			$this->db->where("PH.`branch_id`", (int)$branch);

		if (isset($customer) && $customer != \Constants\ASSORTMENT_CONST::ALL_OPTION)
		{
			$customer = (int)$customer === \Constants\ASSORTMENT_CONST::WALKIN ? 0 : (int)$customer;
			$this->db->where("PH.`customer_id`", (int)$customer);
		}

		if (!empty($search_string)) 
			$this->db->like("CONCAT('PA', PH.`reference_number`,' ', PH.`memo`, ' ', COALESCE(C.`company_name`, PH.`customer`))", $search_string, "both");

		switch ($order_by) 
		{
			case \Constants\ASSORTMENT_CONST::ORDER_BY_REFERENCE:
				$order_field = "PH.`reference_number`";
				break;
			
			case \Constants\ASSORTMENT_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case \Constants\ASSORTMENT_CONST::ORDER_BY_DATE:
				$order_field = "PH.`entry_date`";
				break;

			case \Constants\ASSORTMENT_CONST::ORDER_BY_SUPPLIER:
				$order_field = "PH.`customer`";
				break;
		}

		$this->db->group_by("PH.`id`")
				->order_by($order_field, $order_type);

		if ($status != \Constants\ASSORTMENT_CONST::ALL_OPTION) 
			$this->db->having("status_code", $status); 

		if ($with_limit) 
		{
			$limit = $row_end - $row_start + 1;
			$this->db->limit((int)$limit, (int)$row_start);
		}

		$result = $this->db->get();

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $this->get_assortment_list_count_by_filter($param);

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($row_start + $i + 1);
				$response['data'][$i][] = array($row->location);
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array(date('m-d-Y', strtotime($row->entry_date)));
				$response['data'][$i][] = array($row->customer);
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
	
	public function get_assortment_list_count_by_filter($param)
	{
		extract($param);

		$this->db->select("IF(PH.`is_used` = ".\Constants\ASSORTMENT_CONST::ACTIVE.",
								COALESCE(CASE 
									WHEN SUM(COALESCE(PD.`qty_released`,0)) = 0 THEN ".\Constants\ASSORTMENT_CONST::NO_RECEIVED."
									WHEN SUM(IF(PD.`quantity` - PD.`qty_released` < 0, 0, PD.`quantity` - PD.`qty_released`)) > 0 THEN ".\Constants\ASSORTMENT_CONST::INCOMPLETE."
									WHEN SUM(PD.`quantity`) - SUM(PD.`qty_released`) = 0 THEN ".\Constants\ASSORTMENT_CONST::COMPLETE."
									ELSE ".\Constants\ASSORTMENT_CONST::EXCESS."
								END,'') 
							, 0) AS 'status_code'")
				->from("release_order_head AS PH")
				->join("customer AS C", "C.`id` = PH.`customer_id`", "left")
				->join("release_order_detail AS PD", "PD.`headid` = PH.`id`", "left")
				->where("PH.`is_show`", \Constants\ASSORTMENT_CONST::ACTIVE);

		if (!empty($date_from))
			$this->db->where("PH.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("PH.`entry_date` <=", $date_to." 23:59:59");

		if ($branch != \Constants\ASSORTMENT_CONST::ALL_OPTION) 
			$this->db->where("PH.`branch_id`", (int)$branch);

		if (isset($customer) && $customer != \Constants\ASSORTMENT_CONST::ALL_OPTION)
		{
			$customer = (int)$customer === \Constants\ASSORTMENT_CONST::WALKIN ? 0 : (int)$customer;
			$this->db->where("PH.`customer_id`", (int)$customer);
		}
		
		if (!empty($search_string)) 
			$this->db->like("CONCAT('PA', PH.`reference_number`,' ', PH.`memo`, ' ', COALESCE(C.`company_name`, PH.`customer`))", $search_string, "both");

		$this->db->group_by("PH.`id`");

		if ($status != \Constants\ASSORTMENT_CONST::ALL_OPTION) 
			$this->db->having("status_code", $status); 

		$inner_query = $this->db->get_compiled_select();

		$query_count = "SELECT COUNT(*) AS rowCount FROM ($inner_query)A";

		$result = $this->db->query($query_count);
		$row 	= $result->row();
		$count 	= $row->rowCount;

		$result->free_result();

		return $count;
	}

	public function delete_assortment_head($param)
	{
		extract($param);

		$assortment_head_id = $this->encrypt->decode($head_id);

		$response = array();
		$response['error'] = '';

		$query 	= "SELECT SUM(D.`qty_released`) AS 'total_released', H.`branch_id` 
						FROM release_order_head AS H
						LEFT JOIN release_order_detail AS D ON D.`headid` = H.`id` 
						WHERE H.`id` = ? AND H.`is_show` = ".\Constants\ASSORTMENT_CONST::ACTIVE;

		$result = $this->db->query($query,$assortment_head_id);
		$row 	= $result->row();

		if ($row->total_released > 0)
			throw new Exception($this->_error_message['HAS_RELEASED']);

		if ($row->branch_id != $this->_current_branch_id) 
			throw new Exception($this->_error_message['NOT_OWN_BRANCH']);

		$result->free_result();

		$query_data = array($this->_current_date,$this->_current_user,$assortment_head_id);

		$query 	= "UPDATE `release_order_head` 
					SET 
					`is_show` = ".\Constants\ASSORTMENT_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE_HEAD']);

		return $response;
	}

	public function get_release_order_printout_details()
	{
		$response = array();

		$response['error'] = '';

		$release_order_head_id = $this->encrypt->decode($this->session->userdata('release_slip'));

		$query_head = "SELECT 
							CONCAT('PA',H.`reference_number`) AS 'reference_number', 
							DATE(H.`entry_date`) AS 'entry_date', 
							COALESCE(C.`company_name`, H.`customer`) AS 'customer', 
							H.`memo`
						FROM 
							release_order_head AS H
						LEFT JOIN
							customer AS C ON C.`id` = H.`customer_id`
						LEFT JOIN 
							branch AS B ON B.`id` = H.`branch_id` AND B.`is_show` = ".\Constants\ASSORTMENT_CONST::ACTIVE."
						WHERE 
							H.`id` = ?";

		$result_head = $this->db->query($query_head,$release_order_head_id);
		
		if ($result_head->num_rows() == 1) 
		{
			$row = $result_head->row();

			foreach ($row as $key => $value)
				$response[$key] = $value;

			$response['assortment_number'] = '';
		}
		else
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
			
		$result_head->free_result();

		$query_detail = "SELECT D.`quantity` AS 'quantity', COALESCE(P.`description`,'-') AS 'product', 
							D.`description`, COALESCE(P.`material_code`,'-') AS 'item_code', D.`memo`,
							CASE
								WHEN P.`uom` = 1 THEN 'PCS'
								WHEN P.`uom` = 2 THEN 'KGS'
								WHEN P.`uom` = 3 THEN 'ROLL'
								ELSE ''
							END AS 'uom'
							FROM release_order_head AS H
							LEFT JOIN release_order_detail AS D ON D.`headid` = H.`id`
							LEFT JOIN product AS P ON P.`id` = D.`product_id`
							WHERE H.`id` = ?";

		$result_detail = $this->db->query($query_detail,$release_order_head_id);

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

		$response['page_title'] = 'PICK-UP ASSORTMENT';
		
		return $response;
	}

	public function check_if_transaction_has_product()
	{
		$this->db->select("D.*")
				->from("release_order_detail AS D")
				->join("release_order_head AS H", "H.`id` = D.`headid`", "left")
				->where("H.`is_show`", \Constants\ASSORTMENT_CONST::ACTIVE)
				->where("H.`id`", $this->_assortment_head_id);

		$result = $this->db->get();

		return $result;
	}

	public function get_sales_assortment_detail($param)
	{
		extract($param);

		$response['error'] = '';

		$sales_head_id = $this->encrypt->decode($sales_head_id);

		$query = "SELECT 
						COALESCE(RD.`id`, 0) AS 'id',
						SD.`id` AS 'sales_detail_id', 
						SD.`product_id`, 
						COALESCE(P.`material_code`,'') AS 'material_code',
						COALESCE(CONCAT(P.`description`, 
						IF(P.`is_show` = 0, '(Product Deleted)', '')),'') AS 'product',
						COALESCE(P.`is_show`, 0) AS 'is_deleted',
						COALESCE(RD.`quantity`, SD.`quantity` - SD.`qty_released`) AS 'quantity', 
						COALESCE(RD.`memo`, SD.`memo`) AS 'memo',
						CASE
							WHEN P.`uom` = ".\Constants\ASSORTMENT_CONST::PCS." THEN 'PCS'
							WHEN P.`uom` = ".\Constants\ASSORTMENT_CONST::KG." THEN 'KGS'
							WHEN P.`uom` = ".\Constants\ASSORTMENT_CONST::ROLL." THEN 'ROLL'
							ELSE ''
						END AS 'uom',
						CONCAT('SI', SH.`reference_number`) AS 'sales_reference', 
						COALESCE(RD.`description`, SD.`description`) AS 'description', 
						COALESCE(P.`type`, '') AS 'type',
						COALESCE(CONCAT('SI', SH.`reference_number`), '') AS 'invoice',
						IF(COALESCE(RD.`id`, 0) = 0 AND (SD.`quantity` - SD.`qty_released`) <= 0, 1, 0) AS 'is_removed',
						COALESCE(RD.`qty_released`, 0) AS 'qty_released',
						COALESCE(RD.`qty_remaining`, 0) AS 'qty_remaining'
					FROM 
						`sales_head` AS SH
					LEFT JOIN 
						`sales_detail` AS SD ON SD.`headid` = SH.`id` 
					LEFT JOIN 
						`product` AS P ON P.`id` = SD.`product_id`
					LEFT JOIN 
					(
						SELECT 
							RD.`sales_detail_id`, 
							RD.`quantity`, 
							RD.`id`, 
							RD.`memo`,
							RD.`description`,
							RD.`qty_released`,
							(IF((RD.`quantity` - RD.`qty_released`) < 0, 0, RD.`quantity` - RD.`qty_released`)) AS 'qty_remaining'
						FROM 
							release_order_head AS RH
						LEFT JOIN 
							release_order_detail AS RD ON RD.`headid` = RH.`id`
						WHERE 
							RH.`is_show` = ".\Constants\ASSORTMENT_CONST::ACTIVE." AND 
							RH.`id` = ?
					)
					AS RD ON RD.`sales_detail_id` = SD.`id`
					WHERE 
						SH.`is_show` = ".\Constants\ASSORTMENT_CONST::ACTIVE." AND 
						SH.`is_used` = ".\Constants\ASSORTMENT_CONST::USED." AND 
						SH.`id` = ?
					HAVING 
						is_removed = 0";

		$result = $this->db->query($query, [$this->_assortment_head_id, $sales_head_id]);

		if ($result->num_rows() == 0) 
			throw new Exception($this->_error_message['SALES_NOT_FOUND']);
		else
		{
			$i = 0;
			foreach ($result->result() as $row) 
			{
				$break_line = ($row->type == \Constants\ASSORTMENT_CONST::NON_STOCK || !empty($row->description)) ? '<br/>' : '';
				$response['detail'][$i][] = $row->id == 0 ? array(0) : array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array($this->encrypt->encode($row->sales_detail_id));
				$response['detail'][$i][] = array($row->sales_reference);
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line, $row->description, $row->is_deleted);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->uom);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->qty_released);
				$response['detail'][$i][] = array($row->qty_remaining);
				$response['detail'][$i][] = array($row->memo);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();

		return $response;
	}

	public function remove_imported_sales_from_assortment()
	{
		$response['error'] = '';

		$this->db->trans_start();
			$this->db->where("`sales_detail_id` >", 0);
			$this->db->where("`headid`", $this->_assortment_head_id);
			$this->db->delete("release_order_detail");
		$this->db->trans_complete();

		$error = $this->db->error()['message'];

		if (!empty($error)) 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE_HEAD']);

		return $response;
	}

	public function update_assortment_head_upon_customer_change($param)
	{
		extract($param);

		$assortment_head_data = [
									'customer_id' => $customer_id
								];

		$response['error'] = '';

		$this->db->trans_start();
			$this->db->where("`id`", $this->_assortment_head_id);
			$this->db->update('release_order_head', $assortment_head_data);
		$this->db->trans_complete();

		$error = $this->db->error()['message'];
			
		if (!empty($error)) 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE_HEAD']);

		return $response;
	}

	public function get_customer_sales_list($customer_id, $branch, $response = [])
	{
		$response['sales_list_error'] = '';

		$query_sales_list_data = array($this->_assortment_head_id, $customer_id);
		$query_sales_list = "SELECT 
									SH.`id`,
									IF(COUNT(RD.`id`) > 0, 1, 0) AS 'is_sold',
									CONCAT('SI', SH.`reference_number`) AS 'sales_reference',
									DATE(SH.`entry_date`) AS 'sales_date',
									SUM(SD.`quantity`) AS 'total_qty',
									COALESCE(S.`full_name`, '') AS 'salesman',
									SUM(IF((SD.`quantity` - SD.`qty_released`) < 0, 0, SD.`quantity` - SD.`qty_released`)) AS 'total_remaining_qty'
							FROM
								sales_head AS SH
							LEFT JOIN
								user AS S ON S.`id` = SH.`salesman_id`
							LEFT JOIN 
								sales_detail AS SD ON SD.`headid` = SH.`id`
							LEFT JOIN 
							(
								SELECT 
									RD.`sales_detail_id`, 
									RD.`id`, 
									RH.`branch_id`
								FROM 
									release_order_head AS RH
								LEFT JOIN 
									release_order_detail AS RD ON RD.`headid` = RH.`id`
								WHERE 
									RH.`is_show` = ".\Constants\ASSORTMENT_CONST::ACTIVE." AND 
									RH.`id` = ?
							)
							AS RD ON RD.`sales_detail_id` = SD.`id`
							WHERE
								SH.`is_show` = ".\Constants\ASSORTMENT_CONST::ACTIVE." AND 
								SH.`is_used` = ".\Constants\ASSORTMENT_CONST::USED." AND 
								SH.`customer_id` = ?
							GROUP BY SH.`id`
							HAVING 
								total_remaining_qty > 0 OR is_sold = 1";

		$result_sales_list = $this->db->query($query_sales_list, $query_sales_list_data);

		if ($result_sales_list->num_rows() == 0) 
			$response['sales_list_error'] = $this->_error_message['SALES_NOT_FOUND'];
		else
		{
			$i = 0;
			foreach ($result_sales_list->result() as $row) 
			{
				$response['sales_lists'][$i][] = array($this->encrypt->encode($row->id));
				$response['sales_lists'][$i][] = array($row->is_sold);
				$response['sales_lists'][$i][] = array($row->sales_reference);
				$response['sales_lists'][$i][] = array(date('m-d-Y', strtotime($row->sales_date)));
				$response['sales_lists'][$i][] = array($row->salesman);
				$response['sales_lists'][$i][] = array($row->total_qty);
				$i++;
			}
		}

		$result_sales_list->free_result();

		return $response;
	}
}
