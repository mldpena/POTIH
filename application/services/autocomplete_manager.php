<?php

namespace Services;

class Autocomplete_Manager
{
	private $_CI;

	public function __construct()
	{
		$this->_CI = $CI =& get_instance();
	}

	public function get_recent_names_from_table($param, $table_name)
	{
		extract($param);

		$result 	= $this->_CI->recent_name_model->get_product_by_term($term,$with_inventory);

		$i = 0;
		
		foreach ($result->result() as $row) 
		{
			$response[$i]['label'] = $row->description;
			$response[$i]['value'] = $row->id;
			$response[$i]['ret_datas'] = ($with_inventory) ? array($row->id,$row->description,$row->material_code,$row->inventory) : array($row->id,$row->description,$row->material_code, $row->type);
			$i++;			
		}

		$result->free_result();

		return $response;
	}
}

?>