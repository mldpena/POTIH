<?php

namespace Services;

class Customer_Manager
{
	private $_CI;
	private $_current_user = 0;
	private $_current_date = '';
	private $_error_message = array('CODE_EXISTS' => 'Customer code already exists!',
									'NAME_EXISTS' => 'Company Name already exists!',
									'UNABLE_TO_INSERT' => 'Unable to insert customer!',
									'UNABLE_TO_UPDATE' => 'Unable to update customer!',
									'UNABLE_TO_SELECT' => 'Unable to get select details!',
									'UNABLE_TO_DELETE' => 'Unable to delete customer!',
									'UNABLE_TO_GET_DATA' => 'Error while retrieving dat. Please try again.',
									'TRANSACTION_EXISTS' => 'Cannot delete customer with record or transaction!');

	public function __construct()
	{
		$this->_CI = $CI =& get_instance();

		$this->_CI->load->model('customer_model');

		$this->_current_user 		= $this->_CI->encrypt->decode(get_cookie('temp'));
		$this->_current_date 		= date("Y-m-d H:i:s");
	}

	/**
	 * Get customer list result set based on customer filters and format returned data
	 * @param  array $param [array of filters]
	 * @return array $response [formatted result set]
	 */
	public function get_customer_list_info($param)
	{
		$row_start = (int)$param['row_start'];
		
		$response = [];

		$response['rowcnt'] = 0;

		$result = $this->_CI->customer_model->get_customer_list_by_filter($param);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			
			$response['rowcnt'] = $this->_CI->customer_model->get_customer_list_count_by_filter($param);

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = array($this->_CI->encrypt->encode($row->id));
				$response['data'][$i][] = array($row_start + $i + 1);
				$response['data'][$i][] = array($row->code);
				$response['data'][$i][] = array($row->company_name);
				$response['data'][$i][] = array($row->contact);
				$response['data'][$i][] = array($row->tin);
				$response['data'][$i][] = array($row->is_vatable);
				$response['data'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function insert_new_customer_details($param)
	{
		extract($param);

		$this->_validate_customer_details($code, $company_name);

		$customer_field_data = array('code' => $code,
									'company_name' => $company_name,
									'office_address' => $office_address,
									'plant_address' => $plant_address,
									'contact' => $contact,
									'contact_person' => $contact_person,
									'tin' => $tin,
									'is_vatable' => $tax,
									'business_style' => $business_style,
									'date_created' => $this->_current_date,
									'created_by' => $this->_current_user);

		$response = $this->_CI->customer_model->insert_new_customer($customer_field_data);

		if (!empty($response['error']))
			throw new \Exception($this->_error_message['UNABLE_TO_INSERT']);
			
		return $response;
	}

	public function get_customer_details($customer_id)
	{
		$response = [];

		$response['error'] = '';

		$result = $this->_CI->customer_model->get_customer_details_by_id($customer_id);

		if ($result->num_rows() == 1) 
		{
			$row = $result->row();

			$response['code'] 			= $row->code;
			$response['company_name'] 	= $row->company_name;
			$response['office_address'] = $row->office_address;
			$response['plant_address'] 	= $row->plant_address;
			$response['contact'] 		= $row->contact;
			$response['contact_person'] = $row->contact_person;
			$response['tin'] 			= $row->tin;
			$response['tax'] 			= $row->is_vatable;
			$response['business_style'] = $row->business_style;
		}
		else
			throw new \Exception($this->_error_message["UNABLE_TO_SELECT"]);

		$result->free_result();
		
		return $response;
	}

	public function update_customer_details($param)
	{
		extract($param);

		$this->_validate_customer_details($code, $company_name);

		$customer_field_data = array('code' => $code,
									'company_name' => $company_name,
									'office_address' => $office_address,
									'plant_address' => $plant_address,
									'contact' => $contact,
									'contact_person' => $contact_person,
									'tin' => $tin,
									'is_vatable' => $tax,
									'business_style' => $business_style,
									'last_modified_by' => $this->_current_user,
									'last_modified_date' => $this->_current_date);

		$response = $this->_CI->customer_model->update_customer_details_by_id($customer_field_data);

		if (!empty($response['error']))
			throw new \Exception($this->_error_message['UNABLE_TO_UPDATE']);
			
		return $response;
	}

	public function delete_customer($param)
	{
		extract($param);

		$customer_id = $this->_CI->encrypt->decode($head_id);

		$response = $this->validate_customer_transaction($customer_id);

		$updated_customer_fields = [
										'is_show' => \Constants\CUSTOMER_CONST::DELETED,
										'last_modified_date' => $this->_current_date,
										'last_modified_by' => $this->_current_user
									];

		$response = $this->_CI->customer_model->update_customer_details_by_id($updated_customer_fields, $customer_id);

		if (!empty($response['error']))
			throw new \Exception($this->_error_message['UNABLE_TO_DELETE']);

		return $response;
	}

	public function validate_customer_transaction($customer_id)
	{
		$response['error'] = '';

		$result = $this->_CI->customer_model->check_customer_transaction($customer_id);

		$row = $result->row();

		$transaction_count = $row->transaction_count;

		$result->free_result();

		if ($transaction_count > 0)
			throw new \Exception($this->_error_message['TRANSACTION_EXISTS']);

		return $response;
	}

	private function _validate_customer_details($code, $company_name)
	{
		$result = $this->_CI->customer_model->check_if_field_data_exists(array("`code`" => $code));

		if ($result->num_rows() > 0) 
			throw new \Exception($this->_error_message['CODE_EXISTS']);
			
		$result->free_result();

		$result = $this->_CI->customer_model->check_if_field_data_exists(array("`company_name`" => $company_name));

		if ($result->num_rows() > 0) 
			throw new \Exception($this->_error_message['NAME_EXISTS']);
			
		$result->free_result();
	}

	public function import_customer_from_csv()
	{
		$exploded_name 	= explode(".", $_FILES["file"]["name"]);
		$extension 		= end($exploded_name);

		$response = [];

		$response['error'] = '';

		$i = 0;

		if ($_FILES['file']['type'] == 'application/vnd.ms-excel' && $extension == 'csv')
		{
			$customer_csv_file = $_FILES['file']['tmp_name'];
			$handle = fopen($customer_csv_file, "r");
			$customer_data_list = [];

			while (($customer_csv_data = fgetcsv($handle, 10000)) !== FALSE) 
			{
				$i++;

				if ($i > 1 && count($customer_csv_data) >= 9)
				{
					if (count($customer_csv_data) < 9)
					{
						$response['logs'][] = 'Row #'.$i." : Unable to process current row because of incomplete detail count!";
						continue;
					}

					if (
							!in_array(strtolower($customer_csv_data[7]), ['yes', 'no']) || 
							empty($customer_csv_data[0]) || 
							empty($customer_csv_data[1]) ||
							empty($customer_csv_data[2]) ||
							empty($customer_csv_data[3])
						) 
					{
						$response['logs'][] = 'Row #'.$i." : Unable to process current row because of incomplete details!";
						continue;
					}

					/**
					 * Parse data from csv to utf8
					 */
					foreach ($customer_csv_data as $key => $value) 
						$customer_csv_data[$key] = utf8_encode($value);

					$with_error 	= FALSE;
					$customer_code 	= trim($customer_csv_data[0]);
					$customer_name 	= trim($customer_csv_data[1]);
					$office_address = trim($customer_csv_data[2]);
					$plant_address 	= trim($customer_csv_data[3]);
					$contact 		= trim($customer_csv_data[4]);
					$contact_person = trim($customer_csv_data[5]);
					$tin 			= trim($customer_csv_data[6]);
					$business_style = trim($customer_csv_data[8]);

					$is_vatable 	= strtolower($customer_csv_data[7]) == 'yes' ? \Constants\CUSTOMER_CONST::VATABLE : \Constants\CUSTOMER_CONST::NONVAT;

					$result = $this->_CI->customer_model->check_if_field_data_exists(["`code`" => $customer_code]);
				
					if ($result->num_rows() > 0) 
					{
						$response['logs'][] = 'Row #'.$i." : Customer Code [".$customer_code."] already exists!";
						$with_error = TRUE;
					}

					$result->free_result();

					$result = $this->_CI->customer_model->check_if_field_data_exists(["`company_name`" => $customer_name]);

					if ($result->num_rows() > 0) 
					{
						$response['logs'][] = 'Row #'.$i." : Customer Name [".$customer_name."] already exists!";
						$with_error = TRUE;
					}
						
					$result->free_result();

					if (!$with_error) 
					{
						$customer_data_list = 	[
													'code' => $customer_code,
													'company_name' => $customer_name,
													'office_address' => $office_address,
													'plant_address' => $plant_address,
													'contact' => $contact,
													'contact_person' => $contact_person,
													'tin' => $tin,
													'is_vatable' => $is_vatable,
													'business_style' => $business_style,
													'date_created' => $this->_current_date,
													'created_by' => $this->_current_user
												];

						$customer_inserted_result = $this->_CI->customer_model->insert_new_customer($customer_data_list);
					
						if (!empty($customer_inserted_result['error'])) 
							$response['logs'][] = 'Row #'.$i." : Unable to process current row!";
						else
							$response['logs'][] = 'Row #'.$i." : Successfully imported!";
					}
				}
			}
		}
		else
			$response['error'] = 'Invalid file type!';
		
		return $response;
	}
}

?>