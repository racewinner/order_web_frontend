<?php
namespace App\Controllers;

use App\Controllers\Secure_area;
use App\Models\Admin;
use App\Models\Employee;
use App\Models\Hom;
use App\Models\Product;
use App\Models\PriceList;
use App\Models\Branch;
use App\Models\Cms;

class Home extends BaseController
{
	private $priceList = [];

	function __construct()
	{
		parent::__construct('home');

		$PriceList = new PriceList();
		$this->priceList = $PriceList->get_all();
	}

	function get_form_width()
	{
		return 360;
	}
	
	function index()
	{
		$d = empty($ss);

    	$Employee = new Employee();
		$Admin = new Admin();
		$Product = new Product();
		$user_info = $Employee->get_logged_in_employee_info();
		$img_host = $Admin->get_plink('img_host');

		$this->data["link1"]    = $Admin->get_plink('link newsletter');	
		$this->data["link2"]    = $Admin->get_plink('link cash & carry');	
		$this->data["link3"]    = $Admin->get_plink('link day-today');	
		$this->data["link3a"]   = $Admin->get_plink('link day-today upcoming');	
		$this->data["link4"]    = $Admin->get_plink('link usave');	
		$this->data["link4a"]   = $Admin->get_plink('link usave upcoming');	
		$this->data["link5"]    = $Admin->get_plink('link special event');		
		$this->data["period1"]  = $Admin->get_plink('link newsletter period');	
		$this->data["period2"]  = $Admin->get_plink('link cash & carry period');	
		$this->data["period3"]  = $Admin->get_plink('link day-today period');	
		$this->data["period3a"] = $Admin->get_plink('link day-today upcoming period');	
		$this->data["period4"]  = $Admin->get_plink('link usave period');	
		$this->data["period4a"] = $Admin->get_plink('link usave upcoming period');	
		$this->data["period5"]  = $Admin->get_plink('link special event period');						
		$this->data["switch"]   = $Admin->get_plink('state special event');			
		$this->data["switch2"]  = $Admin->get_plink('state newsletter');	
		$this->data["slides"]   = $Admin->get_scount('slides');		
		$this->data["sponsors"] = $Admin->get_scount('sponsors');
		$this->data["user_info"] = $user_info;
		$router = service('router');

		$this->data['cms'] = Cms::getHomeCmsByPagePosition($user_info, $this->priceList, $img_host, $this);

		echo view('v2/pages/home', $this->data); // after you stored the query results inside the $data array, send the array to the view 
	}
  
	function preview() {
		$Admin = new Admin();
		$Product = new Product();

		$img_host = $Admin->get_plink('img_host');
		$user_info = false;//new \stdClass();

		// branch
		session()->set('branch', request()->getGet('branch'));

		// organization
		session()->set('organization', request()->getGet('organization'));

		// price_list
		$price_list = explode(',', request()->getGet('price_list'));
		// $user_info->price_list001 = in_array('001', $price_list) ? 1 : 0;
		// $user_info->price_list005 = in_array('005', $price_list) ? 1 : 0;
		// $user_info->price_list007 = in_array('007', $price_list) ? 1 : 0;
		// $user_info->price_list008 = in_array('008', $price_list) ? 1 : 0;
		// $user_info->price_list009 = in_array('009', $price_list) ? 1 : 0;
		// $user_info->price_list010 = in_array('010', $price_list) ? 1 : 0;
		// $user_info->price_list011 = in_array('011', $price_list) ? 1 : 0;
		// $user_info->price_list012 = in_array('012', $price_list) ? 1 : 0;
		// $user_info->price_list999 = in_array('999', $price_list) ? 1 : 0;

		// date
		$date = request()->getGet('date');

		$this->data["link1"]    = $Admin->get_plink('link newsletter');	
		$this->data["link2"]    = $Admin->get_plink('link cash & carry');	
		$this->data["link3"]    = $Admin->get_plink('link day-today');	
		$this->data["link3a"]   = $Admin->get_plink('link day-today upcoming');	
		$this->data["link4"]    = $Admin->get_plink('link usave');	
		$this->data["link4a"]   = $Admin->get_plink('link usave upcoming');	
		$this->data["link5"]    = $Admin->get_plink('link special event');		
		$this->data["period1"]  = $Admin->get_plink('link newsletter period');	
		$this->data["period2"]  = $Admin->get_plink('link cash & carry period');	
		$this->data["period3"]  = $Admin->get_plink('link day-today period');	
		$this->data["period3a"] = $Admin->get_plink('link day-today upcoming period');	
		$this->data["period4"]  = $Admin->get_plink('link usave period');	
		$this->data["period4a"] = $Admin->get_plink('link usave upcoming period');	
		$this->data["period5"]  = $Admin->get_plink('link special event period');						
		$this->data["switch"]   = $Admin->get_plink('state special event');			
		$this->data["switch2"]  = $Admin->get_plink('state newsletter');	
		$this->data["slides"]   = $Admin->get_scount('slides');		
		$this->data["sponsors"] = $Admin->get_scount('sponsors');
		$this->data["user_info"] = $user_info;
		$router = service('router');

		$this->data['cms'] = Cms::getHomeCmsByPagePosition($user_info, $this->priceList, $img_host, $this, $date);

		echo view('v2/pages/home', $this->data); // after you stored the query results inside the $data array, send the array to the view 
	}


	// function preview() {
	// 	$Admin = new Admin();
	// 	$Product = new Product();

	// 	$img_host = $Admin->get_plink('img_host');
	// 	$user_info = new \stdClass();

	// 	// branch
	// 	session()->set('branch', request()->getGet('branch'));

	// 	// organization
	// 	session()->set('organization', request()->getGet('organization'));

	// 	// price_list
	// 	$price_list = explode(',', request()->getGet('price_list'));
	// 	$user_info->price_list001 = in_array('001', $price_list) ? 1 : 0;
	// 	$user_info->price_list005 = in_array('005', $price_list) ? 1 : 0;
	// 	$user_info->price_list007 = in_array('007', $price_list) ? 1 : 0;
	// 	$user_info->price_list008 = in_array('008', $price_list) ? 1 : 0;
	// 	$user_info->price_list009 = in_array('009', $price_list) ? 1 : 0;
	// 	$user_info->price_list010 = in_array('010', $price_list) ? 1 : 0;
	// 	$user_info->price_list011 = in_array('011', $price_list) ? 1 : 0;
	// 	$user_info->price_list012 = in_array('012', $price_list) ? 1 : 0;
	// 	$user_info->price_list999 = in_array('999', $price_list) ? 1 : 0;

	// 	// date
	// 	$date = request()->getGet('date');

	// 	$this->data['top_ribbon'] = Cms::getActiveTopRibbon($date);
	// 	// $this->data['products_carousels'] = Cms::getActiveProductsCarousels($user_info, $this->priceList, $img_host, $this, $date);
	// 	// $this->data['brochures'] = Cms::getActiveBrochures($date);
	// 	// $this->data['home_banners'] = Cms::getActiveHomeBanners($date);
	// 	// $this->data['bottom_banners'] = Cms::getActiveBottomBanners($date);
	// 	// $this->data['brands'] = Cms::getActiveBrands($date);

	// 	echo view('home', $this->data); // after you stored the query results inside the $data array, send the array to the view 
	// }

	function mobile() {
		$is_mobile = request()->getPost('is_mobile');
		session()->set('is_mobile', $is_mobile);
		echo 'success';
	}
		
    function refresh_products(){  // show products based on type clicked
		$Admin = new Admin();
		$img_host = $Admin->get_plink('img_host');
		$type = request()->getPost('type');
		
		$codes = $Admin->get_featured_codes($type);
		$featured_prods = $Admin->get_featured($codes, $type);
		$manage_table = get_products_manage_table($featured_prods, $this->priceList, $this , '', $img_host, 'grid');

        echo $manage_table;
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
	
	function get_total_items_cart(){ // total items
        $Employee = new Employee();
        $Hom = new Hom();
		$user_info = $Employee->get_logged_in_employee_info();
		$data = $Hom->get_total_items_cart($user_info->person_id);
		echo $data;
	}
	
	function get_cart_quantities($prod_code)
	{
		$Employee = new Employee();
		$Product = new Product();
		$user_info = $Employee->get_logged_in_employee_info();
		return $Product->get_cart_quantity($prod_code , $user_info->person_id);
	}
	
    function check_both_promos(){ 
	 // show products based on type clicked
	 $Admin = new Admin();
	//   $this->load->model('admin');
	  $pid = request()->getPost('person_id');
	  $data = $Admin->check_both_promos($pid);	  
	  echo $data;
    }
    function check_daytoday(){  // show products based on type clicked
	    // $this->load->model('admin');
		$Admin = new Admin();
		$pid = request()->getPost('person_id');
		$data = $Admin->check_daytoday($pid);
		echo $data;
    }
    function check_usave(){  
	    // $this->load->model('admin');
		$Admin = new Admin();
		$pid = request()->getPost('person_id');
		$data = $Admin->check_usave($pid);
		echo $data;
    }
	
    function add_tracking(){  
	    // $this->load->model('admin');
		$Admin = new Admin();
		$Employee = new Employee();

		$user = $Employee->get_logged_in_employee_info();
		$tid = $this->request->getPost('id');
		$tlink = $this->request->getPost('link');
		$tperiod = $this->request->getPost('period');
		$data = $Admin->add_tracking($user->person_id, $tid, $tlink, $tperiod);
		echo $data;
    }

	// public function push_scount()
	// {
	// 	$s1 = $this->request->getPost('s1');
	// 	$s2 = $this->request->getPost('s2');

	// 	$Admin = new Admin();
	// 	$s = $Admin->push_scount($s1, $s2);

	// 	echo $s;
	// }
	
	function is_guest(){
	    $Employee = new Employee();
		if($Employee->get_logged_in_employee_info()->username == "guest"){ return true; } else{ return false; }
	}
	
	function logout()
	{
		$Employee = new Employee();
		return $Employee->logout();
	}

	function forgot_password() 
	{
		echo view('v2/pages/forgot_password'); // after you stored the query results inside the $data array, send the array to the view 
	}

	function reinstall_password()
	{
		echo view('v2/pages/reinstall_password'); // after you stored the query results inside the $data array, send the array to the view 
	}

	function customer_register()
	{
		echo view('v2/pages/customer_register'); // after you stored the query results inside the $data array, send the array to the view 
	}

}

?>