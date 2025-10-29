<?php
namespace App\Models;
use CodeIgniter\Model;

class Employee extends Model
{
	protected $table = 'epos_employees';
	protected $primaryKey = 'person_id';
	protected $guarded = ['id'];
	protected $allowedFields = [
		'username',
		'password',
		'email',
		'presell_band',
		'deleted',
		'price_list001',
		'price_list005',
		'price_list007',
		'price_list008',
		'price_list009',
		'price_list010',
		'price_list011',
		'price_list012',
		'price_list999',
		'api_key',
		'last_login',
		'token',
		'expiry',
		'branches',
		'delivery',
		'collect',
		'pay',
	];

	/*
	Determines if a given person_id is an employee
	*/
	function exists($person_id)
	{
		$this->db->from('employees');
		$this->db->where('employees.person_id', $person_id);
		$query = $this->db->get();

		return ($query->num_rows() == 1);
	}

	/*	/*
	Returns all the customers
	*/
	function get_all($limit = 30, $offset = 0, $sort_key = 1)
	{
		$db = \Config\Database::connect();

		$query = $db->table('epos_employees')->where('deleted', 0);
		//$this->db->where_not_in('username' , 'admin');

		switch ($sort_key) {
			case 1:
				$query->orderBy("username", "asc");
				break;
			case 2:
				$query->orderBy("username", "desc");
				break;
			case 3:
				$query->orderBy("email", "asc");
				break;
			case 4:
				$query->orderBy("email", "desc");
				break;
			default:
				break;
		}

		//		$this->db->order_by("first_name", "asc");
		$query->limit($limit);
		$query->offset($offset);
		return $query->get();
	}

	function count_all()
	{
		$db = \Config\Database::connect();

		$builder = $db->table('epos_employees')->where('deleted', 0);
		return $builder->countAllResults();
	}

	function total_search_num_rows($search)
	{
		$db = \Config\Database::connect();

		$builder = $db->table('epos_employees')->where("(email LIKE '%" . $db->escapeLikeString($search) . "%' or
        username LIKE '%" . $db->escapeLikeString($search) . "%') and deleted=0");
		return $builder->countAllResults();
	}

	public static function checkUsernameAvailable($username, $exclude_pid=null)
	{
		$model = new Employee();
		$model->where('username', $username);
		if(!empty($exclude_pid)) {
			$model->where('person_id', '<>', $exclude_pid);
		}
		$count = $model->countAllResults();

		return $count > 0 ? false : true;
	}

	public static function checkEmailAvailable($email, $exclude_pid=null)
	{
		$model = new Employee();
		$model->where('email', $email);
		if(!empty($exclude_pid)) {
			$model->where('person_id', '<>', $exclude_pid);
		}
		$count = $model->countAllResults();

		return $count > 0 ? false : true;
	}

	/*
   Get search suggestions to find customers
   */

	// function get_search_suggestions($search , $limit=25 , $user_info)
	// {
	// 	$suggestions = array();

	// 	$this->db->from('employees');
	// 	$this->db->where("(username LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0");
	// 	$this->db->order_by("username", "asc");
	// 	$by_name = $this->db->get();
	// 	foreach($by_name->result() as $row)
	// 	{
	// 		$suggestions[]=$row->username;
	// 	}

	// 	//only return $limit suggestions
	// 	if(count($suggestions > $limit))
	// 	{
	// 		$suggestions = array_slice($suggestions, 0,$limit);
	// 	}
	// 	return $suggestions;

	// }

	// protected $table = 'employees'; // Assuming your table name is 'employees'

	public function get_search_suggestions($search, $user_info, $limit = 25)
	{
		$suggestions = [];

		$db = \Config\Database::connect();

		$builder = $db->table('epos_employees');
		// $builder = $this->db->table($this->table);
		$builder->select('username');
		$builder->where("username LIKE '%" . $db->escapeLikeString($search) . "%'");
		$builder->where('deleted', 0);
		$builder->orderBy('username', 'asc');
		$builder->limit($limit);

		$results = $builder->get()->getResult();

		foreach ($results as $row) {
			$suggestions[] = $row->username;
		}

		return $suggestions;
	}

	/*
	Get search suggestions to find customers
	*/
	function get_customer_search_suggestions($search, $limit = 25)
	{
		$suggestions = array();
		return $suggestions;

	}

	/*
	Preform a search on customers
	*/
	function search($search, $limit = 20, $offset = 0, $sort_key = 1)
	{
		$db = \Config\Database::connect();

		$q = $db->table('epos_employees');
		$q->where("(username LIKE '%" . $db->escapeLikeString($search) . "%') and deleted=0");

		switch ($sort_key) {
			case 1:
				$q->orderBy("username", "asc");
				break;
			case 2:
				$q->orderBy("username", "desc");
				break;
			case 3:
				$q->orderBy("email", "asc");
				break;
			case 4:
				$q->orderBy("email", "desc");
				break;
			default:
				break;
		}
		//$this->db->order_by("first_name", "asc");
		$q->limit($limit);
		$q->offset($offset);
		return $q->get();
	}

	function get_payment_methods($employee_id)
	{
		$db = \Config\Database::connect();

		$query = $db->table('epos_emp_payment_methods')
			->select('*')
			->where('emp_id', $employee_id)
			->get();

		if ($query->getNumRows() == 0) {
			return [];
		}
		return $query->getResult()[0];
	}
	function get_payment_charges($employee_id)
	{
		$db = \Config\Database::connect();

		$query = $db->table('epos_emp_payment_charges')
			->select('*')
			->where('emp_id', $employee_id)
			->get();

		if ($query->getNumRows() == 0) {
			return [];
		}
		return $query->getResult()[0];
	}
	/*
	Gets information about a particular employee
	*/
	function get_info($employee_id)
	{
		$query = $this->select('*')
			->where('person_id', $employee_id)
			->get();

		if ($query->getNumRows() == 1) {
			$userArr = json_decode(json_encode($query->getRow()), true);
			$userArr = [
				...$userArr,
				'branches' => explode(',', $userArr['branches'] ?? '')
			];
			$userObj = json_decode(json_encode($userArr));
			return $userObj;
		} else {
			//Get empty base parent object, as $employee_id is NOT an employee
			$person_obj = parent::get_info(-1);

			//Get all the fields from employee table
			$fields = $this->db->list_fields('employees');

			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field) {
				$person_obj->$field = '';
			}

			return $person_obj;
		}
	}

	/*
	Updates api key for an employee
	*/
	function update_key($pid, $key)
	{
		$db = \Config\Database::connect();
		$data = array('api_key' => $key);
		$success = $db->table('epos_employees')
			->where('person_id', $pid)
			->update($data);
		return true;
	}

	function save_payment_methods($payment_methods, $employee_id, $new_employee_id) 
	{
		$db = \Config\Database::connect();
		$arr = explode(",", $payment_methods);

		$payment_methods_to_save = array();
		if($payment_methods) {
			foreach ($arr as $value) {
				$payment_methods_to_save[$value] = 1;
			}
		}
		
		foreach (["e_order", "depot", "echo_pay", "bank_transfer", "credit_account", "debit_credit_card"] as $value) {
			if (!in_array($value, $arr)) {
				$payment_methods_to_save[$value] = 0;
			}	
		}
		$payment_methods_to_save['emp_id'] = $new_employee_id;

		$success = false;
		if (intval($employee_id) < 1) {
			$success = $db->table('epos_emp_payment_methods')->insert($payment_methods_to_save);
		} else {
			$db = \Config\Database::connect();

			$query = $db->table('epos_emp_payment_methods')
			->select('emp_id')
			->where('emp_id', $employee_id)
			->get();

			if ($query->getNumRows() == 0) {
				$success = $db->table('epos_emp_payment_methods')->insert($payment_methods_to_save);
			} else {
				$success = $db->table('epos_emp_payment_methods')
							->where('emp_id', $employee_id)
							->update($payment_methods_to_save);
			}
		}
		return $success;
	}

	function save_payment_charges($payment_charges, $employee_id, $new_employee_id) 
	{
		$db = \Config\Database::connect();
		$arr = explode(",", $payment_charges);

		$payment_charges_to_save = json_decode($payment_charges, true);
		$payment_charges_to_save['emp_id'] = $new_employee_id;

		$success = false;
		if (intval($employee_id) < 1) {
			$success = $db->table('epos_emp_payment_charges')->insert($payment_charges_to_save);
		} else {
			$db = \Config\Database::connect();

			$query = $db->table('epos_emp_payment_charges')
			->select('emp_id')
			->where('emp_id', $employee_id)
			->get();

			if ($query->getNumRows() == 0) {
				$success = $db->table('epos_emp_payment_charges')->insert($payment_charges_to_save);
			} else {
				$success = $db->table('epos_emp_payment_charges')
							->where('emp_id', $employee_id)
							->update($payment_charges_to_save);
			}
		}
		return $success;
	}
	/*
	Inserts or updates an employee
	*/
	// function save($employee_data,$employee_id)
	function save_employees($employee_data, $employee_id)
	{
		$db = \Config\Database::connect();
		// Get User Band
		$band = substr($employee_data['presell_band'], 0, 1);
		if ($band == "") {
			$band = "g";
		}

		//Run these queries as a transaction, we want to make sure we do all or nothing
		if (intval($employee_id) < 1) {
			$success = $db->table('epos_employees')->insert($employee_data);

			$builder = $db->table('epos_employees')
				->where('username', $employee_data['username'])
				->get();
			$employee_id = $builder->getRow()->person_id;
		} else {
			$success = $db->table('epos_employees')
				->where('person_id', $employee_id)
				->update($employee_data);
		}

		//We have either inserted or updated a new employee, now lets set permissions.
		if ($success) {
			//First lets clear out any permissions the employee currently has.
			$success = $this->db->table('epos_permissions')->delete(array('person_id' => $employee_id));

			$builder = $db->table('epos_permissions')
				->where('person_id', $employee_id);

			$success = $builder->delete();

			//Now insert the new permissions, update cart quantity based on user band
			if ($success) {
				// To load employee info.
				$employee_info = $this->get_info($employee_id);

				// load cart entries
				$array = array('person_id' => $employee_id, 'presell' => '1');
				$p = $db->table('epos_cart')->where($array)->get();

				if ($p->getNumRows() > 0) {
					foreach ($p->getResult() as $row) {
						$b = Presell_Import::getLowestPricePresellByCode($employee_info, $row->prod_code);
						$quantity = $b->getRow()->{$band . "_qty"};

						// update quantity based on user band
						$data = array('quantity' => $quantity);
						$db->table('epos_cart')->where('id', $row->id)->update($data);
					}
				}

				$data = [
					['module_id' => 'contactus', 'person_id' => $employee_id],
					['module_id' => 'orders', 'person_id' => $employee_id],
					['module_id' => 'pastorders', 'person_id' => $employee_id],
					['module_id' => 'products', 'person_id' => $employee_id],
					['module_id' => 'home', 'person_id' => $employee_id],
					['module_id' => 'promos', 'person_id' => $employee_id],
					['module_id' => 'presells', 'person_id' => $employee_id]
				];

				$db->table('epos_permissions')->insertBatch($data);


				if ($employee_id == 1)
					$success = $db->table('epos_permissions')->insert(array('module_id' => 'employees', 'person_id' => 1));

				return $employee_id;
			}
		}

		return -1;
	}

	/*
	Attempts to login employee and set session. Returns boolean based on outcome.
	*/
	function login($username, $password)
	{

		$result = $this->where([
			'username' => $username,
			'password' => md5($password),
			'deleted' => 0,
		])->first();

		if ($result) {
			session()->set('person_id', $result['person_id']);
			session()->set('organization_id', $result['organization_id']);

			return true;
		}

		return false;
	}

	/*
	Logs out a user by destorying all session data and redirect to login
	*/
	public function logout()
	{
		session()->destroy();
		return redirect()->to(base_url('login'));
	}

	/*
	Determins if a employee is logged in
	*/
	function is_logged_in()
	{
		// return $this->session->userdata('person_id')!=false;
		return session()->has('person_id');
	}

	/*
	Gets information about the currently logged in employee.
	*/
	function get_logged_in_employee_info()
	{
		if ($this->is_logged_in()) {
			return $this->get_info(session()->get('person_id'));
		}

		return false;
	}

	/*
	Determins whether the employee specified employee has access the specific module.
	*/
	function has_permission($module_id, $person_id)
	{
		$db = \Config\Database::connect();

		if ($module_id == null) {
			return true;
		}

		$query = $db->table('epos_permissions')
			->where(['person_id' => $person_id, 'module_id' => $module_id])
			->get();

		return $query->getNumRows() == 1;
	}

  function get_last_kiss_branch_id($person_id)
  {
		$db = \Config\Database::connect();

    if ($person_id == null) {
			return null;
		}

    $query = $db->table('epos_employees')
    ->where(['person_id' => $person_id])
    ->get();

    $row = $query->getRow();

    return $row->last_kiss_branch;
  }

  function save_last_kiss_branch_id($person_id, $last_kiss_branch_id)
  {
		$db = \Config\Database::connect();

    if ($person_id == null || $last_kiss_branch_id == null) {
			return false;
		}

		$success = $db->table('epos_employees')
			->where('person_id', $person_id)
			->update(['last_kiss_branch' => $last_kiss_branch_id]);

		return $success;
  }

	/*
	Determins employee presell band.
	*/
	function presell_band($person_id)
	{
		$db = \Config\Database::connect();

		$query = $db->table('epos_employees')
			->where('epos_employees.person_id', $person_id)
			->get();

		if ($query->getNumRows() == 1) {
			return $query->getRow()->presell_band;
		}
	}

}
?>