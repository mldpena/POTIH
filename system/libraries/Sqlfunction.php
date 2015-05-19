<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_Sqlfunction{

	public function __construct() {
		$ci =& get_instance();
		$ci->load->library('myfunction');
		$ci->load->library('encrypt');
	}

	public function execute_query($query,$dataArray)
	{
		$ci =& get_instance();
		$data = array();
		$data['error'] = false;
		$data['id'] = 0;
		$data['errmsg'] = '';

		if (is_array($query)) {
			$temp = "";
			for ($i=0; $i < count($query); $i++) { 
				$temp .= $query.";";
			}
			$query = $temp;	
		}

		for ($i=0; $i < 5; $i++) { 
			$ci->db->trans_start();
			$ci->db->query($query,$dataArray);
			$data['id'] = $ci->db->insert_id();
			$ci->db->trans_complete();
			if ($ci->db->trans_status() === FALSE){
				$data['id'] = 0;
				continue;
			}
			else
			{
				break;
			}
		}

		$err = $ci->db->_error_message();
		if (!empty($err)) {
			$data['error'] = true;
			$data['id'] = 0;
			$data['errmsg'] = $err;
		}

		return $data;		
	}

	function execute_transaction($queryArray, $dataArray = array())
	{
		$ci =& get_instance();
		$data = array();
		$data['error'] = false;
		$data['id'] = 0;
		$data['errmsg'] = '';

		$ci->db->trans_start();

		for ($i=0; $i < count($queryArray); $i++) { 
			for ($x=0; $x < 5; $x++) { 
				if (count($dataArray) == 0) {
					$ci->db->query($queryArray[$i]);
				}
				else{
					$ci->db->query($queryArray[$i],$dataArray[$i]);
				}

				if ($ci->db->_error_number() == 0){
					break;
				}
			}

			$data['id'] = $ci->encrypt->encode($ci->db->insert_id());
		}

		$ci->db->trans_complete();

		$err = $ci->db->_error_message();

		if (!empty($err)) {
			$data['error'] = true;
			$data['id'] = 0;
			$data['errmsg'] = $err;
		}

		return $data;
	}
}
