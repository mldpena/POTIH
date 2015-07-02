<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends CI_Model {

	private $_user_head_id = 0;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('CODE_EXISTS' => 'Code already exists!',
									'NAME_EXISTS' => 'Name already exists!',
									'USERNAME_EXISTS' => 'User Name already exists!',
									'UNABLE_TO_INSERT' => 'Unable to insert user!',
									'UNABLE_TO_INSERT_PERMISSION' => 'Unable to insert permissions!',
									'UNABLE_TO_UPDATE' => 'Unable to update user!',
									'UNABLE_TO_SELECT' => 'Unable to get select details!',
									'UNABLE_TO_DELETE' => 'Unable to delete user!',
									'UNABLE_TO_DELETE_PERMISSION' => 'Unable to delete permissions',
									'NO_BRANCH_ASSIGNED' => 'No branch assigned to this user!',
									'UNABLE_TO_GET_PERMISSIONS' => 'Unable to get user permissions!',
									'ACCOUNT_NOT_EXISTS' => 'User Account does not exists!');

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		$this->load->library('encrypt');
		$this->load->file(CONSTANTS.'user_const.php');
		$this->load->library('sql');
		$this->load->helper('cookie');

		$this->_user_head_id 		= $this->encrypt->decode($this->uri->segment(3));
		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");

		parent::__construct();
	}

	/**
	 * Insert new user. Partial code w/o user permissions
	 * @param  $param [array]
	 * @return $response [array] 
	 */

	public function insert_new_user($param)
	{
		extract($param);

		$this->validate_user($param,USER_CONST::INSERT_PROCESS);

		$password 	= $this->encrypt->encode_md5($password);

		$response 	= array();
		$query 		= array();
		$query_data = array();

		$response['error'] = '';

		$query_user_data = array($user_code,$full_name,$user_name,$password,$contact,$status,$this->_current_date,$this->_current_date,$this->_current_user,$this->_current_user);
 		$query_user = "INSERT INTO `user`
					(`code`,
					`full_name`,
					`username`,
					`password`,
					`contact_number`,
					`is_active`,
					`date_created`,
					`last_modified_date`,
					`created_by`,
					`last_modified_by`)
					VALUES
					(?,?,?,?,?,?,?,?,?,?);";

		array_push($query,$query_user,"SET @insert_id := LAST_INSERT_ID();");
		array_push($query_data,$query_user_data,array());

		$query_permissions = "INSERT INTO `user_permission`
					(`branch_id`,
					`user_id`,
					`permission_code`)
					VALUES";

		$query_permission_code = "";
		$query_permissions_data = array();

		foreach ($branches as $key => $value) 
		{
			foreach ($permission_list as $key_permission => $value_permission) 
			{
				$query_permission_code .= ",(?,@insert_id,?)";
				array_push($query_permissions_data,$value,$value_permission);
			}
		}

		array_push($query,$query_permissions.substr($query_permission_code,1).";");
		array_push($query_data,$query_permissions_data);

		$result = $this->sql->execute_transaction($query,$query_data);
		
		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_INSERT']);	

		return $response;
	}

	/**
	 * Delete user account. Only set is_show to 0 and update modified date and user
	 * @param  $param [array]
	 * @return $response [json array]
	 */
	
	public function delete_user($param)
	{
		extract($param);

		$this->_current_date = date('Y-m-d h:i:s');
		$user_id 	= $this->encrypt->decode($head_id);

		$response = array();
		$response['error'] = '';

		$query_data = array($this->_current_date,$this->_current_user,$user_id);

		$query 	= "UPDATE `user` 
					SET 
					`is_show` = ".USER_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_DELETE']);

		return $response;
	}
	
	public function update_user($param)
	{
		extract($param);

		$this->validate_user($param,USER_CONST::UPDATE_PROCESS);

		$response 	= array();
		$query = array();
		$query_data = array();

		$response['error'] = '';
		$passwordField = '';

		$query_user_data = array($user_code,$full_name,$user_name,$contact,$status,$this->_current_date,$this->_current_user,$this->_user_head_id);
		
		if ($password != USER_CONST::DUMMY_PASSWORD) 
		{
			$password 	= $this->encrypt->encode_md5($password);
			array_unshift($query_data,$password);
			$passwordField .= "`password` = ?,";
		}

		$query_user = "UPDATE`user`
						SET
						$passwordField
						`code` = ?,
						`full_name` = ?,
						`username` = ?,
						`contact_number` = ?,
						`is_active` = ?,
						`last_modified_date` = ?,
						`last_modified_by` = ?
						WHERE `id` = ?;";

		array_push($query,$query_user,"DELETE FROM user_permission WHERE `user_id` = ?;");
		array_push($query_data,$query_user_data,$this->_user_head_id);

		$query_permissions = "INSERT INTO `user_permission`
					(`branch_id`,
					`user_id`,
					`permission_code`)
					VALUES";

		$query_permission_code = "";
		$query_permissions_data = array();

		foreach ($branches as $key => $value) 
		{
			foreach ($permission_list as $key_permission => $value_permission) 
			{
				$query_permission_code .= ",(?,?,?)";
				array_push($query_permissions_data,$value,$this->_user_head_id,$value_permission);
			}
		}

		array_push($query,$query_permissions.substr($query_permission_code,1).";");
		array_push($query_data,$query_permissions_data);

		$result = $this->sql->execute_transaction($query,$query_data);
		
		if ($result['error'] != '') 
			throw new Exception($this->_error_message['UNABLE_TO_UPDATE']);

		return $response;
	}

	/**
	 * Get user list
	 * @param  $param [array]
	 * @return $response [json array]
	 */
	
	public function get_user_list($param)
	{
		extract($param);

		$conditions		= "";
		$order_field 	= "";

		$response 	= array();
		$query_data = array($this->_current_user);

		$response['rowcnt'] = 0;
		
		

		if (!empty($search_string)) 
		{
			$conditions .= " AND CONCAT(`code`,' ',`full_name`,' ',`username`) LIKE ?";
			array_push($query_data,'%'.$search_string.'%');
		}

		if ($status != USER_CONST::ALL_OPTION) 
		{
			switch ($status) 
			{
				case 1:
					$status = USER_CONST::ACTIVE;
					break;
				
				case 2:
					$status = USER_CONST::INACTIVE;
					break;
			}

			$conditions .= " AND `is_active` = ?";
			array_push($query_data,$status);
		}

		switch ($order_by) 
		{
			case USER_CONST::ORDER_BY_NAME:
				$order_field = "`full_name`";
				break;
			
			case USER_CONST::ORDER_BY_CODE:
				$order_field = "`code`";
				break;
		}

		$query = "SELECT `id`, `code`, `full_name`, `username`, `contact_number`,
						CASE
							WHEN `is_active` = ".USER_CONST::ACTIVE." THEN 'Active'
							WHEN `is_active` = ".USER_CONST::INACTIVE." THEN 'Inactive'
						END AS 'status'
						FROM user
						WHERE `is_show` = ".USER_CONST::ACTIVE." AND `id` <> ? $conditions
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
				$response['data'][$i][] = array($row->code);
				$response['data'][$i][] = array($row->full_name);
				$response['data'][$i][] = array($row->username);
				$response['data'][$i][] = array($row->contact_number);
				$response['data'][$i][] = array($row->status);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();

		return $response;
	}
	
	public function get_user_details($param)
	{

		$response = array();

		$response['error'] = '';

		$query = "SELECT `code`, `full_name`, `is_active`, `username`, `password`, `contact_number`, `id`
					FROM `user` 
					WHERE `is_show` = ".USER_CONST::ACTIVE." AND `id` = ?";

		$result = $this->db->query($query,$this->_user_head_id);

		if ($result->num_rows() != 1) 
			throw new Exception($this->_error_message['ACCOUNT_NOT_EXISTS']);
		else
		{
			$row = $result->row();

			$response['user_code'] 	= $row->code;
			$response['username'] 	= $row->username;
			$response['full_name'] 	= $row->full_name;
			$response['is_active'] 	= $row->is_active;
			$response['contact'] 	= $row->contact_number;
			$response['is_own_profile'] = $this->encrypt->decode(get_cookie('temp')) == $row->id ? USER_CONST::OWN_PROFILE : USER_CONST::OTHER_PROFILE;

			$query_branches = "SELECT DISTINCT(UP.`branch_id`) AS 'branch_id'
								FROM user_permission AS UP
								LEFT JOIN branch AS B ON B.`id` = UP.`branch_id`
								WHERE B.`is_show` = ".USER_CONST::ACTIVE." AND `user_id` = ?";
			
			$result_branches = $this->db->query($query_branches,$this->_user_head_id);
			
			if ($result_branches->num_rows() == 0) 
				throw new Exception($this->_error_message['NO_BRANCH_ASSIGNED']);
			else
			{
				foreach ($result_branches->result() as $row) 
					$response['branches'][] = $row->branch_id;
			}

			$result_branches->free_result();

			$query_permissions = "SELECT DISTINCT(UP.`permission_code`) AS 'permissions'
								FROM user_permission AS UP
								WHERE `user_id` = ?";

			$result_permissions = $this->db->query($query_permissions,$this->_user_head_id);
			
			if ($result_permissions->num_rows() == 0) 
				throw new Exception($this->_error_message['UNABLE_TO_GET_PERMISSIONS']);
			else
			{
				foreach ($result_permissions->result() as $row) 
					$response['permissions'][] = $row->permissions;
			}

			$result_permissions->free_result();
		}

		$result->free_result();

		return $response;
	}

	private function validate_user($param, $function_type)
	{
		extract($param);

		$query = "SELECT * FROM user WHERE `code` = ? AND `is_show` = ".USER_CONST::ACTIVE;
		$query .= $function_type == USER_CONST::INSERT_PROCESS ? "" : " AND `id` <> ?";

		$query_data = array($user_code);
		if ($function_type == USER_CONST::UPDATE_PROCESS) 
			array_push($query_data,$this->_user_head_id);

		$result = $this->db->query($query,$query_data);
		if ($result->num_rows() > 0) 
			throw new Exception($this->_error_message['CODE_EXISTS']);
			
		$result->free_result();

		$query = "SELECT * FROM user WHERE LOWER(`full_name`) = ? AND `is_show` = ".USER_CONST::ACTIVE;
		$query .= $function_type == USER_CONST::INSERT_PROCESS ? "" : " AND `id` <> ?";

		$query_data = array(strtolower($full_name));

		if ($function_type == USER_CONST::UPDATE_PROCESS) 
			array_push($query_data,$this->_user_head_id);

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() > 0) 
			throw new Exception($this->_error_message['NAME_EXISTS']);
			
		$result->free_result();

		$query = "SELECT * FROM user WHERE LOWER(`username`) = ? AND `is_show` = ".USER_CONST::ACTIVE;
		$query .= $function_type == USER_CONST::INSERT_PROCESS ? "" : " AND `id` <> ?";

		$query_data = array($user_name);

		if ($function_type == USER_CONST::UPDATE_PROCESS) 
			array_push($query_data,$this->_user_head_id);

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() > 0) 
			throw new Exception($this->_error_message['USERNAME_EXISTS']);
			
		$result->free_result();
	}
}
