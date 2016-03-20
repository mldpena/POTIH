<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SalesReservation_Model extends CI_Model {

	private $_sales_reservation_head_id = 0;
	private $_current_branch_id = 0;

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() 
	{
		parent::__construct();

		$this->_sales_reservation_head_id = (int)$this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));

		$this->load->constant('salesreservation_const');
	}

	public function get_sales_reservation_head_info_by_id()
	{
		$this->db->select("
							CONCAT('SO', SRH.`reference_number`) AS 'reference_number', 
							SRH.`for_branch_id`,
							SRH.`branch_id`,
							SRH.`customer_id`,
							SRH.`walkin_customer_name`,
							COALESCE(C.`office_address`, SRH.`walkin_customer_address`) AS 'address',
							DATE(SRH.`entry_date`) AS 'entry_date', 
							IF(SRH.`due_date` = '0000-00-00', '', SRH.`due_date`) AS 'due_date', 
							SRH.`memo`,
							SRH.`salesman_id`,
							SRH.`is_used`, 
							SUM(IF(SRD.`quantity` - SRD.`sold_qty` < 0, 0, SRD.`quantity` - SRD.`sold_qty`)) AS 'remaining_qty', 
							SUM(SRD.`sold_qty`) AS 'sold_qty'
						")
				->from("sales_reservation_head AS SRH")
				->join("sales_reservation_detail AS SRD", "SRD.`headid` = SRH.`id`", "left")
				->join("customer AS C", "C.`id` = SRH.`customer_id`", "left")
				->where("SRH.`is_show`", \Constants\SALESRESERVATION_CONST::ACTIVE)
				->where("SRH.`id`", $this->_sales_reservation_head_id)
				->group_by("SRH.`id`");

		return $this->db->get();
	}

	public function get_sales_reservation_detail_info_by_id()
	{
		$this->db->select("
							SRD.`id`,
							SRD.`product_id`, 
							COALESCE(P.`material_code`,'') AS 'material_code', 
							COALESCE(CONCAT(P.`description`, IF(P.`is_show` = 0, '(Product Deleted)', '')),'') AS 'product',
							COALESCE(P.`is_show`, 0) AS 'is_deleted',
							CASE
								WHEN P.`uom` = ".\Constants\SALESRESERVATION_CONST::PCS." THEN 'PCS'
								WHEN P.`uom` = ".\Constants\SALESRESERVATION_CONST::KG." THEN 'KGS'
								WHEN P.`uom` = ".\Constants\SALESRESERVATION_CONST::ROLL." THEN 'ROLL'
								ELSE ''
							END AS 'uom', 
							SRD.`quantity`, 
							SRD.`memo`, 
							SRD.`description`, 
							COALESCE(P.`type`, '') AS 'type', 
							SRD.`sold_qty`
						")
				->from("sales_reservation_detail AS SRD")
				->join("product AS P", "P.`id` = SRD.`product_id`", "left")
				->where("SRD.`headid`", $this->_sales_reservation_head_id);

		return $this->db->get();
	}

	public function get_sales_reservation_list_by_filter($param, $with_limit = TRUE)
	{
		extract($param);

		$this->db->select("
							SRH.`id`, 
							COALESCE(B.`name`, '') AS 'location',
							COALESCE(B2.`name`, '') AS 'for_branch',
							CONCAT('SO', SRH.`reference_number`) AS 'reference_number',
							COALESCE(C.`company_name`, SRH.`walkin_customer_name`) AS 'customer',
							COALESCE(S.`full_name`, '') AS 'salesman',
							DATE(SRH.`entry_date`) AS 'entry_date',
							IF(SRH.`is_used` = ".\Constants\SALESRESERVATION_CONST::ACTIVE.", SRH.`memo`, 'Unused') AS 'memo',
							IF(SRH.`is_used` = ".\Constants\SALESRESERVATION_CONST::ACTIVE.",
								COALESCE(CASE 
									WHEN SUM(COALESCE(SRD.`sold_qty`,0)) = 0 THEN 'No Received'
									WHEN SUM(IF(SRD.`quantity` - SRD.`sold_qty` < 0, 0, SRD.`quantity` - SRD.`sold_qty`)) > 0 THEN 'Incomplete'
									WHEN SUM(SRD.`quantity`) - SUM(SRD.`sold_qty`) = 0 THEN 'Complete'
									ELSE 'Excess'
								END,'') 
							, '') AS 'status',
							IF(SRH.`is_used` = ".\Constants\SALESRESERVATION_CONST::ACTIVE.",
								COALESCE(CASE 
									WHEN SUM(COALESCE(SRD.`sold_qty`,0)) = 0 THEN ".\Constants\SALESRESERVATION_CONST::NO_SOLD."
									WHEN SUM(IF(SRD.`quantity` - SRD.`sold_qty` < 0, 0, SRD.`quantity` - SRD.`sold_qty`)) > 0 THEN ".\Constants\SALESRESERVATION_CONST::INCOMPLETE."
									WHEN SUM(SRD.`quantity`) - SUM(SRD.`sold_qty`) = 0 THEN ".\Constants\SALESRESERVATION_CONST::COMPLETE."
									ELSE ".\Constants\SALESRESERVATION_CONST::EXCESS."
								END,'') 
							, 0) AS 'status_code'
						")
					->from("sales_reservation_head AS SRH")
					->join("sales_reservation_detail AS SRD", "SRD.`headid` = SRH.`id`", "left")
					->join("branch AS B", "B.`id` = SRH.`branch_id`", "left")
					->join("branch AS B2", "B2.`id` = SRH.`for_branch_id`", "left")
					->join("customer AS C", "C.`id` = SRH.`customer_id`", "left")
					->join("user AS S", "S.`id` = SRH.`salesman_id`", "left")
					->where("SRH.`is_show`", \Constants\SALESRESERVATION_CONST::ACTIVE);

		if (!empty($date_from))
			$this->db->where("SRH.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("SRH.`entry_date` <=", $date_to." 23:59:59");

		if ($branch != \Constants\SALESRESERVATION_CONST::ALL_OPTION) 
			$this->db->where("SRH.`branch_id`", (int)$branch);

		if ($for_branch != \Constants\SALESRESERVATION_CONST::ALL_OPTION) 
			$this->db->where("SRH.`for_branch_id`", (int)$for_branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('SO', SRH.`reference_number`, ' ', SRH.`memo`, ' ', COALESCE(C.`company_name`, SRH.`walkin_customer_name`))", $search_string, "both");

		if ($customer != \Constants\SALESRESERVATION_CONST::ALL_OPTION)
		{
			$customer = (int)$customer === \Constants\SALESRESERVATION_CONST::WALKIN ? 0 : (int)$customer;
			$this->db->where("SRH.`customer_id`", (int)$customer);
		}

		switch ($order_by) 
		{
			case \Constants\SALESRESERVATION_CONST::ORDER_BY_REFERENCE:
				$order_field = "SRH.`reference_number`";
				break;
			
			case \Constants\SALESRESERVATION_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case \Constants\SALESRESERVATION_CONST::ORDER_BY_DATE:
				$order_field = "SRH.`entry_date`";
				break;

			case \Constants\SALESRESERVATION_CONST::ORDER_BY_CUSTOMER:
				$order_field = "`customer`";
				break;
		}

		$this->db->group_by("SRH.`id`")
				->order_by($order_field, $order_type);

		if ($status != \Constants\SALESRESERVATION_CONST::ALL_OPTION)
		{
			if ($status != \Constants\SALESRESERVATION_CONST::INCOMPLETE_NO_SOLD)
				$this->db->having("status_code", $status); 
			else
			{
				$this->db->or_having("status_code", \Constants\SALESRESERVATION_CONST::INCOMPLETE); 
				$this->db->or_having("status_code", \Constants\SALESRESERVATION_CONST::NO_SOLD); 
			}
		}

		if ($with_limit) 
		{
			$limit = $row_end - $row_start + 1;
			$this->db->limit((int)$limit, (int)$row_start);
		}
		
		return $this->db->get();
	}

	public function get_sales_reservation_list_count_by_filter($param)
	{
		extract($param);

		$this->db->select("
							COALESCE(C.`company_name`, SRH.`walkin_customer_name`) AS 'customer',
							IF(SRH.`is_used` = ".\Constants\SALESRESERVATION_CONST::ACTIVE.",
								COALESCE(CASE 
									WHEN SUM(COALESCE(SRD.`sold_qty`,0)) = 0 THEN ".\Constants\SALESRESERVATION_CONST::NO_SOLD."
									WHEN SUM(IF(SRD.`quantity` - SRD.`sold_qty` < 0, 0, SRD.`quantity` - SRD.`sold_qty`)) > 0 THEN ".\Constants\SALESRESERVATION_CONST::INCOMPLETE."
									WHEN SUM(SRD.`quantity`) - SUM(SRD.`sold_qty`) = 0 THEN ".\Constants\SALESRESERVATION_CONST::COMPLETE."
									ELSE ".\Constants\SALESRESERVATION_CONST::EXCESS."
								END,'') 
							, 0) AS 'status_code'
						")
					->from("sales_reservation_head AS SRH")
					->join("sales_reservation_detail AS SRD", "SRD.`headid` = SRH.`id`", "left")
					->join("branch AS B", "B.`id` = SRH.`branch_id`", "left")
					->join("branch AS B2", "B2.`id` = SRH.`for_branch_id`", "left")
					->join("customer AS C", "C.`id` = SRH.`customer_id`", "left")
					->where("SRH.`is_show`", \Constants\SALESRESERVATION_CONST::ACTIVE);

		if (!empty($date_from))
			$this->db->where("SRH.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("SRH.`entry_date` <=", $date_to." 23:59:59");

		if ($branch != \Constants\SALESRESERVATION_CONST::ALL_OPTION) 
			$this->db->where("SRH.`branch_id`", (int)$branch);

		if ($for_branch != \Constants\SALESRESERVATION_CONST::ALL_OPTION) 
			$this->db->where("SRH.`for_branch_id`", (int)$for_branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('SO', SRH.`reference_number`, ' ', SRH.`memo`, ' ', COALESCE(C.`company_name`, SRH.`walkin_customer_name`))", $search_string, "both");

		if ($customer != \Constants\SALESRESERVATION_CONST::ALL_OPTION)
		{
			$customer = (int)$customer === \Constants\SALESRESERVATION_CONST::WALKIN ? 0 : (int)$customer;
			$this->db->where("SRH.`customer_id`", (int)$customer);
		}

		switch ($order_by) 
		{
			case \Constants\SALESRESERVATION_CONST::ORDER_BY_REFERENCE:
				$order_field = "SRH.`reference_number`";
				break;
			
			case \Constants\SALESRESERVATION_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case \Constants\SALESRESERVATION_CONST::ORDER_BY_DATE:
				$order_field = "SRH.`entry_date`";
				break;

			case \Constants\SALESRESERVATION_CONST::ORDER_BY_CUSTOMER:
				$order_field = "`customer`";
				break;
		}

		$this->db->group_by("SRH.`id`")
				->order_by($order_field, $order_type);

		if ($status != \Constants\SALESRESERVATION_CONST::ALL_OPTION)
		{
			if ($status != \Constants\SALESRESERVATION_CONST::INCOMPLETE_NO_SOLD)
				$this->db->having("status_code", $status); 
			else
			{
				$this->db->or_having("status_code", \Constants\SALESRESERVATION_CONST::INCOMPLETE); 
				$this->db->or_having("status_code", \Constants\SALESRESERVATION_CONST::NO_SOLD); 
			}
		}
		
		$inner_query = $this->db->get_compiled_select();

		$query_count = "SELECT COUNT(*) AS rowCount FROM ($inner_query)A";

		$result = $this->db->query($query_count);
		$row 	= $result->row();
		$count 	= $row->rowCount;

		$result->free_result();

		return $count;
	}

	public function insert_new_sales_reservation_detail($reservation_detail_data)
	{
		$response = [];

		$this->db->trans_start();
			$this->db->insert('sales_reservation_detail', $reservation_detail_data);
			$new_sales_reservation_id = $this->db->insert_id();
		$this->db->trans_complete();

		$response['error'] 	= $this->db->error()['message'];
		$response['id'] 	= $this->encrypt->encode($new_sales_reservation_id);

		return $response;
	}


	public function update_sales_reservation_table($reservation_data, $table_name, $table_id = 0)
	{
		$response = [];

		$this->db->trans_start();
			$this->db->where("`id`", $table_id);
			$this->db->update($table_name, $reservation_data);
		$this->db->trans_complete();

		$response['error'] = $this->db->error()['message'];

		return $response;
	}

	public function delete_sales_reservation_detail_by_id($reservation_detail_id = 0)
	{
		$response = [];

		$this->db->trans_start();
			$this->db->where("`id`", $reservation_detail_id);
			$this->db->delete("sales_reservation_detail");
		$this->db->trans_complete();

		$response['error'] = $this->db->error()['message'];

		return $response;
	}

	public function get_due_reservation_notification_count()
	{
		$count = 0;

		$this->db->select("
							SUM(COALESCE(SRD.`sold_qty`,0)) AS 'qty_sold',
							SUM(COALESCE(SRD.`quantity`,0)) AS 'total_qty'
						")
				->from("sales_reservation_head AS SRH")
				->join("sales_reservation_detail AS SRD", "SRD.`headid` = SRH.`id`", "left")
				->where("SRH.`is_show`", \Constants\SALESRESERVATION_CONST::ACTIVE)
				->where("SRH.`for_branch_id`", $this->_current_branch_id)
				->where("SRH.`due_date` < CURDATE()")
				->group_by("SRH.`id`")
				->having("qty_sold < total_qty");

		$inner_query = $this->db->get_compiled_select();

		$query_count = "SELECT COUNT(*) AS rowCount FROM ($inner_query)A";

		$result = $this->db->query($query_count);
		$row 	= $result->row();
		$count 	= $row->rowCount;

		$result->free_result();

		return $count;
	}

	public function get_current_product_reservation($product_id, $branch_id)
	{
		$this->db->select("
							CONCAT('SO', SRH.`reference_number`) AS 'reference_number',
							COALESCE(S.`full_name`, '') AS 'salesman',
							COALESCE(B.`name`, '') AS 'branch',
							DATE(SRH.`entry_date`) AS 'entry_date',
							SRD.`quantity`,
							SRD.`quantity` - SRD.`sold_qty` AS 'unsold_qty'
						")
				->from("sales_reservation_head AS SRH")
				->join("sales_reservation_detail AS SRD", "SRD.`headid` = SRH.`id` AND SRD.`product_id` = ".$product_id." AND (SRD.`quantity` - SRD.`sold_qty` > 0)", "inner")
				->join("branch AS B", "B.`id` = SRH.`for_branch_id`", "left")
				->join("user AS S", "S.`id` = SRH.`salesman_id`", "left")
				->where("SRH.`is_show`", \Constants\SALESRESERVATION_CONST::ACTIVE)
				->where("SRH.`for_branch_id`", $branch_id)
				->order_by("SRH.`entry_date`", "ASC");

		return $this->db->get();
	}

	public function get_transaction_total_sold_quantity($sales_head_id)
	{
		$this->db->select("SUM(SRD.`sold_qty`) AS 'sold_qty', SRH.`branch_id`")
				->from("sales_reservation_head AS SRH")
				->join("sales_reservation_detail AS SRD", "SRD.`headid` = SRH.`id`", "left")
				->where("SRH.`id`", $sales_head_id)
				->where("SRH.`is_show`", \Constants\SALESRESERVATION_CONST::ACTIVE);

		return $this->db->get();
	}
}
