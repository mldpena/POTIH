<?php

namespace Services;

class Product_Checker_Manager
{
	private $_CI;

	public function __construct()
	{
		$this->_CI = $CI =& get_instance();
		$this->_CI->load->model('product_model');
		$this->_CI->load->constant('product_const');
	}

	/**
	 * Get the first 10 result of product search based on material code and product name
	 * @param  array  $param          
	 * @param  boolean $with_inventory
	 * @return array $response
	 */
	public function get_product_autocomplete($param, $with_inventory = FALSE)
	{
		extract($param);

		$branch_id 	= $CI->encrypt->decode(get_cookie('branch'));
		$result 	= $this->_CI->product_model->get_product_by_term($term, $branch_id, $with_inventory);

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

	/**
	 * Check current inventory based from checker type
	 * @param  array $param      
	 * @param  int $checker_type [0 - Minimum checker, 1 - Maximum checker]
	 * @return array $response
	 */
	public function check_current_inventory($param, $checker_type)
	{
		extract($param);

		$data 		= array();
		$branch_id 	= $CI->encrypt->decode(get_cookie('branch'));

		$response = array();
		$response['is_insufficient'] = 0;
		$response['is_excess'] = 0;

		$result = $this->_CI->product_model->get_product_inventory_info($product_id, $branch_id);

		$row = $result->row();

		if ($checker_type == \Constants\PRODUCT_CONST::MIN_CHECKER) 
		{
			if (($row->current_inventory - $qty) < 0 && $row->min_inv != 0) 
				$response['is_insufficient'] = \Constants\PRODUCT_CONST::NEGATIVE_INV;
			elseif (($row->current_inventory - $qty) >= 0 && ($row->current_inventory - $qty) <= $row->min_inv && $row->min_inv != 0)
				$response['is_insufficient'] = \Constants\PRODUCT_CONST::MINIMUM;
		}
		else
		{
			if (($row->current_inventory + $qty) >= $row->max_inv && $row->max_inv != 0) 
				$response['is_excess'] = TRUE;
		}
		
		$response['checker'] = $checker_type;
		$response['current_inventory'] = $row->current_inventory;

		$result->free_result();

		return $response;
	}
}

?>