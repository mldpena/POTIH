<?php

namespace Services;

class Delivery_Manager
{
	private $_CI;
	private $_delivery_head_id;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('UNABLE_TO_INSERT' => 'Unable to insert delivery detail!',
									'UNABLE_TO_UPDATE' => 'Unable to update delivery detail!',
									'UNABLE_TO_UPDATE_HEAD' => 'Unable to update delivery head!',
									'UNABLE_TO_SELECT_HEAD' => 'Unable to get delivery head details!',
									'UNABLE_TO_SELECT_DETAILS' => 'Unable to get delivery details!',
									'UNABLE_TO_DELETE' => 'Unable to delete delivery detail!',
									'UNABLE_TO_DELETE_HEAD' => 'Unable to delete delivery head!',
									'HAS_RECEIVED' => 'Item Delivery can only be deleted if delivery status is no received!',
									'NOT_OWN_BRANCH' => 'Cannot delete item delivery entry of other branches!',
									'NO_ITEMS_TO_PRINT' => 'No items to print!',
									'NO_RECEIVE_FOUND' => 'No customer receive details found!');

	public function __construct()
	{
		$this->_CI = $CI =& get_instance();

		$this->_current_branch_id 	= $this->_CI->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->_CI->encrypt->decode(get_cookie('temp'));
		$this->_delivery_head_id 	= $this->_CI->encrypt->decode($this->_CI->uri->segment(3));

		$this->_current_date 		= date("Y-m-d H:i:s");
	}

	public function transfer_to_new_customer_return($param)
	{
		extract($param);
		$response = array();

		$response['error'] = '';

		$selected_customer_receive_detail_id = $this->_CI->encrypt->decode_array($selected_detail_id);

		$customer_receive_detail = array();

		$current_customer_name = '';
		$new_customer_return_head_id = 0;
		$return_customer_return_ids = [];
		$customer_return_detail = [];
		
		$result_customer_receive_details = $this->_CI->delivery_model->get_customer_receive_detail($selected_customer_receive_detail_id);

		if ($result_customer_receive_details->num_rows() == 0) 
			throw new \Exception($this->_error_message['NO_RECEIVE_FOUND']);

		$result_delivery_head_info = $this->_CI->delivery_model->get_stock_delivery_head_info($this->_delivery_head_id);

		if ($result_delivery_head_info->num_rows() == 0)
			throw new Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);

		$delivery_head_info_row = $result_delivery_head_info->row();

		$i = 0;

		foreach ($result_customer_receive_details->result() as $row) 
		{
			if ($current_customer_name == '' || strtolower($current_customer_name) != strtolower($row->customer_name)) 
			{
				$current_customer_name = $row->customer_name;
				
				$result_new_customer_return = get_next_number('return_head','reference_number',array('entry_date' => date("Y-m-d H:i:s"),
																									'customer' => $current_customer_name,
																									'memo' => 'SD'.$delivery_head_info_row->reference_number,
																									'is_used' => 1));
				if ($result_new_customer_return['error'] != '')
					throw new \Exception($this->_error_message['UNABLE_TO_GENERATE_REFERENCE']);

				$new_customer_return_head_id = $this->_CI->encrypt->decode($result_new_customer_return['id']);

				array_push($return_customer_return_ids, $result_new_customer_return['id']);
			}
			

			array_push($customer_return_detail, array('headid' => $new_customer_return_head_id,
													'quantity' => 1,
													'product_id' => $row->product_id,
													'description' => $row->description));

			$current_customer_name = $row->customer_name;
			$i++;
		}
				
		$result_customer_receive_details->free_result();
		$result_delivery_head_info->free_result();

		$this->_CI->delivery_model->transfer_details_to_new_return($customer_return_detail);

		$response['id'] = $return_customer_return_ids;

		return $response;
	}
}

?>