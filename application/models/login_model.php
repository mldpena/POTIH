<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_Model extends CI_Model {

	/**
	 * Load constants
	 */
	public function __construct() {
		parent::__construct();

		$this->load->constant('login_const');
	}

	/**
	 * Check user name and password in database for verification
	 * @param  array $param
	 * @return result set 
	 */
	public function check_user_credential($username, $password)
	{
		$this->db->select("`id`,`is_active`, `is_first_login`, `password`, `full_name`, `username`")
				->from("`user`")
				->where("`username`",$username)
				->where("`password`",$password);

		$result = $this->db->get();

		return $result;
	}

	/**
	 * Get branch list of the verified user
	 * @param  int $user_id 
	 * @return result set 
	 */
	public function get_user_branch_list($user_id)
	{
		$this->db->select("DISTINCT(U.`branch_id`) AS 'branch_id', B.`name` AS 'branch_name'")
				->from("`user_permission` AS U")
				->join("`branch` AS B","B.id = U.`branch_id`","left")
				->where("B.`is_show`",\Constants\LOGIN_CONST::ACTIVE)
				->where("U.`user_id`",$user_id);

		$result = $this->db->get();

		return $result;
	}

	/**
	 * Get permission codes of the verified user
	 * @param  $param [array]
	 * @return result set 
	 */
	
	public function get_permission_list_by_userid($user_id, $branch_id)
	{
		$this->db->select("DISTINCT(`permission_code`)")
				->from("`user_permission`")
				->where("`user_id`",$user_id)
				->where("`branch_id`",$branch_id);

		$result = $this->db->get();

		return $result;
	}

	/**
	 * Check if the user data set in the cookies exists in DB
	 * @param  string $username
	 * @param  string $fullname 
	 * @param  int $user_id 
	 * @return result set
	 */
	public function check_session_user_credential_exists($username, $fullname, $user_id)
	{
		$this->db->select("`id`, `username`, `password`, `full_name`")
				->from("`user`")
				->where("`is_show`",\Constants\LOGIN_CONST::ACTIVE)
				->where("`username`",$username)
				->where("`full_name`",$fullname)
				->where("`id`",$user_id);

		$result = $this->db->get();

		return $result;
	}

	/**
	 * Update first login status upon first login
	 * @param  int $id
	 */
	public function update_first_login_status($id)
	{
		$update_fields = array("is_first_login" => \Constants\LOGIN_CONST::ACTIVE);

		$this->db->where('id', $id);
		$this->db->update("`user`", $update_fields);
	}

}	
