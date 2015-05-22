<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends CI_Model {

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		$this->load->library('encrypt');
		$this->load->library('constants/user_const');
		$this->load->library('sql');
		$this->load->helper('cookie');
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
		$date_today = date('Y-m-d h:i:s');
		$user_id	= $this->encrypt->decode(get_cookie('temp'));
		$password 	= $this->encrypt->encode_md5($password);

		$response 	= array();
		$query_data = array($user_code,$full_name,$user_name,$password,$contact,$status,$date_today,$date_today,$user_id,$user_id);
		
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
		{
			$response['error'] = 'Unable to save user!';
		}
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
				$query_permissions_data = array($key,$result['id'],100);
				$result_permissions = $this->sql->execute_query($query_permissions,$query_permissions_data);

				if ($result_permissions['error'] != '') 
				{
					$response['error'] = 'Unable to insert permissions!';
				}
			}
		}

		return $response;
	}

	public function delete_user($param)
	{
		extract($param);

		$date_today = date('Y-m-d h:i:s');
		$user_id 	= $this->encrypt->decode($user_id);
		$user_last_modified_id = $this->encrypt->decode(get_cookie('temp'));

		$response = array();
		$response['error'] = '';

		$query_data = array($date_today,$user_last_modified_id,$user_id);
		$query 	= "UPDATE `user` 
					SET 
					`is_show` = ".USER_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
		{
			$response['error'] = 'Unable to delete user!';
		}

		return $response;
	}

	public function get_user_list($param)
	{
		extract($param);

		$conditions		= "";
		$order_field 	= "";
		$user_id		= $this->encrypt->decode(get_cookie('temp'));

		$response 	= array();
		$query_data = array($user_id);

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

		return $response;
	}
}
