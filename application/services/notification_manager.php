<?php

namespace Services;

class Notification_Manager
{
	private $_CI;

	public function __construct()
	{
		$this->_CI = $CI =& get_instance();
	}

	public function get_header_notifications()
	{
		$response = array();
		$response['error'] = '';

		$this->_CI->load->model('adjust_model');
		$this->_CI->load->model('request_model');
		$this->_CI->load->model('product_model');

		$response['notification']['pending_adjust_count'] 	= $this->_CI->adjust_model->get_pending_adjust_count();
		$response['notification']['stock_request_count'] 	= $this->_CI->request_model->get_stock_request_count_with_no_receive();
		$response['notification']['product_min_warning_count']	= $this->_CI->product_model->get_product_warning_count('MIN');
		$response['notification']['product_max_warning_count']	= $this->_CI->product_model->get_product_warning_count('MAX');
		$response['notification']['all_count'] = $response['notification']['pending_adjust_count'] + 
															$response['notification']['stock_request_count'] + 
															$response['notification']['product_max_warning_count'] + 
															$response['notification']['product_min_warning_count'];

		return $response;
	}
}

?>