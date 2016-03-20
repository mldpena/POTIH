<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales_Model extends CI_Model {

	private $_sales_head_id = 0;
	private $_current_branch_id = 0;

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() 
	{
		parent::__construct();

		$this->_sales_head_id 		= (int)$this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));

		$this->load->constant('sales_const');
	}

	public function get_sales_head_info_by_id()
	{
		$this->db->select("
							CONCAT('SO', SH.`reference_number`) AS 'reference_number', 
							SH.`for_branch_id`,
							SH.`branch_id`,
							SH.`customer_id`,
							SH.`walkin_customer_name`,
							COALESCE(C.`office_address`, SH.`walkin_customer_address`) AS 'address',
							DATE(SH.`entry_date`) AS 'entry_date',
							SH.`memo`,
							SH.`salesman_id`,
							SH.`is_used`, 
							SUM(IF(SD.`quantity` - SD.`qty_released` < 0, 0, SD.`quantity` - SD.`qty_released`)) AS 'remaining_qty', 
							SUM(SD.`qty_released`) AS 'qty_released',
							SH.`is_vatable`,
							SH.`ponumber`,
							SH.`drnumber`
						")
				->from("sales_head AS SH")
				->join("sales_detail AS SD", "SD.`headid` = SH.`id`", "left")
				->join("customer AS C", "C.`id` = SH.`customer_id`", "left")
				->where("SH.`is_show`", \Constants\SALES_CONST::ACTIVE)
				->where("SH.`id`", $this->_sales_head_id)
				->group_by("SH.`id`");

		return $this->db->get();
	}

	public function get_sales_detail_info_by_id()
	{
		$this->db->select("
							SD.`id`,
							SD.`product_id`, 
							COALESCE(P.`material_code`,'') AS 'material_code', 
							COALESCE(CONCAT(P.`description`, IF(P.`is_show` = 0, '(Product Deleted)', '')),'') AS 'product',
							COALESCE(P.`is_show`, 0) AS 'is_deleted',
							CASE
								WHEN P.`uom` = ".\Constants\SALES_CONST::PCS." THEN 'PCS'
								WHEN P.`uom` = ".\Constants\SALES_CONST::KG." THEN 'KGS'
								WHEN P.`uom` = ".\Constants\SALES_CONST::ROLL." THEN 'ROLL'
								ELSE ''
							END AS 'uom', 
							SD.`quantity`, 
							SD.`memo`, 
							SD.`description`, 
							COALESCE(P.`type`, '') AS 'type', 
							SD.`qty_released`,
							FORMAT(SD.`price`, 2) AS 'price',
							FORMAT(SD.`price` * SD.`quantity`, 2) AS 'amount',
							SD.`reservation_detail_id`
						")
				->from("sales_detail AS SD")
				->join("product AS P", "P.`id` = SD.`product_id`", "left")
				->where("SD.`headid`", $this->_sales_head_id);

		return $this->db->get();
	}

	public function get_sales_list_by_filter($param, $with_limit = TRUE)
	{
		extract($param);

		$this->db->select("
							SH.`id`, 
							COALESCE(B.`name`, '') AS 'location',
							COALESCE(B2.`name`, '') AS 'for_branch',
							CONCAT('SO', SH.`reference_number`) AS 'reference_number',
							COALESCE(C.`company_name`, SH.`walkin_customer_name`) AS 'customer',
							COALESCE(S.`full_name`, '') AS 'salesman',
							DATE(SH.`entry_date`) AS 'entry_date',
							IF(SH.`is_used` = ".\Constants\SALES_CONST::ACTIVE.", SH.`memo`, 'Unused') AS 'memo',
							IF(SH.`is_used` = ".\Constants\SALES_CONST::ACTIVE.",
								COALESCE(CASE 
									WHEN SUM(COALESCE(SD.`qty_released`,0)) = 0 THEN 'No Received'
									WHEN SUM(IF(SD.`quantity` - SD.`qty_released` < 0, 0, SD.`quantity` - SD.`qty_released`)) > 0 THEN 'Incomplete'
									WHEN SUM(SD.`quantity`) - SUM(SD.`qty_released`) = 0 THEN 'Complete'
									ELSE 'Excess'
								END,'') 
							, '') AS 'status',
							IF(SH.`is_used` = ".\Constants\SALES_CONST::ACTIVE.",
								COALESCE(CASE 
									WHEN SUM(COALESCE(SD.`qty_released`,0)) = 0 THEN ".\Constants\SALES_CONST::NO_DELIVERY."
									WHEN SUM(IF(SD.`quantity` - SD.`qty_released` < 0, 0, SD.`quantity` - SD.`qty_released`)) > 0 THEN ".\Constants\SALES_CONST::INCOMPLETE."
									WHEN SUM(SD.`quantity`) - SUM(SD.`qty_released`) = 0 THEN ".\Constants\SALES_CONST::COMPLETE."
									ELSE ".\Constants\SALES_CONST::EXCESS."
								END,'') 
							, 0) AS 'status_code'
						")
					->from("sales_head AS SH")
					->join("sales_detail AS SD", "SD.`headid` = SH.`id`", "left")
					->join("branch AS B", "B.`id` = SH.`branch_id`", "left")
					->join("branch AS B2", "B2.`id` = SH.`for_branch_id`", "left")
					->join("customer AS C", "C.`id` = SH.`customer_id`", "left")
					->join("user AS S", "S.`id` = SH.`salesman_id`", "left")
					->where("SH.`is_show`", \Constants\SALES_CONST::ACTIVE);

		if (!empty($date_from))
			$this->db->where("SH.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("SH.`entry_date` <=", $date_to." 23:59:59");

		if ($branch != \Constants\SALES_CONST::ALL_OPTION) 
			$this->db->where("SH.`branch_id`", (int)$branch);

		if ($for_branch != \Constants\SALES_CONST::ALL_OPTION) 
			$this->db->where("SH.`for_branch_id`", (int)$for_branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('SO', SH.`reference_number`, ' ', SH.`memo`, ' ', COALESCE(C.`company_name`, SH.`walkin_customer_name`))", $search_string, "both");

		if ($customer != \Constants\SALES_CONST::ALL_OPTION)
		{
			$customer = (int)$customer === \Constants\SALES_CONST::WALKIN ? 0 : (int)$customer;
			$this->db->where("SH.`customer_id`", (int)$customer);
		}

		switch ($order_by) 
		{
			case \Constants\SALES_CONST::ORDER_BY_REFERENCE:
				$order_field = "SH.`reference_number`";
				break;
			
			case \Constants\SALES_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case \Constants\SALES_CONST::ORDER_BY_DATE:
				$order_field = "SH.`entry_date`";
				break;

			case \Constants\SALES_CONST::ORDER_BY_CUSTOMER:
				$order_field = "`customer`";
				break;
		}

		$this->db->group_by("SH.`id`")
				->order_by($order_field, $order_type);

		if ($status != \Constants\SALES_CONST::ALL_OPTION)
			$this->db->having("status_code", $status); 

		if ($with_limit) 
		{
			$limit = $row_end - $row_start + 1;
			$this->db->limit((int)$limit, (int)$row_start);
		}
		
		return $this->db->get();
	}

	public function get_sales_list_count_by_filter($param)
	{
		extract($param);

		$this->db->select("
							COALESCE(C.`company_name`, SH.`walkin_customer_name`) AS 'customer',
							IF(SH.`is_used` = ".\Constants\SALES_CONST::ACTIVE.",
								COALESCE(CASE 
									WHEN SUM(COALESCE(SD.`qty_released`,0)) = 0 THEN ".\Constants\SALES_CONST::NO_DELIVERY."
									WHEN SUM(IF(SD.`quantity` - SD.`qty_released` < 0, 0, SD.`quantity` - SD.`qty_released`)) > 0 THEN ".\Constants\SALES_CONST::INCOMPLETE."
									WHEN SUM(SD.`quantity`) - SUM(SD.`qty_released`) = 0 THEN ".\Constants\SALES_CONST::COMPLETE."
									ELSE ".\Constants\SALES_CONST::EXCESS."
								END,'') 
							, 0) AS 'status_code'
						")
					->from("sales_head AS SH")
					->join("sales_detail AS SD", "SD.`headid` = SH.`id`", "left")
					->join("branch AS B", "B.`id` = SH.`branch_id`", "left")
					->join("branch AS B2", "B2.`id` = SH.`for_branch_id`", "left")
					->join("customer AS C", "C.`id` = SH.`customer_id`", "left")
					->where("SH.`is_show`", \Constants\SALES_CONST::ACTIVE);

		if (!empty($date_from))
			$this->db->where("SH.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("SH.`entry_date` <=", $date_to." 23:59:59");

		if ($branch != \Constants\SALES_CONST::ALL_OPTION) 
			$this->db->where("SH.`branch_id`", (int)$branch);

		if ($for_branch != \Constants\SALES_CONST::ALL_OPTION) 
			$this->db->where("SH.`for_branch_id`", (int)$for_branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('SO', SH.`reference_number`, ' ', SH.`memo`, ' ', COALESCE(C.`company_name`, SH.`walkin_customer_name`))", $search_string, "both");

		if ($customer != \Constants\SALES_CONST::ALL_OPTION)
		{
			$customer = (int)$customer === \Constants\SALES_CONST::WALKIN ? 0 : (int)$customer;
			$this->db->where("SH.`customer_id`", (int)$customer);
		}

		switch ($order_by) 
		{
			case \Constants\SALES_CONST::ORDER_BY_REFERENCE:
				$order_field = "SH.`reference_number`";
				break;
			
			case \Constants\SALES_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case \Constants\SALES_CONST::ORDER_BY_DATE:
				$order_field = "SH.`entry_date`";
				break;

			case \Constants\SALES_CONST::ORDER_BY_CUSTOMER:
				$order_field = "`customer`";
				break;
		}

		$this->db->group_by("SH.`id`")
				->order_by($order_field, $order_type);

		if ($status != \Constants\SALES_CONST::ALL_OPTION)
		{
			if ($status != \Constants\SALES_CONST::INCOMPLETE_NO_DELIVERY)
				$this->db->having("status_code", $status); 
			else
			{
				$this->db->or_having("status_code", \Constants\SALES_CONST::INCOMPLETE); 
				$this->db->or_having("status_code", \Constants\SALES_CONST::NO_DELIVERY); 
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

	public function insert_new_sales_detail($reservation_detail_data)
	{
		$response = [];

		$this->db->trans_start();
			$this->db->insert('sales_detail', $reservation_detail_data);
			$new_sales_id = $this->db->insert_id();
		$this->db->trans_complete();

		$response['error'] 	= $this->db->error()['message'];
		$response['id'] 	= $this->encrypt->encode($new_sales_id);

		return $response;
	}


	public function update_sales_table($reservation_data, $table_name, $table_id = 0)
	{
		$response = [];

		$this->db->trans_start();
			$this->db->where("`id`", $table_id);
			$this->db->update($table_name, $reservation_data);
		$this->db->trans_complete();

		$response['error'] = $this->db->error()['message'];

		return $response;
	}

	public function delete_sales_detail_by_id($reservation_detail_id = 0)
	{
		$response = [];

		$this->db->trans_start();
			$this->db->where("`id`", $reservation_detail_id);
			$this->db->delete("sales_detail");
		$this->db->trans_complete();

		$response['error'] = $this->db->error()['message'];

		return $response;
	}

	public function get_transaction_total_delivered_quantity($sales_head_id)
	{
		$this->db->select("SUM(SD.`qty_released`) AS 'qty_released', SH.`branch_id`")
				->from("sales_head AS SH")
				->join("sales_detail AS SD", "SD.`headid` = SH.`id`", "left")
				->where("SH.`id`", $sales_head_id)
				->where("SH.`is_show`", \Constants\SALES_CONST::ACTIVE);

		return $this->db->get();
	}

	public function delete_imported_reservation_by_transaction_id()
	{
		$response = [];

		$this->db->trans_start();
			$this->db->where("`reservation_detail_id` >", 0);
			$this->db->where("`headid`", $this->_sales_head_id);
			$this->db->delete("sales_detail");
		$this->db->trans_complete();

		$response['error'] = $this->db->error()['message'];

		return $response;
	}
}