<?php
namespace App\Controllers;
use App\Controllers\interfaces\iData_controller;
use CodeIgniter\Email\Email;

use App\Models\Employee;
use App\Models\Product;
use App\Models\Admin;
use App\Models\Order;
use App\Models\UnknownProduct;
use App\Services\EmailService;
use App\Models\PriceList;

class Orders extends Secure_area implements iData_controller
{
	private $priceList = [];

	function __construct()
	{
		parent::__construct('orders');
		$PriceList = new PriceList();
		$this->priceList = $PriceList->get_all();	
	}

	function index($page='')
	{
		$Employee = new Employee();
		$Admin = new Admin();
		$Order = new Order();
		$UnknownProduct = new UnknownProduct();

		if(!$Employee->is_logged_in()) {			
			return redirect()->to('/');			
		}

		$user_info = $Employee->get_logged_in_employee_info();
		$pid = $user_info->person_id;
		$this->data['controller_name'] = request()->uri->getSegment(1);

		$img_host = $Admin->get_plink('img_host');
		$this->data['img_host'] = $img_host;
		
		$types = [
			['id' => 'general', 'label' => 'General', 'orders' => [], 'lines' => 0, 'items' => 0], 
			['id' => 'tobacco', 'label' => 'Tobacco', 'orders' => [], 'lines' => 0, 'items' => 0], 
			['id' => 'chilled', 'label' => 'Chilled', 'orders' => [], 'lines' => 0, 'items' => 0], 
			['id' => 'spresell', 'label' => 'Seasonal Presell', 'orders' => [], 'lines' => 0, 'items' => 0], 
		];
		foreach($types as &$type) {
			$type['lines'] = $Order->get_lines($pid, $type['id']);
			$type['items'] = $Order->get_items($pid, $type['id']);

			$orders = $Order->get_all_cart($pid, $type['id'])->getResult();
			foreach($orders as $order) {
				Order::populateProduct($order, $this->priceList, $user_info, 0);
				if(!empty($order->product)) {
					$type['orders'][] = $order;
				}
			}
		}
		$this->data["types"] = $types;

		$cart = Order::get_cart_info($pid);
    $this->data['total_quantity']   = $cart['total_quantity'];
		$this->data['total_amount']     = $cart['total_amount'];
		$this->data['total_epoints']    = $cart['total_epoints'];
		$this->data['delivery_charge']  = $cart['total_quantity'] == 0 ? "0.00" : $cart['delivery_charge'];
		$this->data['total_vats']       = $cart['total_vats'];

		$this->data['form_width'] = $this->get_form_width();
	    
	  $this->data["slides"] = $Admin->get_scount('slides');
		$this->data['unknown_products'] = $UnknownProduct->get_all_products($user_info->username);

    // filter only 10 product--------------
    // foreach($types as &$type) {
		// 	$type['lines'] = $Order->get_lines($pid, $type['id']);
		// 	$type['items'] = $Order->get_items($pid, $type['id']);

		// 	$orders = $Order->get_limited_cart($pid, $type['id'])->getResult();
    //   $type['orders'] = [];
		// 	foreach($orders as $order) {
		// 		Order::populateProduct($order, $this->priceList, $user_info, 0);
		// 		if(!empty($order->product)) {
		// 			$type['orders'][] = $order;
		// 		}
		// 	}
		// }
		// $this->data["types"] = $types;
    // ------------------------------------

		if($page == 'checkout') {
			return view('v2/pages/myaccount/checkout', $this->data);
		} else if($page == 'payment') {
			return view('v2/pages/myaccount/payment', $this->data);
		} else {
			if(request()->isAJAX()) {
        $this->data["cls"] = "my-cart-body-limited";
				return view('v2/partials/my_cart_content' , $this->data);
			} else {
        $this->data["cls"] = "";
				return view('v2/pages/orders' , $this->data);
			}
		}
	}
  
	function mini_cart($page='')
	{
		$Employee = new Employee();
		$Admin = new Admin();
		$Order = new Order();
		$UnknownProduct = new UnknownProduct();

		if(!$Employee->is_logged_in()) {			
			return redirect()->to('/');			
		}

		$user_info = $Employee->get_logged_in_employee_info();
		$pid = $user_info->person_id;
		$this->data['controller_name'] = request()->uri->getSegment(1);

		$img_host = $Admin->get_plink('img_host');
		$this->data['img_host'] = $img_host;
		
		$types = [
			['id' => 'general', 'label' => 'General', 'orders' => [], 'lines' => 0, 'items' => 0], 
			// ['id' => 'tobacco', 'label' => 'Tobacco', 'orders' => [], 'lines' => 0, 'items' => 0], 
			// ['id' => 'chilled', 'label' => 'Chilled', 'orders' => [], 'lines' => 0, 'items' => 0], 
			// ['id' => 'spresell', 'label' => 'Seasonal Presell', 'orders' => [], 'lines' => 0, 'items' => 0], 
		];
		foreach($types as &$type) {
			// $type['lines'] = $Order->get_lines($pid, $type['id']);
      $type['lines'] = $Order->get_lines_ignore_type($pid, $type['id']);

			// $type['items'] = $Order->get_items($pid, $type['id']);
			$type['items'] = $Order->get_items_ignore_type($pid, $type['id']);

			// $orders = $Order->get_all_cart($pid, $type['id'])->getResult();
      $orders = $Order->get_all_cart_ignore_type($pid, $type['id'])->getResult();

			foreach($orders as $order) {
				Order::populateProduct($order, $this->priceList, $user_info, 0);
				if(!empty($order->product)) {
					$type['orders'][] = $order;
				}
			}
		}
		$this->data["types"] = $types;

		$cart = Order::get_cart_info($pid);
    $this->data['total_quantity']   = $cart['total_quantity'];
		$this->data['total_amount']     = $cart['total_amount'];
		$this->data['total_epoints']    = $cart['total_epoints'];
		$this->data['delivery_charge']  = $cart['total_quantity'] == 0 ? "0.00" : $cart['delivery_charge'];
		$this->data['total_vats']       = $cart['total_vats'];

		$this->data['form_width'] = $this->get_form_width();
	    
	  $this->data["slides"] = $Admin->get_scount('slides');
		$this->data['unknown_products'] = $UnknownProduct->get_all_products($user_info->username);

    // filter only 10 product--------------
    foreach($types as &$type) {
			// $type['lines'] = $Order->get_lines($pid, $type['id']);
			$type['lines'] = $Order->get_lines_ignore_type($pid, $type['id']);

			// $type['items'] = $Order->get_items($pid, $type['id']);
			$type['items'] = $Order->get_items_ignore_type($pid, $type['id']);


			// $orders = $Order->get_limited_cart($pid, $type['id'])->getResult();
			$orders = $Order->get_limited_cart_ignore_type($pid, $type['id'])->getResult();

      $type['orders'] = [];
			foreach($orders as $order) {
				Order::populateProduct($order, $this->priceList, $user_info, 0);
				if(!empty($order->product)) {
					$type['orders'][] = $order;
				}
			}
		}
		$this->data["types"] = $types;
    // ------------------------------------

		if($page == 'checkout') {
			return view('v2/pages/myaccount/checkout', $this->data);
		} else if($page == 'payment') {
			return view('v2/pages/myaccount/payment', $this->data);
		} else {
			if(request()->isAJAX()) {
        $this->data["cls"] = "my-cart-body-limited";
				return view('v2/partials/my_cart_content' , $this->data);
			} else {
        $this->data["cls"] = "";
				return view('v2/pages/orders' , $this->data);
			}
		}
	}

	public function checkout()
	{
		return $this->index('checkout');
	}

	public function payment()
	{
		return $this->index('payment');
	}

	function search()
	{
	}

	function suggest()
	{
	}

	function get_row()
	{
	}

	function view($data_item_id=-1)
	{
	}

	function save($data_item_id=-1)
	{
	}

	function delete()
	{
	}

	function get_form_width()
	{
		return 350;
	}

	function get_product($prod_id)
	{
		$Order = new Order();

		return $Order->get_product($prod_id);
	}

	function to_cart_quantity()
	{
		$Employee = new Employee();
		$Order = new Order();
		$Admin = new Admin();

		$type = request()->getPost('type');
		$mode = request()->getPost('mode');
		$prod_code = request()->getPost('prod_code');
		$quantity = request()->getPost('quantity');
		$user_info = $Employee->get_logged_in_employee_info();
		$result = $Order->to_cart_quantity($prod_code , $mode , $user_info->person_id , $quantity, $type);

		// Fetch Image Host
		$img_host = $Admin->get_plink('img_host');
		$table_data = session()->get('is_mobile') == "1" ?
			get_cart_order_manage_table_mobile($Order->get_all_cart($user_info->person_id, $type), $this->priceList, $type , $this, $img_host) : 
			get_cart_order_manage_table($Order->get_all_cart($user_info->person_id, $type), $type , $this, $img_host);
		echo $table_data;
	}


	function add_another_item()
	{
		return redirect(base_url("products"));
	}

	function cartinfo()
	{
		$Employee = new Employee();
		$user_info = $Employee->get_logged_in_employee_info();
		$cart = Order::get_cart_info($user_info->person_id);
		return response()->setJSON($cart);
	}
	
	function get_total_items_cart($type='general')
	{
		$id = $this->input->post('id');
		//$user_info = $this->Employee->get_logged_in_employee_info();
		$data = $this->Order->get_count_cart_products($id, $type);
		return $data;
	}


	function save_for_later($type='general')
	{
		$Employee = new Employee();
		$Order = new Order();
		$user_info = $Employee->get_logged_in_employee_info();
		if($Order->get_count_cart_products($user_info->person_id, $type) == 0)
			echo 100;
		else echo $Order->save_for_later($user_info->person_id , 0, $type);
	}

	function send_order($type='general')
	{
		$db = \Config\Database::connect();
		$Employee = new Employee();
		$Order = new Order();

		$user_info = $Employee->get_logged_in_employee_info();
		if($Order->get_count_cart_products($user_info->person_id, $type) == 0)
		{
			echo 100;
			return;
		}
		
		$res = $Order->save_for_later($user_info->person_id , 1, $type);
		
		if($res != true)
		{
			echo "Failed: ".$res;
			return;
		}
        $datetime=date('dmY_His',time());
  		$q = $db->table('epos_orders');
		  $q->where('person_id' , $user_info->person_id);
		  $q->where('type' , $type);
		  $q->where('opened' , 1);
		  $q->where('presell' , 0);
		  $q->orderBy('order_id','desc');
		  $q->limit(1);
		$row = $q->get()->getRow();
		$order_id = $row->order_id;
		$epos = $row->epos;
        //$order_id = $this->db->get()->row()->order_id;
		if($epos == 0){ $origin = 'wo2'; } else { $origin = 'eo'; }
        $file_name = substr("00000".$user_info->username,-5).'_'.$datetime.'_'.$origin.'-'.$order_id.'_';
    	srand((double)microtime()*1000000);
			while(1)
			        {
				     $l = rand(48 , 122);
                     if (($l>57 && $l<65) || ($l>90&&$l<97)) continue;
                     $file_name .= chr($l);
				     if (strlen($file_name)>37) break;
                    }
        $file_name .='_'.ucfirst($type) . ($type=='spresell' ? '.pre' : '.ord');
		$first_line = $Order->get_order_file_data($user_info->person_id , 1, $type);
		if($first_line < 0)
		{
			echo $first_line;
			return;
		}
		$file_data = $Order->get_order_file_data($user_info->person_id , 2, $type);
        $vv=substr($file_data,-3);
        $file_data = substr($file_data,0,strlen($file_data)-3);
        if($file_data < 0)
		{
			echo $file_data;
			return;
		}
		$file_data = $first_line.$file_data;
		//$file_path = "/home/uws003/public_html/temp/".$file_name;
		$file_path = FCPATH . 'temp/' . $file_name;
		
		//$file_path = "/home/staging/public_html/temp/".$file_name; // --- SWAP
		if(!write_file($file_path, $file_data))
		{
			echo -103;
			//echo getcwd();
			return;
		}
        if (!$db->query("UPDATE epos_orders SET filename='".$file_name."', type='".$type."', order_date='".date('Ymd',time())."', order_time='".substr($datetime,-6)."' WHERE order_id=".$order_id))
        {
            echo -104;
            return;
        }
		if(!$Order->close_and_complete_order($user_info->person_id, $type))
		{
			echo -105;
			return;
		}

        $db->query('DELETE FROM epos_orders WHERE opened=1 and type="'.$type.'" and person_id='.$user_info->person_id);

		$addr_mail = $Order->from_addr_mail();
		$send_message = $Order->from_message_mail($user_info->person_id, $order_id, 0, $type=='spresell');
		$mail_subject = lang('orders_email_subject').$user_info->username.' ['.ucfirst($type).'] order id : '.$origin.'-'.$order_id;
		if($type == 'spresell') $mail_subject = "SEASONAL PRESELL ORDER! " . $mail_subject;

		EmailService::send($addr_mail['email_addr'], 'mh@uniteduk.com', $addr_mail['company_name'], $mail_subject, $send_message);

		// Second email
		EmailService::send($addr_mail['email_addr'], 'yasirikram@gmail.com', $addr_mail['company_name'], $mail_subject, $send_message);

		if($type != 'spresell') {
			///////////////////////////////////////////////// --- FTP Start
			$ftp_stream = ftp_connect('order2.uniteduk.co.uk');
			//$ftp_stream = ftp_connect('staging456.uniteduk.co.uk'); // --- SWAP
			if ($ftp_stream==false){ echo 'cannot connect to orders server'; return; }
			
			$login_stat = ftp_login($ftp_stream,'yasir@order2.uniteduk.co.uk','Yasir123$%^');
			//$login_stat = ftp_login($ftp_stream,'staging','tWG8y&ZLtZ)9E0&pQ#CSU1Zn');  // --- SWAP
			if ($login_stat==false){ echo 'cannot log in to orders server'; ftp_close($ftp_stream); return; }
			
			//$file_ul=ftp_put($ftp_stream,'epos_link_files/ordersin/'.$file_name,'/home/uws003/public_html/temp/'.$file_name,FTP_BINARY);
			// echo FCPATH.'temp/'.$file_name;
			// exit;
			$file_path = FCPATH . 'tempftp/' . $file_name;
			if(write_file($file_path, $file_data))
			{
				// echo  FCPATH.'tempftp/'.$file_name;
				// exit;
				//$file_ul = ftp_put($ftp_stream, FCPATH.'tempftp',  FCPATH.'temp/'.$file_name, FTP_BINARY);
			}
			
			//$file_ul = ftp_put($ftp_stream,'public_html/temp_live/ordersin/'.$file_name,'/home/staging/public_html/temp/'.$file_name,FTP_BINARY); // --- SWAP
			// if ($file_ul==false){ echo 'unable to write ORDER file'; ftp_close($ftp_stream); return; }
			
			ftp_close($ftp_stream);
			//////////////////////////////////////////////// --- FTP End
		}
		
		echo "Send Order success.";
        return;
    }
	
	////////////////////////////////////////////////
	// Manually Generate Duplicate Order Emails
	function resend_orders($start,$end = ""){	
	    // When only one order is passed
		if($start != "" && $end == ""){$end = $start;}	
		
	    // first check user is admin
		$Employee = new Employee();
		$Order = new Order();
		$db = \Config\Database::connect();
		
		try {
			$logged_in_employee_info = $Employee->get_logged_in_employee_info();
			if($logged_in_employee_info->username == "admin"){
				// Loop through the order number range
				for($i=$start; $i<=$end; $i++){
					$q = "SELECT * FROM epos_orders AS o, epos_employees AS e WHERE o.order_id=".$i." AND o.completed='1' AND o.person_id = e.person_id ORDER BY o.order_id DESC";
					$res = $db->query($q);
					if($res->getNumRows() > 0){
						foreach($res->getResult() as $r){
							// To send email
							$person_id = $r->person_id;
							$username  = $r->username;
							$order_id  = $r->order_id;
							$epos      = $r->epos;
							$presell   = $r->presell;
							$date      = $r->order_date; 
							$time      = $r->order_time; 
							if($epos == 0){ $origin = 'wo2'; } else { $origin = 'eo'; }
							$send_message = $Order->from_message_mail($person_id , $order_id, $presell);
							$addr_mail = $Order->from_addr_mail();
							if($presell == 1){ $presell = "Presell"; }else{ $presell = ""; }
							$ord_date = substr($date , 0 , 4)."/".substr($date , 4 , 2)."/".substr($date , 6 , 2);
							$ord_time = substr($time , 0 , 2).":".substr($time , 2 , 2).":".substr($time , 4 , 2);
							$mail_subject = lang('Main.orders_email_subject').$username.' '.$presell.' order id : '.$origin.'-'.$order_id.' - DATE: '.$ord_date.' '.$ord_time.' - COPY';
	
							EmailService::send($addr_mail['email_addr'], 'freelanceferif@gmail.com', $addr_mail['company_name'], $mail_subject, $send_message);

							// To send ftp
							$first_line = $Order->get_order_file_data1($r, 1);
							if($first_line < 0)
							{
								echo $first_line;
								return;
							}
							$file_data = $Order->get_order_file_data1($r, 2);
							$vv=substr($file_data,-3);
							$file_data = substr($file_data,0,strlen($file_data)-3);
							if($file_data < 0)
							{
								echo $file_data;
								return;
							}
							$file_data = $first_line.$file_data;
							$file_path = FCPATH . 'temp/' . $r->filename;
							
							if(!write_file($file_path, $file_data))
							{
								echo -103;
								return;
							}

							$ftp_stream = ftp_connect('localhost');
							if ($ftp_stream==false){ echo 'cannot connect to orders server'; return; }
							
							$login_stat = ftp_login($ftp_stream,'test','test');
							if ($login_stat==false){ echo 'cannot log in to orders server'; ftp_close($ftp_stream); return; }
							
							// $file_ul = ftp_put($ftp_stream,'public_html/temp_live/ordersin/'.$file_name,'/home/staging/public_html/temp/'.$file_name,FTP_BINARY); // --- SWAP
							$file_ul = ftp_put($ftp_stream,'/test/'.$r->filename, $file_path, FTP_BINARY); // --- SWAP
							ftp_close($ftp_stream);
						}
					}else{
						echo "[".$i."] order not found -----<br />";	
					}
				}
			}else{
			   echo "Access Denied";
			}
		} catch (\Exception $e) {
			echo "error occured.";
		}
	}
	
	/*
	// Manually Generate Duplicate Order Emails 31853 - 32067 (27th Jan til date)
	function resend_orders($start,$end){		
		// first check user is admin
		$this->load->model('Employee');
		$logged_in_employee_info = $this->Employee->get_logged_in_employee_info();
		if($logged_in_employee_info->username == "admin"){
			for($i=$start; $i<=$end; $i++){
				//$q = "SELECT * FROM epos_orders WHERE order_id=".$i." AND completed='1' ORDER BY order_id DESC";
				$q = "SELECT * FROM epos_orders, epos_employees WHERE epos_orders.order_id=".$i." AND epos_orders.completed='1' AND epos_orders.person_id = epos_employees.person_id ORDER BY order_id DESC";			
				$r = $this->db->query($q);
				if($r->num_rows() > 0){
					foreach($r->result() as $res){
						$person_id = $res->person_id;
						$username = $res->username;
						$order_id = $res->order_id;
						$epos     = 
						$presell = $res->presell;
						$send_message = $this->Order->from_message_mail($person_id , $order_id, $presell);
						$addr_mail = $this->Order->from_addr_mail();
						if($presell == 1){ $presell = "Presell"; }else{ $presell = ""; }
						$ord_date = substr($res->order_date , 0 , 4)."/".substr($res->order_date , 4 , 2)."/".substr($res->order_date , 6 , 2);
						$ord_time = substr($res->order_time , 0 , 2).":".substr($res->order_time , 2 , 2).":".substr($res->order_time , 4 , 2);
						$mail_subject = $this->lang->line('orders_email_subject').$username.' '.$presell.' order id : '.$origin.'-'.$order_id.' - DATE: '.$ord_date.' '.$ord_time.' - COPY';
						$config_mailtype['mailtype'] = "html";
						$this->email->initialize($config_mailtype);
						$this->email->from($addr_mail['email_addr'], $addr_mail['company_name']);
						//$this->email->to('telesales@uniteduk.com');
						$this->email->to('yasirikram@gmail.com'); // --- SWAP
						$this->email->subject($mail_subject);
						$this->email->message($send_message);
						$this->email->send();
						$this->email->clear();	
						echo $origin."-".$order_id." order email sent<br />";
					}
				}else{
						echo $origin."-".$i." order not found -----<br />";	
				}
			}
		}else{
		   echo "Access Denied";
		}
	}
	*/

	function excel_import()
	{
		echo view("orders/excel_import", null);
	}

	function do_excel_import()
	{
		$Employee = new Employee();
		$Order = new Order();

		$db = \Config\Database::connect();

		$user_info = $Employee->get_logged_in_employee_info();
		$is_empty = request()->getPost('empty_trolley')=='' ? 0:1;
        $j=0;
        if($is_empty == 1)
		{
			$Order->empty_cart($user_info->person_id);
		}
		$msg = 'File Import Error.';
		$failCodes = array();
		if ($_FILES['file_path']['error']!=UPLOAD_ERR_OK)
		{
			$msg = lang('Main.products_excel_import_failed');
			echo array('success'=>false, 'message'=>$msg);
			return;
		}
		else
		{
			if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== FALSE)
			{

				$i = 0;
				while (($data = fgetcsv($handle)) !== FALSE)
				{
                    if (strlen($data[0])>39) {
				                               echo array('success'=>false,'message'=>'The barcode data is invalid.');
                                       		   redirect(base_url("orders"));
                                               return;
                                             }
                    
                    $barcode = $db->escapeLikeString(iconv("Windows-1252" , "UTF-8//IGNORE" , preg_replace("/[^0-9]/", "",substr($data[0],0,16))));

					if($Order->save_excel($barcode , $user_info))
					{
						$j ++;

					}
					else
					{
						$failCodes[] = $barcode;
					}

				}

				$i++;

			}
			else
			{
				echo array('success'=>false,'message'=>'The file has no data. Please try again.');
				return;
			}

		}


		$success = true;
		if(count($failCodes) > 1)
		{
			$msg = "Imported barcodes from FOB.".count($failCodes)." barcodes were not identified :".implode(", ", $failCodes);
			$success = false;
		}
		else
		{
			$msg = "Import products successful";
		}

		return redirect(base_url("orders"));

	}

}
?>
