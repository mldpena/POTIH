<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Purchaseinventory_Model extends CI_Model {

	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		$this->load->library('encrypt');
		$this->load->library('constants/product_const');
		$this->load->library('sql');
		$this->load->helper('cookie');
		parent::__construct();
	}

	
	
}
