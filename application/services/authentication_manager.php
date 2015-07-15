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
		$this->_CI->load->library('encrypt');
		$this->_CI->load->model('login_model');
		$this->load->file(CONSTANTS.'login_const.php');
	}

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

	public function validate_user_input_credential($param)
	{
		$result = $this->_CI->login_model->check_user_credential($param);

		if ($result->num_rows() != 1) 
			throw new Exception($this->_error_message['INVALID_CREDENTIAL']);
		else
		{
			$row = $result->row();

			if ($row->is_active == LOGIN_CONST::INACTIVE) 
				throw new Exception($this->_error_message['ACCOUNT_DEACTIVATED']);
			else
			{
				$query = "SELECT DISTINCT(U.`branch_id`) AS 'branch_id', B.`name` AS 'branch_name'
						FROM user_permission AS U 
						LEFT JOIN branch AS B ON B.`id` = U.`branch_id`
						WHERE B.`is_show` = ".LOGIN_CONST::ACTIVE." AND U.`user_id` = ?";

				$result_branch = $this->db->query($query,$row->id);

				if ($result_branch->num_rows() == 0)
					throw new Exception($this->_error_message['NO_BRANCH']);
				else
				{
					$i = 0;
					$branches = array();

					foreach($result_branch->result() as $row_branches) 
					{
						$branches[$i]['id'] 	= $row_branches->branch_id;
						$branches[$i]['value'] 	= $row_branches->branch_name;

						$i++;
					}

					$response['branches'] 		= $branches;
					$response['is_first_login'] = $row->is_first_login;
					$response['is_default_password'] = $row->password == $this->encrypt->encode_md5('123456') ? true : false;

					$result_branch->free_result();
				}
			
			}
		
		}

		$result->free_result();
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
		$this->check_if_user_exists();
	}

	private function check_if_all_cookies_set()
	{
		$isset = true;

		if (!isset($_COOKIE['username']) || !isset($_COOKIE['fullname']) || !isset($_COOKIE['temp']) || !isset($_COOKIE['branch']) || !isset($_COOKIE['permissions']))
			$isset = false;

		if (isset($_COOKIE['permissions']) && count(json_decode($_COOKIE['permissions'])) == 0)
			$isset = false;

		return $isset;
	}

	private function check_if_user_exists()
	{
		$isset 		= true;
		$username 	= $this->_CI->encrypt->decode(get_cookie('username'));
		$fullname 	= $this->_CI->encrypt->decode(get_cookie('fullname'));
		$user_id 	= $this->_CI->encrypt->decode(get_cookie('temp'));

		$query_data = array($user_id, $username, $fullname);

		$result = $this->_CI->login_model->check_session_user_credential_exists($query_data);

		if ($result->num_rows() != 1) 
			$isset = false;

		$result->free_result();

		return $isset;
	}

	private function delete_user_cookies()
	{
		delete_cookie('permissions');
		delete_cookie('username');
		delete_cookie('fullname');
		delete_cookie('temp');
		delete_cookie('branch');
	}

	private function logout_user()
	{
		$this->delete_user_cookies();
		header('Location:'.base_url().'login');
		exit();
	}

}

?>