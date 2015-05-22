<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Material_Model extends CI_Model {

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		$this->load->library('encrypt');
		$this->load->library('constants/material_const');
		$this->load->library('sql');
		$this->load->helper('cookie');
		parent::__construct();
	}


	/**
	 * Check user name and password in database for verification
	 * @param  $param [array]
	 * @return $response [array]
	 */
	public function add_new_material($param)
	{
		extract($param);
		$date_today = date('Y-m-d h:i:s');
		$user_id	= $this->encrypt->decode(get_cookie('temp'));

		$response 	= array();
		$query_data = array($code,$name,$date_today,$date_today,$user_id,$user_id);
		$response['error'] = '';

 		$query = "INSERT INTO `material_type`
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
			$response['error'] = 'Unable to save material!';
		}
		else
		{
			$response['id'] = $result['id'];
		}

		return $response;
	}
	public function search_material_list($param){

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

		$query = "SELECT `id`, `code`, `name` from material_type where `is_show` = 1  $conditions ORDER BY $order_field";
					
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
	public function update_material($param)
	{
		extract($param);
		$date_today = date('Y-m-d h:i:s');
		$user_id	= $this->encrypt->decode(get_cookie('temp'));
		$material_id = $this->encrypt->decode($material_id);

		$response 	= array();
		$query_data = array($code,$name,$date_today,$user_id,$material_id);
		$response['error'] = '';

		$query = "UPDATE `material_type`
					SET
					`code` = ?,
					`name` = ?,
					`last_modified_date` =?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
		{
			$response['error'] = 'Unable to save material!';
		}
		else
		{
			$response['id'] = $result['id'];
		}

		return $response;

	}
	public function get_material($param){

		extract($param);

		$response = array();

		$response['error'] = '';

		$material_id = $this->encrypt->decode($material_id);

		$query = "SELECT `code`, `name` from material_type where `is_show` = 1 AND  id= ?";
						

		$result = $this->db->query($query,$material_id);

		if ($result->num_rows() == 1) 
		{
			$row = $result->row();

			$response['data']['code'] 	= $row->code;
			$response['data']['name'] 	= $row->name;
			
		}
		else
		{
			$response['error'] = 'Material not found!';
		}

		$result->free_result();

		return $response;
	}
	public function delete_material($param)
	{
		extract($param);

		$date_today = date('Y-m-d h:i:s');
		$user_id	= $this->encrypt->decode(get_cookie('temp'));
		$material_id = $this->encrypt->decode($material_id);

		$response = array();
		$response['error'] = '';

		$query_data = array($date_today,$user_id,$material_id);
		$query 	= "UPDATE `material_type` 
					SET 
					`is_show` =".MATERIAL_CONST::DELETED.",
					`last_modified_date` = ?,
					`last_modified_by` = ?
					WHERE `id` = ?";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
		{
			$response['error'] = 'Unable to delete material!';
		}

		return $response;
	}

}
