<?php
namespace App\Models;
use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;
class Order extends Model
{
	protected $table            = 'epos_orders';
    protected $primaryKey       = 'order_id';

	function get_all_cart($person_id, $type='general')
	{
		$db = \Config\Database::connect();
		$branch = session()->get('branch');
		$organization_id = session()->get('organization_id');

		$query = "SELECT * FROM epos_cart 
				  WHERE person_id={$person_id} AND branch={$branch} ";
		if (!empty($organization_id)) {
			$query .= "AND organization_id={$organization_id} ";
		}
		$query.= "AND group_type='{$type}' ";
		$query.= "ORDER BY line_position DESC";

		return $db->query($query);
	}

  function get_all_cart_ignore_type($person_id, $type='general')
	{
		$db = \Config\Database::connect();
    $branch = session()->get('branch');
    $organization_id = session()->get('organization_id');

		// $query = "SELECT * FROM epos_cart WHERE person_id={$person_id} AND group_type='{$type}' ORDER BY line_position DESC";
		$query =  " SELECT * FROM epos_cart WHERE person_id={$person_id} " . 
              " AND branch={$branch} ";
    if (!empty($organization_id)) {
    $query .= " AND organization_id={$organization_id} ";
    }
    $query .= " ORDER BY line_position DESC";

		return $db->query($query);
	}

  function get_limited_cart($person_id, $type='general')
	{
		$db = \Config\Database::connect();
    $branch = session()->get('branch');
    $organization_id = session()->get('organization_id');

		$query =  " SELECT * FROM epos_cart WHERE person_id={$person_id} " . 
              " AND branch={$branch} ";
    if (!empty($organization_id)) {
    $query .= " AND organization_id={$organization_id} ";
    }
    $query .= " AND group_type='{$type}' ";
    $query .= " ORDER BY line_position DESC LIMIT 10";

		return $db->query($query);
	}

  function get_limited_cart_ignore_type($person_id, $type='general')
	{
		$db = \Config\Database::connect();
    $branch = session()->get('branch');
    $organization_id = session()->get('organization_id');

		// $query = "SELECT * FROM epos_cart WHERE person_id={$person_id} AND group_type='{$type}' ORDER BY line_position DESC LIMIT 10";
		$query =  " SELECT * FROM epos_cart WHERE person_id={$person_id}" . 
              " AND branch={$branch} "; 
    if (!empty($organization_id)) {
    $query .= " AND organization_id={$organization_id} ";
    }
    $query .= " ORDER BY line_position DESC LIMIT 10";

		return $db->query($query);
	}

	function get_lines($person_id, $type='general', $presell=0)
	{
		$db = \Config\Database::connect();
		$branch = session()->get('branch');
		$organization_id = session()->get('organization_id');

		$builder = $db->table('epos_cart');
		$builder->where('person_id', $person_id);
		$builder->where('presell', $presell);
		$builder->where('group_type', $type);
		$builder->where('branch', $branch);
		if (!empty($organization_id)) {
			$builder->where('organization_id', $organization_id);
		}
		$builder->groupBy('prod_code');

		$result = $builder->get();
		$numRows = $result->getNumRows();

		return $numRows;		
	}

  function get_lines_ignore_type($person_id, $type='general', $presell=0)
	{
		$db = \Config\Database::connect();
		$branch = session()->get('branch');
		$organization_id = session()->get('organization_id');

		$builder = $db->table('epos_cart');
		$builder->where('person_id', $person_id);
		$builder->where('presell', $presell);
		$builder->where('branch', $branch);
		if (!empty($organization_id)) {
			$builder->where('organization_id', $organization_id);
		}
	
		// $builder->where('group_type', $type);
		// $builder->groupBy('prod_code');
		$result = $builder->get();
		$numRows = $result->getNumRows();

		return $numRows;		
	}

	function get_items($person_id, $type='general', $presell=0)
	{
		$db = \Config\Database::connect();
		$branch = session()->get('branch');
		$organization_id = session()->get('organization_id');

		$builder = $db->table('epos_cart');
		$builder->selectSum('quantity');
		$builder->where('person_id', $person_id);
		$builder->where('presell', $presell);
		$builder->where('group_type', $type);
		$builder->where('branch', $branch);
		if (!empty($organization_id)) {
			$builder->where('organization_id', $organization_id);
		}
		
		$result = $builder->get()->getRow();
		$quantitySum = $result->quantity ?? 0; 

		return $quantitySum;
	}

  function get_items_ignore_type($person_id, $type='general', $presell=0)
	{
		$db = \Config\Database::connect();
		$branch = session()->get('branch');
		$organization_id = session()->get('organization_id');

		$builder = $db->table('epos_cart');
		$builder->selectSum('quantity');
		$builder->where('person_id', $person_id);
		$builder->where('presell', $presell);
		$builder->where('branch', $branch);
		if (!empty($organization_id)) {
			$builder->where('organization_id', $organization_id);
		}
	
		// $builder->where('group_type', $type);
		// $builder->groupBy('prod_code');
		$result = $builder->get()->getRow();
		$quantitySum = $result->quantity ?? 0; 

		return $quantitySum;
	}


	function get_count_cart_products($person_id, $type, $presell=0)
	{
		$db = \Config\Database::connect();
		$branch = session()->get('branch');
		$organization_id = session()->get('organization_id');

		$builder = $db->table('epos_cart');
		$builder->where('person_id', $person_id);
		$builder->where('group_type', $type);
		$builder->where('presell', $presell);
		$builder->where('branch', $branch);
		if (!empty($organization_id)) {
			$builder->where('organization_id', $organization_id);
		}
		$builder->groupBy('prod_code');

		$count = $builder->countAllResults();
		return $count;
	}

	function get_product($prod_id)
	{
		$db = \Config\Database::connect();

		$select = "p.*, pi.url as image_url, pi.version as image_version";
        $builder = $db->table('epos_product p')
					->select($select)
					->join('epos_product_images pi', 'CAST(SUBSTRING(p.prod_code, 2, 6) AS UNSIGNED)=pi.prod_code', 'left')
					->where('prod_id' , $prod_id);
		return $builder->get()->getRow();
	}

	function get_products_by_order($db, $order_id)
	{
		$db = $db ? $db : \Config\Database::connect();

		$select = "*";
        $q = $db->table('epos_orders_products')
				->select($select)
				->where('order_id' , $order_id)
				->get();
		return $q->getRow();
	}

	function available_product($db, $prod_code, $branch, $org_id) 
	{
		$db = $db ? $db : \Config\Database::connect();

		$select = "*";
        $q = $db->table('epos_product')
				->select($select)
				->where('prod_code', $prod_code)
				->where('branch', $branch)
				->where('organization_id', $org_id)
				->where('is_disabled', 'N')
				->where(new RawSql("(availability IS NULL OR availability != 'N')"))
				->get();
		return $q->getNumRows() > 0;	
	}

	function exist_in_cart_by_item($db, $person_id, $prod_code, $prod_type, $branch, $org_id)
	{
		$db = $db ? $db : \Config\Database::connect();

		$select = "*";
        $q = $db->table('epos_cart')
				->select($select)
				->where('prod_code', 		$prod_code)
				->where('group_type', 		$prod_type)
				->where('person_id', 		$person_id)
				->where('branch', 			$branch)
				->where('organization_id', 	$org_id)
				->get();
		return $q->getNumRows() > 0;	
	}
	function add_to_cart_by_item($db, $person_id, $prod_code, $prod_type, $branch, $org_id, $qty)
	{
		$db = $db ? $db : \Config\Database::connect();

		$exist_item_in_cart = $this->exist_in_cart_by_item($db, $person_id, $prod_code, $prod_type, $branch, $org_id);

		$db->transStart();
		if ($exist_item_in_cart)
		{
			$db->query( "UPDATE epos_cart ".
						"SET quantity={$qty} ".
						"WHERE prod_code={$prod_code} AND group_type='{$prod_type}' AND person_id={$person_id} ".
						"AND branch='{$branch}' AND organization_id='{$org_id}'" );
		} else {
			// get item type like general, tobacco, ...
			$type = $prod_type;
			// $query = "SELECT ct.type FROM epos_product as p LEFT JOIN epos_categories as ct on p.group_desc = ct.filter_desc ".
            //          "WHERE p.prod_code={$prod_code} AND p.branch='{$branch}' AND p.organization_id='{$org_id}' ".
			// 		 "AND p.is_disabled='N' AND (p.availability IS NULL OR p.availability != 'N') AND ct.display=1 ";
			// $result = $db->query($query);
			// if ($result->getNumRows() > 0) {
			// 	$type = $result->getResult()[0]->type;
			// }

			// get line_position for sort of product in cart
			$line_position = 0;
        	$query = $db->table('epos_cart')
						->select('line_position')
						->orderBy('line_position','desc')
						->get();
			if ($query->getNumRows() > 0) {
				$line_position = $query->getResult()[0]->line_position;
			}
			$line_position = (int)$line_position + 1;

			// ADD TO CART
			$cart_data = array(
				'prod_code'			=> $prod_code,
				'quantity'			=> $qty,
				'group_type'		=> $type,
				'line_position'		=> $line_position,
				'person_id'			=> $person_id,
				'branch'			=> $branch,
				'organization_id'	=> $org_id
			);
			$db->table('epos_cart')->insert($cart_data);
		}
		$db->transComplete();
		if ($db->transStatus() === FALSE)
			return false;
		else
			return true;
	}

	function getFTPcredential()
	{
		$db = \Config\Database::connect();
		$branch = session()->get('branch');
		$ftp_credential = array();
		
		// get FTP credential based on branch
		$result = $db->table('epos_branches_ordering_config')->where('branch' , $branch)->get()->getRow();
		if (!empty($result->cc_email)) 		{ $ftp_credential['cc_email'    ] = $result->cc_email; 	    }
		if (!empty($result->from_email))	{ $ftp_credential['from_email'  ] = $result->from_email; 	}

		if (!empty($result->smtp_server))	{ $ftp_credential['smtp_server'  ] = $result->smtp_server; 	}
		if (!empty($result->smtp_username))	{ $ftp_credential['smtp_username'] = $result->smtp_username;}
		if (!empty($result->smtp_password))	{ $ftp_credential['smtp_password'] = $result->smtp_password;}

		if (!empty($result->ftp_host)) 		{ $ftp_credential['ftp_host'	] = $result->ftp_host; 		}
		if (!empty($result->ftp_path)) 		{ $ftp_credential['ftp_path'	] = $result->ftp_path; 		}
		if (!empty($result->ftp_username)) 	{ $ftp_credential['ftp_username'] = $result->ftp_username; 	}
		if (!empty($result->ftp_password)) 	{ $ftp_credential['ftp_password'] = $result->ftp_password; 	}

		// get FTP credential from app config
		$result = $db->table('epos_app_config')->where('key' , 'ftp_host')->get()->getRow();
		if (empty($ftp_credential['ftp_host']) && !empty($result->value)) { 
			$ftp_credential['ftp_host'] = $result->value; 		
		}

		$result = $db->table('epos_app_config')->where('key' , 'ftp_path')->get()->getRow();
		if (empty($ftp_credential['ftp_path']) && !empty($result->value)) { 
			$ftp_credential['ftp_path'] = $result->value; 		
		}

		$result = $db->table('epos_app_config')->where('key' , 'ftp_username')->get()->getRow();
		if (empty($ftp_credential['ftp_username']) && !empty($result->value)) { 
			$ftp_credential['ftp_username'] = $result->value; 		
		}

		$result = $db->table('epos_app_config')->where('key' , 'ftp_password')->get()->getRow();
		if (empty($ftp_credential['ftp_password']) && !empty($result->value)) { 
			$ftp_credential['ftp_password'] = $result->value; 		
		}

		// get FTP credential by default
		if (empty($ftp_credential['ftp_host'])) { 
			$ftp_credential['ftp_host'] = 'order2.uniteduk.co.uk'; 		
		}
		if (empty($ftp_credential['ftp_path'])) { 
			$ftp_credential['ftp_path'] = 'tempftp'; 		
		}
		if (empty($ftp_credential['ftp_username'])) { 
			$ftp_credential['ftp_username'] = 'yasir@order2.uniteduk.co.uk'; 		
		}
		if (empty($ftp_credential['ftp_password'])) { 
			$ftp_credential['ftp_password'] = 'Yasir123$%^'; 		
		}

		// remove trailing slash from ftp_path
		if (!empty($ftp_credential['ftp_path'])) {
			$ftp_credential['ftp_path'] = trim($ftp_credential['ftp_path'], "/\\ \t\n\r\0\x0B");
		}

		return $ftp_credential;
	}

	function from_addr_mail()
	{
		$db = \Config\Database::connect();
		$result = $db->table('epos_app_config')->where('key' , 'email')->get()->getRow();
		$mail_addr = $result->value;

		$result = $db->table('epos_app_config')->where('key' , 'company')->get()->getRow();
		$company_name = $result->value;

		$result = $db->table('epos_app_config')->where('key' , 'seller_mail_addr')->get()->getRow();
		$seller_mail_addr = $result->value;

		$addr = array('email_addr' => $mail_addr , 'company_name' => $company_name , 'seller_mail_addr' => $seller_mail_addr);
		return $addr;
	}

	function from_message_mail($person_id=0, $order_id=0, $type="general", $delivery_method="#pane-pickup-depot", $delivery_date="", $presell=0, $spresell=0)
	{
		$db = \Config\Database::connect();

    	$query = "SELECT p.*, op.order_id, op.quantity, op.group_type, op.price FROM epos_orders_products as op";
		$query .= " LEFT JOIN epos_product as p on op.prod_code=p.prod_code";
		$query .= " WHERE order_id=".$order_id." AND group_type='".$type."' GROUP BY op.prod_code ORDER BY p.prod_desc ASC, p.prod_uos ASC";
		$results_cart = $db->query($query);

		$nCount = 0;
        if($presell==1){ $presell=" Presell "; }else{ $presell = " "; }
		$message = "<html><body>";
		if($spresell == 1) {
			$message .= "<span style='font-family:Arial; font-size:18px;'><b>THIS IS A SEASONAL PRESELL ORDER!<b><p>We will notify you when your stock is available for Collection or Delivery.</br><p></span>";
		}
		$message .= "<span style='font-family:Arial; font-size:13px;'>";
		$message .= "Thank you for your".$presell."order. </br>Your order ref is wo-".$order_id.".</br> Please note prices are shown for guidance only and exclude VAT. ".html_entity_decode("e&oe")."<span>";//e&amp;oe
		
		$message .= "<div style='font-family:Arial; font-size:13px; font-weight:bold;'>";
		$message .= "This order is for your " . ucfirst($type) . " trolley.";
		$message .= '</div>';

		$message .= "<div style='font-family:Arial; font-size:13px; font-weight:bold;'>";
		$message .= "It is expected to be ready for ";
		if ($delivery_method == "#pane-pickup-depot") {
			$message .= "Collection ";
		} else {
			$message .= "Delivery ";
		}
		$message .= 'on ' . $delivery_date;
		$message .= '</div>';

		$message .= "<div style='font-family:Arial; font-size:13px; font-weight:bold;'>";
		$message .= 'If you need to make a payment, please ensure it is done promptly to avoid delays.';
		$message .= '</div>';

		$message .= "<div style='font-family:Arial; font-size:13px; font-weight:bold;'>";
		$message .= 'We reserve the right to charge for restocking items where a customer has failed to make a payment, collect or refuses delivery.';
		$message .= '</div>';

		$message .= "<table cellspacing='1px' style='width:98%; border: 1px solid #ccc;'>";
		$message .= "<thead><tr style='background-color:#11ccdd;'><th>No</th><th>Product</th><th>Description</th><th>Size</th><th>UOS</th><th>Price</th><th>Qty</th><th>Total</th></tr></thead>";
		$message .= "<tbody>";
		$total_amount = 0;
		$total_quantity = 0;
		foreach($results_cart->getResult() as $res_cart)
		{
			if($res_cart->quantity > 0){
				$nCount ++;
				
				$line_total = number_format($res_cart->price * $res_cart->quantity,2,'.','');
				$total_amount = $total_amount + $line_total;
				$total_quantity = $total_quantity + $res_cart->quantity;
				
				$time = $res_cart->price_end + ((23* 3600) + 3599);
				if( (time()>=$res_cart->price_start && time() <= $time) || $res_cart->price_start == 0 ){
					   $sell = $res_cart->price; $ltotal = $line_total;
				}else{ $sell = 'Call For Price'; $ltotal = 'Call For Price'; }
				
				$epoints = '';
				if($res_cart->epoints != 0){$epoints = '<br />'.$res_cart->epoints.' <img src="http://m9order.uniteduk.co.uk/images/epoints.png" width="35px" style="margin-bottom:0px; width:35px !important; height:13px !important">';}
				
				$message .= "<tr>";
				$message .= "<td style='width:5%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'> ".$nCount."</td>";
				$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_cart->prod_code."</td>";
				//$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_cart->prod_code."".$epoints."</td>";
				$message .= "<td style='width:40%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_cart->prod_desc."</td>";
				$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_cart->prod_pack_desc."</td>";
				$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_cart->prod_uos."</td>";
				$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$sell."</td>";
				$message .= "<td style='width:5%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_cart->quantity."</td>";
				$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$ltotal."</td>";
				$message .= "</tr>";
			}
		}
		$message .= "<tr style='background-color:#EEEEEE;'><td>&nbsp;</td><td stype='padding-top:5px'>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
		//$message .= "<tr style='background-color:#EEEEEE;'><td>&nbsp;</td><td stype='padding-top:5px'>".$total_epoints." <img src='http://m9order.uniteduk.co.uk/images/epoints.png' width='35px' style='margin-bottom:0px; width:35px !important; height:13px !important'></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
		$message .= "<td stype='padding-top:5px'>Total</td><td stype='padding-top:5px'><b>".$total_quantity."</b></td><td stype='padding-top:5px'><b>£".$total_amount."</b></td></tr>";
		$message .= "</table></body></html>";
		return $message;
	}
	
/*	function from_message_mail($person_id=0,$order_id=0)
	{
        $query="SELECT * FROM epos_orders_products WHERE order_id=".$order_id." GROUP BY prod_code ORDER BY prod_desc ASC, prod_uos ASC";
		$results_cart = $this->db->query($query);

		$nCount = 0;

		$message = "<html><body>";
		$message .= "<span style='font-family:Arial; font-size:18px;'>";
		$message .= "Thank you for your order. </br>Your order ref is wo-".$order_id.".</br> Please note prices are shown for guidance only and exclude VAT. e&amp;oe<span>";
		$message .= "<table cellspacing='1px' style='width:98%; border-left: 1px solid gray; border-right:1px solid gray; border-bottom:2px solid gray;'>";
		$message .= "<thead><tr style='background-color:#11ccdd;'><th>No</th><th>Product</th><th>Description</th><th>Size</th><th>UOS</th><th>Price</th><th>Qty</th><th>Total</th></tr></thead>";
		$message .= "<tbody>";
		$total_amount = 0;
		$total_quantity = 0;
		foreach($results_cart->result() as $res_cart)
		{
			$nCount ++;
			$message .= "<tr>";
			$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$nCount."</td>";
			$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_cart->prod_code."</td>";
			$message .= "<td style='width:30%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_cart->prod_desc."</td>";
			$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_cart->prod_pack_desc."</td>";
			$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_cart->prod_uos."</td>";
			$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_cart->prod_sell."</td>";
			$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$res_cart->quantity."</td>";
			$line_total = number_format($res_cart->prod_sell * $res_cart->quantity,2,'.','');
			$total_amount = $total_amount + $line_total;
			$total_quantity = $total_quantity + $res_cart->quantity;
			$message .= "<td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$line_total."</td>";
			$message .= "</tr>";
		}
		$message .= "<tr style='background-color:#EEEEEE;'><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
		$message .= "<td>Total</td><td style='text-align:right;'>".$total_quantity."</td><td style='text-align:right;'>".$total_amount."</td></tr>";
		$message .= "</table></body></html>";
		return $message;
	}*/

	function to_cart_quantity($prod_code , $mode , $person_id , $quantity = 1, $type='general')
	{
		$db = \Config\Database::connect();
    	$branch = session()->get('branch');
    	$organization_id = session()->get('organization_id');

		if($mode == 3)
		{
			$query = "DELETE FROM epos_cart WHERE person_id={$person_id} " . 
                	 "AND branch={$branch} ";
			if (!empty($organization_id)) {
				$query.= "AND organization_id={$organization_id} ";
			}
			$query.= "AND prod_code='{$prod_code}' AND presell=0 ";
			$query.= "AND group_type='{$type}' ";

			$db->transStart();
			$db->query($query);
			$db->transComplete();

			if ($db->transStatus() === FALSE)
				return -1;
			else
				return true;
		}

		$query = "SELECT * FROM epos_cart WHERE prod_code='" . $prod_code."' AND person_id=" . $person_id . " " .
              	 "AND branch={$branch} ";
		if (!empty($organization_id)) {
			$query.= "AND organization_id={$organization_id} ";
		}
		$query.= "AND presell=0 ";
		$query.= "AND group_type='{$type}' ";

		$res = $db->query($query);
		if($res->getNumRows() == 0) {
			if ($mode == 1 || $mode == 4) {
				$cart_data = array(
					'prod_code'=>$prod_code,
					'quantity'=>$quantity ,
					'person_id'=>$person_id,
          			'branch'=>$branch,
				);

				$db->transStart();
				$db->table('epos_cart')->insert($cart_data);
				$db->transComplete();
				if ($db->transStatus() === FALSE)
					return -1;
				else
					return 1;
			}
			else if ($mode == 2)
				return 0;
			else
				return -1;
		}
		else if ($res->getNumRows() == 1)
		{
			$res_row = $res->getRow();
			$quantity1 = $res_row->quantity;
			if($mode == 1)
				$quantity1 = $quantity1 + 1;
			else if ($mode == 2) {
				 if ($quantity1 > 0) {
					$quantity1 = $quantity1 - 1;
				 }
			}
			else if($mode == 4) {
				$quantity1 = $quantity;
			}

			if($quantity1 == 0) {
				$db->transStart();
				$query = "DELETE FROM epos_cart WHERE prod_code='{$prod_code}' AND person_id={$person_id} " . 
						 "AND branch={$branch} ";
				if (!empty($organization_id)) {
					$query.= "AND organization_id={$organization_id} ";
				}
				$query.= "AND presell=0 ";
				$query.= "AND group_type='{$type}' ";

				$db->query($query);
				$db->transComplete();
				if ($db->transStatus() === FALSE)
					return -1;
				else
					return true;
			} else {
				$cart_data = array('quantity'=>$quantity1);
				$db->transStart();
				$q = $db->table('epos_cart');
				$q->where('prod_code' , $prod_code);
				$q->where('person_id' , $person_id);
				$q->where('presell' , '0');
				$q->where('group_type', $type);
				$q->where('branch', $branch);

				if (!empty($organization_id)) {
					$q->where('organization_id', $organization_id);
				}
				$q->update($cart_data);
				$db->transComplete();
				if ($db->transStatus() === FALSE)
					return -1;
				else
					return true;
			}
		}
		else
			return -1;
	}
	
	public static function get_cart_info($person_id)
	{
		$db = \Config\Database::connect();

		$Employee = new Employee();
		$person_info = $Employee->get_info($person_id);
		$branch = session()->get('branch');
		$organization_id = session()->get('organization_id');

		$query = "SELECT * FROM epos_cart WHERE person_id={$person_id} AND branch={$branch} ";
		if (!empty($organization_id)) {
			$query .= "AND organization_id={$organization_id} ";
		}
		$query .= "AND presell=0 ORDER BY group_type";
		$results = $db->query($query);

		$cart_types = [];
		$total_lines 	= 0;
		$total_quantity = 0;
		$total_amount 	= 0;
		$total_epoints 	= 0;
    	$total_vats 	= 0;
		foreach($results->getResult() as $res)
		{
			$product = Product::getLowestPriceProductByCode($person_info, $res->prod_code, true, $res->group_type == 'spresell');
			if($product) {
				$total_lines    += 1;
				$total_quantity += $res->quantity;
				$total_amount 	+= $res->quantity * $product->prod_sell;
				$total_epoints 	+= $res->quantity * $product->epoints;
				$total_vats 	+= (($res->quantity * $product->prod_sell * $product->vat_rate) / 100);

				if( !in_array($res->group_type, array_keys($cart_types)) ) {
					$cart_types[$res->group_type] = [
						'lines' 	=> 1,
						'quantity' 	=> $res->quantity,
						'amount' 	=> $res->quantity * $product->prod_sell,
						'epoints' 	=> $res->quantity * $product->epoints,
						'vat' 		=> (($res->quantity * $product->prod_sell * $product->vat_rate) / 100)
					];
				} else {
					$cart_types[$res->group_type] = [
						'lines' 	=> $cart_types[$res->group_type]['lines'   ] + 1,
						'quantity' 	=> $cart_types[$res->group_type]['quantity'] + $res->quantity,
						'amount' 	=> $cart_types[$res->group_type]['amount'  ] + $res->quantity * $product->prod_sell,
						'epoints' 	=> $cart_types[$res->group_type]['epoints' ] + $res->quantity * $product->epoints,
						'vat' 		=> $cart_types[$res->group_type]['vat' 	   ] + (($res->quantity * $product->prod_sell * $product->vat_rate) / 100),
					];
				}
			}
		}

		return [
			'total_lines' 		=> $total_lines ?? 0,
			'total_quantity' 	=> $total_quantity ?? 0,
			'total_amount' 		=> $total_amount ?? 0,
			'total_epoints' 	=> $total_epoints ?? 0,
			'total_vats' 		=> $total_vats ?? 0,
			'cart_types'		=> $cart_types,
    		'delivery_charge' 	=> $person_info->delivery_charge ?? 0,
		];
	}
	
	function save_for_later($person_id , $opened, $type='general', $presell=0, $ref="", $payload=[], $passed_db=null)
	{
		if ($passed_db) {
			$db = $passed_db;
		} else {
			$db = \Config\Database::connect();
		}

		$Hom = new Hom();
		$Employee = new Employee();
		$u = $Employee->get_info($person_id);	
		$branch = session()->get('branch');
		$organization_id = session()->get('organization_id');
	
		$delivery_date = $payload['delivery_date'];
		$delivery_charge = $payload['delivery_charge'];
		$collection_container = !empty($payload['collection_container']) ? $payload['collection_container'] :'';
		$payment_method = $payload['payment_method'];
		$delivery_method = $payload['delivery_method'];
		if ($delivery_method == '#pane-via-delivery') {
			$delivery_method = 'via-delivery';
		} else {
			$delivery_method = 'from-depot';
		}

		$results = $db->table('epos_orders')
			->where('person_id' , $person_id)
			->where('opened' , 1)
			->where('type' , $type)
			->where('presell' , $presell)
			->where('branch', $branch)
			->where('organization_id', $organization_id)
			->orderBy('order_id','desc')
			->get();
		
		$order_data = array('person_id'  		=> $person_id ,
							'order_date' 		=> date("Ymd",time()),
							'order_time' 		=> date("His",time()),
							'filename'   		=> '',
							'completed'  		=> 0,
							'opened'     		=> $opened, 
							'type'       		=> $type,
							'presell'    		=> $presell,
							'branch'	 		=> $branch,
							'organization_id' 	=> $organization_id,
							'delivery_date'		=> $delivery_date,
							'delivery_charge'	=> $delivery_charge,
							'collection_container'	=> $collection_container,
							'payment_method'	=> $payment_method,
							'delivery_method' 	=> $delivery_method,
							);
		
		if ($results->getNumRows() == 0 || $presell == 1){
			if ( $Hom->get_total_items_cart($person_id, $type, $presell) >= 1 ){
				$db->transStart();
				$db->table($this->table)->insert($order_data);
				$order_id = $db->insertID();
				$db->transComplete();
				if ($db->transStatus() === FALSE) return -1;
			}
			else { return false;	}
		}
		else if ($results->getNumRows() > 0 && $presell == 0){
			$res = $results->getRow();
			$order_id = $res->order_id;
			$db->transStart();
			$db->table($this->table)->where('order_id' , $order_id)->update($order_data);
			$db->transComplete();
			if ($db->transStatus() === FALSE) return -2;
		}
		else if ($results->getNumRows() == 0 && $presell == 0){
			$db->transStart();
			$db->table($this->table)->insert($order_data);
			$order_id = $db->insertID();
			$db->transComplete();
			if ($db->transStatus() === FALSE) return -3;
		}
		
		$db->query("DELETE FROM epos_orders 
						 WHERE person_id = {$person_id} AND order_id != '{$order_id}' AND opened = 1 AND presell = {$presell} 
						 								AND branch = {$branch}  	  AND organization_id = {$organization_id}");
		$query = "DELETE FROM epos_orders_products 
				  WHERE order_id = '{$order_id}' AND presell = {$presell} AND branch = {$branch} AND organization_id = {$organization_id}";    
		$db->transStart();
		$db->query($query);
		$db->transComplete();
		if ($db->transStatus() === FALSE) return -4;

		$results1 = $db->query("SELECT * FROM epos_cart 
									 WHERE person_id='{$person_id}' AND presell={$presell} AND branch = {$branch} AND organization_id = {$organization_id}");		
		foreach ($results1->getResult() as $res1) {
			$found = 0;
			// TODO check for $ref cart item id in presell table, allow if $ref match.
			if ($ref != "") {			
				$builder = $db->table('epos_presell');
				$builder->where('period_ref',$ref);
				$builder->where('prod_code',$res1->prod_code);
				$builder->where('ordered','0');
				$builder->where('branch', $branch);
				$builder->where('organization_id', $organization_id);

				$q = $builder->get();
				if ($q->getNumRows() > 0) { 
					$f = str_pad($q->getRow()->price_list, 3, '0', STR_PAD_LEFT);
					$f = 'price_list'.$f;
					if($u->{$f} == 1){ $found = 1; }
				}
			} else { 
				$found = 1; 
			}

			if ($found == 1) {
				$product = Product::getLowestPriceProductByCode($u, $res1->prod_code, true, $res1->group_type == 'spresell');
				if ($product) {
					$order_product_data = array(
						'order_id' 			=> $order_id ,
						'quantity' 			=> $res1->quantity ,
						'prod_code'			=> $res1->prod_code ,
						'presell'			=> $res1->presell,
						'group_type' 		=> $res1->group_type,
						'price' 			=> $product->prod_sell,
						'branch'	 		=> $branch,
						'organization_id' 	=> $organization_id,
					);
					$db->transStart();
					$db->table('epos_orders_products')->insert( $order_product_data);
					$db->transComplete();
					if ($db->transStatus() == FALSE) return -5;
				}
			}
		}
		if ($ref == "") {
			$query = "DELETE FROM epos_cart WHERE person_id='{$person_id}' 
					  AND group_type='{$type}' AND presell={$presell} 
					  AND branch = {$branch}   AND organization_id = {$organization_id}";
			$db->transStart();
			$db->query($query);
			$db->transComplete();
			if($db->transStatus() == FALSE) return -6;
		}
		return true;
	}

	function get_order_file_data1($order, $option)
	{
		$db = \Config\Database::connect();
		//get first line
		if($option == 1) {
			$res = $db->table('epos_employees')->where('person_id' , $order->person_id)->get()->getRow();
			$account_number = substr('000000'.$res->username,-5);
			$order_date = substr($order->order_date,6,2).substr($order->order_date,4,2).substr($order->order_date,0,4);
			$order_time = $order->order_time;
			$first_line = $order_date;
			$first_line.= $order_time;
			$first_line.= $account_number;
			if ($order->presell == 1) { 
				$first_line .= " Presell_".$ref;
			}
			$first_line .= "\r\n";
			return $first_line;
		} 
		//get file data
		else if ($option == 2) {
			$order_id = $order->order_id;
            $result_vv = $db->query("SELECT count(*) as vv FROM epos_orders_products WHERE order_id='".$order_id."' and presell=".$order->presell."");
			$result_vv2 = $result_vv->getRow();
			$vv=substr('000'.$result_vv2->vv,-3);
            $vv_count=0;

            if ($order->presell == 1) { 
				$table = "presell"; 
			} else { 
				$table = "product"; 
			}
			$query = "SELECT p.*, op.quantity, op.price ";
			$query.= " FROM epos_orders_products as op ";
			$query.= " LEFT JOIN epos_{$table} as p on op.prod_code=p.prod_code ";
			$query.= " WHERE op.order_id={$order_id} AND op.presell={$order->presell}";
			
			$results = $db->query($query);
			$nCount = 1;
			$file_data = "";
			foreach ($results->getResult() as $res_prod) {
                $nCount1 = substr("000".$nCount,-3);
                $prod_code = substr("0000000".$res_prod->prod_code,-7);
                $quantity = substr("0000".$res_prod->quantity,-4);
				$price = substr("00000000".number_format($res_prod->price,2,'.',''),-8);
                if (strlen($price) > 8) {
					return -206;
				}
				$file_data = $nCount1.$prod_code.$quantity.$price."\r\n";
				$nCount ++;
				if ($nCount == 1000) {
				   $file_data ="over 999 !!!"."\r\n";
				   break;
				}
			}
			$file_data .= "EOF".$nCount."\r\n".$vv;
			return $file_data;
		}
	}

	function get_order_file_data($person_id, $option, $type='general', $presell=0, $ref="")
	{
		$db = \Config\Database::connect();
		$branch = session()->get('branch');
		$organization_id = session()->get('organization_id');

		// get first line
		if ($option == 1) {
			$res = $db->table('epos_employees')->where('person_id' , $person_id)->get()->getRow();
			$account_number = substr('000000'.$res->username,-5);
			$builder = $db->table($this->table);
			$builder->where('person_id' , $person_id);
			$builder->where('opened' , 1);
			$builder->where('type' , $type);
			$builder->where('presell' , $presell);
			$builder->where('branch', $branch);
			$builder->where('organization_id', $organization_id);
            $builder->orderBy('order_id','desc');
            $builder->limit(1);

            $result = $builder->get();
			if ($result->getNumRows() == 0) 
				return -201;

            $res = $result->getRow();
			$order_date = substr($res->order_date,6,2).substr($res->order_date,4,2).substr($res->order_date,0,4);
			$order_time = $res->order_time;

			$first_line = $order_date;
			$first_line.= $order_time;
			$first_line.= $account_number;

			if ($presell == 1){ 
				$first_line .= " Presell_".$ref;
			}
			
			$first_line .= "\r\n";
			return $first_line;
		}
		// get file data
		else if ($option == 2) {
			$builder = $db->table($this->table);
			$builder->where('person_id' , $person_id);
			$builder->where('opened' , 1);
			$builder->where('type' , $type);
			$builder->where('presell' , $presell);
			$builder->where('branch', $branch);
			$builder->where('organization_id', $organization_id);
            $builder->orderBy('order_id','desc');
            $builder->limit(1);

			$result = $builder->get();
			if ($result->getNumRows() == 0) 
				return -202;

			$res = $result->getRow();
			$order_id = $res->order_id;
            $result_vv = $db->query("SELECT count(*) AS vv 
										  FROM epos_orders_products 
										  WHERE order_id='{$order_id}' AND presell={$presell} 
										  AND branch={$branch} AND organization_id={$organization_id} 
										  AND group_type='{$type}'");
			$result_vv2 = $result_vv->getRow();
			$vv=substr('000'.$result_vv2->vv,-3);
            $vv_count=0;

            if ($presell == 1) { 
				$table = "presell"; 
			} else { 
				$table = "product"; 
			}
			$query = "SELECT p.*, op.order_id, op.quantity, op.price 
					  FROM epos_orders_products AS op 
					  LEFT JOIN epos_{$table} AS p ON op.prod_code=p.prod_code 
					  WHERE op.order_id={$order_id} AND op.presell={$presell} 
					  AND op.branch={$branch} AND op.organization_id={$organization_id} 
					  AND op.group_type='{$type}' 
					  GROUP BY op.prod_code";
			
			$results = $db->query($query);
			$nCount = 1;
			$file_data = "";
			foreach ($results->getResult() as $res_prod) {
                $nCount1 = substr("000".$nCount,-3);
                $prod_code = substr("0000000".$res_prod->prod_code,-7);
                $quantity = substr("0000".$res_prod->quantity,-4);
				$price = substr("00000000".number_format($res_prod->price,2,'.',''),-8);
                if (strlen($price) > 8) {
					return -206;
				}
				$file_data.= $nCount1.$prod_code.$quantity.$price."\r\n";
				$nCount ++;
				if ($nCount == 1000) {
				   $file_data ="over 999 !!!"."\r\n";
				   break;
				}
			}

			$file_data .= "EOF".$vv; //"EOF".$nCount."\r\n".$vv;
			return $file_data;
		}
	}

	function close_and_complete_order($person_id, $type='general', $presell = 0)
	{
		$db = \Config\Database::connect();
		$branch = session()->get('branch');
		$organization_id = session()->get('organization_id');

		$builder = $db->table($this->table);
		$builder->where('person_id' , $person_id);
		$builder->where('opened' , 1);
		$builder->where('type' , $type);
		$builder->where('presell' , $presell);
		$builder->where('branch' , $branch);
		$builder->where('organization_id' , $organization_id);

		$results = $builder->get();
		if ($results->getNumRows() == 0) {
			return -1;
		}

		$res = $results->getRow();
		$order_id = $res->order_id;
		$order_data = array(
			'opened' => 0 ,
			'completed' => 1
		);
		return $db->table($this->table)
				  ->where('order_id' , $order_id)
				  ->where('presell' , $presell)
				  ->update($order_data);

	}

	function save_excel($barcode , $user_info , $presell = 0)
	{
		$db = \Config\Database::connect();

    	$query="SELECT prod_id, prod_code, prod_uos, prod_desc, prod_pack_desc,
                       vat_code, prod_price, group_desc, prod_code1, start_date,
                       price_list, prod_level1, prod_level2, prod_level3,
                       MIN(prod_sell) as prod_sell, prod_rrp, wholesale, retail, p_size, is_disabled, promo, van, shelf_life
				FROM epos_product
				WHERE is_disabled='N' AND (wholesale like'".$barcode."' OR retail like'".$barcode."') AND (price_list = '000' ";

		if ($user_info->price_list999) $query .= "OR price_list = '999' ";
		if ($user_info->price_list012) $query .= "OR price_list = '12'  ";
		if ($user_info->price_list011) $query .= "OR price_list = '11'  ";
		if ($user_info->price_list010) $query .= "OR price_list = '10'  ";
		if ($user_info->price_list009) $query .= "OR price_list = '09'  ";
		if ($user_info->price_list008) $query .= "OR price_list = '08'  ";
		if ($user_info->price_list007) $query .= "OR price_list = '07'  ";
		if ($user_info->price_list005) $query .= "OR price_list = '05'  ";
		if ($user_info->price_list001) $query .= "OR price_list = '01'  ";

		$query .= ") GROUP BY prod_code ORDER BY prod_code DESC LIMIT 6 ";
		$res = $db->query($query);

		if ($res->getNumRows() == 0) {
			return false;
		} else {
			foreach ($res->getResult() as $res_row) {
				$builder = $db->table('epos_cart')
							  ->where(array('prod_code'=>$res_row->prod_code,
                                       			 'person_id'=>$user_info->person_id,
									   			 'presell'=>$presell));
				$res1 = $builder->get();
				if ($res1->getNumRows() == 0) {
					$insert_data = array(
						'prod_code' => $res_row->prod_code,
						'quantity' => 1 ,
						'person_id' => $user_info->person_id,
					);
					$db->table('epos_cart')->insert($insert_data);
					continue;
				} else {
				    $db->query("UPDATE epos_cart SET quantity=quantity+1 WHERE prod_code='".$res_row->prod_code."' AND person_id=".$user_info->person_id." and presell=".$presell."");
				}
			}
		}
		return true;
	}

	function empty_cart($person_id, $presell = 0)
	{
		$db = \Config\Database::connect();
		
		$query = "DELETE FROM epos_cart WHERE person_id='".$person_id."' and presell=".$presell."";
		$db->transStart();
		$db->query($query);
		$db->transComplete();

		if ($db->transStatus() === FALSE)
			return -1;
		else
			return true;
	}

	function empty_cart_by_type($person_id, $group_type, $presell = 0)
	{
		$db = \Config\Database::connect();
		$branch = session()->get('branch');
		$organization_id = session()->get('organization_id');
		
		$query = "DELETE FROM epos_cart WHERE person_id='".$person_id."' AND presell=".$presell." AND group_type='".$group_type."' AND branch=".$branch;
		if (!empty($organization_id)) {
			$query .= " AND organization_id=".$organization_id;
		}
		
		$db->transStart();
		$db->query($query);
		$db->transComplete();

		if ($db->transStatus() === FALSE)
			return -1;
		else
			return true;
	}

	public static function populateProduct(&$order, $priceList, $user_info, $spresell=0) {
		$product = Product::getLowestPriceProductByCode($user_info, $order->prod_code, true, $order->group_type == 'spresell');
		if (!$product) $product = Product::getLowestPriceProductByCode($user_info, $order->prod_code, false, $order->group_type == 'spresell');
		if (!$product) return;	

		Product::populate($product, $priceList, $user_info, $spresell);
		$order->product = $product;

		$background = '';
		$promotion = '';
		if (isset($product->promo) && $product->promo=='Y') {
			if ($product->price_list=='08') { 
				$background="#a0e2c8"; 
				$promotion="DAY-TODAY EXPRESS ELITE"; 
			} else if($product->price_list=='10') { 
				$background="#a0e2c8"; 
				$promotion="DAY-TODAY PRICE"; 
			} else if($product->price_list=='11') { 
				$background="#a0e2c8"; 
				$promotion="DAY-TODAY EXPRESS PRICE"; 
			} else if($product->price_list=='12') { 
				$background="#f5c1c2"; 
				$promotion="USAVE PRICE";  
			}  else {
				$background="#b4e9f8"; 
				$promotion="C & C PROMOTION";
			}
		}

		$order->background = $background;
		$order->promotion = $promotion;
	}
}
?>
