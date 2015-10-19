<?php

namespace Services;

class Transaction_Manager
{
	private $_CI;

	public function __construct()
	{
		$this->_CI = $CI =& get_instance();
		$this->_CI->load->library('sql');
	}

	/**
	 * Get the next max invoice number per table type
	 * @param  string  $table_name
	 * @param  string  $field            [field name for getting next max number]
	 * @param  array   $additional_field [additional info to be included upon inserting]
	 * @param  integer $default_value
	 * @return array   $response
	 */
	public function get_next_max_number($table_name = '', $field = '', $additional_field = array(), $default_value = 100000)
	{
		$user_id 	= $CI->encrypt->decode(get_cookie('temp'));
		$branch_id 	= $CI->encrypt->decode(get_cookie('branch'));
		$next_value = $default_value + 1;
		$query 		= array();
		$query_data = array();

		array_push($query,"SET @invoiceno_d = 0;");
		array_push($query_data,array());

		array_push($query,"SELECT COAlESCE(MAX(`$field` + 0),$default_value) INTO @invoiceno_d FROM `$table_name` WHERE `is_show` = 1 FOR UPDATE;");
		array_push($query_data,array());

		$query_temp 		= "INSERT INTO `$table_name`(`$field`,`date_created`,`created_by`,`last_modified_by`,`branch_id`";
		$query_temp_values 	= "VALUES(IF(@invoiceno_d = 0,'$next_value',@invoiceno_d+1),NOW(),?,?,?";
		$query_data_temp 	= array($user_id,$user_id,$branch_id);

		foreach ($additional_field as $key => $value) {
			$query_temp .= ",`".$key."`";
			$query_temp_values .= ",?";
			array_push($query_data_temp,$value);
		}

		$query_temp = $query_temp.") ".$query_temp_values.")";

		array_push($query,$query_temp,"SET @insert_id = LAST_INSERT_ID();","SELECT @insert_id AS 'id';");
		array_push($query_data,$query_data_temp,array(),array());

		$response = $this->_CI->sql->execute_transaction($query,$query_data);

		return $response;
	}
}

?>