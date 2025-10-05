<?php
namespace App\Models;
use CodeIgniter\Model;
class Manager extends Model
{
	protected $table            = 'epos_managers';
    protected $primaryKey       = 'manager_id';

	/*
	Determines if a given manager_id is a manager
	*/
	function exists($manager_id)
	{
		$this->db->from('managers');
		$this->db->where('managers.manager_id',$manager_id);
		$query = $this->db->get();

		return ($query->num_rows()==1);
	}

	/*	/*
	Returns all the managers
	*/
	function get_all($limit = 30 , $offset = 0 , $sort_key = 1)
	{
		$this->db->from('managers');
		$this->db->where('deleted',0);

		switch($sort_key)
		{
			case 1:
				$this->db->order_by("username", "asc");
				break;
			case 2:
				$this->db->order_by("username", "desc");
				break;
			case 3:
				$this->db->order_by("email", "asc");
				break;
			case 4:
				$this->db->order_by("email", "desc");
				break;
			default:
				break;
		}

		$this->db->limit($limit);
		$this->db->offset($offset);
		return $this->db->get();
	}

	function count_all()
	{
		$this->db->from('managers');
		$this->db->where('deleted',0);
		return $this->db->count_all_results();
	}
	
	/*
	Gets information about a particular manager
	*/
	function get_info($manager_id)
	{
		$query = $this->select('*')
            ->where('manager_id', $manager_id)
            ->get();

        if ($query->getNumRows() == 1) {
            return $query->getRow();
        }
		else
		{
			//Get empty base parent object, as $employee_id is NOT an employee
			$person_obj=parent::get_info(-1);

			//Get all the fields from employee table
			$fields = $this->db->list_fields('managers');

			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$person_obj->$field='';
			}

			return $person_obj;
		}
	}

	/*
	Attempts to login managers and set session. Returns boolean based on outcome.
	*/
	function login($username, $password)
	{
		$result = $this->where([
			'username' => $username,
			'password' => md5($password),
			'deleted'  => 0,
		])->first();
		
		// Check if a record is found
		if ($result) {
			// Set session data
			session()->set('manager_id', $result['manager_id']);
			session()->set('manager_username', $result['username']);
			return true;
		}
		
		return false;
	}

	/*
	Logs out a user by destorying all session data and redirect to login
	*/
	function logout()
	{
		session()->destroy();
		redirect('cpanel');
	}

	/*
	Determins if a manager is logged in
	*/
	function is_logged_in()
	{
		// return $this->session->userdata('manager_id')!=false;
		return session()->has('manager_id');
	}

	/*
	Gets information about the currently logged in manager.
	*/
	function get_logged_in_manager_info()
	{
		if($this->is_logged_in())
		{
			// return $this->get_info($this->session->userdata('manager_id'));
			return $this->get_info(session()->get('manager_id'));
		}

		return false;
	}

	/*
	Determins whether the manager has access the specific module.
	*/
	function has_permission($module_id,$manager_id)
	{
		$db = \Config\Database::connect();

        if ($module_id == null) {
            return true;
        }

        $query = $db->table('admin_permissions')
            ->where(['manager_id' => $manager_id, 'module_id' => $module_id])
            ->get();

        return $query->getNumRows() == 1;

	
	}

}

