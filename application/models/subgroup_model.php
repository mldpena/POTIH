<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subgroup_Model extends CI_Model {

	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		$this->load->library('encrypt');
		$this->load->file(CONSTANTS.'subgroup_const.php');
		$this->load->library('sql');
		$this->load->helper('cookie');

		$this->_current_branch_id 	= $this->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");

		parent::__construct();
	}


	/**
	 * Check user name and password in database for verification
	 * @param  $param [array]
	 * @return $response [array]
	 */

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
			case SUBGROUP_CONST::ORDER_BY_CODE:
				$order_field = " `code`";
				break;

			case SUBGROUP_CONST::ORDER_BY_NAME:
				$order_field = " `name`";
				break;
		}

		$query = "SELECT `id`, `code`, `name` from subgroup where `is_show` = ".SUBGROUP_CONST::ACTIVE." $conditions ORDER BY $order_field";
					
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
		$query_data = array($code,$name,$this->_current_date,$this->_current_date,$this->_current_user,$this->_current_user);
		$response['error'] = '';

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
			$response['error'] = 'Unable to save sub group!';
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

		$query = "SELECT `code`, `name` from subgroup where `is_show` = ".SUBGROUP_CONST::ACTIVE." AND  id = ?";
						

		$result = $this->db->query($query,$subgroup_id);

		if ($result->num_rows() == 1) 
		{
			$row = $result->row();
			$response['data']['code'] 	= $row->code;
			$response['data']['name'] 	= $row->name;
		}
		else
			$response['error'] = 'Subgroup not found!';

		$result->free_result();

		return $response;
	}

	public function update_subgroup($param)
	{
		extract($param);
		$subgroup_id = $this->encrypt->decode($subgroup_id);

		$response 	= array();
		$query_data = array($code,$name,$this->_current_date,$this->_current_user,$subgroup_id);
		$response['error'] = '';

		$query = "UPDATE `subgroup`
					SET
					`code` = ?,
					`name` = ?,
					`last_modified_date` =?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to save subgroup!';
		else
			$response['id'] = $result['id'];

		return $response;

	}
	
	public function delete_subgroup($param)
	{
		extract($param);

		$subgroup_id = $this->encrypt->decode($subgroup_id);

		$response = array();
		$response['error'] = '';

		$query_data = array($this->_current_date,$this->_current_user,$subgroup_id);
		$query 	= "UPDATE `subgroup` 
					SET 
					`is_show` =".SUBGROUP_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
			$response['error'] = 'Unable to delete subgroup!';

		return $response;
	}

}
