<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_Model extends CI_Model {

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		$this->load->library('encrypt');
		$this->load->library('constants/login_const');
		$this->load->helper('cookie');
		parent::__construct();
	}

	/**
	 * Check user name and password in database for verification
	 * @param  $param [array]
	 * @return $response [array]
	 */
	
	public function check_user_credential($param)
	{
		extract($param);
		$response 	= array();
		$password 	= $this->encrypt->encode_md5($password);
		$query_data = array($user_name,$password);

		$response['error']		= '';
		
		$query 	= "SELECT `id`,`is_active`, `is_first_login` 
					FROM user 
					WHERE `username` = ? AND `password` = ? AND `is_show` = ".LOGIN_CONST::ACTIVE;

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() != 1) 
			$response['error'] = 'Invalid User Name / Password!';
		else
		{
			$row = $result->row();

			if ($row->is_active == LOGIN_CONST::INACTIVE) 
				$response['error'] = 'Account currently deactivated!';
			else
			{
				$query = "SELECT DISTINCT(U.`branch_id`) AS 'branch_id', B.`name` AS 'branch_name'
						FROM user_permission AS U 
						LEFT JOIN branch AS B ON B.`id` = U.`branch_id`
						WHERE B.`is_show` = ".LOGIN_CONST::ACTIVE." AND U.`user_id` = ?";

				$result_branch = $this->db->query($query,$row->id);

				if ($result_branch->num_rows() == 0)
					$response['error'] = 'No branch exists for your account!';
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

					$result_branch->free_result();
				}
			
			}
		
		}

		$result->free_result();

		return $response; 
	}

	/**
	 * Check user name and password again and set user cookies
	 * @param  $param [array]
	 * @return $response [array]
	 */
	
	public function set_user_session($param)
	{
		extract($param);
		$response 	= array();
		$password 	= $this->encrypt->encode_md5($password);
		$query_data = array($user_name,$password);

		$response['error']	= '';

		$query 	= "SELECT `username`, `id`, `full_name`, `is_active`,`is_first_login`
						FROM user 
						WHERE `username` = ? AND `password` = ? AND `is_show` = ".LOGIN_CONST::ACTIVE;

		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() != 1) 
			$response['error']	= 'Invalid User Name / Password!';
		else
		{
			$row = $result->row();

			if ($row->is_active == LOGIN_CONST::INACTIVE) 
				$response['error'] = 'Account currently deactivated!';
			else
			{
				set_cookie('username',$this->encrypt->encode($row->username));
				set_cookie('fullname',$this->encrypt->encode($row->full_name));
				set_cookie('temp',$this->encrypt->encode($row->id));
				set_cookie('branch',$this->encrypt->encode($branch_id));

				if ($row->is_first_login == LOGIN_CONST::FIRST_LOGIN) 
					$this->_update_first_login($row->id);
			}
			
		}

		$result->free_result();

		return $response;
	}

	private function _update_first_login($id)
	{
		$query 	=	"UPDATE `user`
						SET `is_first_login` = 1
						WHERE `id` = ?";

		$result = $this->db->query($query,$id);
	}



}	
