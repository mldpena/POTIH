<?php

namespace Services;

class Autocomplete_Manager
{
	private $_CI;

	public function __construct()
	{
		$this->_CI = $CI =& get_instance();
	}

	public function get_recent_names($param, $type)
	{
		$this->_CI->load->model('recent_name_model');

		$response = array();
		
		extract($param);

		$result = $this->_CI->recent_name_model->get_recent_names_by_term($term, $type);

		$i = 0;
		
		foreach ($result->result() as $row) 
		{
			$response[$i]['label'] = $row->name;
			$response[$i]['value'] = $row->name;
			$response[$i]['ret_datas'] = array($row->name);
			$i++;			
		}

		$result->free_result();

		return $response;
	}
}

?>