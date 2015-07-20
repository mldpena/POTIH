<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('get_name_list_from_table')) 
{
	function get_name_list_from_table($is_option = false, $table = '', $include_all = false, $default_value = 0)
	{
		$CI =& get_instance();

		$data_list = (!$is_option) ? array() : '';

		$query = "SELECT CONCAT(`name`) AS 'name', `id`
					FROM $table WHERE `is_show` = 1"; 

		$result = $CI->db->query($query);

		if ($include_all) {
			if (!$is_option) {
				$data_list[0] = 'ALL';
			}else{
				$data_list .= "<option value='0'>ALL</option>";
			}
		}
		
		if ($result->num_rows() > 0) {
			foreach ($result->result() as $row) {
				if (!$is_option) {
					$data_list[$row->id] = $row->name;
				}else{
					$selected = ($default_value != 0 && $default_value == $row->id) ? 'selected' : '';
					$data_list .= "<option value='".$row->id."' $selected>".$row->name."</option>";
				}
			}
		}

		return $data_list;
	}
}

/* End of file query_helper.php */
/* Location: ./system/helpers/query_helper.php */