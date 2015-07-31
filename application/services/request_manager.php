<?php

namespace Services;

class Request_Manager
{
	private $_CI;
	private $_request_head_id;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('UNABLE_TO_GENERATE_REFERENCE' => 'Unablet to generate new reference number!',
									'NO_REMAINING_SR_FOUND' => 'No remaining stock request detail found!',
									'UNABLE_TO_GET_REQUEST_HEAD_DATA' => 'Unable to get request head branch id!');

	public function __construct()
	{
		$this->_CI = $CI =& get_instance();

		$this->_current_branch_id 	= $this->_CI->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->_CI->encrypt->decode(get_cookie('temp'));
		$this->_request_head_id 	= $this->_CI->encrypt->decode($this->_CI->uri->segment(3));

		$this->_current_date 		= date("Y-m-d h:i:s");
	}

	public function create_stock_delivery_from_selected_request_detail($param)
	{
		extract($param);
		$response = array();

		$response['error'] = '';

		$to_branch_id 				= 0;
		$new_stock_delivery_head_id = 0;
		$selected_request_detail_id = $this->_CI->encrypt->decode_array($selected_detail_id);

		$stock_request_head_result = $this->_CI->request_model->get_stock_request_head_info($this->_request_head_id);

		if ($stock_request_head_result->num_rows() == 1) 
		{
			$row = $stock_request_head_result->row();
			$to_branch_id = $row->branch_id;
		}
		else
			throw new \Exception($this->_error_message['UNABLE_TO_GET_REQUEST_HEAD_DATA']);
			
		$stock_request_head_result->free_result();

		$new_stock_delivery_detail = array();

		$result_stock_delivery_details = $this->_CI->request_model->get_stock_request_details_with_remaining($selected_request_detail_id);

		if ($result_stock_delivery_details->num_rows() > 0) 
		{
			$new_stock_delivery_head_result = get_next_number('stock_delivery_head','reference_number',array('entry_date' => date("Y-m-d h:i:s"), 
																											'to_branchid' => $to_branch_id,
																											'delivery_receive_date' => date("Y-m-d h:i:s"),
																											'delivery_type' => 3));
			if ($new_stock_delivery_head_result['error'] != '')
				throw new \Exception($this->_error_message['UNABLE_TO_GENERATE_REFERENCE']);

			$new_stock_delivery_head_id = $this->_CI->encrypt->decode($new_stock_delivery_head_result['id']);

			foreach ($result_stock_delivery_details->result() as $row) 
			{
				array_push($new_stock_delivery_detail, array('headid' => $new_stock_delivery_head_id,
															'quantity' => $row->quantity - $row->qty_delivered,
															'product_id' => $row->product_id,
															'description' => $row->description,
															'memo' => $row->memo,
															'is_for_branch' => 1,
															'request_detail_id' => $row->id));
			}
		}
		else
			throw new \Exception($this->_error_message['NO_REMAINING_SR_FOUND']);
			
		$result_stock_delivery_details->free_result();

		$this->_CI->request_model->create_delivery_from_remaining_request_detail($new_stock_delivery_detail);

		$response['id'] = $new_stock_delivery_head_result['id'];

		return $response;
	}
}

?>