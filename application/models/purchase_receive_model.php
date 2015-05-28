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
		$branch_id 		= 0;

		$response['head_error'] 	= '';
		$response['detail_error'] 	= ''; 

		$query_head = "SELECT `reference_number`, COALESCE(DATE(`entry_date`),'') AS 'entry_date', `memo`, `branch_id`
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

		$query_detail = "SELECT PRD.`id`, COALESCE(CONCAT('PO',PH.`reference_number`),0) AS 'purchase_reference', 
						PRD.`product_id`, COALESCE(P.`material_code`,'') AS 'material_code', 
						COALESCE(P.`description`,'') AS 'product', PRD.`quantity`, PRD.`memo`, 
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
				$response['detail'][$i][] = array($i+1);
				$response['detail'][$i][] = array($row->purchase_reference);
				$response['detail'][$i][] = array($row->product, $row->product_id);
				$response['detail'][$i][] = array($row->material_code);
				$response['detail'][$i][] = array($row->quantity);
				$response['detail'][$i][] = array($row->inventory);
				$response['detail'][$i][] = array($row->qty_receive);
				$response['detail'][$i][] = array('');
				$response['detail'][$i][] = array('');
				$i++;
			}
		}

		$result_detail->free_result();

		return $response;
	}

}
