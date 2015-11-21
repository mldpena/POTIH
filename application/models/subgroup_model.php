<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subgroup_Model extends CI_Model {

	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('CODE_EXISTS' => 'Code already exists!',
									'NAME_EXISTS' => 'Name already exists!',
									'CANNOT_DELETE_SUB_GROUP' => 'Cannot delete sub group. Sub Group currently being used by a product.',
									'UNABLE_TO_INSERT' => 'Unable to insert sub group!',
									'UNABLE_TO_UPDATE' => 'Unable to update sub group!',
									'UNABLE_TO_SELECT' => 'Unable to get select details!',
									'UNABLE_TO_DELETE' => 'Unable to delete sub group!');

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		parent::__construct();

		$this->load->constant('subgroup_const');

		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d H:i:s");
	}

	public function search_subgroup_list($param)
	{
		extract($param);
		$response 	= array();
		$response['rowcnt'] = 0;
		$conditions = "";
		$order_field = "";
		$query_data = array();

		if (!empty($search)) 
		{
			$conditions .= " AND CONCAT(`code`,' ',`name`) LIKE ?";
			array_push($query_data,'%'.$search.'%');
		}

		switch ($orderby) {
			case \Constants\SUBGROUP_CONST::ORDER_BY_CODE:
				$order_field = " `code`";
				break;

			case \Constants\SUBGROUP_CONST::ORDER_BY_NAME:
				$order_field = " `name`";
				break;
		}

		$query = "SELECT `id`, `code`, `name` from subgroup where `is_show` = ".\Constants\SUBGROUP_CONST::ACTIVE." $conditions ORDER BY $order_field";
					
		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->encrypt->encode($row->id));
				$response['data'][$i][] = array($i+1);
				$response['data'][$i][] = array($row->code);
				$response['data'][$i][] = array($row->name);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();

		return $response;
	}

	public function add_new_subgroup($param)
	{

		extract($param);

		$response 	= array();
		$response['error'] = '';

		$this->validate_sub_group($param,\Constants\SUBGROUP_CONST::INSERT_PROCESS);

		$query_data = array($code,$name,$this->_current_date,$this->_current_date,$this->_current_user,$this->_current_user);

 		$query = "INSERT INTO `subgroup`
					(`code`,
					`name`,
					`date_created`,
					`last_modified_date`,
					`created_by`,
					`last_modified_by`)
					VALUES
					(?,?,?,?,?,?)";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_INSERT']);
		else
			$response['id'] = $result['id'];

		return $response;
	}

	public function get_subgroup_details($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';

		$subgroup_id = $this->encrypt->decode($subgroup_id);

		$query = "SELECT `code`, `name` from subgroup where `is_show` = ".\Constants\SUBGROUP_CONST::ACTIVE." AND  id = ?";

		$result = $this->db->query($query,$subgroup_id);

		if ($result->num_rows() == 1) 
		{
			$row = $result->row();
			$response['data']['code'] 	= $row->code;
			$response['data']['name'] 	= $row->name;
		}
		else
			throw new Exception($this->_error_message['UNABLE_TO_SELECT']);

		$result->free_result();

		return $response;
	}

	public function update_subgroup($param)
	{
		extract($param);
		$subgroup_id = $this->encrypt->decode($subgroup_id);

		$response 	= array();
		$response['error'] = '';

		$this->validate_sub_group($param,\Constants\SUBGROUP_CONST::UPDATE_PROCESS);

		$query_data = array($code,$name,$this->_current_date,$this->_current_user,$subgroup_id);

		$query = "UPDATE `subgroup`
					SET
					`code` = ?,
					`name` = ?,
					`last_modified_date` =?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE']);
		else
			$response['id'] = $result['id'];

		return $response;

	}
	
	public function delete_subgroup($param)
	{
		extract($param);

		$subgroup_id = $this->encrypt->decode($head_id);

		$response = array();
		$response['error'] = '';

		$query = "SELECT * FROM product WHERE `subgroup_id` = ? AND `is_show` = ".\Constants\SUBGROUP_CONST::ACTIVE;

		$result = $this->db->query($query,$subgroup_id);

		if ($result->num_rows() > 0) 
			throw new Exception($this->_error_message['CANNOT_DELETE_SUB_GROUP']);

		$result->free_result();

		$query_data = array($this->_current_date,$this->_current_user,$subgroup_id);
		$query 	= "UPDATE `subgroup` 
					SET 
					`is_show` =".\Constants\SUBGROUP_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE']);

		return $response;
	}

	private function validate_sub_group($param, $function_type)
	{
		extract($param);

		$query = "SELECT * FROM subgroup WHERE `code` = ? AND `is_show` = ".\Constants\SUBGROUP_CONST::ACTIVE;
		$query .= $function_type == \Constants\SUBGROUP_CONST::INSERT_PROCESS ? "" : " AND `id` <> ?";

		$query_data = array($code);
		if ($function_type == \Constants\SUBGROUP_CONST::UPDATE_PROCESS) 
		{
			$id = $this->encrypt->decode($subgroup_id);
			array_push($query_data,$id);
		}

		$result = $this->db->query($query,$query_data);
		if ($result->num_rows() > 0) 
			throw new Exception($this->_error_message['CODE_EXISTS']);
			
		$result->free_result();

		$query = "SELECT * FROM subgroup WHERE `name` = ? AND `is_show` = ".\Constants\SUBGROUP_CONST::ACTIVE;
		$query .= $function_type == \Constants\SUBGROUP_CONST::INSERT_PROCESS ? "" : " AND `id` <> ?";

		$query_data = array($name);

		if ($function_type == \Constants\SUBGROUP_CONST::UPDATE_PROCESS) 
		{
			$id = $this->encrypt->decode($subgroup_id);
			array_push($query_data,$id);
		}

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() > 0) 
			throw new Exception($this->_error_message['NAME_EXISTS']);
			
		$result->free_result();
	}

	public function get_subgroup_by_code($code, $is_show = 1)
	{
		$this->db->select("`name`, `id`")
				->from("subgroup")
				->where("`code`", $code)
				->where("`is_show`", $is_show);

		$result = $this->db->get();

		return $result;
	}
}
