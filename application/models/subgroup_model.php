<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subgroup_Model extends CI_Model {

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		$this->load->library('encrypt');
		$this->load->library('constants/subgroup_const');
		$this->load->library('sql');
		$this->load->helper('cookie');
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
			case 1:
				$order_field= " `code`";
				break;
			case 2:
				$order_field= " `name`";
				break;
			
			default:
				# code...
				break;
		}

		$query = "SELECT `id`, `code`, `name` from subgroup where `is_show` = 1  $conditions ORDER BY $order_field";
					
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

		return $response;
	}
	public function add_subgroup($param)
	{

		extract($param);
		$date_today = date('Y-m-d h:i:s');
		$user_id	= $this->encrypt->decode(get_cookie('temp'));

		$response 	= array();
		$query_data = array($code,$name,$date_today,$date_today,$user_id,$user_id);
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
		{
			$response['error'] = 'Unable to save subgroup!';
		}
		else
		{
			$response['id'] = $result['id'];
		}

		return $response;

	}
	public function get_subgroup($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';

		$subgroup_id = $this->encrypt->decode($subgroup_id);

		$query = "SELECT `code`, `name` from subgroup where `is_show` = 1 AND  id= ?";
						

		$result = $this->db->query($query,$subgroup_id);

		if ($result->num_rows() == 1) 
		{
			$row = $result->row();

			$response['data']['code'] 	= $row->code;
			$response['data']['name'] 	= $row->name;
			
		}
		else
		{
			$response['error'] = 'Subgroup not found!';
		}

		$result->free_result();

		return $response;
	}
	public function update_subgroup($param)
	{
		extract($param);
		$date_today = date('Y-m-d h:i:s');
		$user_id	= $this->encrypt->decode(get_cookie('temp'));
		$subgroup_id = $this->encrypt->decode($subgroup_id);

		$response 	= array();
		$query_data = array($code,$name,$date_today,$user_id,$subgroup_id);
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
		{
			$response['error'] = 'Unable to save subgroup!';
		}
		else
		{
			$response['id'] = $result['id'];
		}

		return $response;

	}
	public function delete_subgroup($param)
	{
		extract($param);

		$date_today = date('Y-m-d h:i:s');
		$user_id	= $this->encrypt->decode(get_cookie('temp'));
		$subgroup_id = $this->encrypt->decode($subgroup_id);

		$response = array();
		$response['error'] = '';

		$query_data = array($date_today,$user_id,$subgroup_id);
		$query 	= "UPDATE `subgroup` 
					SET 
					`is_show` =".SUBGROUP_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
		{
			$response['error'] = 'Unable to delete subgroup!';
		}

		return $response;
	}

}
