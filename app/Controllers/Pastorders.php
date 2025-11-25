<?php
namespace App\Controllers;
use App\Controllers\interfaces\iData_controller;
use App\Models\Employee;
use App\Models\Pastorder;
use App\Models\Admin;
use App\Models\Product;


class Pastorders extends Secure_area implements iData_controller
{
	function __construct()
	{
		parent::__construct('pastorders');
	}

	function index()
	{
		$Employee = new Employee();
		$Pastorder = new Pastorder();
		$admin = new Admin();

		if(!$Employee->is_logged_in()) {			
			return redirect()->to('/');			
		}

		$user_info = $Employee->get_logged_in_employee_info();

		$sort_key = request()->getGet('sort_key') ?? 4;
		$per_page = request()->getGet('per_page') ?? 30;
		$total_rows = $Pastorder->count_all($user_info);
		$offset = request()->getGet('offset') ?? 0;
		$orders = $Pastorder->get_all($user_info, $per_page, $offset, $sort_key)->getResult();

		$this->data['sort_key'] = $sort_key;
		$this->data['total_rows'] = $total_rows;
		$this->data['total_page'] = ($per_page !== 0) ? floor($total_rows / $per_page) + 1 : 0;
		$this->data['per_page'] = $per_page;
		$this->data['curd_page'] = intval($offset / $per_page) + 1;
		$this->data['img_host'] = $admin->get_plink('img_host');
		$this->data['user_info'] = $user_info;
	    $this->data["slides"] = $admin->get_scount('slides');
		$this->data['orders'] = $orders;
		$this->data['from'] = $offset + 1;
		$this->data['to'] = $offset + count($orders);
		$this->data['offset'] = $offset;
		$this->data['base_url'] = "/pastorders?";

		// echo view('pastorders/manage',$this->data);
		echo view('v2/pages/pastorders.php', $this->data);
	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
	}

	function get_row()
	{
	}

	function view($order_id=-1)
	{

        $cust_info = $this->Employee->get_info($this->db->query("SELECT person_id FROM epos_orders WHERE order_id=".$order_id)->row()->person_id);
        $completed = $this->Pastorder->get_order_completed($order_id);
		$opened = $this->Pastorder->get_order_opened($order_id);
		if($completed == 0)
			$data1 = "<table cellspacing='1px' style='width:98%; margin: 5px 5px 5px 5px; border-left: 1px solid gray; border-right:1px solid gray; border-bottom:2px solid gray;'><thead><tr style='background-color:#11ccdd;'><th>Product</th><th>Description</th><th>Size</th><th>UOS</th><th>Price</th><th>Qty</th></tr></thead>";
		else if($completed == 1)
			$data1 = "<table cellspacing='1px' style='width:98%; margin: 5px 5px 5px 5px; border-left: 1px solid gray; border-right:1px solid gray; border-bottom:2px solid gray;'><thead><tr style='background-color:#11ccdd;'><th>Product</th><th>Description</th><th>Size</th><th>UOS</th><th>Price</th><th>Qty</th><th>&nbsp;</th></tr></thead>";

		$res_order = $this->Pastorder->get_order_product($order_id , $completed, $cust_info);

		if($opened == 1)
			$data1 .= "<tbody><tr><td colspan='6' style='text-align:center;'><span style='color:#FF0000;'>You have been editing this order.</span></td></tr></tbody></table>";
		else
			$data1 .= $res_order;

		$data['manage_table'] = $data1;
		$data['completed'] = $completed;
		$data['order_id'] = $order_id;
		$data['user_info'] = $cust_info;
		$data['controller_name'] = strtolower(get_class());
		return view("pastorders/form",$data);

	}

	function get_order()
	{
		$Employee = new Employee();
		$Pastorder = new Pastorder();
		$db = \Config\Database::connect();
		$order_id  = request()->getPost('order_id');
		
        $cust_info = $Employee->get_info($db->query("SELECT person_id FROM epos_orders WHERE order_id=".$order_id)->getRow()->person_id);
		$completed = $Pastorder->get_order_completed($order_id);
		$opened    = $Pastorder->get_order_opened($order_id);
		if($completed == 0) {
			$data1 = "<div id='table_wrapper'>
			          <table class='tablesorter' cellspacing='1px' style='width:98%;'>
					  <thead><tr><th>Product</th><th>Description</th><th>Size</th><th>UOS</th><th>Price</th><th>Qty</th></tr></thead>";
					}
		else if($completed == 1) {
			$data1 = "<div id='table_wrapper'>
			          <table class='tablesorter' cellspacing='1px' style='width:98%;'>
					  <thead><tr><th>Product</th><th>Description</th><th>Size</th><th>UOS</th><th>Price</th><th>Qty</th><th>&nbsp;</th></tr></thead>";
					}
		$res_order = $Pastorder->get_order_product($order_id , $completed, $cust_info);

		if($opened == 1)
			$data1 .= "<tbody><tr><td colspan='6' style='text-align:center;'><span style='color:#FF0000;'>You have been editing this order.</span></td></tr></tbody></table></div>";
		else
			$data1 .= $res_order;

		echo json_encode(array('manage_table'=>$data1 , 'completed'=>$completed));
	}

	function save($item_id=-1)
	{
	}


	function delete()
	{
	}


	/*
	get the width for the add/edit form
	*/
	function get_form_width()
	{
		return 640;
	}

	function get_person($person_id)
	{
		return $this->Employee->get_info($person_id);
	}

	function get_total_amount($order_id)
	{
		$db = \Config\Database::connect();
		return $db->query("SELECT sum( price * quantity ) as total_value FROM epos_orders_products WHERE order_id=".$order_id)->getRow()->total_value;
	}

	function sort_order()
	{
		$sort_key = $this->input->post('sort_key');
		$search = $this->input->post('search');
		$search_mode = $this->input->post('search_mode');
		$per_page = $this->input->post('per_page');
		// Fetch Image Host
		$img_host = $this->admin->get_plink('img_host');

		if($per_page == 0) $per_page = 30;

		$user_info = $this->Employee->get_logged_in_employee_info();

   		$total_rows = $this->Pastorder->count_all($user_info);

		$total_page = floor($total_rows / $per_page) + 1;
		$uri_segment = 6;
		$total_page = floor($total_rows / $per_page) + 1;
        $data_rows='';
		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><span style='font-family:Arial;'>Show&nbsp;</span><select name='per_page' id='per_page' onchange=\"select_per_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\">";
		$data_rows .= "<option value='10' ";
		if($per_page == 10) $data_rows .= "selected='true'";
		$data_rows .= ">10</option><option value='25'";
		if($per_page == 25) $data_rows .= "selected='true'";
		$data_rows .= ">25</option><option value='30' ";
		if($per_page == 30) $data_rows .= "selected='true'";
		$data_rows .= ">30</option><option value='40' ";
		if($per_page == 40) $data_rows .= "selected='true'";
		$data_rows .= ">40</option><option value='50' ";
		if($per_page == 50) $data_rows .= "selected='true'";
		$data_rows .= ">50</option><option value='75' ";
		if($per_page == 75) $data_rows .= "selected='true'";
		$data_rows .= ">75</option><option value='100' ";
		if($per_page == 100) $data_rows .= "selected='true'";
		$data_rows .= ">100</option><option value='150' ";
		if($per_page == 150) $data_rows .= "selected='true'";
		$data_rows .= ">150</option><option value='200' ";
		if($per_page == 200) $data_rows .= "selected='true'";
		$data_rows .= ">200</option></select><span style='font-family:Arial;'>&nbsp;Rows&nbsp;Per&nbsp;Page</span></div>";
		$data_rows .= "<div class='btnseparator' style='fload:right;'></div><div class='pGroup' style='float:right;'>";
		$data_rows .= "<div class='pNext pButton' onclick=\"next_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div><div class='pLast pButton' onclick=\"last_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator' style='float:right;'></div><div class='pGroup' style='float:right;'>";
		$data_rows .= "<span class='pcontrol'>Page&nbsp;<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page'";
		$data_rows .= "onkeyup=\"set_direct_page(event , '";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\">&nbsp;of&nbsp;<span id='last_page_number'>".$total_page."</span></span></div><div class='btnseparator' style='float:right;'></div>";
		$data_rows .= "<div class='pGroup' style='float:right;'><div class='pFirst pButton' onclick=\"first_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div><div class='pPrev pButton' onclick=\"prev_page('";
		$data_rows .= site_url("pastorders/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator' style='float:right;'></div>";
		$data_rows .= "********************";
        $data_rows .= get_orders_manage_table_data_rows(
					$user_info ,
					$this->Pastorder->get_all(
					$per_page ,
					0 ,
					$sort_key) ,
					$user_info ,
					$this ,
					$this->request->getUri()->getSegment($uri_segment),
					$img_host);
		echo $data_rows;
	}
	function search()
	{
	}
	
	function continue_order($mode , $order_id)
	{
		$Pastorder = new Pastorder();
		if($mode == 1) $Pastorder->set_my_trolley($order_id);
		$type = $Pastorder->trolley_type($order_id);
		$redirect = "orders/".$type;
		return redirect()->to(base_url($redirect));
	}
	
	function reuse_order($order_id)
	{
		$Pastorder = new Pastorder();
		$Pastorder->reuse_trolley($order_id);
		
		$redirect = "orders/general";
		return redirect()->to(base_url($redirect));
	}
	
	function to_cart()
	{
		$Employee = new Employee();
		$Product = new Product();
		$prod_code = request()->getPost('prod_code');
		$mode = request()->getPost('mode');
		$quantity = request()->getPost('quantity');
		$user_info = $Employee->get_logged_in_employee_info();
		$quantity = $Product->to_cart($prod_code , $mode , $user_info->person_id , $quantity);
		echo $quantity;
	}

	function add_to_cart()
	{
		$prod_code = $this->input->post('prod_code');
		$quantity = $this->input->post('quantity');
		$order_id = $this->input->post('order_id');
		$user_info = $this->Employee->get_logged_in_employee_info();
		echo json_encode($this->Pastorder->add_to_cart1($prod_code , $quantity , $user_info->person_id , $order_id));
	}
}
?>
