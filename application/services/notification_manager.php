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
		$this->_CI->load->model('delivery_model');
		$this->_CI->load->model('product_model');

		$view_page_permission = $this->_CI->permission_checker->check_page_permission_for_notification();

		$response['notification']['pending_adjust_count'] 	= !$view_page_permission['pending_adjustment'] ? 0 : $this->_CI->adjust_model->get_pending_adjust_count();
		$response['notification']['stock_request_count'] 	= !$view_page_permission['stock_request_from'] ? 0 : $this->_CI->request_model->get_stock_request_notification_count('REQUESTED_BY_OTHER_BRANCH_NO_DELIVERY');
		$response['notification']['stock_receive_count'] 	= !$view_page_permission['stock_receive'] ? 0 : $this->_CI->delivery_model->get_stock_delivery_count_with_no_receive();
		$response['notification']['product_min_warning_count']	= !$view_page_permission['warning'] ? 0 : $this->_CI->product_model->get_product_warning_count('MIN');
		$response['notification']['product_max_warning_count']	= !$view_page_permission['warning'] ? 0 : $this->_CI->product_model->get_product_warning_count('MAX');
		$response['notification']['product_negative_count']	= !$view_page_permission['warning'] ? 0 : $this->_CI->product_model->get_product_warning_count('NEGATIVE');
		$response['notification']['stock_request_due_incomplete_count'] = !$view_page_permission['stock_request_to'] ? 0 : $this->_CI->request_model->get_stock_request_notification_count('DUE_INCOMPLETE');
		$response['notification']['stock_request_due_no_delivery_count'] = !$view_page_permission['stock_request_to'] ? 0 : $this->_CI->request_model->get_stock_request_notification_count('DUE_NO_DELIVERY');

		$response['notification']['all_count'] = $response['notification']['pending_adjust_count'] + 
															$response['notification']['stock_request_count'] + 
															$response['notification']['product_max_warning_count'] + 
															$response['notification']['product_min_warning_count'] +
															$response['notification']['stock_receive_count'] +
															$response['notification']['product_negative_count'] + 
															$response['notification']['stock_request_due_incomplete_count'] + 
															$response['notification']['stock_request_due_no_delivery_count'];

		return $response;
	}
}

?>