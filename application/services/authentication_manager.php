<?php

namespace Services;

class Authentication_Manager
{
	private $_CI;
	private $_error_message = array('INVALID_CREDENTIAL' => 'Invalid User Name / Password!',
									'ACCOUNT_DEACTIVATED' => 'Account currently deactivated!',
									'NO_BRANCH' => 'No branch exists for your account!',
									'NO_PERMISSION' => 'No permission exists!');

	public function __construct()
	{
		$this->_CI = $CI =& get_instance();
		$this->_CI->load->model('login_model');
	}

	/**
	 * Check if there are existing cookies stored required for authentication ang verify the user using the 
	 * stored cookies
	 * @return bool
	 */
	public function check_user_credentials()
	{
		$isset_cookies = $this->check_if_all_cookies_set();

		if (!$isset_cookies) 
			$this->logout_user();

		$is_exists = $this->check_user_exists();

		if (!$is_exists) 
			$this->logout_user();

		return true;
	}

	/**
	 * Validate username and password entered by the user
	 * @param  array $param [user_name, password]
	 * @return array
	 */
	public function validate_user_input_credential($param)
	{
		extract($param);

		$response['error'] = '';
		
		$password = $this->_CI->encrypt->encode_md5($password);

		$user_verification_result = $this->_CI->login_model->check_user_credential($user_name, $password);

		if ($user_verification_result->num_rows() != 1) 
			throw new \Exception($this->_error_message['INVALID_CREDENTIAL']);
		else
		{
			$row = $user_verification_result->row();

			if ($row->is_active == \Constants\LOGIN_CONST::INACTIVE) 
				throw new \Exception($this->_error_message['ACCOUNT_DEACTIVATED']);
			else
			{
				$branch_result_set = $this->_CI->login_model->get_user_branch_list($row->id);

				if ($branch_result_set->num_rows() == 0)
					throw new \Exception($this->_error_message['NO_BRANCH']);
				else
				{
					$i = 0;
					$branch_list = array();

					foreach($branch_result_set->result() as $row_branches) 
					{
						$branch_list[$i]['id'] 		= $row_branches->branch_id;
						$branch_list[$i]['value'] 	= $row_branches->branch_name;

						$i++;
					}

					$response['branches'] 		= $branch_list;
					$response['is_first_login'] = $row->is_first_login;
					$response['is_default_password'] = $row->password == $this->_CI->encrypt->encode_md5('123456') ? TRUE : FALSE;

					$branch_result_set->free_result();
				}
			
			}
		
		}

		$user_verification_result->free_result();

		return $response;
	}

	/**
	 * Get user credentials and set needed cookies for authentication
	 * @param array $param
	 * @return array
	 */
	public function set_user_session($param)
	{
		extract($param);

		$permissions = array();

		$response['error'] = '';
		
		$password = $this->_CI->encrypt->encode_md5($password);

		$user_verification_result = $this->_CI->login_model->check_user_credential($user_name, $password);
		$user_detail_row = $user_verification_result->row();

		$permission_list_result = $this->_CI->login_model->get_permission_list_by_userid($user_detail_row->id);

		if ($permission_list_result->num_rows() == 0) 
			throw new \Exception($this->_error_message['NO_PERMISSION']);
		else
		{
			foreach ($permission_list_result->result() as $row_permission) 
				array_push($permissions,$row_permission->permission_code);

			set_cookie('permissions',json_encode($permissions));
		}

		$permission_list_result->free_result();

		set_cookie('username',$this->_CI->encrypt->encode($user_detail_row->username));
		set_cookie('fullname',$this->_CI->encrypt->encode($user_detail_row->full_name));
		set_cookie('temp',$this->_CI->encrypt->encode($user_detail_row->id));
		set_cookie('branch',$this->_CI->encrypt->encode($branch_id));
		set_cookie('branch_name',$branch_name);

		if ($user_detail_row->is_first_login == \Constants\LOGIN_CONST::FIRST_LOGIN)
			$this->_CI->login_model->update_first_login_status($user_detail_row->id);
			
		return $response;
	}

	/**
	 * Check if all needed cookies exist
	 * @return bool
	 */
	private function check_if_all_cookies_set()
	{
		$isset = true;

		if (!isset($_COOKIE['username']) || !isset($_COOKIE['fullname']) || !isset($_COOKIE['temp']) || !isset($_COOKIE['branch']) || !isset($_COOKIE['branch_name']) || !isset($_COOKIE['permissions']))
			$isset = false;

		if (isset($_COOKIE['permissions']) && count(json_decode($_COOKIE['permissions'])) == 0)
			$isset = false;

		return $isset;
	}

	/**
	 * Check if the user data set in the cookie exists in DB
	 * @return bool
	 */
	private function check_if_user_exists()
	{
		$isset 		= true;
		$username 	= $this->_CI->encrypt->decode(get_cookie('username'));
		$fullname 	= $this->_CI->encrypt->decode(get_cookie('fullname'));
		$user_id 	= $this->_CI->encrypt->decode(get_cookie('temp'));
		$result 	= $this->_CI->login_model->check_session_user_credential_exists($username, $fullname, $user_id);

		if ($result->num_rows() != 1) 
			$isset = false;

		$result->free_result();


		return $isset;
	}

	public function logout()
	{
		$this->logout_user();
	}

	public function check_set_cookies()
	{
		$this->check_if_all_cookies_set();
	}

	public function check_user_exists()
	{
		return $this->check_if_user_exists();
	}

	/**
	 * Delete user session cookies
	 */
	private function delete_user_cookies()
	{
		delete_cookie('permissions');
		delete_cookie('username');
		delete_cookie('fullname');
		delete_cookie('temp');
		delete_cookie('branch');
		delete_cookie('branch_name');
	}

	/**
	 * Redirects to login page after deleting user cookies
	 */
	private function logout_user()
	{
		$this->delete_user_cookies();
		header('Location:'.base_url().'login');
		exit();
	}

}

?>