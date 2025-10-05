<?php
namespace App\Models;
use CodeIgniter\Model;
class Module extends Model
{
	protected $table            = 'epos_modules';    

    function __construct()
    {
        parent::__construct();
    }

	function get_module_name($module_id)
	{
		$query = $this->db->get_where('modules', array('module_id' => $module_id), 1);

		if ($query->num_rows() ==1)
		{
			$row = $query->row();
			return $this->lang->line($row->name_lang_key);
		}

		return $this->lang->line('error_unknown');
	}

	function get_module_desc($module_id)
	{
		$query = $this->db->get_where('modules', array('module_id' => $module_id), 1);
		if ($query->num_rows() ==1)
		{
			$row = $query->row();
			return $this->lang->line($row->desc_lang_key);
		}

		return $this->lang->line('error_unknown');
	}

	function get_all_modules()
	{
		$this->db->from('modules');
		$this->db->order_by("sort", "asc");
		return $this->db->get();
	}

	function get_allowed_modules($person_id)
	{
		$Employee = new Employee();
		$user_info = $Employee->get_logged_in_employee_info();

		$modules = $this->join('epos_permissions','epos_permissions.module_id = epos_modules.module_id')
			->where("epos_permissions.person_id", $person_id)
			->orderBy("sort", "asc")
			->findAll();

		if( !empty($user_info->price_list010) ) array_splice($modules, 2, 0, array([
			'module_id' => 'seasonal_presell',
		]));

		return $modules;
	}
}
?>
