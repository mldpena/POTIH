<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_Model extends CI_Model {

	/**
	 * Load constants
	 */
	public function __construct() {
		parent::__construct();
		$this->load->file(CONSTANTS.'login_const.php');
	}

	/**
	 * Check user name and password in database for verification
	 * @param  array $param
	 * @return set 
	 */
	
	public function check_user_credential($param)
	{
		extract($param);
		$response 	= array();
		$password 	= $this->encrypt->encode_md5($password);
		/*$query_data = array($user_name,$password);*/

		$response['error']		= '';
		
		$this->db->select('`id`,`is_active`, `is_first_login`, `password`')
				->where('username',$user_name)
				->where('password',$password);

		$result = $this->db->get('user');

		/*$query 	= "SELECT `id`,`is_active`, `is_first_login`, `password`
					FROM user 
					WHERE `username` = ? AND `password` = ? AND `is_show` = ".LOGIN_CONST::ACTIVE;

		$result = $this->db->query($query,$query_data);*/

		return $result;
	}

	/**
	 * [get_user_branch_list description]
	 * @param  int $user_id 
	 * @return 
	 */
	public function get_user_branch_list($user_id)
	{

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
			throw new Exception($this->_error_message['INVALID_CREDENTIAL']);
		else
		{
			$row = $result->row();

			if ($row->is_active == LOGIN_CONST::INACTIVE) 
				throw new Exception($this->_error_message['ACCOUNT_DEACTIVATED']);
			else
			{
				$permissions 		= array();
				$query_permission 	= "SELECT DISTINCT `permission_code` FROM user_permission WHERE `user_id` = ? AND `branch_id` = ?";
				$result_permission 	= $this->db->query($query_permission, array($row->id,$branch_id));

				if ($result->num_rows() != 1) 
					throw new Exception($this->_error_message['NO_PERMISSION']);
				else
				{
					foreach ($result_permission->result() as $row_permission) 
						array_push($permissions,$row_permission->permission_code);

					set_cookie('permissions',json_encode($permissions));
				}

				$result_permission->free_result();

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

	public function check_session_user_credential_exists($query_data = array())
	{
		$query = "SELECT `id`, `username`, `password`, `full_name`
					FROM `user` AS U 
					WHERE U.`is_show` = ".LOGIN_CONST::ACTIVE." AND U.`username` = ? AND U.`full_name` = ? AND U.`id` = ?";

		$result = $this->db->query($query,$query_data);

		return $result;
	}

	private function _update_first_login($id)
	{
		$query 	=	"UPDATE `user`
						SET `is_first_login` = 1
						WHERE `id` = ?";

		$result = $this->db->query($query,$id);
	}


}	
