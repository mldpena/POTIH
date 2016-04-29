<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recent_Name_Model extends CI_Model {
	/**
	 * Load Encrypt Class for encryption, cookie and constants
	 */
	public function __construct() {
		parent::__construct();
	}

	public function get_recent_names_by_term($term, $type)
	{
		$this->db->select("`name`")
				->from("`recent_name`")
				->where("`type`", $type)
				->like("`name`", $term, "both")
				->limit(10);

		$result = $this->db->get();

		return $result;
	}
}