<?php

namespace Services;

class Purchase_Manager
{
	private $_CI;
	private $_current_purchase_head_id;
	private $_current_branch_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('UNABLE_TO_INSERT' => 'Unable to insert purchase detail!',
									'UNABLE_TO_UPDATE' => 'Unable to update purchase detail!',
									'UNABLE_TO_UPDATE_HEAD' => 'Unable to update purchase head!',
									'UNABLE_TO_SELECT_HEAD' => 'Unable to get purchase head details!',
									'UNABLE_TO_SELECT_DETAILS' => 'Unable to get purchase details!',
									'UNABLE_TO_DELETE' => 'Unable to delete purchase detail!',
									'UNABLE_TO_DELETE_HEAD' => 'Unable to delete purchase head!',
									'HAS_RECEIVED' => 'PO can only be deleted if purchase status is no received!',
									'NOT_OWN_BRANCH' => 'Cannot delete purchase order entry of other branches!',
									'UNABLE_TO_GENERATE_REFERENCE' => 'Unablet to generate new reference number!',
									'NO_REMAINING_PO_FOUND' => 'No remaining purchase detail found!');

	public function __construct()
	{
		$this->_CI = $CI =& get_instance();

		$this->_current_branch_id 	= $this->_CI->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->_CI->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d h:i:s");
		$this->_current_purchase_head_id = $this->_CI->uri->segment(3);
	}

	public function transfer_remaining_po_to_new($param)
	{
		extract($param);
		$response = array();

		$response['error'] = '';

		$new_purchase_head_id = 0;
		$selected_purchase_detail_id = $this->_CI->encrypt->decode_array($selected_detail_id);

		$new_purchase_detail = array();
		$old_purchase_detail = array();

		$result_old_purchase_details = $this->_CI->purchaseorder_model->get_purchase_order_details_with_remaining($selected_purchase_detail_id);

		if ($result_old_purchase_details->num_rows() > 0) 
		{
			$new_purchase_head_result = get_next_number('purchase_head','reference_number',array('entry_date' => date("Y-m-d h:i:s")));

			if ($new_purchase_head_result['error'] != '')
				throw new \Exception($this->_error_message['UNABLE_TO_GENERATE_REFERENCE']);

			$new_purchase_head_id = $this->_CI->encrypt->decode($new_purchase_head_result['id']);

			$i = 0;

			foreach ($result_old_purchase_details->result() as $row) 
			{
				array_push($new_purchase_detail, array('headid' => $new_purchase_head_id,
														'quantity' => $row->recv_quantity,
														'product_id' => $row->product_id,
														'description' => $row->description,
														'memo' => $row->memo));

				$old_purchase_detail[$i]['id'] = $row->id;
				$old_purchase_detail[$i]['detail'] = array('quantity' => $row->quantity - $row->recv_quantity);

				$i++;
			}
		}
		else
			throw new \Exception($this->_error_message['NO_REMAINING_PO_FOUND']);
			
		$result_old_purchase_details->free_result();

		$this->_CI->purchaseorder_model->transfer_remaining_details_to_new_po($old_purchase_detail, $new_purchase_detail);

		$response['id'] = $new_purchase_head_result['id'];

		return $response;
	}
}

?>