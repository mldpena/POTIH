<?php

namespace Services;

class Product_Manager
{
	private $_CI;
	private $_error_message = array('CODE_EXISTS' => 'Material code already exists!',
									'NAME_EXISTS' => 'Product Name already exists!',
									'UNABLE_TO_INSERT' => 'Unable to insert product!',
									'UNABLE_TO_SAVE_INVENTORY' => 'Unable to insert min and max values!',
									'UNABLE_TO_UPDATE' => 'Unable to update product!',
									'UNABLE_TO_SELECT' => 'Unable to get select details!',
									'UNABLE_TO_DELETE' => 'Unable to delete product!',
									'UNABLE_TO_GET_TRANSACTION' => 'Error while processing your requests. Please try again.',
									'NO_TRANSACTION_FOUND' => 'No transaction found!');

	public function __construct()
	{
		$this->_CI = $CI =& get_instance();
		$this->_CI->load->model('product_model');
		$this->_CI->load->constant('product_const');
	}

	/**
	 * Get product list result set based on product filters and format returned data
	 * @param  array $param [array of filters]
	 * @return array $response [formatted result set]
	 */
	public function get_product_list_info($param)
	{
		$response = array();

		$response['rowcnt'] = 0;

		$result = $this->_CI->product_model->get_product_list_by_filter($param);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->_CI->encrypt->encode($row->id));
				$response['data'][$i][] = array($i+1);
				$response['data'][$i][] = array($row->material_code);
				$response['data'][$i][] = array($row->description);
				$response['data'][$i][] = array($row->type);
				$response['data'][$i][] = array($row->material_type);
				$response['data'][$i][] = array($row->subgroup);
				$response['data'][$i][] = array(number_format($row->inventory,0));
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	/**
	 * Set minimum and maximum inventory per branch
	 * @return array $response [branch result set]
	 */
	public function get_min_max_per_branch()
	{
		$response = array();

		$branch_list = get_name_list_from_table(FALSE,'branch');
		
		$i = 0;

		foreach ($branch_list as $key => $value) 
		{
			$response['data'][$i][] = array(0);		
			$response['data'][$i][] = array($i+1);		
			$response['data'][$i][] = array($value,$key);		
			$response['data'][$i][] = array(0);		
			$response['data'][$i][] = array(0);	
			$i++;		
		}

		return $response;
	}

	public function get_material_and_subgroup_by_character($param)
	{
		extract($param);

		$response 	= array();
		$response['error']			= '';
		$response['material_name']	= '';
		$response['material_id'] 	= 0;
		$response['subgroup_name'] 	= '';
		$response['subgroup_id'] 	= 0;

		$this->_CI->load->model('material_model');
		$this->_CI->load->model('subgroup_model');

		$result = $this->_CI->material_model->get_material_by_code($code[0]);

		if ($result->num_rows() == 1) 
		{
			$row = $result->row();
			$response['material_name']	= $row->name;
			$response['material_id'] 	= $row->id;
		}

		$result->free_result();

		$result = $this->_CI->subgroup_model->get_subgroup_by_code($code[1]);

		if ($result->num_rows() == 1) 
		{
			$row = $result->row();
			$response['subgroup_name']	= $row->name;
			$response['subgroup_id'] 	= $row->id;
		}

		$result->free_result();

		return $response;
	}
}

?>