<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends CI_Model {

	private $_user_head_id = 0;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		$this->load->library('encrypt');
		$this->load->library('constants/user_const');
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

		$password 	= $this->encrypt->encode_md5($password);

		$response 	= array();
		$query_data = array($user_code,$full_name,$user_name,$password,$contact,$status,$this->_current_date,$this->_current_date,$this->_current_user,$this->_current_user);
		
		$response['error'] = '';

 		$query = "INSERT INTO `user`
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
					(?,?,?,?,?,?,?,?,?,?)";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to save user!';
		else
		{
			$insert_id = $this->encrypt->decode($result['id']);

			$query_permissions = "INSERT INTO `user_permission`
						(`branch_id`,
						`user_id`,
						`permission_code`)
						VALUES
						(?,?,?)";

			foreach ($branches as $key => $value) 
			{
				$query_permissions_data = array($value,$insert_id,100);
				$result_permissions = $this->sql->execute_query($query_permissions,$query_permissions_data);

				if ($result_permissions['error'] != '') 
					$response['error'] = 'Unable to insert permissions!';
			}
		}

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
		$user_id 	= $this->encrypt->decode($user_id);

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
			$response['error'] = 'Unable to delete user!';

		return $response;
	}
	
	public function update_user($param)
	{
		extract($param);

		$password 	= $this->encrypt->encode_md5($password);

		$response 	= array();
		$query_data = array($user_code,$full_name,$user_name,$password,$contact,$status,$this->_current_date,$this->_current_user,$this->_user_head_id);
		$response['error'] = '';

		$query = "UPDATE`user`
					SET
					`code` = ?,
					`full_name` = ?,
					`username` = ?,
					`password` = ?,
					`contact_number` = ?,
					`is_active` = ?,
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?;";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to save user!';
		else
		{
			$query_delete_previous_permissions = "DELETE FROM user_permission WHERE `user_id` = ?";
			$result_delete_previous_permissions = $this->sql->execute_query($query_delete_previous_permissions,$user_id);

			if ($result_delete_previous_permissions['error'] != '') 
				$response['error'] = 'Unable to delete permissions!';
			else
			{
				$query_permissions = "INSERT INTO `user_permission`
										(`branch_id`,
										`user_id`,
										`permission_code`)
										VALUES
										(?,?,?)";

				foreach ($branches as $key => $value) 
				{
					$query_permissions_data = array($value,$user_id,100);
					$result_permissions = $this->sql->execute_query($query_permissions,$query_permissions_data);

					if ($result_permissions['error'] != '') 
						$response['error'] = 'Unable to insert permissions!';
				}
			}
		}
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
			switch ($type) 
			{
				case 1:
					$type = USER_CONST::ACTIVE;
					break;
				
				case 2:
					$type = USER_CONST::INACTIVE;
					break;
			}

			$conditions .= " AND `is_active` = ?";
			array_push($query_data,$type);
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

		$query = "SELECT `code`, `full_name`, `is_active`, `username`, `password`, `contact_number`
					FROM `user` 
					WHERE `is_show` = ".USER_CONST::ACTIVE." AND `id` = ?";

		$result = $this->db->query($query,$this->_user_head_id);

		if ($result->num_rows() != 1) 
			$response['error'] = 'Account does not exists!';
		else
		{
			$row = $result->row();

			$response['user_code'] 	= $row->code;
			$response['username'] 	= $row->username;
			$response['full_name'] 	= $row->full_name;
			$response['is_active'] 	= $row->is_active;
			$response['contact'] 	= $row->contact_number;

			$query_branches = "SELECT DISTINCT(UP.`branch_id`) AS 'branch_id'
								FROM user_permission AS UP
								LEFT JOIN branch AS B ON B.`id` = UP.`branch_id`
								WHERE B.`is_show` = ".USER_CONST::ACTIVE." AND `user_id` = ?";
			
			$result_branches = $this->db->query($query_branches,$user_id);
			
			if ($result_branches->num_rows() == 0) 
				$response['error'] = 'No branch assigned to this account!';
			else
			{
				foreach ($result_branches->result() as $row) 
					$response['branches'][] = $row->branch_id;
			}
		}

		$result->free_result();

		return $response;
	}
}
