<?php
namespace App\Controllers;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Admin;
use App\Models\PriceList;

class Promos extends Secure_area /* implements iData_controller*/
{
	private $priceList = [];

	function __construct()
	{
		parent::__construct('promos');

		$PriceList = new PriceList();
		$this->priceList = $PriceList->get_all();
	}
	
	function index()
	{
		$Employee = new Employee();
		$Admin = new Admin();
		$Product = new Product();

		if(!$Employee->is_logged_in()) {			
			return redirect()->to('/');			
		}

		$type = request()->uri->getSegment(3);
		$sort_key = $this->request->getGet('sort_key') ?? 9;
		$category_id = $this->request->getGet('category_id') ?? 0;
		$per_page = $this->request->getGet('per_page') ?? 50;
		$offset = $this->request->getGet('offset') ?? 0;
		$view_mode	= $this->request->getGet('view_mode') ?? (session()->get('view_mode') ?? 'grid');
		$im_new      = $this->request->getGet('im_new') ?? 0;
		$plan_profit = $this->request->getGet('plan_profit') ?? 0;
		$favorite  = $this->request->getGet('favorite') ?? 0;
		$rrp = $this->request->getGet('rrp') ?? 0;
		$pmp = $this->request->getGet('pmp') ?? 0;
		$non_pmp = $this->request->getGet('non_pmp') ?? 0;
		$own_label = $this->request->getGet('own_label') ?? 0;
		$search1 = $this->request->getGet('search1') ?? '';

		$filter_brands = urldecode($this->request->getGet('filter_brands')) ?? '';
		$filter_priceEnds = urldecode($this->request->getGet('filter_priceEnds')) ?? '';
		$mobile = $this->request->getGet('mobile') ?? 0;
		$scf = $this->request->getGet('scf') ?? 0;
		$sbf = $this->request->getGet('sbf') ?? 0;
		$spf = $this->request->getGet('spf') ?? 0;

		//$this->load->model('admin'); // we load the model, so controller know where to get the methods from
		$user_info = $Employee->get_logged_in_employee_info();
		
		// Fetch Image Host
		$img_host = $Admin->get_plink('img_host');

		// To get brands
		$this->data['brands'] = $Product->get_brands($user_info, [
			'category_id' => $category_id, 
			'promo' => 1,
			'price_mode' => $type,
		]);

		// To get priceEnds
		$this->data['priceEnds'] = $Product->get_priceEnds($user_info, [
			'category_id' => $category_id, 
			'brand' => $filter_brands,
			'promo' => 1,
			'price_mode' => $type,
		]);
		
		$total_rows = $Product->total_search_num_rows_category($user_info, [
			'category_id' => $category_id,
			'priceEnd' => $filter_priceEnds,
			'promo' => 1,
			'price_mode' => $type,
			'im_new' => $im_new,
			'plan_profit' => $plan_profit,
			'own_label' => $own_label,
			'favorite' => $favorite,
			'rrp' => $rrp,
			'pmp' => $pmp,
			'non_pmp' => $non_pmp,
			'search1' => $search1,
		]);
		$products = $Product->search_category($user_info, [
			'per_page' => $per_page, 
			'offset' => $offset,
			'limit' => $per_page,
			'category_id' => $category_id,
			'priceEnd' => $filter_priceEnds,
			'promo' => 1,
			'price_mode' => $type,
			'im_new' => $im_new,
			'plan_profit' => $plan_profit,
			'own_label' => $own_label,
			'favorite' => $favorite,
			'rrp' => $rrp,
			'pmp' => $pmp,
			'non_pmp' => $non_pmp,
			'sort_key' => $sort_key,
			'search1' => $search1,
		]);

		$this->data['user_info'] = $user_info;
		$this->data['category'] = $Product->fetch_category(0);
		$this->data['controller_name'] = strtolower(get_class());
		$this->data['total_rows'] = $total_rows;
		$this->data['img_host'] = $img_host;
		$this->data['type'] = $type;
		$this->data['sort_key'] = $sort_key;
		$this->data['category_id'] = $category_id;
		$this->data['per_page'] = $per_page;
		$this->data['offset'] = $offset;
		$this->data['curd_page'] = intval($offset / $per_page) + 1;
		$this->data['view_mode'] = $view_mode;
		$this->data['im_new'] = $im_new;
		$this->data['plan_profit'] = $plan_profit;
		$this->data['favorite'] = $favorite;
		$this->data['rrp'] = $rrp;
		$this->data['pmp'] = $pmp;
		$this->data['non_pmp'] = $non_pmp;
		$this->data['own_label'] = $own_label;
		$this->data['from'] = $offset + 1;
		$this->data['to'] = $offset + count($products->getResult());
		$this->data['filter_brands'] = json_decode($filter_brands) ?? [];
		$this->data['filter_priceEnds'] = json_decode($filter_priceEnds) ?? [];
		$this->data['scf'] = $scf;
		$this->data['sbf'] = $sbf;
		$this->data['spf'] = $spf;
		$this->data['search1'] = $search1;
		$this->data['total_page'] = floor($total_rows / $per_page) + 1;
		$this->data['total_rows'] = $total_rows;
		$this->data['base_url'] = "/promos/index/du?"
			."&sort_key=".$sort_key
			."&category_id=".$category_id
			."&view_mode=".$view_mode
			."&im_new=".$im_new
			."&plan_profit=".$plan_profit
			."&own_label=".$own_label
			."&favorite=".$favorite
			."&rrp=".$rrp
			."&pmp=".$pmp
			."&non_pmp=".$non_pmp;

		$this->data['products'] = [];
		foreach($products->getResult() as $row) {
			Product::populate($row, $this->priceList, $user_info, false);
			$this->data['products'][] = $row;
		}

		// echo view('promos', $this->data); // after you stored the query results inside the $data array, send the array to the view 
		echo view('v2/pages/promos', $this->data);
	}
	
	function fetch_subcategory(){
		$Product = new Product();
		if(request()->getPost('cat')!=''){
			 $output = '<option value="" disabled selected>Sub Category</option>';
			$subcategory = $Product->fetch_category(request()->getPost('cat'));
			foreach($subcategory as $r){
				 $output .= '<option value="'.$r->category_id.'">'.$r->category_name.'</option>';
			}
			echo $output;
		}
	}
	
	function get_form_width()
	{
		return 360;
	}
	
	function get_cart_quantities($prod_code)
	{

		$Employee = new Employee();
		$Product = new Product();

		$user_info = $Employee->get_logged_in_employee_info();
		return $Product->get_cart_quantity($prod_code , $user_info->person_id);
	}	
	
	function get_duplicate($prod_code)
	{
		$Product = new Product();
		return $Product->get_duplicate($prod_code);
	    //$q = "SELECT * FROM epos_products WHERE prod_code='".$prod_code."' and (price_list='8' OR price_list='10' OR price_list='11' OR price_list='12')";
		//$r = $this->db->query($q);
		//if($r->num_rows() != 0){ return true; }else{ return false; }
	}
		
    function refresh_products(){  // show products based on type clicked
        $Admin = new Admin();

		$type = request()->getPost('type');
		
		$codes = $Admin->get_featured_codes($type);
		$data["products"]= $Admin->get_featured($codes, $type);
        echo view('home_products', $data);
    }
	
	function to_cart(){  // add or remove quantity 
		$prod_code = request()->getPost('prod_code');
		$mode = request()->getPost('mode');
		$quantity = request()->getPost('quantity');
		$user_info = $this->Employee->get_logged_in_employee_info();
		$quantity = $this->Product->to_cart($prod_code , $mode , $user_info->person_id , $quantity);
		echo $quantity;
	}
	
	function get_total_items_cart(){ // total items
		$user_info = $this->Employee->get_logged_in_employee_info();
		$data = $this->Hom->get_total_items_cart($user_info->person_id);
		echo $data;
	}
	
	function logout()
	{
		$Employee = new Employee();
		$Employee->logout();
	}

}

?>