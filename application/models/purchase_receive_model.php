<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_Receive_Model extends CI_Model {

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		$this->load->library('encrypt');
		$this->load->library('constants/purchase_receive_const');
		$this->load->library('sql');
		$this->load->helper('cookie');
		parent::__construct();
	}

	public function get_purchase_receive_details()
	{
		$response 		= array();
		$receive_head_id = $this->encrypt->decode($this->uri->segment(3));
		$branch_id 		= $this->encrypt->decode(get_cookie('branch'));

		$response['head_error'] 	= '';
		$response['detail_error'] 	= ''; 
		$response['po_list_error'] 	= ''; 

		$query_head = "SELECT CONCAT('PR',`reference_number`) AS 'reference_number', COALESCE(DATE(`entry_date`),'') AS 'entry_date', `memo`, `branch_id`
					FROM `purchase_receive_head`
					WHERE `is_show` = ".PURCHASE_RECEIVE_CONST::ACTIVE." AND `id` = ?";

		$result_head = $this->db->query($query_head,$receive_head_id);

		if ($result_head->num_rows() != 1) 
		{
			$response['head_error'] = 'Unable to get damage head details!';
		}
		else
		{
			$row = $result_head->row();

			$response['reference_number'] 	= $row->reference_number;
			$response['entry_date'] 		= $row->entry_date;
			$response['memo'] 				= $row->memo;
			$response['branch_id'] 			= $row->branch_id;
		}

		$result_head->free_result();

		$query_detail_data = array($receive_head_id);

		$query_detail = "SELECT PRD.`id`, PRD.`purchase_detail_id` AS 'po_detail_id', COALESCE(CONCAT('PO',PH.`reference_number`),0) AS 'purchase_reference', 
						PRD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', PD.`quantity` AS 'quantity', PD.`memo`, 
						COALESCE(PBI.`inventory`,0) AS 'inventory', COALESCE(PRD.`quantity`,0) AS 'qty_receive'
					FROM `purchase_receive_detail` AS PRD
					LEFT JOIN `purchase_receive_head` AS PRH ON PRD.`headid` = PRH.`id` AND PRH.`is_show` = ".PURCHASE_RECEIVE_CONST::ACTIVE."
					LEFT JOIN `purchase_detail` AS PD ON PD.`id` = PRD.`purchase_detail_id`
					LEFT JOIN `purchase_head` AS PH ON PH.`id` = PD.`headid` 
					LEFT JOIN `product` AS P ON P.`id` = PRD.`product_id` AND P.`is_show` = ".PURCHASE_RECEIVE_CONST::ACTIVE."
					LEFT JOIN `product_branch_inventory` AS PBI ON PBI.`product_id` = P.`id` AND PBI.`branch_id` = PH.`branch_id`
					WHERE PRD.`headid` = ?";

		$result_detail = $this->db->query($query_detail,$query_detail_data);

		if ($result_detail->num_rows() == 0) 
		{
			$response['detail_error'] = 'No purchase receive details found!';
		}
		else
		{
			$i = 0;
			foreach ($result_detail->result() as $row) 
			{
				$response['detail'][$i][] = array($this->encrypt->encode($row->id));
				$response['detail'][$i][] = array($this->encrypt->encode($row->po_detail_id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->purchase_reference);
				$response['detail'][$i][] = array($row->product, $row->product_id);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->inventory);
				$response['detail'][$i][] = array($row->memo);
				$response['detail'][$i][] = array($row->qty_receive);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$result_detail->free_result();

		$query_po_list = "SELECT PH.`id`,  CONCAT('PO',PH.`reference_number`) AS 'po_number', DATE(PH.`entry_date`) AS 'po_date', 
							SUM(PD.`quantity` - PD.`recv_quantity`) AS 'total_qty'
							FROM purchase_head AS PH
							LEFT JOIN purchase_detail AS PD ON PD.`headid` = PH.`id`
							WHERE PH.`is_show` = 1 AND PH.`is_used` = 1 AND PH.`for_branchid` = ? AND (PD.`quantity` - PD.`recv_quantity`) > 0";

		$result_po_list = $this->db->query($query_po_list,$branch_id);

		if ($result_po_list->num_rows() == 0) 
		{
			$response['po_list_error'] = 'No purchase found!';
		}
		else
		{
			$i = 0;
			foreach ($result_po_list->result() as $row) 
			{
				$response['po_lists'][$i][] = array($this->encrypt->encode($row->id));
				$response['po_lists'][$i][] = array(0);
				$response['po_lists'][$i][] = array($row->po_number);
				$response['po_lists'][$i][] = array($row->po_date);
				$response['po_lists'][$i][] = array($row->total_qty);
				$i++;
			}
		}

		$result_po_list->free_result();

		return $response;
	}

	public function get_po_details($param)
	{
		extract($param);

		$response 	= array();
		$po_head_id = $this->encrypt->decode($po_head_id);

		$response['error'] = '';

		$query = "SELECT PD.`id` AS 'po_detail_id', PD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', PD.`quantity`, PD.`memo`, 
						COALESCE(PBI.`inventory`,0) AS 'inventory', CONCAT('PO',PH.`reference_number`) AS 'po_number'
					FROM `purchase_detail` AS PD
					LEFT JOIN `purchase_head` AS PH ON PD.`headid` = PH.`id` AND PH.`is_show` = ".PURCHASE_RECEIVE_CONST::ACTIVE."
					LEFT JOIN `purchase_receive_detail` AS D ON D.`purchase_detail_id` = D.`id`
					LEFT JOIN `purchase_head` AS H ON H.`id` = PD.`headid`
					LEFT JOIN `product` AS P ON P.`id` = PD.`product_id` AND P.`is_show` = ".PURCHASE_RECEIVE_CONST::ACTIVE."
					LEFT JOIN `product_branch_inventory` AS PBI ON PBI.`product_id` = P.`id` AND PBI.`branch_id` = PH.`for_branchid`
					WHERE PD.`headid` = ?";

		$result = $this->db->query($query,$po_head_id);

		if ($result->num_rows() == 0) 
		{
			$response['error'] = 'No purchase detail found!';
		}
		else
		{
			$i = 0;
			foreach ($result->result() as $row) 
			{
				$response['detail'][$i][] = array(0);
				$response['detail'][$i][] = array($this->encrypt->encode($row->po_detail_id));
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->po_number);
				$response['detail'][$i][] = array($row->product,$row->product_id);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->inventory);
				$response['detail'][$i][] = array($row->memo);
				$response['detail'][$i][] = array($row->recv_quantity);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$result->free_result();

		return $response;
	}

	public function insert_receive_detail($param)
	{
		extract($param);

		$response = array();

		$response['error'] = '';

		$receive_head_id = $this->encrypt->decode($this->uri->segment(3));
		$purchase_detail_id = $this->encrypt->decode($purchase_detail_id);

		$query_data = array($receive_head_id,$quantity,$product_id,$purchase_detail_id);

		$query = "INSERT INTO `purchase_receive_detail`
					(`headid`,
					`quantity`,
					`product_id`,
					`purchase_detail_id`)
					VALUES
					(?,?,?,?);";

		$result = $this->sql->execute_query($query,$query_data);

		if ($result['error'] != '') 
		{
			$response['error'] = 'Unable to save purchase receive detail!';
		}
		else
		{
			$response['id'] = $result['id'];
		}

		return $response;
	}
}
