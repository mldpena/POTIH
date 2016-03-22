<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Return_Model extends CI_Model {

	private $_return_head_id = 0;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('UNABLE_TO_INSERT' => 'Unable to insert customer return detail!',
									'UNABLE_TO_UPDATE' => 'Unable to update customer return detail!',
									'UNABLE_TO_UPDATE_HEAD' => 'Unable to update customer return head!',
									'UNABLE_TO_SELECT_HEAD' => 'Unable to get customer return head details!',
									'UNABLE_TO_SELECT_DETAILS' => 'Unable to get customer return details!',
									'UNABLE_TO_DELETE' => 'Unable to delete customer return detail!',
									'UNABLE_TO_DELETE_HEAD' => 'Unable to delete customer return head!',
									'NOT_OWN_BRANCH' => 'Cannot delete customer return entry of other branches!');

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		parent::__construct();

		$this->load->constant('return_const');

		$this->_return_head_id 		= $this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d H:i:s");		
	}

	public function get_return_details()
	{
		$response 		= array();
		$branch_id 		= 0;

		$response['error'] 	= '';
		$response['detail_error'] 	= ''; 

		$query_head = "SELECT 
							CONCAT('RD',`reference_number`) AS 'reference_number',
							COALESCE(DATE(`entry_date`),'') AS 'entry_date', 
							`memo`, 
							`branch_id`, 
							`customer`, 
							`received_by`, 
							`is_used`,
							`customer_id`
						FROM 
							`return_head`
						WHERE 
							`is_show` = ".\Constants\RETURN_CONST::ACTIVE." AND 
							`id` = ?";

		$result_head = $this->db->query($query_head,$this->_return_head_id);

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
			$response['received_by'] 		= $row->received_by;
			$response['is_editable'] 		= $row->branch_id == $this->_current_branch_id ? TRUE : FALSE;
			$response['is_saved'] 			= $row->is_used == 1 ? TRUE : FALSE;
		}

		$query_detail = "SELECT 
							RD.`id`, RD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
							COALESCE(CONCAT(P.`description`, IF(P.`is_show` = 0, '(Product Deleted)', '')),'') AS 'product',
							COALESCE(P.`is_show`, 0) AS 'is_deleted',
							CASE
								WHEN P.`uom` = ".\Constants\RETURN_CONST::PCS." THEN 'PCS'
								WHEN P.`uom` = ".\Constants\RETURN_CONST::KG." THEN 'KGS'
								WHEN P.`uom` = ".\Constants\RETURN_CONST::ROLL." THEN 'ROLL'
								ELSE ''
							END AS 'uom', 
							RD.`quantity`, RD.`memo`, RD.`description`, 
							COALESCE(P.`type`, '') AS 'type', 
							RD.`received_by`
						FROM `return_detail` AS RD
						LEFT JOIN `return_head` AS RH ON RD.`headid` = RH.`id` AND RH.`is_show` = ".\Constants\RETURN_CONST::ACTIVE."
						LEFT JOIN `product` AS P ON P.`id` = RD.`product_id`
						WHERE RD.`headid` = ?";

		$result_detail = $this->db->query($query_detail,$this->_return_head_id);

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = 'No return details found!';
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$break_line = $row->type == \Constants\RETURN_CONST::STOCK ? '' : '<br/>';
				$response['detail'][$i][] = array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line, $row->description, $row->is_deleted);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->uom);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->received_by);
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

	public function insert_return_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$query_data 		= array($this->_return_head_id,$qty,$product_id,$memo,$description,$received_by);

		$query = "INSERT INTO `return_detail`
					(`headid`,
					`quantity`,
					`product_id`,
					`memo`,
					`description`,
					`received_by`)
					VALUES
					(?,?,?,?,?,?);";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_INSERT']);
		else
			$response['id'] = $result['id'];

		return $response;
	}

	public function update_return_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';
		$return_detail_id 	= $this->encrypt->decode($detail_id);
		$query_data 		= array($qty,$product_id,$memo,$description,$received_by,$return_detail_id);

		$query = "UPDATE `return_detail`
					SET
					`quantity` = ?,
					`product_id` = ?,
					`memo` = ?,
					`description` = ?,
					`received_by` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE']);

		return $response;
	}

	public function delete_return_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$return_detail_id 	= $this->encrypt->decode($detail_id);

		$query = "DELETE FROM `return_detail` WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$return_detail_id);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE']);

		return $response;

	}

	public function update_return_head($param)
	{
		extract($param);

		$response = array();

		$response['error'] 	= '';
		$entry_date 		= $entry_date.' '.date('H:i:s');
		$query_data 		= array($entry_date, $memo, $customer_name, $received_by, $this->_current_user, $this->_current_date, $customer_id, $this->_return_head_id);

		$query = "UPDATE `return_head`
					SET
						`entry_date` = ?,
						`memo` = ?,
						`customer` = ?,
						`received_by` = ?,
						`is_used` = ".\Constants\RETURN_CONST::USED.",
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

	public function search_return_list($param, $with_limit = TRUE)
	{
		extract($param);

		$response['rowcnt'] = 0;

		$this->db->select("
							RD.`id`, 
							COALESCE(B.`name`,'') AS 'location', 
							CONCAT('RD',RD.`reference_number`) AS 'reference_number',
							COALESCE(DATE(`entry_date`),'') AS 'entry_date', 
							IF(RD.`is_used` = 0, 'Unused', RD.`memo`) AS 'memo', 
							COALESCE(C.`company_name`, RD.`customer`) AS 'customer',
							RD.`received_by`
						")
				->from("return_head AS RD")
				->join("customer AS C", "C.`id` = RD.`customer_id`", "left")
				->join("branch AS B", "B.`id` = RD.`branch_id` AND B.`is_show` = ".\Constants\RETURN_CONST::ACTIVE, "left")
				->where("RD.`is_show`", \Constants\RETURN_CONST::ACTIVE);


		if (!empty($date_from))
			$this->db->where("RD.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("RD.`entry_date` <=", $date_to." 23:59:59");

		if ($branch != \Constants\RETURN_CONST::ALL_OPTION) 
			$this->db->where("RD.`branch_id`", (int)$branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('RD', RD.`reference_number`,' ', RD.`memo`, ' ', COALESCE(C.`company_name`, RD.`customer`), ' ', RD.`received_by`)", $search_string, "both");

		switch ($order_by) 
		{
			case \Constants\RETURN_CONST::ORDER_BY_REFERENCE:
				$order_field = "RD.`reference_number`";
				break;
			
			case \Constants\RETURN_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case \Constants\RETURN_CONST::ORDER_BY_DATE:
				$order_field = "RD.`entry_date`";
				break;
		}

		$this->db->order_by($order_field, $order_type);

		if ($with_limit) 
		{
			$limit = $row_end - $row_start + 1;
			$this->db->limit((int)$limit, (int)$row_start);
		}
		
		$result = $this->db->get();

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $this->get_return_list_count_by_filter($param);

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($row_start + $i + 1);
				$response['data'][$i][] = array($row->location);
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array(date('m-d-Y', strtotime($row->entry_date)));
				$response['data'][$i][] = array($row->customer);
				$response['data'][$i][] = array($row->received_by);
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function get_return_list_count_by_filter($param)
	{
		extract($param);

		$this->db->from("return_head AS RD")
				->join("customer AS C", "C.`id` = RD.`customer_id`", "left")
				->where("RD.`is_show`", \Constants\RETURN_CONST::ACTIVE);

		if (!empty($date_from))
			$this->db->where("RD.`entry_date` >=", $date_from." 00:00:00");

		if (!empty($date_to))
			$this->db->where("RD.`entry_date` <=", $date_to." 23:59:59");

		if ($branch != \Constants\RETURN_CONST::ALL_OPTION) 
			$this->db->where("RD.`branch_id`", (int)$branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('RD', RD.`reference_number`,' ', RD.`memo`, ' ', COALESCE(C.`company_name`, RD.`customer`), ' ', RD.`received_by`)", $search_string, "both");
		
		return $this->db->count_all_results();
	}

	public function delete_return_head($param)
	{
		extract($param);

		$return_id 		= $this->encrypt->decode($head_id);

		$response = array();
		$response['error'] = '';

		$query 	= "SELECT `branch_id` FROM return_head WHERE id = ?";
		$result = $this->db->query($query,$return_id);
		$row 	= $result->row();

		if ($row->branch_id != $this->_current_branch_id) {
			throw new Exception($this->_error_message['NOT_OWN_BRANCH']);
		}

		$result->free_result();

		$query_data = array($this->_current_date,$this->_current_user,$return_id);
		$query 	= "UPDATE `return_head` 
					SET 
					`is_show` = ".\Constants\RETURN_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE_HEAD']);
		
		return $response;
	}

	public function get_receive_printout_detail()
	{
		$response = array();

		$response['error'] = '';

		$return_id = $this->encrypt->decode($this->session->userdata('customer_return'));

		$query_head = "SELECT CONCAT('RD',`reference_number`) AS 'reference_number', 
						DATE(`entry_date`) AS 'entry_date'
					FROM return_head
					WHERE `id` = ?";

		$result_head = $this->db->query($query_head,$return_id);
		
		if ($result_head->num_rows() == 1) 
		{
			$row = $result_head->row();

			foreach ($row as $key => $value)
				$response[$key] = $value;
		}
		else
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
			
		$result_head->free_result();

		$query_detail = "SELECT D.`quantity` AS 'quantity', COALESCE(P.`description`,'-') AS 'product', 
							D.`description`, COALESCE(P.`material_code`,'-') AS 'item_code', D.`received_by`, D.`memo` AS 'receive_memo',
							CASE
								WHEN P.`uom` = 1 THEN 'PCS'
								WHEN P.`uom` = 2 THEN 'KGS'
								WHEN P.`uom` = 3 THEN 'ROLL'
								ELSE ''
							END AS 'uom'
							FROM return_head AS H
							LEFT JOIN return_detail AS D ON D.`headid` = H.`id`
							LEFT JOIN product AS P ON P.`id` = D.`product_id`
							WHERE H.`id` = ?";

		$result_detail = $this->db->query($query_detail,$return_id);

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

		$response['title'] = 'CUSTOMER RETURN SUMMARY';

		return $response;
	}

	public function check_if_transaction_has_product()
	{
		$this->db->select("D.*")
				->from("return_detail AS D")
				->join("return_head AS H", "H.`id` = D.`headid`", "left")
				->where("H.`is_show`", \Constants\RETURN_CONST::ACTIVE)
				->where("H.`id`", $this->_return_head_id);

		$result = $this->db->get();

		return $result;
	}

	public function get_customer_return_by_transaction($param)
	{
		extract($param);
		
		$this->db->select("RH.`id`, COALESCE(B.`name`,'') AS 'location', CONCAT('RD',RH.`reference_number`) AS 'reference_number',
							COALESCE(DATE(`entry_date`),'') AS 'entry_date', IF(RH.`is_used` = 0, 'Unused', RH.`memo`) AS 'memo', 
							RH.`customer`, RH.`received_by`")
				->from("return_head AS RH")
				->join("branch AS B", "B.`id` = RH.`branch_id` AND B.`is_show` = ".\Constants\RETURN_CONST::ACTIVE, "left")
				->where("RH.`is_show`", \Constants\RETURN_CONST::ACTIVE)
				->where("RH.`is_used`", \Constants\RETURN_CONST::USED);

		if (!empty($date_from))
			$this->db->where("RH.`entry_date` >=", $date_from.' 00:00:00');

		if (!empty($date_to))
			$this->db->where("RH.`entry_date` <=", $date_to.' 23:59:59');

		if ($branch != \Constants\RETURN_CONST::ALL_OPTION) 
			$this->db->where("RH.`branch_id`", $branch);

		if (!empty($search_string)) 
			$this->db->like("CONCAT('RD',RH.`reference_number`,' ',RH.`memo`,' ',RH.`customer`,' ',RH.`received_by`)", $search_string, "both");

		switch ($order_by) 
		{
			case \Constants\RETURN_CONST::ORDER_BY_REFERENCE:
				$order_field = "RH.`reference_number`";
				break;
			
			case \Constants\RETURN_CONST::ORDER_BY_LOCATION:
				$order_field = "B.`name`";
				break;

			case \Constants\RETURN_CONST::ORDER_BY_DATE:
				$order_field = "RH.`entry_date`";
				break;
		}

		$this->db->order_by($order_field, $order_type);
		
		$result = $this->db->get();

		return $result;
	}
}
