<?php
namespace App\Controllers;

use App\Models\Branch;
use App\Models\Manager;
use App\Models\Employee;
use App\Models\Admin;


class Employees extends Secure_area
{
	function __construct()
	{
		parent::__construct('employees');
	}

	function index()
	{
		$Employee = new Employee();
		$Admin = new Admin();
		$user_info = $Employee->get_logged_in_employee_info();

		$sort_key = $this->request->getGet('sort_key') ?? 1;
		$per_page = intval($this->request->getGet('per_page') ?? 30);
		$search = urldecode($this->request->getGet('search') ?? '');
		$offset = intval($this->request->getGet('offset') ?? 0);

		if (!$search) {
			$this->data['employees'] = $Employee->get_all($per_page, $offset, $sort_key)->getResult();
			$this->data['total_rows'] = $Employee->count_all();
		} else {
			$this->data['employees'] = $Employee->search($search, $per_page, $offset, $sort_key)->getResult();
			$this->data['total_rows'] = $Employee->total_search_num_rows($search);
		}

		$this->data['search'] = $search;
		$this->data['total_page'] = intval($this->data['total_rows'] / $per_page) + 1;
		$this->data['per_page'] = $per_page;
		$this->data['offset'] = $offset;
		$this->data['controller_name'] = $this->request->uri->getSegment(1);
		$this->data['form_width'] = $this->get_form_width();
		$this->data['sort_key'] = $sort_key;


		$this->data['from'] = $offset + 1;
		$this->data['to'] = $offset + count($this->data['employees']);
		$this->data['curd_page'] = intval($offset / $per_page) + 1;
		$this->data['base_url'] = "/employees?search=$search";
		$this->data["slides"] = $Admin->get_scount('slides');

		return view('v2/pages/employee/employees', $this->data);
	}

	function generate_key($pid=null)
	{
		$key = '';
		srand((double) microtime() * 1000000);
		while (1) {
			$l = rand(48, 122);
			if (($l > 57 && $l < 65) || ($l > 90 && $l < 97))
				continue;
			$key .= chr($l);
			if (strlen($key) > 79)
				break;
		}

		if(!empty($pid)) {
			$Employee = new Employee();
			$Employee->update_key($pid, $key);
		}

		echo $key;
	}

	function search()
	{
		$search = $this->input->post('search');
		$sort_key = $this->input->post('sort_key');
		$per_page = $this->input->post('per_page');
		if ($search == "")
			$search_page = "12345678901234567890";
		else
			$search_page = $search;

		$user_info = $this->Employee->get_logged_in_employee_info();
		$total_rows = $this->Employee->total_search_num_rows($search);
		$total_page = floor($total_rows / $per_page) + 1;
		$uri_segment = 7;

		$data_rows = "search";
		$data_rows .= "********************";
		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><span style='font-family:Arial;'>Show&nbsp;</span><select name='per_page' id='per_page' onchange=\"select_per_page('";
		$data_rows .= site_url("employees/index/");
		$data_rows .= "');\">";
		$data_rows .= "<option value='10' ";
		if ($per_page == 10)
			$data_rows .= "selected='true'";
		$data_rows .= ">10</option><option value='25'";
		if ($per_page == 25)
			$data_rows .= "selected='true'";
		$data_rows .= ">25</option><option value='30' ";
		if ($per_page == 30)
			$data_rows .= "selected='true'";
		$data_rows .= ">30</option><option value='40' ";
		if ($per_page == 40)
			$data_rows .= "selected='true'";
		$data_rows .= ">40</option><option value='50' ";
		if ($per_page == 50)
			$data_rows .= "selected='true'";
		$data_rows .= ">50</option><option value='75' ";
		if ($per_page == 75)
			$data_rows .= "selected='true'";
		$data_rows .= ">75</option><option value='100' ";
		if ($per_page == 100)
			$data_rows .= "selected='true'";
		$data_rows .= ">100</option><option value='150' ";
		if ($per_page == 150)
			$data_rows .= "selected='true'";
		$data_rows .= ">150</option><option value='200' ";
		if ($per_page == 200)
			$data_rows .= "selected='true'";
		$data_rows .= ">200</option></select><span style='font-family:Arial;'>&nbsp;Rows&nbsp;Per&nbsp;Page</span></div>";

		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><div class='pFirst pButton' onclick=\"first_page('";
		$data_rows .= site_url("employees/index/");
		$data_rows .= "');\"><span></span></div>";

		$data_rows .= "<div class='pPrev pButton' onclick=\"prev_page('";
		$data_rows .= site_url("employees/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator'></div>";
		$data_rows .= "<div class='pGroup'><span class='pcontrol'>Page&nbsp;<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page' onkeyup=\"set_direct_page(event ,'";
		$data_rows .= site_url("employees/index/");
		$data_rows .= "');\">";
		$data_rows .= "&nbsp;of&nbsp;<span id='last_page_number'>$total_page</span></span></div><div class='btnseparator'></div>";
		$data_rows .= "<div><div class='pNext pButton' onclick=\"next_page('";
		$data_rows .= site_url("employees/index/");
		$data_rows .= "');\"><span></span></div><div class='pLast pButton' onclick=\"last_page('";
		$data_rows .= site_url("cucstomers/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "</div><div class='btnseparator'></div>";
		$data_rows .= "********************";

		$data_rows .= get_customer_manage_table_data_rows(
			$this->Employee->search(
				$search,
				$per_page,
				$this->request->uri->getSegment($uri_segment),
				$sort_key
			),
			$this,
			$user_info
		);
		echo $data_rows;
	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		$Employee = new Employee();

		$q = request()->getPost('term');
		$user_info = $Employee->get_logged_in_employee_info();
		$suggestions = $Employee->get_search_suggestions($q, $user_info, 30);
		echo json_encode($suggestions);
	}

	function sort_user()
	{
		$sort_key = $this->input->post('sort_key');
		$search = $this->input->post('search');
		$search_mode = $this->input->post('search_mode');
		$per_page = $this->input->post('per_page');

		if ($per_page == 0)
			$per_page = 30;

		$user_info = $this->Employee->get_logged_in_employee_info();

		if ($search_mode == "default") {
			$total_rows = $this->Employee->total_search_num_rows($search);
			$total_page = floor($total_rows / $per_page) + 1;
			$uri_segment = 6;
		} else if ($search_mode == "search") {
			if ($search == "")
				$search_page = "12345678901234567890";
			else
				$search_page = $search;

			$total_rows = $this->Employee->total_search_num_rows($search);
			$uri_segment = 7;
			$total_page = floor($total_rows / $per_page) + 1;
		}
		$data_rows = '';
		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><span style='font-family:Arial;'>Show&nbsp;</span><select name='per_page' id='per_page' onchange=\"select_per_page('";
		$data_rows .= site_url("employees/index/");
		$data_rows .= "');\">";
		$data_rows .= "<option value='10' ";
		if ($per_page == 10)
			$data_rows .= "selected='true'";
		$data_rows .= ">10</option><option value='25'";
		if ($per_page == 25)
			$data_rows .= "selected='true'";
		$data_rows .= ">25</option><option value='30' ";
		if ($per_page == 30)
			$data_rows .= "selected='true'";
		$data_rows .= ">30</option><option value='40' ";
		if ($per_page == 40)
			$data_rows .= "selected='true'";
		$data_rows .= ">40</option><option value='50' ";
		if ($per_page == 50)
			$data_rows .= "selected='true'";
		$data_rows .= ">50</option><option value='75' ";
		if ($per_page == 75)
			$data_rows .= "selected='true'";
		$data_rows .= ">75</option><option value='100' ";
		if ($per_page == 100)
			$data_rows .= "selected='true'";
		$data_rows .= ">100</option><option value='150' ";
		if ($per_page == 150)
			$data_rows .= "selected='true'";
		$data_rows .= ">150</option><option value='200' ";
		if ($per_page == 200)
			$data_rows .= "selected='true'";
		$data_rows .= ">200</option></select><span style='font-family:Arial;'>&nbsp;Rows&nbsp;Per&nbsp;Page</span></div>";

		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><div class='pFirst pButton' onclick=\"first_page('";
		$data_rows .= site_url("employees/index/");
		$data_rows .= "');\"><span></span></div>";

		$data_rows .= "<div class='pPrev pButton' onclick=\"prev_page('";
		$data_rows .= site_url("employees/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator'></div>";
		$data_rows .= "<div class='pGroup'><span class='pcontrol'>Page&nbsp;<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page' onkeyup=\"set_direct_page(event ,'";
		$data_rows .= site_url("employees/index/");
		$data_rows .= "');\">";
		$data_rows .= "&nbsp;of&nbsp;<span id='last_page_number'>$total_page</span></span></div><div class='btnseparator'></div>";
		$data_rows .= "<div><div class='pNext pButton' onclick=\"next_page('";
		$data_rows .= site_url("employees/index/");
		$data_rows .= "');\"><span></span></div><div class='pLast pButton' onclick=\"last_page('";
		$data_rows .= site_url("employees/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "</div><div class='btnseparator'></div>";
		$data_rows .= "********************";

		$data_rows .= get_customer_manage_table_data_rows(
			$this->Employee->search(
				$search,
				$per_page,
				$this->request->uri->getSegment($uri_segment),
				$sort_key
			),
			$this,
			$user_info
		);
		echo $data_rows;

	}

	function edit($person_id=null)
	{
		$Employee = new Employee();
    $Branch = new Branch();
		
		$this->data['employee'] = null;
		if(!empty($person_id)) $this->data['employee'] = $Employee->get_info($person_id);

		$this->data['band_options'] = BAND_OPTIONS;

		$this->data['price_options'] = PRICE_OPTIONS;
		unset($this->data['price_options']['001']);

    $this->data['all_branches'] = $Branch->get_all_branches();

		return view('v2/pages/employee/employee_edit', $this->data);
	}

	function save($employee_id = -1)
	{
		$Employee = new Employee();

		$employee_id = request()->getPost('person_id');
		if ($employee_id == 0 || $employee_id == '') $employee_id = -1;

    $organization_id = session()->get('organization_id');
    if (empty($organization_id)) {
      $organization_id = -1;
    }

		$employee_data = array(
			'username' => request()->getPost('username'),
			'email' => request()->getPost('email'),
			'branches' => request()->getPost('branches'),
			'presell_band' => request()->getPost('presell_band'),
			'price_list005' => request()->getPost('price_list005') == '' ? 0 : 1,
			'price_list007' => request()->getPost('price_list007') == '' ? 0 : 1,
			'price_list008' => request()->getPost('price_list008') == '' ? 0 : 1,
			'price_list009' => request()->getPost('price_list009') == '' ? 0 : 1,
			'price_list010' => request()->getPost('price_list010') == '' ? 0 : 1,
			'price_list011' => request()->getPost('price_list011') == '' ? 0 : 1,
			'price_list012' => request()->getPost('price_list012') == '' ? 0 : 1,
			'price_list999' => request()->getPost('price_list999') == '' ? 0 : 1,
			'delivery' => request()->getPost('delivery'),
			'delivery_charge' => request()->getPost('delivery_charge') ?? 0,
			'collect' => request()->getPost('collect'),
			'pay' => request()->getPost('pay'),
      'organization_id' => $organization_id,
		);

		if (request()->getPost('password') != '') {
			$employee_data['password'] = md5(request()->getPost('password'));
		}

		if ($Employee->save_employees($employee_data, $employee_id)) {
			return redirect()->to(base_url('employees'));
		} else {

		}
	}
	function get_form_width()
	{
		return 700;
	}

	function checkExist()
	{
		try {
			$username = request()->getGet('username');
			$email = request()->getGet('email');
			$person_id = request()->getGet('person_id');

			if(!Employee::checkUsernameAvailable($username, $person_id)) {
				return response()->setJSON([
					'success' => 0,
					'msg' => 'The same username already exists'
				]);
			}

			if(!Employee::checkEmailAvailable($email, $person_id)) {
				return response()->setJSON([
					'success' => 0,
					'msg' => 'The same email already exists'
				]);
			}

			return response()->setJSON([
				'success' => 1,
			]);
		} catch(\Exception $e) {

		}
	}
}
?>