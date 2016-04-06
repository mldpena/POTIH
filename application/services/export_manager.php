<?php

namespace Services;

class Export_Manager
{
	private $_CI;

	public function __construct()
	{
		$this->_CI = $CI =& get_instance();
	}

	public function parse_get_product_list($param)
	{
		$this->_CI->load->constant('product_const');
		$this->_CI->load->model('product_model');

		$response = array();

		$response['rowcnt'] = 0;

		$result = $this->_CI->product_model->get_product_list_by_filter($param, FALSE);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $this->_CI->product_model->get_product_list_count_by_filter($param);

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = $row->material_code;
				$response['data'][$i][] = $row->description;
				$response['data'][$i][] = $row->type;
				$response['data'][$i][] = $row->material_type;
				$response['data'][$i][] = $row->subgroup;
				$response['data'][$i][] = $row->inventory;
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function parse_get_purchase_by_transaction($param)
	{
		$this->_CI->load->model('purchaseorder_model');

		$response = array();

		$response['rowcnt'] = 0;

		$result = $this->_CI->purchaseorder_model->get_purchase_by_transaction($param);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = $row->location;
				$response['data'][$i][] = $row->forbranch;
				$response['data'][$i][] = $row->reference_number;
				$response['data'][$i][] = $row->type;
				$response['data'][$i][] = $row->entry_date;
				$response['data'][$i][] = $row->supplier;
				$response['data'][$i][] = $row->memo;
				$response['data'][$i][] = $row->total_qty;
				$response['data'][$i][] = $row->status;
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function parse_get_purchase_return_by_transaction($param)
	{
		$this->_CI->load->model('purchasereturn_model');

		$response = array();

		$response['rowcnt'] = 0;

		$result = $this->_CI->purchasereturn_model->get_purchase_return_by_transaction($param);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = $row->location;
				$response['data'][$i][] = $row->reference_number;
				$response['data'][$i][] = $row->entry_date;
				$response['data'][$i][] = $row->supplier;
				$response['data'][$i][] = $row->memo;
				$response['data'][$i][] = $row->total_qty;
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function parse_get_purchase_receive_by_transaction($param)
	{
		$this->_CI->load->model('purchasereceive_model');

		$response = array();

		$response['rowcnt'] = 0;

		$result = $this->_CI->purchasereceive_model->get_purchase_receive_by_transaction($param);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = $row->location;
				$response['data'][$i][] = $row->for_branch;
				$response['data'][$i][] = $row->reference_number;
				$response['data'][$i][] = $row->po_numbers;
				$response['data'][$i][] = $row->entry_date;
				$response['data'][$i][] = $row->memo;
				$response['data'][$i][] = $row->total_qty;
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function parse_get_delivery_by_transaction($param)
	{
		$this->_CI->load->model('delivery_model');

		$response = array();

		$response['rowcnt'] = 0;

		$result = $this->_CI->delivery_model->get_delivery_by_transaction($param);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = $row->reference_number;
				$response['data'][$i][] = $row->from_branch;
				$response['data'][$i][] = $row->to_branch;
				$response['data'][$i][] = $row->entry_date;
				$response['data'][$i][] = $row->delivery_type;
				$response['data'][$i][] = $row->memo;
				$response['data'][$i][] = $row->total_qty;
				$response['data'][$i][] = $row->status;
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function parse_get_delivery_receive_by_transaction($param, $receive_type)
	{
		$this->_CI->load->model('delivery_model');

		$response = array();

		$response['rowcnt'] = 0;

		$result = $this->_CI->delivery_model->get_delivery_receive_by_transaction($param, $receive_type);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = $row->reference_number;
				$response['data'][$i][] = $row->from_branch;

				if ($receive_type == 'TRANSFER')
					$response['data'][$i][] = $row->to_branch;

				$response['data'][$i][] = $row->entry_date;
				$response['data'][$i][] = $row->memo;
				$response['data'][$i][] = $row->total_qty;
				$response['data'][$i][] = $row->status;
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function parse_get_customer_return_by_transaction($param)
	{
		$this->_CI->load->model('return_model');

		$response = array();

		$response['rowcnt'] = 0;

		$result = $this->_CI->return_model->get_customer_return_by_transaction($param);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = $row->location;
				$response['data'][$i][] = $row->reference_number;
				$response['data'][$i][] = $row->entry_date;
				$response['data'][$i][] = $row->customer;
				$response['data'][$i][] = $row->memo;
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function parse_get_damage_by_transaction($param)
	{
		$this->_CI->load->model('damage_model');

		$response = array();

		$response['rowcnt'] = 0;

		$result = $this->_CI->damage_model->get_damage_by_transaction($param);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = $row->location;
				$response['data'][$i][] = $row->reference_number;
				$response['data'][$i][] = $row->entry_date;
				$response['data'][$i][] = $row->memo;
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function parse_get_inventory_adjustment($param)
	{
		$this->_CI->load->model('adjust_model');

		$response = array();

		$response['rowcnt'] = 0;

		$result = $this->_CI->adjust_model->get_inventory_adjustment_list($param);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				$response['data'][$i][] = $row->material_code;
				$response['data'][$i][] = $row->description;
				$response['data'][$i][] = $row->type;
				$response['data'][$i][] = $row->from_branch;
				$response['data'][$i][] = $row->date_created;
				$response['data'][$i][] = $row->old_inventory;
				$response['data'][$i][] = $row->current_inventory;
				$response['data'][$i][] = $row->requested_new_inventory;
				$response['data'][$i][] = $row->status;
				$response['data'][$i][] = $row->memo;
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function parse_get_inventory_warning($param)
	{
		extract($param);		

		$this->_CI->load->constant('product_const');
		$this->_CI->load->model('product_model');
		$this->_CI->load->model('branch_model');

		$response = [];
		$branch_column_list = '';
		$string_buffer = '';
		$response['rowcnt'] = 0;
		$response['branch_name'] = [];

		$branch_result = $this->_CI->branch_model->get_branch_list();
		
		if($branch_result->num_rows() > 0) 
		{
			foreach ($branch_result->result() as $row) 
			{
				if ((int)$branch === (int)$row->id) 
					$string_buffer = ",COALESCE(PBI.`inventory`,0) AS 'inventory'";
				else
				{
					$branch_column_list .= ",SUM(IF(PBI2.`branch_id` = ".$row->id.", PBI2.`inventory`, 0)) AS '".$row->name."'";
					$response['branch_name'][] = strtoupper($row->name);
				}
			}

			$branch_column_list .= $string_buffer;
			$response['branch_name'][] = 'INVENTORY';
		}

		$result = $this->_CI->product_model->get_product_warning_list_by_filter($param, FALSE, $branch_column_list);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{
				foreach ($row as $key => $value) 
					$response['data'][$i][] = $value;

				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function parse_get_branch_inventory($param)
	{
		extract($param);		

		$this->_CI->load->constant('product_const');
		$this->_CI->load->model('product_model');
		$this->_CI->load->model('branch_model');

		$branch_column_list = "";
		$array_branch_name_list = array();
		$key_array_exception 	= array('material_code', 'description', 'type', 'total_inventory');
		$branch = explode(',', $branch);

		$result_branch = $this->_CI->branch_model->get_branch_list();

		if ($result_branch->num_rows() > 0) 
		{
			foreach ($result_branch->result() as $row) 
			{
				if (in_array($row->id, $branch)) 
					array_push($array_branch_name_list, $row->name);

				$branch_column_list .= ",SUM(IF(PBI.`branch_id` = ".$row->id.", PBI.`inventory`, 0)) AS '".$row->name."'";	
			}
		}
		
		$result_branch->free_result();

		$response = array();

		$response['rowcnt'] = 0;
		$response['header'] = array();
		$response['formats'] = array();
		$response['align'] = array();
		$response['width'] = array();
		$response['count'] = 4;

		$result = $this->_CI->product_model->get_product_branch_inventory_list_by_filter($param, $branch_column_list, FALSE);

		$header_flag = FALSE;

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $this->_CI->product_model->get_product_branch_inventory_list_count_by_filter($param);

			foreach ($result->result() as $row) 
			{
				if (!$header_flag)
				{
					array_push($response['header'], 'MATERIAL CODE', 'PRODUCT', 'TYPE');
					array_push($response['formats'], 'String', 'String', 'String');
					array_push($response['align'], 'Center', 'Left', 'Center');
					array_push($response['width'], 20, 60, 20);
				}

				foreach ($row as $key => $value)
				{
					if (!in_array($key,$key_array_exception)) 
					{
						if (in_array($key,$array_branch_name_list))
						{
							if (!$header_flag)
							{
								array_push($response['header'], strtoupper($key));
								array_push($response['formats'], 'Number-0');
								array_push($response['align'], 'Center');
								array_push($response['width'], 20);
								$response['count']++;
							}

							$response['data'][$i][] = $value;
						}
					}
					else
      					$response['data'][$i][] = $value;
				}

				if (!$header_flag)
				{
					array_push($response['header'], 'TOTAL INVENTORY');
					array_push($response['formats'], 'Number-0');
					array_push($response['align'], 'Center');
					array_push($response['width'], 30);
				}

				$header_flag = TRUE;
				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function parse_get_product_transaction_list_info($param)
	{
		$this->_CI->load->constant('product_const');
		$this->_CI->load->model('product_model');

		$response = array();

		$response['rowcnt'] = 0;

		$result = $this->_CI->product_model->get_transaction_summary_by_filter($param, FALSE);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{		
				foreach ($row as $key => $value)
				{
					if ($key !== 'sales_reservation')
      					$response['data'][$i][] = $value;
				}

      			$response['data'][$i][] = $row->beginv + $row->purchase_receive + $row->customer_return + $row->stock_receive 
      											+ $row->adjust_increase - $row->damage - $row->purchase_return - $row->stock_delivery - $row->customer_delivery 
      											- $row->adjust_decrease - $row->release;

      			$response['data'][$i][] = $row->sales_reservation;

				$i++;
			}
		}

		$result->free_result();
		
		return $response;
	}

	public function parse_generate_sales_report($param)
	{
		$this->_CI->load->model('sales_model');

		$response = [];

		$response['rowcnt'] = 0;
		$response['customer_name'] = '';

		$total_amount = 0;

		$result = $this->_CI->sales_model->get_sales_list_by_filter($param, FALSE);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{		
				$response['data'][$i][] = $row->reference_number;

				if ($param['report_type'] != \Constants\SALES_CONST::DAILY_SALES_REPORT) 
					$response['data'][$i][] = date('m-d-Y', strtotime($row->entry_date));

				if ($param['report_type'] != \Constants\SALES_CONST::CUSTOMER_SALES_REPORT) 
					$response['data'][$i][] = $row->customer;
				else
					$response['customer_name'] = $row->customer;

				$response['data'][$i][] = $row->salesman;
				$response['data'][$i][] = number_format($row->amount, 2);

				$total_amount += $row->amount;

				$i++;
			}

			$data_length = count($response['data'][0]);

			for ($x=0; $x < $data_length; $x++) 
			{ 
				$value = '';

				if ($x == ($data_length - 2))
					$value = 'Total';
				else if ($x == ($data_length - 1))
 					$value = number_format($total_amount, 2);

				$response['data'][$i][] = $value;
			}

			if ($param['report_type'] != \Constants\SALES_CONST::CUSTOMER_SALES_REPORT)
			{
				$salesman_result = $this->_CI->sales_model->get_salesman_sales_by_filter($param);

				if ($salesman_result->num_rows() > 0) 
				{
					$i++;

					for ($x=0; $x < $data_length; $x++) 
						$response['data'][$i][] = '';

					$i++;
					
					$response['data'][$i][] = '';

					if ($param['report_type'] == \Constants\SALES_CONST::PERIODIC_SALES_REPORT)
						$response['data'][$i][] = '';

					$response['data'][$i][] = 'Salesman Breakdown';

					for ($x=count($response['data'][$i]); $x < $data_length; $x++) 
						$response['data'][$i][] = '';

					foreach ($salesman_result->result() as $row) 
					{		

						$i++;

						for ($x=0; $x < $data_length; $x++) 
						{ 
							$value = '';

							if ($x == ($data_length - 3))
								$value = $row->salesman;
							else if ($x == ($data_length - 2))
								$value = $row->invoice_count.' invoices';
							else if ($x == ($data_length - 1))
			 					$value = number_format($row->amount, 2);

							$response['data'][$i][] = $value;
						}
					}
				}

				$salesman_result->free_result();
			}
		}

		$result->free_result();

		return $response;
	}

	public function parse_generate_sales_book_report($param)
	{
		extract($param);

		$this->_CI->load->model('sales_model');

		$response = [];

		$response['rowcnt'] = 0;

		$total_amount = 0;
		$total_vatable_amount = 0;
		$total_vat_amount = 0;
		$total_vat_exempt_amount = 0;

		$result = $this->_CI->sales_model->get_sales_list_by_filter($param, FALSE);

		if ($result->num_rows() > 0) 
		{
			$i = 0;
			$response['rowcnt'] = $result->num_rows();

			foreach ($result->result() as $row) 
			{		
				$response['data'][$i][] = date('m/d/Y', strtotime($row->entry_date));
				$response['data'][$i][] = $row->reference_number;
				$response['data'][$i][] = $row->customer;
				$response['data'][$i][] = $row->amount;
				$response['data'][$i][] = $row->vatable_amount;
				$response['data'][$i][] = $row->vat_amount;
				$response['data'][$i][] = $row->vat_exempt_amount;

				$total_amount += $row->amount;
				$total_vatable_amount += $row->vatable_amount;
				$total_vat_amount += $row->vat_amount;
				$total_vat_exempt_amount += $row->vat_exempt_amount;

				$i++;
			}
		}

		$response['branch_name'] = strtoupper($branch_name);
		$response['date_from'] = date("m/d/Y", strtotime($date_from));
		$response['date_to'] = date("m/d/Y", strtotime($date_to));
		$response['total_amount'] = $total_amount;
		$response['total_vatable_amount'] = $total_vatable_amount;
		$response['total_vat_amount'] = $total_vat_amount;
		$response['total_vat_exempt_amount'] = $total_vat_exempt_amount;

		$result->free_result();

		return $response;
	}
}

?>