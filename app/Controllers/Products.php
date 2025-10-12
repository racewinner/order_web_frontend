<?php
namespace App\Controllers;
use App\Controllers\interfaces\iData_controller;

use App\Models\Employee;
use App\Models\Product;
use App\Models\Admin;
use App\Models\PriceList;
use App\Models\Cms;

class Products extends BaseController implements iData_controller
{
	private $priceList = [];

	function __construct()
	{
		parent::__construct('products');	
		helper('utils_helper');

		$PriceList = new PriceList();
		$this->priceList = $PriceList->get_all();
	}

	function index()
	{
		$Employee = new Employee();
		$Product = new Product();
		$Admin = new Admin();

		$search0 = urldecode($this->request->getGet('search0')) ?? '';
		$search1 = urldecode($this->request->getGet('search1')) ?? '';
		$search2 = urldecode($this->request->getGet('search2')) ?? '';
		$sort_key = $this->request->getGet('sort_key') ?? 3;
		$category_id = $this->request->getGet('category_id') ?? 0;
		$per_page = intval($this->request->getGet('per_page') ?? 30);
		$offset = intval($this->request->getGet('offset') ?? 0);
		$im_new      = $this->request->getGet('im_new') ?? 0;
		$plan_profit = $this->request->getGet('plan_profit') ?? 0;
		$favorite  = $this->request->getGet('favorite') ?? 0;
		$rrp = $this->request->getGet('rrp') ?? 0;
		$pmp = $this->request->getGet('pmp') ?? 0;
		$non_pmp = $this->request->getGet('non_pmp') ?? 0;
		$own_label = $this->request->getGet('own_label') ?? 0;
		$filter_brands = urldecode($this->request->getGet('filter_brands')) ?? '';
		$filter_brand_arr = json_decode($filter_brands, true) ?? [];
		$view_mode = $this->request->getGet('view_mode') ?? 'grid';
		$spresell = $this->request->getGet('spresell') ?? 0;
		$mobile = session()->get('is_mobile');

		if($this->request->getGet('view_mode') != null) session()->set('view_mode', $view_mode);

		$user_info = $Employee->is_logged_in() ? $Employee->get_logged_in_employee_info() : null;

		$this->data['brands'] = $Product->get_brands($user_info, [
			'search0' => $search0,
			'search1' => $search1,
			'search2' => $search2,
			'category_id' => $category_id,
			'im_new' => $im_new,
			'plan_profit' => $plan_profit,
			'own_label' => $own_label,
			'brand'=> $filter_brands,
			'favorite' => $favorite,
			'rrp' => $rrp,
			'pmp' => $pmp,
			'non_pmp' => $non_pmp,
			'spresell' => $spresell		
		]);

		$total_rows = $Product->total_search_num_rows_category($user_info, [
			'search0' => $search0,
			'search1' => $search1,
			'search2' => $search2,
			'category_id' => $category_id,
			'im_new' => $im_new,
			'plan_profit' => $plan_profit,
			'own_label' => $own_label,
			'brand'=> $filter_brands,
			'favorite' => $favorite,
			'rrp' => $rrp,
			'pmp' => $pmp,
			'non_pmp' => $non_pmp,
			'spresell' => $spresell
		]);
		$config['total_rows'] = $total_rows;
		$this->data['total_rows'] = $total_rows;
		$this->data['total_page'] = floor($total_rows / $per_page) + 1;

    $config['full_tag_open'] = '<ul>';
    $config['full_tag_close'] = '</ul>';
    $config['first_link'] = '<<';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['last_link'] = '>>';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    $config['prev_link'] = '<';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['next_link'] = '>';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li><b>';
    $config['cur_tag_close'] = '</b></li>';
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
		$config['per_page'] = $per_page;
		$config['offset'] = 0;

		$this->data['controller_name'] = request()->uri->getSegment(1);
		$this->data['form_width'] = $this->get_form_width();
		
		// Fetch Image Host
		$img_host = $Admin->get_plink('img_host');
		$products = $Product->search_category($user_info, [
			'search0' => $search0, 
			'search1' => $search1,
			'search2' => $search2, 
			'limit' => $per_page, 
			'offset' => $offset, 
			'sort_key' => $sort_key, 
			'category_id' => $category_id, 
			'im_new' => $im_new, 
			'plan_profit' => $plan_profit, 
			'own_label' => $own_label, 
			'brand' => $filter_brands, 
			'favorite' => $favorite,
			'rrp' => $rrp,
			'pmp' => $pmp,
			'non_pmp' => $non_pmp,
			'spresell' => $spresell
		]);

		$this->data['from'] = $offset + 1;
		$this->data['to'] = $offset + count($products->getResult());
		$this->data['products'] = [];
		foreach($products->getResult() as $row) {
			Product::populate($row, $this->priceList, $user_info, false);
			$this->data['products'][] = $row;
		}

		$this->data['sort_key'] = $sort_key;
		$this->data['curd_page'] = intval($offset / $per_page) + 1;

		$this->data['categories'] = $Product->get_all_categories($category_id);
		$this->data['category_id'] = $category_id;
		$this->data['im_new'] = $im_new;
		$this->data['plan_profit'] = $plan_profit;
		$this->data['favorite'] = $favorite;
		$this->data['rrp'] = $rrp;
		$this->data['pmp'] = $pmp;
		$this->data['non_pmp'] = $non_pmp;
		$this->data['own_label'] = $own_label;
		$this->data['view_mode'] = $view_mode;
		$this->data['rn'] = count(request()->uri->getSegments());
		$this->data['filter_brands'] = $filter_brand_arr;
		$this->data['per_page'] = $per_page;
		$this->data['offset'] = $offset;
		$this->data['search0'] = $search0;
		$this->data['search1'] = $search1;
		$this->data['search2'] = $search2;
		$this->data['spresell'] = $spresell;
		$this->data['base_url'] = base_url("products/index?search_mode=search"
			."&search0=".$search0
			."&search1=".$search1
			."&search2=".$search2
			."&sort_key=".$sort_key
			."&category_id=".$category_id
			."&view_mode=".$view_mode
			."&im_new=".$im_new
			."&plan_profit=".$plan_profit
			."&own_label=".$own_label
			."&favorite=".$favorite
			."&rrp=".$rrp
			."&pmp=".$pmp
			."&non_pmp=".$non_pmp
			."&spresell=".$spresell
		);

		if(empty($filter_brand_arr)) {
			$this->data['category_banners'] = Cms::getActiveCategoryBanners($category_id);
		} else {
			// $this->data['category_banners'] = Cms::getBrandBanners($filter_brand_arr[0]);
		}

		if(!empty($search0)) {
			$sponsor = Cms::getActiveSponsor($search0);
			if($sponsor) {
				$sponsor_products = $Product->search_category($user_info, [
					'search1' => $sponsor['prod_codes'],
					'limit' => 1000, 
					'offset' => 0, 
				]);
				$sponsor['manage_table'] = get_products_manage_table($sponsor_products, $this->priceList, $this , $sort_key, $img_host, $view_mode, $mobile,$spresell);
				$this->data['sponsor'] = $sponsor;
			}
		}

		// echo view('products/manage' , $this->data);
		echo view('v2/pages/products' , $this->data);
	}

	function brand($brand) {
		$Employee = new Employee();
		$Product = new Product();
		$Admin = new Admin();

		if(empty($brand)) {
			return redirect()->to(base_url('/'));
		}

		$user_info = $Employee->is_logged_in() ? $Employee->get_logged_in_employee_info() : null;

		$per_page = intval($this->request->getGet('per_page') ?? 30);
		$offset = intval($this->request->getGet('offset') ?? 0);
		$sort_key = $this->request->getGet('sort_key') ?? 3;
		$view_mode = $this->request->getGet('view_mode') ?? 'grid';
		$spresell = $this->request->getGet('spresell') ?? 0;
		$mobile = session()->get('is_mobile');

		$total_rows = $Product->total_search_num_rows_category($user_info, [
			'brand'=> "['".$brand."']",
		]);
		$this->data['categories'] = $Product->get_all_categories(0);
		$this->data['total_rows'] = $total_rows;
		$this->data['total_page'] = floor($total_rows / $per_page) + 1;
		$this->data['controller_name'] = request()->uri->getSegment(1);
		$this->data['form_width'] = $this->get_form_width();
		
		// Fetch Image Host
		$img_host = $Admin->get_plink('img_host');
		$products = $Product->search_category($user_info, [
			'limit' => $per_page, 
			'offset' => $offset, 
			'sort_key' => $sort_key, 
			'brand' => "['".$brand."']", 
		]);
		$this->data['from'] = $offset + 1;
		$this->data['to'] = $offset + count($products->getResult());
		$this->data['manage_table'] = get_products_manage_table($products, $this->priceList, $this , $sort_key, $img_host, $view_mode, $mobile,$spresell);

		$this->data['sort_key'] = $sort_key;
		$this->data['curd_page'] = intval($offset / $per_page) + 1;
		$this->data['view_mode'] = $view_mode;
		$this->data['rn'] = count(request()->uri->getSegments());
		$this->data['per_page'] = $per_page;
		$this->data['offset'] = $offset;
		$this->data['category_banners'] = Cms::getBrandBanners($brand);

		echo view('products/manage' , $this->data);
	}

	function refresh()
	{
		$low_inventory=request()->getPost('low_inventory');
		$is_serialized=request()->getPost('is_serialized');
		$no_description=request()->getPost('no_description');

		$this->data['search_section_state']=request()->getPost('search_section_state');
		$this->data['low_inventory']=request()->getPost('low_inventory');
		$this->data['is_serialized']=request()->getPost('is_serialized');
		$this->data['no_description']=request()->getPost('no_description');
		$this->data['controller_name'] = request()->uri->getSegment(1);
		$this->data['form_width']=$this->get_form_width();
		$this->data['manage_table']=get_items_manage_table($this->Item->get_all_filtered($low_inventory,$is_serialized,$no_description),$this);
		echo view('items/manage',$this->data);
	}

	public function show_by_code($prod_code)
	{
		$Product = new Product();
		$Employee = new Employee();
		$Admin = new Admin();

		if(!$Employee->is_logged_in()) return redirect()->to('/');			
		$user_info = $Employee->get_logged_in_employee_info();

		$product = $Product->getLowestPriceProductByCode($user_info, $prod_code);
		if(isset($product)) {
			$img_host = $Admin->get_plink('img_host');
			Product::populate($product, $this->priceList, $user_info, false);
			return view('v2/components/Product', [
				'product' => $product, 
				'view_mode' => 'grid', 
				'img_host' => $img_host,
				'user_info' => $user_info,
			]);
		} else {
			echo '';
		}
	}

	public function show($prod_id)
	{
		$Product = new Product();
		$Employee = new Employee();

		$product = $Product->get_info($prod_id);
		if(!empty($product)) {
			$user_info = $Employee->get_logged_in_employee_info();
			$this->data['user_info'] = $user_info;

			Product::populate($product, $this->priceList, $user_info, false);

			$this->data['product'] = $product;

			// To get product detail
			$prod_detail_url = "https://img.uniteduk.co.uk/brandbank/api_ecomm_product_data.php?product={$product->prod_code}&key=ghygogggfos345UYGYIGFUID675nbjkblesr98435j899";
			$response = file_get_contents($prod_detail_url);
			if($response != "null") {
				$json_decoded = json_decode($response, true);
				$this->data['detail'] = $json_decoded;
				foreach($json_decoded['languages'][0]['groupingSets'] as $set) {
					if($set['name'] == 'All Attributes') {
						$this->data['all_attributes'] = $set['attributes'];
					}
				}
			}

			echo view('v2/pages/product_detail.php', $this->data);
		} else {
			echo 'No found product';
		}
	}

	function find_item_info()
	{
		$item_number=request()->getPost('scan_item_number');
		echo json_encode($this->Item->find_item_info($item_number));
	}

	function search()
	{ 
		$Product = new Product();
		$Admin = new Admin();
		$search0 = request()->getPost('search0');
		$search1 = request()->getPost('search1');
		$search2 = request()->getPost('search2');
		$sort_key = request()->getPost('sort_key');
		$category_id = request()->getPost('category_id');
		$per_page = request()->getPost('per_page');
		$Employee = new Employee();
		
		// Fetch Image Host
		$img_host = $Admin->get_plink('img_host');

		$user_info = $Employee->get_logged_in_employee_info();

		$config['base_url'] = base_url("products/index?search_mode=search&search0=".$search0."&search1=".$search1."&search2=".$search2."&sort_key=".$sort_key."&category_id=".$category_id);
		$config['total_rows'] = $Product->total_search_num_rows_category($user_info, [
			'search0' => $search0, 
			'search1' => $search1, 
			'search2' => $search2, 
			'category_id' => $category_id
		]);
		$total_rows = $config['total_rows'];
		$total_page = floor($total_rows / $per_page) + 1;
		$config['per_page'] = $per_page;
		$config['offset'] = 0;
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '<<';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = '>>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link'] = '<';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><b>';
		$config['cur_tag_close'] = '</b></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		// $this->pagination->initialize($config);
		$this->pager = \Config\Services::pager();
		
		$data_rows = "search";
		$data_rows .= "********************";

		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><span style='font-family:Arial;'>Show&nbsp;&nbsp;</span><select name='per_page' id='per_page' onchange=\"select_per_page('";
		$data_rows .= base_url("products/index/");
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

		$data_rows .= "<div class='btnseparator' style='float:right;'></div>";
		$data_rows .= "<div class='pGroup' style='float:right;'>";
		$data_rows .= "<div class='pNext pButton' onclick=\"pNext('";
		$data_rows .= base_url("products/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "<div class='pLast pButton' onclick=\"pLast('";
		$data_rows .= base_url("products/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "</div><div class='btnseparator' style='float:right;'></div><div class='pGroup' style='float:right;'>";
		$data_rows .= "<span class='pcontrol'>Page&nbsp;";
		$data_rows .= "<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page' onkeyup=\"set_direct_page(event , '";
		$data_rows .= base_url("products/index/");
		$data_rows .= "');\">&nbsp;of&nbsp;<span id='last_page_number'>".$total_page."</span></span></div><div class='btnseparator' style='float:right;'></div>";
		$data_rows .= "<div class='pGroup' style='float:right;'><div class='pFirst pButton' onclick=\"pFirst('";
		$data_rows .= base_url("products/index/");
		$data_rows .= "');\"><span></span></div><div class='pPrev pButton'  onclick=\"pPrev('";
		$data_rows .= base_url("products/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator' style='float:right;'></div></div>";
		$data_rows .= "********************";
		$data_rows .= get_products_manage_table_data_rows(
			$Product->search_category($user_info, [
				'search0' => $search0, 
				'search1' => $search1, 
				'search2' => $search2, 
				'limit' => $config['per_page'], 
				'sort_key' => $sort_key, 
				'category_id' => $category_id
			]), 
			$this->priceList, $this, $img_host);
		echo $data_rows;
	}


	function suggest()
	{
		$suggestions = $this->Item->get_search_suggestions(request()->getPost('q'),request()->getPost('limit'));
		echo implode("\n",$suggestions);
	}
	function suggest0()
	{
		$Product = new Product();
		$Employee = new Employee();

		$user_info = $Employee->get_logged_in_employee_info();
		$suggestions = $Product->get_search_suggestions0($user_info, request()->getPost('q'),request()->getPost('limit'));
		echo implode("\n",$suggestions);
	}
	function suggest1()
	{
		$Employee = new Employee();
		$Product = new Product();

		$user_info = $Employee->get_logged_in_employee_info();
		$suggestions = $Product->get_search_suggestions1($user_info, request()->getPost('q'), request()->getPost('limit'));
		echo implode("\n",$suggestions);
	}
	function suggest2()
	{
		$Employee = new Employee();
		$Product = new Product();

		$q = request()->getPost('term');
		$user_info = $Employee->get_logged_in_employee_info();
    $category_id = request()->getPost('category_id');
		$suggestions = $Product->get_search_suggestions2( $user_info , $q , 30 , $category_id);
		echo json_encode($suggestions);
		//echo $suggestions;
	}
	function item_search()
	{
		$suggestions = $this->Item->get_item_search_suggestions(request()->getPost('q'),request()->getPost('limit'));
		echo implode("\n",$suggestions);
	}
	function suggest_category()
	{
		$suggestions = $this->Item->get_category_suggestions(request()->getPost('q'));
		echo implode("\n",$suggestions);
	}
	function get_row()
	{
		// $item_id = request()->getPost('row_id');
		
		$item_id = $this->request->getPost('row_id');
		$this->data_row=get_item_data_row($this->Item->get_info($item_id),$this);
		echo $this->data_row;
	}
	function view($item_id=-1)
	{
		$Product = new Product();
		$this->data['ftp_location'] = $Product->get_ftp_location();
		echo view("products/form",$this->data);
	}
	function inventory($item_id=-1)
	{
		$this->data['item_info']=$this->Item->get_info($item_id);
		echo view("items/inventory",$this->data);
	}
	function count_details($item_id=-1)
	{
		$this->data['item_info']=$this->Item->get_info($item_id);
		echo view("items/count_details",$this->data);
	}
	function bulk_edit()
	{
		$this->data = array();
		$suppliers = array('' => $this->lang->line('items_none'));
		foreach($this->Supplier->get_all()->result_array() as $row)
		{
			$suppliers[$row['person_id']] = $row['first_name'] .' '. $row['last_name'];
		}
		$this->data['suppliers'] = $suppliers;
		$this->data['allow_alt_desciption_choices'] = array(
			''=>$this->lang->line('items_do_nothing'),
			1 =>$this->lang->line('items_change_all_to_allow_alt_desc'),
			0 =>$this->lang->line('items_change_all_to_not_allow_allow_desc'));

		$this->data['serialization_choices'] = array(
			''=>$this->lang->line('items_do_nothing'),
			1 =>$this->lang->line('items_change_all_to_serialized'),
			0 =>$this->lang->line('items_change_all_to_unserialized'));
		echo view("items/form_bulk", $this->data);
	}
	function save($item_id=-1)
	{
		$Employee = new Employee();

		$item_data = array(
		'name'=>request()->getPost('name'),
		'description'=>request()->getPost('description'),
		'category'=>request()->getPost('category'),
		'supplier_id'=>request()->getPost('supplier_id')=='' ? null:request()->getPost('supplier_id'),
		'item_number'=>request()->getPost('item_number')=='' ? null:request()->getPost('item_number'),
		'cost_price'=>request()->getPost('cost_price'),
		'unit_price'=>request()->getPost('unit_price'),
		'quantity'=>request()->getPost('quantity'),
		'reorder_level'=>request()->getPost('reorder_level'),
		'location'=>request()->getPost('location'),
		'allow_alt_description'=>request()->getPost('allow_alt_description'),
		'is_serialized'=>request()->getPost('is_serialized')
		);
		$employee_id = $Employee->get_logged_in_employee_info()->person_id;
		$cur_item_info = $this->Item->get_info($item_id);
		if($this->Item->save($item_data,$item_id))
		{
			if($item_id==-1)
			{
				echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_adding').' '.
				$item_data['name'],'item_id'=>$item_data['item_id']));
				$item_id = $item_data['item_id'];
			}
			else
			{
				echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_updating').' '.
				$item_data['name'],'item_id'=>$item_id));
			}

			$inv_data = array
			(
				'trans_date'=>date('Y-m-d H:i:s'),
				'trans_items'=>$item_id,
				'trans_user'=>$employee_id,
				'trans_comment'=>$this->lang->line('items_manually_editing_of_quantity'),
				'trans_inventory'=>$cur_item_info ? request()->getPost('quantity') - $cur_item_info->quantity : request()->getPost('quantity')
			);
			$this->Inventory->insert($inv_data);
			$items_taxes_data = array();
			$tax_names = request()->getPost('tax_names');
			$tax_percents = request()->getPost('tax_percents');
			for($k=0;$k<count($tax_percents);$k++)
			{
				if (is_numeric($tax_percents[$k]))
				{
					$items_taxes_data[] = array('name'=>$tax_names[$k], 'percent'=>$tax_percents[$k] );
				}
			}
			$this->Item_taxes->save($items_taxes_data, $item_id);
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('items_error_adding_updating').' '.
			$item_data['name'],'item_id'=>-1));
		}

	}
	function save_inventory($item_id=-1)
	{
		$Employee = new Employee();
		$employee_id=$Employee->get_logged_in_employee_info()->person_id;
		$cur_item_info = $this->Item->get_info($item_id);
		$inv_data = array
		(
			'trans_date'=>date('Y-m-d H:i:s'),
			'trans_items'=>$item_id,
			'trans_user'=>$employee_id,
			'trans_comment'=>request()->getPost('trans_comment'),
			'trans_inventory'=>request()->getPost('newquantity')
		);
		$this->Inventory->insert($inv_data);
		$item_data = array(
		'quantity'=>$cur_item_info->quantity + request()->getPost('newquantity')
		);
		if($this->Item->save($item_data,$item_id))
		{
			echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_updating').' '.
			$cur_item_info->name,'item_id'=>$item_id));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('items_error_adding_updating').' '.
			$cur_item_info->name,'item_id'=>-1));
		}

	}

	function bulk_update()
	{
		$items_to_update=request()->getPost('item_ids');
		$item_data = array();

		foreach($_POST as $key=>$value)
		{
			if ($key == 'supplier_id')
			{
				$item_data["$key"]=$value == '' ? null : $value;
			}
			elseif($value!='' and !(in_array($key, array('item_ids', 'tax_names', 'tax_percents'))))
			{
				$item_data["$key"]=$value;
			}
		}

		if(empty($item_data) || $this->Item->update_multiple($item_data,$items_to_update))
		{
			$items_taxes_data = array();
			$tax_names = request()->getPost('tax_names');
			$tax_percents = request()->getPost('tax_percents');
			for($k=0;$k<count($tax_percents);$k++)
			{
				if (is_numeric($tax_percents[$k]))
				{
					$items_taxes_data[] = array('name'=>$tax_names[$k], 'percent'=>$tax_percents[$k] );
				}
			}
			$this->Item_taxes->save_multiple($items_taxes_data, $items_to_update);

			echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_bulk_edit')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('items_error_updating_multiple')));
		}
	}

	function delete()
	{
		$items_to_delete=request()->getPost('ids');

		if($this->Item->delete_list($items_to_delete))
		{
			echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_deleted').' '.
			count($items_to_delete).' '.$this->lang->line('items_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('items_cannot_be_deleted')));
		}
	}

	function excel()
	{
    return;
	}

	function excel_import()
	{
    return;
	}



	function do_excel_import()
	{
    	return;
	}
	function get_form_width()
	{
		return 360;
	}

	function sort_product()
	{
		$Product = new Product();
		$Admin = new Admin();
		$Employee = new Employee();

		$sort_key = request()->getPost('sort_key');
		$search0 = request()->getPost('search0');
		$search1 = request()->getPost('search1');
		$search2 = request()->getPost('search2');
		$search_mode = request()->getPost('search_mode');
		$category_id = request()->getPost('category_id');
		$per_page = request()->getPost('per_page');
		$offset = request()->getPost('offset');
		
		// Fetch Image Host
		$img_host = $Admin->get_plink('img_host');

		if($per_page == 0) $per_page = 30;

		$user_info = $Employee->get_logged_in_employee_info();


		if($search_mode == "default")
		{
			$config['base_url'] = base_url('/products/index/default/'.$sort_key."//".$category_id."//");
			$config['total_rows'] = $Product->count_all_category($user_info, [
				'category_id' => $category_id
			]);

			$total_rows = $config['total_rows'];
			$total_page = floor($total_rows / $per_page) + 1;

			$config['per_page'] = $per_page;
		}
		else if($search_mode == "search")
		{
			$config['base_url'] = base_url("products/index?search_mode=search&search0=".$search0."&=search1=".$search1."&=search2=".$search2."&sort_key=".$sort_key."&category_id=".$category_id);
			$config['total_rows'] = $Product->total_search_num_rows_category($user_info, [
				'search0' => $search0, 
				'search1' => $search1, 
				'search2'=>$search2, 
				'category_id' => $category_id
			]);
			$config['per_page'] = $per_page;
			$total_rows = $config['total_rows'];
			$total_page = floor($total_rows / $per_page) + 1;
		}
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '<<';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = '>>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link'] = '<';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><b>';
		$config['cur_tag_close'] = '</b></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		// $this->pagination->initialize($config);
		$this->pager = \Config\Services::pager();


		$data_rows = '';
		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><span style='font-family:Arial;'>Show&nbsp;&nbsp;</span><select name='per_page' id='per_page' onchange=\"select_per_page('";
		$data_rows .= base_url("products/index/");
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
		$data_rows .= ">200</option></select><span style='font-family:Arial;>&nbsp;Rows&nbsp;Per&nbsp;Page</span></div>";

		$data_rows .= "<div class='btnseparator' style='float:right;'></div>";
		$data_rows .= "<div class='pGroup' style='float:right;'>";
		$data_rows .= "<div class='pNext pButton' onclick=\"pNext('";
		$data_rows .= base_url("products/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "<div class='pLast pButton' onclick=\"pLast('";
		$data_rows .= base_url("products/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "</div><div class='btnseparator' style='float:right;'></div><div class='pGroup' style='float:right;'>";
		$data_rows .= "<span class='pcontrol'>Page&nbsp;";
		$data_rows .= "<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page' onkeyup=\"set_direct_page(event , '";
		$data_rows .= base_url("products/index/");
		$data_rows .= "');\">&nbsp;of&nbsp;<span id='last_page_number'>".$total_page."</span></span></div><div class='btnseparator' style='float:right;'></div>";
		$data_rows .= "<div class='pGroup' style='float:right;'><div class='pFirst pButton' onclick=\"pFirst('";
		$data_rows .= base_url("products/index/");
		$data_rows .= "');\"><span></span></div><div class='pPrev pButton'  onclick=\"pPrev('";
		$data_rows .= base_url("products/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator' style='float:right;'></div></div>";
		$data_rows .= "********************";
		$data_rows .= get_products_manage_table_data_rows(
			$Product->search_category($user_info, [
				'search0' => $search0, 
				'search1' => $search1, 
				'search2' => $search2,
				'limit' => $per_page,
				'offset' => $offset, 
				'sort_key' => $sort_key, 
				'category_id' => $category_id
			]), 
			$this->priceList, $this, $img_host);
		echo $data_rows;
	}

	function select_category()
	{
		$Product = new Product();
		$Admin = new Admin();
		$Employee = new Employee();

		$search_mode = request()->getPost('search_mode');
		$sort_key = request()->getPost('sort_key');
		$search0 = request()->getPost('search0');
		$search1 = request()->getPost('search1');
		$search2 = request()->getPost('search2');
		$category_id = request()->getPost('category_id');
		$per_page = request()->getPost('per_page');
		if($per_page == 0) $per_page = 30;
		
		// Fetch Image Host
		$img_host = $Admin->get_plink('img_host');
		
		$user_info = $Employee->get_logged_in_employee_info();
		if($search_mode == "default")
		{
			$config['base_url'] = base_url('/products/index/default/'.$sort_key."//".$category_id."//");
			$config['total_rows'] = $Product->count_all_category($user_info, [
				'category_id' => $category_id
			]);

			$total_rows = $config['total_rows'];
			$total_page = floor($total_rows / $per_page) + 1;

			$config['per_page'] = $per_page;
			$config['offset'] = 0;
		}
		else if($search_mode == "search")
		{
			$config['base_url'] = base_url("products/index?search_mode=search&search0=".$search0."&search1=".$search1."&=search2=".$search2."&sort_key=".$sort_key."&category_id=".$category_id);
			$config['total_rows'] = $Product->total_search_num_rows_category($user_info, [
				'search0' => $search0, 
				'search1' => $search1 , 
				'search2'=>$search2, 
				'category_id' => $category_id
			]);
			$config['per_page'] = $per_page;
			$config['offset'] = 0;
			$total_rows = $config['total_rows'];
			$total_page = floor($total_rows / $per_page) + 1;
		}
		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = '<<';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = '>>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link'] = '<';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><b>';
		$config['cur_tag_close'] = '</b></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		// $this->pagination->initialize($config);
		$this->pager = \Config\Services::pager();


		$data_rows = "";

		$data_rows .= "<div class='btnseparator'></div><div class='pGroup'><span style='font-family:Arial;'>Show&nbsp;&nbsp;</span><select name='per_page' id='per_page' onchange=\"select_per_page('";
		$data_rows .= base_url("products/index/");
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

		$data_rows .= "<div class='btnseparator' style='float:right;'></div>";
		$data_rows .= "<div class='pGroup' style='float:right;'>";
		$data_rows .= "<div class='pNext pButton' onclick=\"pNext('";
		$data_rows .= base_url("products/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "<div class='pLast pButton' onclick=\"pLast('";
		$data_rows .= base_url("products/index/");
		$data_rows .= "');\"><span></span></div>";
		$data_rows .= "</div><div class='btnseparator' style='float:right;'></div><div class='pGroup' style='float:right;'>";
		$data_rows .= "<span class='pcontrol'>Page&nbsp;";
		$data_rows .= "<input type='text' name='page' id='curd_page' value='1' size='4' class='product_search_cell_page' onkeyup=\"set_direct_page(event , '";
		$data_rows .= base_url("products/index/");
		$data_rows .= "');\">&nbsp;of&nbsp;<span id='last_page_number'>".$total_page."</span></span></div><div class='btnseparator' style='float:right;'></div>";
		$data_rows .= "<div class='pGroup' style='float:right;'><div class='pFirst pButton' onclick=\"pFirst('";
		$data_rows .= base_url("products/index/");
		$data_rows .= "');\"><span></span></div><div class='pPrev pButton'  onclick=\"pPrev('";
		$data_rows .= base_url("products/index/");
		$data_rows .= "');\"><span></span></div></div><div class='btnseparator' style='float:right;'></div></div>";
		$data_rows .= "********************";
		$data_rows .= get_products_manage_table_data_rows(
			$Product->search_category($user_info, [
				'search0' => $search0, 
				'search1' => $search1, 
				'search2' => $search2, 
				'limit' => $config['per_page'], 
				'offset' => 0, 
				'sort_key' => $sort_key, 
				'category_id' => $category_id
			]), 
			$this->priceList, $this, $img_host);
		echo $data_rows;
	}
	
	function to_cart()
	{
		$Employee = new Employee();
		$Product = new Product();
		
		$prod_code = request()->getPost('prod_code');
		$spresell = request()->getPost('spresell');
		$mode = request()->getPost('mode');
		$quantity = request()->getPost('quantity');
		$type = request()->getPost('type');
		$user_info = $Employee->get_logged_in_employee_info();
		
		$quantity = $Product->to_cart($prod_code, $mode, $user_info->person_id, $quantity, $spresell, $type);
		echo $quantity;
	}

	function get_cart_quantities($prod_code, $spresell=0)
	{
		$Product = new Product();
		$Employee = new Employee();

		$user_info = $Employee->get_logged_in_employee_info();
		return $Product->get_cart_quantity($prod_code , $user_info->person_id, $spresell);
	}

	function reload_product()
	{
    return;
	}

	function add_to_cart()
	{
		$Employee = new Employee();
		$prod_code = request()->getPost('prod_code');
		$quantity = request()->getPost('quantity');

		$user_info = $Employee->get_logged_in_employee_info();
		$this->Pastorder->add_to_cart($prod_code , $quantity , $user_info->person_id);
	}
	
	function favorite()
	{
		$Product = new Product();
		
		$pid     = request()->getPost('pid');
		$prod_code = request()->getPost('prod_code');
		$state   = request()->getPost('state');
		
		$r = $Product->favorite($pid , $prod_code , $state);
		echo $r;
	    	
	}
}
?>
