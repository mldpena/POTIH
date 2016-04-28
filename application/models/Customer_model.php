<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_Model extends CI_Model {

	private $_customer_id = 0;
	private $_current_user = 0;
	private $_current_date = '';

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		parent::__construct();

		$this->load->constant('customer_const');

		$this->_customer_id 	= $this->encrypt->decode($this->uri->segment(3));
		$this->_current_user 	= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 	= date("Y-m-d H:i:s");
	}

	public function get_customer_list_by_filter($param, $with_limit = TRUE)
	{
		extract($param);

		$order_field 	= "";
		
		$this->db->select(" `id`, 
							`code`, 
							`company_name`, 
							`contact`, 
							`tin`,
							CASE 
								WHEN `is_vatable` = ".\Constants\CUSTOMER_CONST::VATABLE." THEN 'Vatable'
								WHEN `is_vatable` = ".\Constants\CUSTOMER_CONST::NONVAT." THEN 'Non-vat'
							END AS 'is_vatable'")
				->from("customer")
				->where("`is_show`", \Constants\CUSTOMER_CONST::ACTIVE);

		if (isset($is_vat) && $is_vat != \Constants\CUSTOMER_CONST::ALL_OPTION)
			$this->db->where("`is_vatable`", $is_vat);

		if (isset($search_string) && !empty($search_string)) 
			$this->db->like("CONCAT(`code`,' ',`company_name`)", $search_string, "both");

		switch ($order_by) 
		{
			case \Constants\USER_CONST::ORDER_BY_NAME:
				$order_field = "`company_name`";
				break;
			
			case \Constants\USER_CONST::ORDER_BY_CODE:
				$order_field = "`code`";
				break;
		}

		$this->db->order_by($order_field, $order_type);

		if ($with_limit) 
		{
			$limit = $row_end - $row_start + 1;
			$this->db->limit($limit, $row_start);
		}

		$result = $this->db->get();

		return $result;
	}

	public function get_customer_list_count_by_filter($param)
	{
		extract($param);

		$this->db->from("customer")
				->where("`is_show`", \Constants\CUSTOMER_CONST::ACTIVE);

		if ($is_vat != \Constants\CUSTOMER_CONST::ALL_OPTION)
			$this->db->where("`is_vatable`", $is_vat);

		if (!empty($search_string)) 
			$this->db->like("CONCAT(`code`,' ',`company_name`)", $search_string, "both");

		return $this->db->count_all_results();
	}

	public function check_if_field_data_exists($field_data)
	{
		$this->db->select("`id`")
				->from("customer")
				->where("`is_show`", \Constants\CUSTOMER_CONST::ACTIVE);

		if ($this->_customer_id !== 0)
			$this->db->where("`id` <>", $this->_customer_id);

		foreach ($field_data as $key => $value) 
			$this->db->where($key, $value);

		$result = $this->db->get();

		return $result;
	}

	public function insert_new_customer($customer_field_data)
	{
		$response = array();

		$this->db->trans_start();
			$this->db->insert('customer', $customer_field_data);
			$new_customer_id = $this->db->insert_id();
		$this->db->trans_complete();

		$response['error'] 	= $this->db->error()['message'];
		$response['id'] 	= $this->encrypt->encode($new_customer_id);

		return $response;
	}

	public function update_customer_details_by_id($customer_field_data, $customer_id = 0)
	{
		$customer_id = $customer_id == 0 ? $this->_customer_id : $customer_id;

		$response = [];

		$this->db->trans_start();
			$this->db->where("`id`", $customer_id);
			$this->db->update("customer", $customer_field_data);
		$this->db->trans_complete();

		$response['error'] = $this->db->error()['message'];

		return $response;
	}

	public function get_customer_details_by_id($customer_id)
	{
		$this->db->from("customer")
				->where("`is_show`", \Constants\CUSTOMER_CONST::ACTIVE)
				->where("`id`", $customer_id);

		$result = $this->db->get();

		return $result;
	}

	public function check_customer_transaction($customer_id)
	{
		$this->db->select("
					SUM(IF(ROH.`id` IS NULL, 0, 1)) + 
				    SUM(IF(RH.`id` IS NULL, 0, 1)) + 
				    SUM(IF(SH.`id` IS NULL, 0, 1)) + 
				    SUM(IF(SRH.`id` IS NULL, 0, 1)) AS 'transaction_count'")
				->from("customer AS C")
				->join("release_order_head AS ROH", "ROH.`customer_id` = C.`id` AND ROH.`is_show` = ".\Constants\CUSTOMER_CONST::ACTIVE." AND ROH.`is_used` = ".\Constants\CUSTOMER_CONST::USED, "left")
				->join("return_head AS RH", "RH.`customer_id` = C.`id` AND RH.`is_show` = ".\Constants\CUSTOMER_CONST::ACTIVE." AND RH.`is_used` = ".\Constants\CUSTOMER_CONST::USED, "left")
				->join("sales_head AS SH", "SH.`customer_id` = C.`id` AND SH.`is_show` = ".\Constants\CUSTOMER_CONST::ACTIVE." AND SH.`is_used` = ".\Constants\CUSTOMER_CONST::USED, "left")
				->join("sales_reservation_head AS SRH", "SRH.`customer_id` = C.`id` AND SRH.`is_show` = ".\Constants\CUSTOMER_CONST::ACTIVE." AND SRH.`is_used` = ".\Constants\CUSTOMER_CONST::USED, "left")
				->where("C.`id`", $customer_id);

		$result = $this->db->get();

		return $result;
	}
}
