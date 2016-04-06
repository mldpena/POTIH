<?php

namespace Services;

class Sales_Manager
{
	private $_CI;
	private $_number_transformer;
	private $_current_branch_id = 0;
	private $_sales_head_id = 0;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('UNABLE_TO_INSERT' => 'Unable to insert sales invoice detail!',
									'UNABLE_TO_UPDATE' => 'Unable to update sales invoice detail!',
									'UNABLE_TO_UPDATE_HEAD' => 'Unable to update sales invoice head!',
									'UNABLE_TO_SELECT_HEAD' => 'Unable to get sales invoice head details!',
									'UNABLE_TO_SELECT_DETAILS' => 'Unable to get sales invoice details!',
									'UNABLE_TO_DELETE' => 'Unable to delete sales invoice detail!',
									'UNABLE_TO_DELETE_HEAD' => 'Unable to delete sales invoice head!',
									'HAS_DELIVERED' => 'Sales Invoice can only be deleted if sales invoice status is no delivery!',
									'NOT_OWN_BRANCH' => 'Cannot delete sales invoice entry of other branches!',
									'UNABLE_TO_DELETE_RESERVATION' => 'Unable to remove imported sales reservation. Please try again.',
									'RESERVATION_NOT_FOUND' => 'No sales reservation found!',
									'UNABLE_TO_GENERATE_REFERENCE' => 'Unable to generate new reference number!',
									'NO_SALES_REPORT' => 'No sales report found!',
									'PRINT_TRANSACTION_DETAILS_ERROR' => 'Please encode at least one product!');

	public function __construct()
	{
		$this->_CI = $CI =& get_instance();

		$registry = new \Kwn\NumberToWords\Transformer\TransformerFactoriesRegistry([
		    new \Kwn\NumberToWords\Language\English\TransformerFactory
		]);

		$numberToWords = new \Kwn\NumberToWords\NumberToWords($registry);

		$this->_number_transformer = $numberToWords->getNumberTransformer('en');

		$this->_current_branch_id 	= $this->_CI->encrypt->decode(get_cookie('branch'));
		$this->_current_user 		= $this->_CI->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d H:i:s");

		$this->_sales_head_id = (int)$this->_CI->encrypt->decode($this->_CI->uri->segment(3));

		$this->_CI->load->model('sales_model');
	}

	public function get_sales_details()
	{
		$response = [];

		$response['error'] = '';
		$response['detail_error'] = '';
		$response['reservation_list_error'] = ''; 

		$result_head = $this->_CI->sales_model->get_sales_head_info_by_id();

		if ($result_head->num_rows() != 1) 
			throw new \Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= date('m-d-Y', strtotime($row->entry_date));
			$response['memo'] 				= $row->memo;
			$response['customer_id'] 		= $row->customer_id;
			$response['walkin_customer_name'] = $row->walkin_customer_name;
			$response['address'] 			= $row->address;
			$response['for_branch'] 		= $row->for_branch_id;
			$response['salesman_id'] 		= $row->salesman_id;
			$response['is_vatable'] 		= $row->is_vatable;
			$response['is_vatable'] 		= $row->is_vatable;
			$response['ponumber'] 			= $row->ponumber;
			$response['drnumber'] 			= $row->drnumber;
			$response['is_editable'] 		= $row->qty_released == 0 ? (($row->branch_id == $this->_current_branch_id) ? TRUE : FALSE) : FALSE;
			$response['is_saved'] 			= $row->is_used == 1 ? TRUE : FALSE;
			$response['is_incomplete'] 		= $row->remaining_qty > 0 && $row->qty_released > 0 ? TRUE : FALSE;
			$response['transaction_branch'] = $row->branch_id;
			$response['own_branch'] 		= $this->_current_branch_id;
		}

		$result_head->free_result();

		$result_detail = $this->_CI->sales_model->get_sales_detail_info_by_id();

		if ($result_detail->num_rows() == 0) 
			$response['detail_error'] = $this->_error_message['UNABLE_TO_SELECT_DETAILS'];
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$break_line = $row->type == \Constants\SALES_CONST::STOCK ? '' : '<br/>';
				$response['detail'][$i][] = array($this->_CI->encrypt->encode($row->id));
				$response['detail'][$i][] = array($this->_CI->encrypt->encode($row->reservation_detail_id));
				$response['detail'][$i][] = array($row->reservation_number);
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line, $row->description, $row->is_deleted);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->uom);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->price);
				$response['detail'][$i][] = array($row->qty_released);
				$response['detail'][$i][] = array($row->memo);
				$response['detail'][$i][] = array($row->amount);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$result_detail->free_result();

		$reservation_lists = $this->get_customer_reservation_list($response['customer_id'], $response['for_branch']);

		if ($reservation_lists['error'] !== '')
			$response['reservation_list_error'] = $reservation_lists['error'];
		else
			$response['reservation_lists'] = $reservation_lists['data'];

		return $response;
	}

	public function search_sales_list($param)
	{
		$row_start = (int)$param['row_start'];
		
		$response = [];

		$response['rowcnt'] = 0;
		$response['total_amount'] = 0;

		$result = $this->_CI->sales_model->get_sales_list_by_filter($param);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			
			$summary_result = $this->_CI->sales_model->get_sales_list_count_by_filter($param);
			$row = $summary_result->row();
			$response['rowcnt'] = $row->rowCount;
			$response['total_amount'] = number_format($row->total_amount, 2);
			
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
				$response['data'][$i][] = array(number_format($row->amount, 2));
				$response['data'][$i][] = array($row->status);
				$response['data'][$i][] = array('');
				$i++;
			}

			$summary_result->free_result();
		}

		$result->free_result();
		
		return $response;
	}

	public function insert_sales_detail($param)
	{
		extract($param);

		$reservation_detail_id = $this->_CI->encrypt->decode($reservation_detail_id);

		$sales_detail_data = [
								'headid' => $this->_sales_head_id,
								'quantity' => $qty,
								'product_id' => $product_id,
								'description' => $description,
								'price' => $price,
								'memo' => $memo,
								'reservation_detail_id' => $reservation_detail_id
							];

		$response = $this->_CI->sales_model->insert_new_sales_detail($sales_detail_data);

		if (!empty($response['error']))
			throw new \Exception($this->_error_message['UNABLE_TO_INSERT']);
			
		return $response;
	}

	public function update_sales_detail($param)
	{
		extract($param);

		$sales_detail_id = $this->_CI->encrypt->decode($detail_id);

		$reservation_detail_id = $this->_CI->encrypt->decode($reservation_detail_id);

		$sales_detail_data = [
								'headid' => $this->_sales_head_id,
								'quantity' => $qty,
								'product_id' => $product_id,
								'description' => $description,
								'price' => $price,
								'memo' => $memo,
								'reservation_detail_id' => $reservation_detail_id
							];

		$response = $this->_CI->sales_model->update_sales_table($sales_detail_data, \Constants\SALES_CONST::TBL_SALES_DETAIL, $sales_detail_id);

		if (!empty($response['error']))
			throw new \Exception($this->_error_message['UNABLE_TO_UPDATE']);
			
		return $response;
	}

	public function delete_sales_detail($param)
	{
		extract($param);

		$sales_detail_id = $this->_CI->encrypt->decode($detail_id);

		$response = $this->_CI->sales_model->delete_sales_detail_by_id($sales_detail_id);

		if (!empty($response['error']))
			throw new \Exception($this->_error_message['UNABLE_TO_DELETE']);
			
		return $response;
	}

	public function update_sales_head($param)
	{
		extract($param);

		$sales_head_data = [
								'for_branch_id' => $orderfor,
								'customer_id' => $customer_id,
								'walkin_customer_name' => $walkin_customer_name,
								'walkin_customer_address' => $address,
								'entry_date' => $entry_date.' '.date('H:i:s'),
								'memo' => $memo,
								'salesman_id' => $salesman,
								'ponumber' => $ponumber,
								'drnumber' => $drnumber,
								'is_vatable' => $is_vatable,
								'is_used' => \Constants\SALES_CONST::USED
							];

		$response = $this->_CI->sales_model->update_sales_table($sales_head_data, \Constants\SALES_CONST::TBL_SALES_HEAD, $this->_sales_head_id);

		if (!empty($response['error']))
			throw new \Exception($this->_error_message['UNABLE_TO_UPDATE_HEAD']);
			
		return $response;
	}

	public function delete_sales($param)
	{
		extract($param);

		$sales_head_id = $this->_CI->encrypt->decode($head_id);

		$result = $this->_CI->sales_model->get_transaction_total_delivered_quantity($sales_head_id);

		$row 	= $result->row();

		if ($row->qty_released > 0)
			throw new \Exception($this->_error_message['HAS_DELIVERED']);

		if ($row->branch_id != $this->_current_branch_id)
			throw new \Exception($this->_error_message['NOT_OWN_BRANCH']);

		$update_sales_data = [
								'is_show' => \Constants\SALES_CONST::DELETED,
								'last_modified_date' => $this->_current_date,
								'last_modified_by' => $this->_current_user
							];


		$response = $this->_CI->sales_model->update_sales_table($update_sales_data, \Constants\SALES_CONST::TBL_SALES_HEAD, $sales_head_id);

		if (!empty($response['error']))
			throw new \Exception($this->_error_message['UNABLE_TO_DELETE_HEAD']);

		return $response;
	}

	public function remove_imported_reservation()
	{
		$response = $this->_CI->sales_model->delete_imported_reservation_by_transaction_id();

		if (!empty($response['error']))
			throw new \Exception($this->_error_message['UNABLE_TO_DELETE_RESERVATION']);
			
		return $response;
	}

	public function get_customer_reservation_list($customer_id, $branch_id)
	{
		$reservation_lists['error'] = '';

		$result_reservation_list = $this->_CI->sales_model->get_customer_reservation_list_by_id($customer_id, $branch_id);

		if ($result_reservation_list->num_rows() == 0) 
			$reservation_lists['error'] = $this->_error_message['RESERVATION_NOT_FOUND'];
		else
		{
			$i = 0;
			foreach ($result_reservation_list->result() as $row) 
			{
				$reservation_lists['data'][$i][] = array($this->_CI->encrypt->encode($row->id));
				$reservation_lists['data'][$i][] = array($row->is_sold);
				$reservation_lists['data'][$i][] = array($row->reservation_number);
				$reservation_lists['data'][$i][] = array(date('m-d-Y', strtotime($row->reservation_date)));
				$reservation_lists['data'][$i][] = array($row->salesman);
				$reservation_lists['data'][$i][] = array($row->total_qty);
				$i++;
			}
		}

		$result_reservation_list->free_result();

		return $reservation_lists;
	}

	public function get_transaction_reservation_details($param)
	{
		extract($param);

		$response['error'] = '';

		$reservation_head_id = $this->_CI->encrypt->decode($reservation_head_id);

		$result = $this->_CI->sales_model->get_reservation_details_by_id($reservation_head_id);

		if ($result->num_rows() == 0) 
			throw new \Exception($this->_error_message['UNABLE_TO_SELECT_DETAILS']);
		else
		{
			$i = 0;
			foreach ($result->result() as $row) 
			{
				$break_line = $row->type == \Constants\SALES_CONST::STOCK ? '' : '<br/>';
				$response['detail'][$i][] = $row->id == 0 ? array(0) : array($this->_CI->encrypt->encode($row->id));
				$response['detail'][$i][] = array($this->_CI->encrypt->encode($row->reservation_detail_id));
				$response['detail'][$i][] = array($row->reservation_number);
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->product, $row->product_id, $row->type, $break_line, $row->description, $row->is_deleted);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->uom);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->price);
				$response['detail'][$i][] = array($row->qty_released);
				$response['detail'][$i][] = array($row->memo);
				$response['detail'][$i][] = array($row->amount);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();

		return $response;
	}

	public function update_sales_head_upon_customer_change($param)
	{
		extract($param);

		$sales_head_data = [
								'for_branch_id' => $for_branch_id,
								'customer_id' => $customer_id,
								'is_vatable' => $is_vatable
							];

		$response = $this->_CI->sales_model->update_sales_table($sales_head_data, \Constants\SALES_CONST::TBL_SALES_HEAD, $this->_sales_head_id);

		if (!empty($response['error']))
			throw new \Exception($this->_error_message['UNABLE_TO_UPDATE_HEAD']);
			
		return $response;
	}

	public function generate_sales_report($param)
	{
		$row_start = (int)$param['row_start'];
		
		$response = [];

		$response['rowcnt'] = 0;
		$response['total_amount'] = 0;

		$result = $this->_CI->sales_model->get_sales_list_by_filter($param);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			
			$summary_result = $this->_CI->sales_model->get_sales_list_count_by_filter($param);
			$row = $summary_result->row();
			$response['rowcnt'] = $row->rowCount;
			$response['total_amount'] = number_format($row->total_amount, 2);

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($row_start + $i + 1);
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array($row->entry_date);
				$response['data'][$i][] = array($row->customer);
				$response['data'][$i][] = array($row->salesman);	
				$response['data'][$i][] = array(number_format($row->amount, 2));
				$i++;
			}

			$summary_result->free_result();
		}

		$result->free_result();

		return $response;
	}

	public function set_session_data()
	{
		$response['error'] = '';

		$result = $this->_CI->sales_model->check_if_transaction_has_product();

		if ($result->num_rows() == 0)
			throw new Exception($this->_error_message['PRINT_TRANSACTION_DETAILS_ERROR']);
		else
			$this->_CI->session->set_userdata('sales_invoice', $this->_CI->uri->segment(3));

		$result->free_result();
		
		return $response;
	}

	public function get_sales_invoice_printout_details_by_id()
	{
		$response = [];

		$response['error'] = '';

		$sales_head_id = $this->_CI->encrypt->decode($this->_CI->session->userdata('sales_invoice'));

		$result_head = $this->_CI->sales_model->get_sales_head_info_by_id($sales_head_id);
		
		if ($result_head->num_rows() == 1) 
		{
			$row = $result_head->row();

			foreach ($row as $key => $value)
				$response[$key] = $value;

			$response['number_transformer'] = $this->_number_transformer;
			$response['entry_date'] = date('m/d/Y', strtotime($response['entry_date']));
		}
		else
			throw new \Exception($this->_error_message['UNABLE_TO_SELECT_HEAD']);
			
		$result_head->free_result();

		$result_detail = $this->_CI->sales_model->get_sales_detail_info_by_id($sales_head_id);

		if ($result_detail->num_rows() > 0) 
		{
			$i = 0;

			foreach ($result_detail->result() as $row) 
			{
				foreach ($row as $key => $value) 
					$response['detail'][$i][$key] = $value;

				$i++;
			}
		}
		else
			throw new \Exception($this->_error_message['UNABLE_TO_SELECT_DETAILS']);

		$result_detail->free_result();

		return $response;
	}

	public function generate_book_report($param)
	{
		$row_start = (int)$param['row_start'];
		
		$response = [];

		$response['rowcnt'] = 0;
		$response['total_amount'] = 0;
		$response['total_vatable_amount'] = 0;
		$response['total_vat_amount'] = 0;
		$response['total_vat_exempt_amount'] = 0;

		$result = $this->_CI->sales_model->get_sales_list_by_filter($param);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			
			$summary_result = $this->_CI->sales_model->get_sales_list_count_by_filter($param);
			$row = $summary_result->row();
			$response['rowcnt'] = $row->rowCount;
			$response['total_amount'] = number_format($row->total_amount, 2);
			$response['total_vatable_amount'] = number_format($row->total_vatable_amount, 2);
			$response['total_vat_amount'] = number_format($row->total_vat_amount, 2);
			$response['total_vat_exempt_amount'] = number_format($row->total_vat_exempt_amount, 2);

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($row_start + $i + 1);
				$response['data'][$i][] = array(date("m/d/Y", strtotime($row->entry_date)));
				$response['data'][$i][] = array($row->reference_number);
				$response['data'][$i][] = array($row->customer);
				$response['data'][$i][] = array(number_format($row->amount, 2));
				$response['data'][$i][] = array(number_format($row->vatable_amount, 2));
				$response['data'][$i][] = array(number_format($row->vat_amount, 2));
				$response['data'][$i][] = array(number_format($row->vat_exempt_amount, 2));
				$i++;
			}

			$summary_result->free_result();
		}

		$result->free_result();

		return $response;
	}
}

?>