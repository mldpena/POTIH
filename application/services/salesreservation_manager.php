<?php

namespace Services;

class SalesReservation_Manager
{
	private $_CI;
	private $_current_branch_id = 0;
	private $_sales_reservation_head_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('UNABLE_TO_INSERT' => 'Unable to insert sales reservation detail!',
									'UNABLE_TO_UPDATE' => 'Unable to update sales reservation detail!',
									'UNABLE_TO_UPDATE_HEAD' => 'Unable to update sales reservation head!',
									'UNABLE_TO_SELECT_HEAD' => 'Unable to get sales reservation head details!',
									'UNABLE_TO_SELECT_DETAILS' => 'Unable to get sales reservation details!',
									'UNABLE_TO_DELETE' => 'Unable to delete sales reservation detail!',
									'UNABLE_TO_DELETE_HEAD' => 'Unable to delete sales reservation head!',
									'HAS_SOLD' => 'Sales Reservation can only be deleted if sales reservation status is no sold!',
									'NOT_OWN_BRANCH' => 'Cannot delete sales reservation entry of other branches!',
									'UNABLE_TO_GENERATE_REFERENCE' => 'Unablet to generate new reference number!');

	public function __construct()
	{
		$this->_CI = $CI =& get_instance();

		$this->_current_branch_id 	= $this->_CI->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->_CI->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d H:i:s");

		$this->_sales_reservation_head_id = (int)$this->_CI->encrypt->decode($this->_CI->uri->segment(3));

		$this->_CI->load->model('salesreservation_model');
	}

	public function get_sales_reservation_details()
	{
		$response = [];

		$response['error'] = '';
		$response['detail_error'] = '';

		$result_head = $this->_CI->salesreservation_model->get_sales_reservation_head_info_by_id();

		if ($result_head->num_rows() != 1) 
			throw new \Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= date('m-d-Y', strtotime($row->entry_date));
			$response['due_date'] 			= date('m-d-Y', strtotime($row->due_date));
			$response['memo'] 				= $row->memo;
			$response['customer_id'] 		= $row->customer_id;
			$response['walkin_customer_name'] = $row->walkin_customer_name;
			$response['address'] 			= $row->address;
			$response['for_branch'] 		= $row->for_branch_id;
			$response['salesman_id'] 		= $row->salesman_id;
			$response['is_editable'] 		= $row->sold_qty == 0 ? (($row->branch_id == $this->_current_branch_id) ? TRUE : FALSE) : FALSE;
			$response['is_saved'] 			= $row->is_used == 1 ? TRUE : FALSE;
			$response['is_incomplete'] 		= $row->remaining_qty > 0 && $row->sold_qty > 0 ? TRUE : FALSE;
			$response['transaction_branch'] = $row->branch_id;
			$response['own_branch'] 		= $this->_current_branch_id;
		}

		$result_head->free_result();

		$result_detail = $this->_CI->salesreservation_model->get_sales_reservation_detail_info_by_id();

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = $this->_error_message['UNABLE_TO_SELECT_DETAILS'];
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$break_line = $row->type == \Constants\SALESRESERVATION_CONST::STOCK ? '' : '<br/>';
				$response['detail'][$i][] = array($this->_CI->encrypt->encode($row->id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line, $row->description, $row->is_deleted);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->uom);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->sold_qty);
				$response['detail'][$i][] = array($row->memo);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$result_detail->free_result();

		return $response;
	}

	public function search_sales_reservation_list($param)
	{
		$row_start = (int)$param['row_start'];
		
		$response = [];

		$response['rowcnt'] = 0;

		$result = $this->_CI->salesreservation_model->get_sales_reservation_list_by_filter($param);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			
			$response['rowcnt'] = $this->_CI->salesreservation_model->get_sales_reservation_list_count_by_filter($param);

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->_CI->encrypt->encode($row->id));
				$response['data'][$i][] = array($row_start + $i + 1);
				$response['data'][$i][] = array($row->location);
				$response['data'][$i][] = array($row->for_branch);
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array($row->customer);
				$response['data'][$i][] = array($row->salesman);
				$response['data'][$i][] = array($row->entry_date);
				$response['data'][$i][] = array($row->memo);
				$response['data'][$i][] = array($row->status);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function insert_sales_reservation_detail($param)
	{
		extract($param);

		$sales_reservation_detail_data = [
											'headid' => $this->_sales_reservation_head_id,
											'quantity' => $qty,
											'product_id' => $product_id,
											'description' => $description
										];

		$response = $this->_CI->salesreservation_model->insert_new_sales_reservation_detail($sales_reservation_detail_data);

		if (!empty($response['error']))
			throw new \Exception($this->_error_message['UNABLE_TO_INSERT']);
			
		return $response;
	}

	public function update_sales_reservation_detail($param)
	{
		extract($param);

		$reservation_detail_id = $this->_CI->encrypt->decode($detail_id);

		$sales_reservation_detail_data = [
											'headid' => $this->_sales_reservation_head_id,
											'quantity' => $qty,
											'product_id' => $product_id,
											'description' => $description
										];

		$response = $this->_CI->salesreservation_model->update_sales_reservation_table($sales_reservation_detail_data, \Constants\SALESRESERVATION_CONST::TBL_RESERVATION_DETAIL, $reservation_detail_id);

		if (!empty($response['error']))
			throw new \Exception($this->_error_message['UNABLE_TO_UPDATE']);
			
		return $response;
	}

	public function delete_sales_reservation_detail($param)
	{
		extract($param);

		$reservation_detail_id = $this->_CI->encrypt->decode($detail_id);

		$response = $this->_CI->salesreservation_model->delete_sales_reservation_detail_by_id($reservation_detail_id);

		if (!empty($response['error']))
			throw new \Exception($this->_error_message['UNABLE_TO_DELETE']);
			
		return $response;
	}

	public function update_sales_reservation_head($param)
	{
		extract($param);

		$sales_reservation_head_data = [
											'for_branch_id' => $orderfor,
											'customer_id' => $customer_id,
											'walkin_customer_name' => $walkin_customer_name,
											'walkin_customer_address' => $address,
											'entry_date' => $entry_date.' '.date('H:i:s'),
											'due_date' => $due_date,
											'memo' => $memo,
											'salesman_id' => $salesman,
											'is_used' => \Constants\SALESRESERVATION_CONST::USED
										];

		$response = $this->_CI->salesreservation_model->update_sales_reservation_table($sales_reservation_head_data, \Constants\SALESRESERVATION_CONST::TBL_RESERVATION_HEAD, $this->_sales_reservation_head_id);

		if (!empty($response['error']))
			throw new \Exception($this->_error_message['UNABLE_TO_UPDATE_HEAD']);
			
		return $response;
	}

	public function delete_sales_reservation($param)
	{
		extract($param);

		$sales_reservation_head_id = $this->_CI->encrypt->decode($head_id);

		$result = $this->_CI->salesreservation_model->get_transaction_total_sold_quantity($sales_reservation_head_id);

		$row 	= $result->row();

		if ($row->sold_qty > 0)
			throw new \Exception($this->_error_message['HAS_SOLD']);

		if ($row->branch_id != $this->_current_branch_id)
			throw new \Exception($this->_error_message['NOT_OWN_BRANCH']);

		$result->free_result();

		$update_sales_reservation_data = [
											'is_show' => \Constants\SALESRESERVATION_CONST::DELETED,
											'last_modified_date' => $this->_current_date,
											'last_modified_by' => $this->_current_user
										];


		$response = $this->_CI->salesreservation_model->update_sales_reservation_table($update_sales_reservation_data, \Constants\SALESRESERVATION_CONST::TBL_RESERVATION_HEAD, $sales_reservation_head_id);

		if (!empty($response['error']))
			throw new \Exception($this->_error_message['UNABLE_TO_DELETE_HEAD']);

		return $response;
	}

	public function get_product_sales_reservation($product_id, $branch_id)
	{
		$response = [];

		$result = $this->_CI->salesreservation_model->get_current_product_reservation($product_id, $branch_id);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			foreach ($result->result() as $row) 
			{
				$response[$i]['reference_number'] 	= $row->reference_number;
				$response[$i]['salesman'] 			= $row->salesman;
				$response[$i]['branch'] 			= $row->branch;
				$response[$i]['entry_date'] 		= $row->entry_date;
				$response[$i]['quantity_reserved'] 	= $row->quantity;
				$response[$i]['unsold_qty'] 		= $row->unsold_qty;

				$i++;
			}
		}

		$result->free_result();

		return $response;
	}
}

?>