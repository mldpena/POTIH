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

	public function get_sales_head_info_by_id($sales_head_id = 0)
	{
		$current_sales_head_id = $sales_head_id == 0 ? $this->_sales_head_id : $sales_head_id;

		$this->db->select("
							CONCAT('SI', SH.`reference_number`) AS 'reference_number', 
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
							SH.`drnumber`,
							SUM(SD.`quantity` * SD.`price`) AS 'amount',
							COALESCE(C.`company_name`, SH.`walkin_customer_name`) AS 'customer_displayed_name',
							COALESCE(C.`tin`, '') AS 'tin',
							COALESCE(S.`full_name`, '') AS 'salesman',
							COALESCE(C.`business_style`, '') AS 'customer_business_style'
						")
				->from("sales_head AS SH")
				->join("sales_detail AS SD", "SD.`headid` = SH.`id`", "left")
				->join("customer AS C", "C.`id` = SH.`customer_id`", "left")
				->join("user AS S", "S.`id` = SH.`salesman_id`", "left")
				->where("SH.`is_show`", \Constants\SALES_CONST::ACTIVE)
				->where("SH.`id`", $current_sales_head_id)
				->group_by("SH.`id`");

		return $this->db->get();
	}

	public function get_sales_detail_info_by_id($sales_head_id = 0)
	{
		$current_sales_head_id = $sales_head_id == 0 ? $this->_sales_head_id : $sales_head_id;

		$this->db->select("
							SD.`id`,
							SD.`product_id`, 
							COALESCE(P.`material_code`,'') AS 'material_code', 
							COALESCE(CONCAT(P.`description`, IF(P.`is_show` = 0, '(Product Deleted)', '')),'') AS 'product',
							COALESCE(CONCAT('SO', SRH.`reference_number`), '') AS 'reservation_number',
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
				->join("sales_reservation_detail AS SRD", "SRD.`id` = SD.`reservation_detail_id`", "left")
				->join("sales_reservation_head AS SRH", "SRH.`id` = SRD.`headid` AND SRH.`is_show` = ".\Constants\SALES_CONST::ACTIVE." AND SRH.`is_used` = ".\Constants\SALES_CONST::USED, "left")
				->join("product AS P", "P.`id` = SD.`product_id`", "left")
				->where("SD.`headid`", $current_sales_head_id);

		return $this->db->get();
	}

	public function get_sales_list_by_filter($param, $with_limit = TRUE)
	{
		extract($param);

		$this->db->select("
							SH.`id`, 
							COALESCE(B.`name`, '') AS 'location',
							COALESCE(B2.`name`, '') AS 'for_branch',
							CONCAT('SI', SH.`reference_number`) AS 'reference_number',
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
							, 0) AS 'status_code',
							COALESCE(SUM(SD.`price` * SD.`quantity`), 0) AS 'amount',
							IF(SH.`is_vatable` = ".\Constants\SALES_CONST::NONVAT.", 0, 
								(COALESCE(SUM(SD.`quantity` * SD.`price`), 0) * 0.12) / 1.12) AS 'vat_amount',
							IF(SH.`is_vatable` = ".\Constants\SALES_CONST::NONVAT.", 0, 
								COALESCE(SUM(SD.`quantity` * SD.`price`), 0) - 
								(COALESCE(SUM(SD.`quantity` * SD.`price`), 0) * 0.12) / 1.12) AS 'vatable_amount',
							IF(SH.`is_vatable` = ".\Constants\SALES_CONST::NONVAT.", COALESCE(SUM(SD.`price` * SD.`quantity`), 0), 0) AS 'vat_exempt_amount',
							COALESCE(C.`office_address`, SH.`walkin_customer_address`) AS 'customer_address'
						")
					->from("sales_head AS SH")
					->join("sales_detail AS SD", "SD.`headid` = SH.`id`", "left")
					->join("branch AS B", "B.`id` = SH.`branch_id`", "left")
					->join("branch AS B2", "B2.`id` = SH.`for_branch_id`", "left")
					->join("customer AS C", "C.`id` = SH.`customer_id`", "left")
					->join("user AS S", "S.`id` = SH.`salesman_id`", "left")
					->where("SH.`is_show`", \Constants\SALES_CONST::ACTIVE);

		if (isset($transaction_status))
			$this->db->where("SH.`is_used`", (int)$transaction_status);

		if (isset($date_from) && !empty($date_from))
			$this->db->where("SH.`entry_date` >=", $date_from." 00:00:00");

		if (isset($date_to) && !empty($date_to))
			$this->db->where("SH.`entry_date` <=", $date_to." 23:59:59");

		if (isset($branch) && $branch != \Constants\SALES_CONST::ALL_OPTION) 
			$this->db->where("SH.`branch_id`", (int)$branch);

		if (isset($for_branch) &&  $for_branch != \Constants\SALES_CONST::ALL_OPTION) 
			$this->db->where("SH.`for_branch_id`", (int)$for_branch);

		if (isset($customer) && $customer != \Constants\SALES_CONST::ALL_OPTION)
		{
			$customer = (int)$customer === \Constants\SALES_CONST::WALKIN ? 0 : (int)$customer;
			$this->db->where("SH.`customer_id`", (int)$customer);
		}

		if (isset($salesman) && $salesman != \Constants\SALES_CONST::ALL_OPTION) 
			$this->db->where("SH.`salesman_id`", $salesman);

		if (isset($search_string) && !empty($search_string)) 
			$this->db->like("CONCAT('SI', SH.`reference_number`, ' ', SH.`memo`, ' ', COALESCE(C.`company_name`, SH.`walkin_customer_name`))", $search_string, "both");

		if (isset($order_by)) 
		{
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
		}

		$this->db->group_by("SH.`id`")
				->order_by($order_field, $order_type);

		if (isset($status) &&  $status != \Constants\SALES_CONST::ALL_OPTION)
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
							, 0) AS 'status_code',
							COALESCE(SUM(SD.`quantity` * SD.`price`), 0) AS 'amount',
							IF(SH.`is_vatable` = ".\Constants\SALES_CONST::NONVAT.", 0, 
								(COALESCE(SUM(SD.`quantity` * SD.`price`), 0) * 0.12) / 1.12) AS 'vat_amount',
							IF(SH.`is_vatable` = ".\Constants\SALES_CONST::NONVAT.", 0, 
								COALESCE(SUM(SD.`quantity` * SD.`price`), 0) - 
								(COALESCE(SUM(SD.`quantity` * SD.`price`), 0) * 0.12) / 1.12) AS 'vatable_amount',
							IF(SH.`is_vatable` = ".\Constants\SALES_CONST::NONVAT.", COALESCE(SUM(SD.`price` * SD.`quantity`), 0), 0) AS 'vat_exempt_amount' 
						")
					->from("sales_head AS SH")
					->join("sales_detail AS SD", "SD.`headid` = SH.`id`", "left")
					->join("branch AS B", "B.`id` = SH.`branch_id`", "left")
					->join("branch AS B2", "B2.`id` = SH.`for_branch_id`", "left")
					->join("customer AS C", "C.`id` = SH.`customer_id`", "left")
					->where("SH.`is_show`", \Constants\SALES_CONST::ACTIVE);

		if (isset($transaction_status))
			$this->db->where("SH.`is_used`", (int)$transaction_status);

		if (isset($date_from) && !empty($date_from))
			$this->db->where("SH.`entry_date` >=", $date_from." 00:00:00");

		if (isset($date_to) && !empty($date_to))
			$this->db->where("SH.`entry_date` <=", $date_to." 23:59:59");

		if (isset($branch) && $branch != \Constants\SALES_CONST::ALL_OPTION) 
			$this->db->where("SH.`branch_id`", (int)$branch);

		if (isset($for_branch) &&  $for_branch != \Constants\SALES_CONST::ALL_OPTION) 
			$this->db->where("SH.`for_branch_id`", (int)$for_branch);

		if (isset($customer) && $customer != \Constants\SALES_CONST::ALL_OPTION)
		{
			$customer = (int)$customer === \Constants\SALES_CONST::WALKIN ? 0 : (int)$customer;
			$this->db->where("SH.`customer_id`", (int)$customer);
		}

		if (isset($salesman) && $salesman != \Constants\SALES_CONST::ALL_OPTION) 
			$this->db->where("SH.`salesman_id`", $salesman);

		if (isset($search_string) && !empty($search_string)) 
			$this->db->like("CONCAT('SI', SH.`reference_number`, ' ', SH.`memo`, ' ', COALESCE(C.`company_name`, SH.`walkin_customer_name`))", $search_string, "both");

		if (isset($order_by)) 
		{
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
		}

		$this->db->group_by("SH.`id`")
				->order_by($order_field, $order_type);

		if (isset($status) && $status != \Constants\SALES_CONST::ALL_OPTION)
		{
			if ($status != \Constants\SALES_CONST::INCOMPLETE_NO_DELIVERY)
				$this->db->having("status_code", $status); 
			else
			{
				$this->db->or_having("status_code", \Constants\SALES_CONST::INCOMPLETE); 
				$this->db->or_having("status_code", \Constants\SALES_CONST::NO_DELIVERY); 
			}
		}
		
		$inner_query = $this->db->get_compiled_select('', TRUE);

		$this->db->select("
							COUNT(*) AS rowCount, 
							SUM(A.`amount`) AS 'total_amount',
							SUM(A.`vatable_amount`) AS 'total_vatable_amount',
							SUM(A.`vat_amount`) AS 'total_vat_amount',
							SUM(A.`vat_exempt_amount`) AS 'total_vat_exempt_amount'
						")
				->from("($inner_query) AS A");

		return $this->db->get();
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

	public function get_customer_reservation_list_by_id($customer_id, $branch_id)
	{
		$this->db->select("
							SD.`reservation_detail_id`,
							SD.`id`, 
							SH.`for_branch_id`
						")
					->from("sales_head AS SH")
					->join("sales_detail AS SD", "SD.`headid` = SH.`id`", "left")
					->where("SH.`is_show`", \Constants\SALES_CONST::ACTIVE)
					->where("SH.`id`", $this->_sales_head_id);

		$sales_query = $this->db->get_compiled_select('', TRUE);

		$this->db->select("
							SRH.`id`,
							IF(COUNT(SD.`id`) > 0, 1, 0) AS 'is_sold',
						  	CONCAT('SO', SRH.`reference_number`) AS 'reservation_number',
						    DATE(SRH.`entry_date`) AS 'reservation_date',
						    SUM(SRD.`quantity`) AS 'total_qty',
						    COALESCE(S.`full_name`, '') AS 'salesman',
						    SUM(IF((SRD.`quantity` - SRD.`sold_qty`) < 0, 0, SRD.`quantity` - SRD.`sold_qty`)) AS 'total_remaining_qty'
						")
				->from("sales_reservation_head AS SRH")
				->join("sales_reservation_detail AS SRD", "SRD.`headid` = SRH.`id`", "left")
				->join("user AS S", "S.`id` = SRH.`salesman_id`", "left")
				->join("($sales_query) AS SD", "SD.`reservation_detail_id` = SRD.`id`", "left")
				->where("SRH.`is_show`", \Constants\SALES_CONST::ACTIVE)
				->where("SRH.`is_used`", \Constants\SALES_CONST::USED)
				->where("SRH.`customer_id`", $customer_id)
				->group_start()
					->where("SRH.`for_branch_id`", $branch_id)
					->or_where("SRH.`for_branch_id` = SD.`for_branch_id`")
				->group_end()
				->group_by("SRH.`id`")
				->having("total_remaining_qty >", 0)
				->or_having("is_sold", 1);

		return $this->db->get();
	}

	public function get_reservation_details_by_id($sales_reservation_head_id)
	{
		$this->db->select("
							SD.`reservation_detail_id`,
							SD.`quantity`,
							SD.`id`,
							SD.`memo`, 
							SD.`description`,
							FORMAT(SD.`price`, 2) AS 'price',
							SD.`qty_released`
						")
					->from("sales_head AS SH")
					->join("sales_detail AS SD", "SD.`headid` = SH.`id`", "left")
					->where("SH.`is_show`", \Constants\SALES_CONST::ACTIVE)
					->where("SH.`id`", $this->_sales_head_id);

		$sales_query = $this->db->get_compiled_select('', TRUE);

		$this->db->select("
							COALESCE(SD.`id`, 0) AS 'id',
							SRD.`id` AS 'reservation_detail_id',
							CONCAT('SO', SRH.`reference_number`) AS 'reservation_number', 
							SRD.`product_id`, 
							COALESCE(P.`material_code`,'') AS 'material_code',
							COALESCE(SD.`description`, SRD.`description`) AS 'description',
							COALESCE(CONCAT(P.`description`, IF(P.`is_show` = 0, '(Product Deleted)', '')),'') AS 'product',
							COALESCE(P.`is_show`, 0) AS 'is_deleted',
							CASE
								WHEN P.`uom` = ".\Constants\SALES_CONST::PCS." THEN 'PCS'
								WHEN P.`uom` = ".\Constants\SALES_CONST::KG." THEN 'KGS'
								WHEN P.`uom` = ".\Constants\SALES_CONST::ROLL." THEN 'ROLL'
								ELSE ''
							END AS 'uom',
							COALESCE(P.`type`, '') AS 'type',
							COALESCE(SD.`quantity`, SRD.`quantity` - SRD.`sold_qty`) AS 'quantity',
							COALESCE(SD.`memo`, SRD.`memo`) AS 'memo',
							COALESCE(SD.`price`, 0) AS 'price',
							COALESCE(SD.`qty_released`, 0) AS 'qty_released',
							FORMAT(COALESCE(SD.`price`, 0) * COALESCE(SD.`quantity`, SRD.`quantity`), 2) AS 'amount',
							IF(COALESCE(SD.`id`,0) = 0 AND (SRD.`quantity` - SRD.`sold_qty`) <= 0, 1, 0) AS 'is_removed'
						")
				->from("sales_reservation_head AS SRH")
				->join("sales_reservation_detail AS SRD", "SRD.`headid` = SRH.`id`", "left")
				->join("product AS P", "P.`id` = SRD.`product_id`", "left")
				->join("($sales_query) AS SD", "SD.`reservation_detail_id` = SRD.`id`", "left")
				->where("SRH.`is_show`", \Constants\SALES_CONST::ACTIVE)
				->where("SRH.`is_used`", \Constants\SALES_CONST::USED)
				->where("SRH.`id`", $sales_reservation_head_id)
				->having("is_removed", 0);

		return $this->db->get();
	}

	public function get_salesman_sales_by_filter($param)
	{
		extract($param);

		$this->db->select("
							COALESCE(S.`full_name`, '') AS 'salesman',
							COUNT(DISTINCT SH.`id`) AS 'invoice_count',
							COALESCE(SUM(SD.`price` * SD.`quantity`), 0) AS 'amount'
						")
					->from("sales_head AS SH")
					->join("sales_detail AS SD", "SD.`headid` = SH.`id`", "left")
					->join("branch AS B", "B.`id` = SH.`branch_id`", "left")
					->join("branch AS B2", "B2.`id` = SH.`for_branch_id`", "left")
					->join("customer AS C", "C.`id` = SH.`customer_id`", "left")
					->join("user AS S", "S.`id` = SH.`salesman_id`", "left")
					->where("SH.`is_show`", \Constants\SALES_CONST::ACTIVE);

		if (isset($transaction_status))
			$this->db->where("SH.`is_used`", (int)$transaction_status);
		
		if (isset($date_from) && !empty($date_from))
			$this->db->where("SH.`entry_date` >=", $date_from." 00:00:00");

		if (isset($date_to) && !empty($date_to))
			$this->db->where("SH.`entry_date` <=", $date_to." 23:59:59");

		if (isset($for_branch) &&  $for_branch != \Constants\SALES_CONST::ALL_OPTION) 
			$this->db->where("SH.`for_branch_id`", (int)$for_branch);

		if (isset($customer) && $customer != \Constants\SALES_CONST::ALL_OPTION)
		{
			$customer = (int)$customer === \Constants\SALES_CONST::WALKIN ? 0 : (int)$customer;
			$this->db->where("SH.`customer_id`", (int)$customer);
		}

		$this->db->group_by("SH.`salesman_id`");

		return $this->db->get();
	}

	public function check_if_transaction_has_product()
	{
		$this->db->select("D.*")
				->from("sales_detail AS D")
				->join("sales_head AS H", "H.`id` = D.`headid`", "left")
				->where("H.`is_show`", \Constants\SALES_CONST::ACTIVE)
				->where("H.`id`", $this->_sales_head_id);

		$result = $this->db->get();

		return $result;
	}

	public function get_sales_details_with_remaining($select_sales_detail_id)
	{
		$this->db->select("
							`id`, 
							`quantity`, 
							`product_id`, 
							`description`, 
							`memo`, 
							`qty_released`,
							`reservation_detail_id`,
							`price`
						")
				->from("sales_detail")
				->where("`qty_released` < `quantity`")
				->where_in("`id`", $select_sales_detail_id);

		$result = $this->db->get();

		return $result;
	}

	public function transfer_remaining_details_to_new_sales_invoice($old_sales_detail, $new_sales_detail)
	{
		$sales_detail_ids = array();

		for ($i=0; $i < count($old_sales_detail); $i++) 
		{
			$this->db->where("`id`", $old_sales_detail[$i]['id']);
			$this->db->update("sales_detail", $old_sales_detail[$i]['detail']);

			array_push($sales_detail_ids, $old_sales_detail[$i]['id']);
		}

		$this->db->where_in("`id`", $sales_detail_ids)
					->where("`qty_released`", 0);
		$this->db->delete("sales_detail");

		$this->db->insert_batch("sales_detail", $new_sales_detail);
	}
}
