<?php
namespace App\Controllers;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Admin;
use App\Models\PriceList;


// require_once ("secure_area.php");

class Favorites extends Secure_area /* implements iData_controller*/
{
	private $priceList = [];
	
	function __construct()
	{
		parent::__construct('favorites');	

		$PriceList = new PriceList();
		$this->priceList = $PriceList->get_all();
	}
	
	function index()
	{
		$Employee = new Employee();
		$Product = new Product();
		$Admin = new Admin();

		if(!$Employee->is_logged_in()) {			
			return redirect()->to('/');			
		}

		//$this->load->model('admin'); // we load the model, so controller know where to get the methods from
		$user_info = $Employee->get_logged_in_employee_info();
		$products = $Product->get_all_favorites($user_info , 2000 , 0 , 3 , 0);
		
		// Fetch Image Host
		$img_host = $Admin->get_plink('img_host');
		
		$productCount = !is_string($products) ? $products->getNumRows() : 0; // Get the number of rows in the result set

		if ($productCount != 0) {
			$this->data['products'] = [];
			foreach($products->getResult() as $row) {
				Product::populate($row, $this->priceList, $user_info, false);
				$this->data['products'][] = $row;
			}
		} 
		
		$this->data['total_rows'] = $productCount;
		$this->data['segment'] = request()->getUri()->getSegment(2);
		$this->data['controller_name'] = strtolower(get_class());	
		$this->data['category'] = $Product->fetch_category(0);
		// $data['subcategory'] = $this->Product->fetch_category($cat);
		$this->data['total_page'] = 1;
		$this->data['curd_page'] = 1;
		$this->data['view_mode'] = request()->getGet('view_mode') ?? 'grid';

		echo view('v2/pages/favorite', $this->data); // after you stored the query results inside the $data array, send the array to the view 
	}
	
	function resync_favorites(){

		$Employee = new Employee();
		$Product = new Product();
		$Admin = new Admin();

	    $user_info = $Employee->get_logged_in_employee_info();
		$products = $Product->get_all_favorites($user_info , 2000 , 0 , 3 , 0);
		// Fetch Image Host
		$img_host = $Admin->get_plink('img_host');
		if($products != 0){	$data = get_products_manage_table($products, $this->priceList, $this , 3, $img_host); }
					 else { $data = '';}
		//$data = get_products_manage_table( $this->Product->get_all_favorites( 2000 , 0 , $user_info , 3 , 0) , $this , 3);	
		echo $data;
	}
	
	function bulk_favorites(){	
		$Employee = new Employee();

	    $user_info = $Employee->get_logged_in_employee_info();
		$person_id = $user_info->person_id;
		
		// List of Items from Favorites table
		$db = \Config\Database::connect();
		$query = $db->table('epos_favorites')->where('person_id', $person_id)->get();
		
		foreach($query->getResult() as $r){			
			// Check if item exist in Cart already
			$query2 = $db->table('epos_cart')
						->where('prod_code', $r->prod_code)
						->where('person_id', $person_id)
						->get();
			
			if($query2->getNumRows() == 0){
				$cart_data = array(
					'prod_code'     => $r->prod_code ,
					'quantity'      => '1' ,
					'person_id'     => $person_id ,
				);		

				$db->table('epos_cart')->insert($cart_data);
			}
	    }
		
		$ProductModel = new Product();
		$Admin = new Admin();

		$products = $ProductModel->get_all_favorites( $user_info , 2000 , 0 ,  3 , 0);
		
		
		// Fetch Image Host
		$img_host = $Admin->get_plink('img_host');

		if($products != false){	
			$data = get_products_manage_table($products, $this->priceList, $this , 3, $img_host); 
		}else { 
			$data = '';
		}
		//$data = get_products_manage_table( $this->Product->get_all_favorites( 2000 , 0 , $user_info , 3 , 0) , $this , 3);	
		echo $data;
	}
	
	function fetch_subcategory(){
		if($this->input->post('cat')!=''){
			 $output = '<option value="" disabled selected>Sub Category</option>';
			$subcategory = $this->Product->fetch_category($this->input->post('cat'));
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
        $admin = new \App\Models\Admin();
		$type = $this->request->getPost('type');
		
		$codes = $admin->get_featured_codes($type);
		$data["products"]=$admin->get_featured($codes, $type);
        return view('home_products', $data);
    }
	
	function to_cart(){  // add or remove quantity 
		$prod_code = $this->input->post('prod_code');
		$mode = $this->input->post('mode');
		$quantity = $this->input->post('quantity');
		$user_info = $this->Employee->get_logged_in_employee_info();
		$quantity = $this->Product->to_cart($prod_code , $mode , $user_info->person_id , $quantity);
		echo $quantity;
	}
	
	function get_total_items_cart(){ // total items
		$user_info = $this->Employee->get_logged_in_employee_info();
		$data = $this->Hom->get_total_items_cart($user_info->person_id);
		echo $data;
	}
	
	function sort_product(){
		$sort_key = $this->input->post('sort_key');
		$search0 = $this->input->post('search0');
		$search1 = $this->input->post('search1');
		$search2 = $this->input->post('search2');
		$search_mode = $this->input->post('search_mode');
		$category_id = $this->input->post('category_id');
		$per_page = $this->input->post('per_page');
		$user_info = $this->Employee->get_logged_in_employee_info();
		// Fetch Image Host
		$img_host = $this->admin->get_plink('img_host');

		if($search_mode == "default"){
		}
		$data_rows = '<div>&nbsp;</div>';
		$data_rows .= "********************";
		$data_rows .= '<div>';
		$data_rows .= get_products_manage_table_data_rows($this->Product->load_favorites($search0 , $search1 , $search2 , $user_info , $per_page , 0 , $sort_key , $category_id), $this->priceList, $this, $img_host);
		$data_rows .= '</div>';
		echo $data_rows;
	}
	
	function logout()
	{
		$this->Employee->logout();
	}

}

?>