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
		$response 	= array();
		$user_name 	= $param['user'];
		$password 	= $this->encrypt->encode_md5($param['pass']);
		$query_data = array($user_name,$password);
		
		$query 	= "SELECT `id` FROM user WHERE `username` = ? AND `password` = ? AND `is_show` = ".LOGIN_CONST::ACTIVE;
		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() != 1) 
		{
			$response['error'] = 'Invalid User Name / Password!';
		}
		else
		{
			$query = "SELECT DISTINCT(U.`branch_id`) AS 'branch_id', B.`name` AS 'branch_name'
						FROM user_permission AS U 
						LEFT JOIN branch AS B ON B.`id` = U.`branch_id`
						WHERE B.`is_show` = ".LOGIN_CONST::ACTIVE;

			$result_branch = $this->db->query($query);

			if ($result_branch->num_rows() == 0)
			{
				$response['error'] = 'No branch exists for your account!';
			}
			else
			{
				$branches = array();
				foreach($result_branch->result() as $row) 
				{
					$branches[$row->branch_id] = $row->branch_name;
				}

				$response['error']		= '';
				$response['branches'] 	= $branches;

				$result_branch->free_result();
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
		$response 	= array();
		$user_name 	= $param['user'];
		$branch_id	= $param['branch'];
		$password 	= $this->encrypt->encode_md5($param['pass']);
		$query_data = array($user_name,$password);

		$query 	= "SELECT `username`, `id`, `full_name` FROM user WHERE `username` = ? AND `password` = ? AND `is_show` = ".LOGIN_CONST::ACTIVE;
		$result = $this->db->query($query,$query_data);

		if ($result->num_rows() != 1) 
		{
			$response['error']	= 'Invalid User Name / Password!';
		}
		else
		{
			$row = $result->row();

			set_cookie('username',$this->encrypt->encode($row->username));
			set_cookie('fullname',$this->encrypt->encode($row->full_name));
			set_cookie('temp',$this->encrypt->encode($row->id));
			set_cookie('branch',$this->encrypt->encode($branch_id));

			$response['error']	= '';
		}

		$result->free_result();

		return $response;
	}

}
