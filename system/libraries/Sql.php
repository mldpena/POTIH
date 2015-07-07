<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_Sql{

	public function __construct() {
		$CI =& get_instance();
		$CI->load->library('encrypt');
	}

	public function execute_query($query,$dataArray = array())
	{
		$CI =& get_instance();
		$data = array();
		$data['error'] = false;
		$data['id'] = 0;
		$data['errmsg'] = '';

		for ($i=0; $i < 5; $i++) { 
			if (count($dataArray) == 0)
				$CI->db->query($query);
			else
				$CI->db->query($query,$dataArray);

			$data['id'] = $CI->encrypt->encode($CI->db->insert_id());
			if ($CI->db->_error_number() == 0)
				break;
		}

		$err = $CI->db->_error_message();
		if (!empty($err)) {
			$data['error'] = true;
			$data['id'] = 0;
			$data['errmsg'] = $err;
		}

		return $data;		
	}

	function execute_transaction($queryArray, $dataArray = array())
	{
		$CI =& get_instance();
		$data = array();
		$data['error'] = false;
		$data['id'] = 0;
		$data['errmsg'] = '';

		$CI->db->trans_start();

		for ($i=0; $i < count($queryArray); $i++) { 
			for ($x=0; $x < 5; $x++) { 
				if ($queryArray[$i] == "SELECT @insert_id AS 'id';") {
					$result = $CI->db->query($queryArray[$i]);
					$row = $result->row();
					$data['id'] = $CI->encrypt->encode($row->id);
					$result->free_result();
				}
				else{
					if (count($dataArray) == 0) {
						$CI->db->query($queryArray[$i]);
					}
					else{
						$CI->db->query($queryArray[$i],$dataArray[$i]);
					}	
				}
				
				if ($CI->db->_error_number() == 0){
					break;
				}
			}
		}

		$CI->db->trans_complete();

		$err = $CI->db->_error_message();

		if (!empty($err)) {
			$data['error'] = true;
			$data['id'] = 0;
			$data['errmsg'] = $err;
		}

		return $data;
	}
}
