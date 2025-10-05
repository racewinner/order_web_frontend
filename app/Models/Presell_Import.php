<?php
namespace App\Models;
use CodeIgniter\Model;
use App\Models\Employee;
use App\Models\Order;
class Presell_Import extends Model
{
	protected $table            = 'epos_presell';
    protected $primaryKey       = 'prod_id';
	function select()
	{
		//$this->db->order_by('prod_id', 'DESC');
		return $this->orderBy('period_ref asc, prod_id desc')->where('ordered','0')->get();
		 
	}
	
	// function get_presell($pid){
	function get_presell($pid)
	{
		$q = $this->where('prod_id',$pid)->where('ordered','0')->get();
		if($q->getNumRows() > 0){
		  //$data = $q->result();
		  foreach ($q->getResultArray() as $row){
			$data =  $row;
		  }
		  return json_encode($data);
		}
		else{
		  return 0;
		}
	}
	
	function check($ref)
	{
		$q = $this->where('period_ref',$ref)->where('ordered','0')->get();
		if($q->getNumRows() > 0){
		  foreach ($q->getResultArray() as $row){
			$data[] = $row['prod_code'];
		  }
		  return $data;
		}
		else{
		  return 0;
		}
	}
	
	function check_item($uid)
	{
		$this->db->where('unique_id',$uid);
		$this->db->where('ordered','0');
		$q = $this->db->get('presell');
		if($q->num_rows() > 0){
		  foreach ($q->result_array() as $row){
			$data[] = $row['prod_id'];
		  }
		  return $data;
		}
		else{
		  return 0;
		}
	}

	// function insert($data, $ref){		
	function insert_presells($data, $ref){		
	
		// Check for old entries with Period Reference
		$r = $this->check(reset($ref));
		if($r != 0){
			// Delete old Presell entries for this period
			$this->db->where_in('period_ref', $ref);
			$this->db->where_in('ordered', '0');
			$this->db->delete('presell');	
		}
		
		// Insert New entries in Presell Table
		$this->db->insert_batch('presell', $data);
		
		// Check for new entries with Period Reference
		$n = $this->presell_import->check(reset($ref));
		
		// Grab all users from Employees Table
		$users = $this->Employee->get_all(1000,0,1);
		
		// Loop through all users
		foreach($users->result() as $u){
			
			// Get Person_id
			$person_id = $u->person_id;
			
			// Get User Band
			$band = substr($u->presell_band,0,1);
			if($band == ""){ $band = "g"; }
			
			if($r != 0){
				// Delete old Cart entries for this period
				$this->db->where_in('person_id', $person_id);
				$this->db->where_in('prod_id', $r);
				$this->db->where_in('presell', '1');
				$this->db->delete('cart');
			}
		
			// Inser New entries in Cart Table
			$this->db->from('presell');
			$this->db->where_in('prod_id', $n);
			$this->db->where_in('ordered', '0');
			$q = $this->db->get();
			foreach($q->result() as $p){
				$quantity = $p->{$band."_qty"};
				$cart_data[$person_id][] = array(
					'prod_id'        => $p->prod_id ,
					'quantity'       => $quantity ,
					'person_id'      => $person_id ,
					'prod_uos'       => $p->prod_uos ,
					'is_disabled'    => $p->is_disabled ,
					'p_size'         => $p->p_size ,
					'retail'         => $p->retail ,
					'wholesale'      => $p->wholesale ,
					'prod_rrp'       => $p->prod_rrp ,
					'prod_sell'      => $p->prod_sell ,
					'prod_level3'    => $p->prod_level3 ,
					'prod_level2'    => $p->prod_level2 ,
					'prod_level1'    => $p->prod_level1 ,
					'price_list'     => $p->price_list ,
					'prod_code1'     => $p->prod_code1 ,
					'group_desc'     => $p->group_desc ,
					'prod_price'     => $p->prod_price ,
					'vat_code'       => $p->vat_code ,
					'prod_pack_desc' => $p->prod_pack_desc ,
					'prod_desc'      => $p->prod_desc ,
					'start_date'     => $p->start_date ,
					'prod_code'      => $p->prod_code ,
					'van'            => $p->van ,
					'shelf_life'     => $p->shelf_life ,
					'price_start'    => $p->price_start ,
					'price_end'      => $p->price_end ,
					'brand'          => $p->brand ,
					'epoints'        => $p->epoints ,
					'unique_id'      => $p->unique_id ,
					'presell'        => '1'
				);						
			}// loop end
			// Insert New entreis in Cart Table
			$this->db->insert_batch('cart' , $cart_data[$person_id]);
		}//loop end
		
		//echo count($cart_data);
		echo "Presell Import Successful";
	}
	
	function process($ref){	

		$db = \Config\Database::connect();
		$p = [];
		// Double check cart and delete old entries
		$q0 = $this->where('ordered','0')->get();
		if($q0->getNumRows() > 0){
			foreach ($q0->getResultArray() as $row){
				$data0[] = $row['prod_code'];
			}

			$builder = $db->table('epos_cart');
			$builder->whereNotIn('prod_code', $data0);
			$builder->where('presell', '1');
			$builder->delete();
		}				
			
		// Check for presell entries with Period Reference
		$n = $this->check($ref);
		
		// Grab all users from Employees Table
		
		$Employee = new Employee();
		$Order = new Order();

		$users = $Employee->get_all(1000,0,1);
		// Loop through all users
		foreach($users->getResult() as $u){
			$person_id = $u->person_id; // Get Person_id
			$band = substr($u->presell_band,0,1); // Get User Band
			if($band == ""){ $band = "g"; }
			
			//$ignore = 0;
			//if($this->Order->get_count_cart_products($person_id, 1) == 0) { $ignore = 1; }
			//if($ignore == 0 && ($person_id == 37 ||$person_id == 38 || $person_id == 39 || $person_id == 40 || $person_id == 539) ){ 
			
			// TODO - Load Cart entries instead ; loop through each user and process order
			   
			// Create order of presell items with period reference
			if( $Order->save_for_later($person_id, 1, 1, $ref) ){	
				$datetime = date('dmY_His',time());
				$order_id = $Order->where('person_id' , $person_id)
				->where('opened' , 1)
				->where('presell' , 1)
				->orderBy('order_id','desc')
				->limit(1)
				->get()
				->row()->order_id;		
				
				$file_name = substr("00000".$u->username,-5).'_'.$datetime.'_wo2-'.$order_id.'_';
				srand((double)microtime()*1000000);
				while(1){
					$l = rand(48 , 122);
					if (($l>57 && $l<65) || ($l>90&&$l<97)) continue;
					$file_name .= chr($l);
					if (strlen($file_name)>37) break;
				}
				$file_name .='_Presell_'.$ref;
				$file_name .='.ord';
				$first_line = $Order->get_order_file_data($person_id , 1 , 1, $ref);
				if($first_line < 0){ echo $first_line; return; }
				$file_data = $Order->get_order_file_data($person_id , 2 , 1, $ref);
				$vv = substr($file_data,-3);
				$file_data = substr($file_data,0,strlen($file_data)-3);
				if($file_data < 0){	echo $file_data; return; }
				$file_data = $first_line.$file_data;
				//$file_path = "/home/uws003/public_html/temp/".$file_name;
				$file_path = "/home/uws001/public_html/temp/".$file_name;
				//$file_path = "/home/staging/public_html/temp/".$file_name; // --- SWAP
				if(!write_file($file_path, $file_data)){ echo -103;	return;	}
				
				if(!$db->query("UPDATE epos_orders SET filename='".$file_name."', order_date='".date('Ymd',time())."', order_time='".substr($datetime,-6)."' WHERE order_id=".$order_id." AND presell=1")){ echo -104; return; }
				if(!$Order->close_and_complete_order($person_id, 1)){ echo -105; return; }
		
				$db->query('DELETE FROM epos_orders WHERE opened=1 and person_id='.$person_id.' and presell=1');
				
				// Store order id in an array
				$p[] = 'wo2-'.$order_id;
				
				///////////////////////////////////////////////// --- FTP Start				
				$ftp_stream = ftp_connect('ordersin.uniteduk.co.uk');
				//$ftp_stream = ftp_connect('staging456.uniteduk.co.uk'); // --- SWAP
				if ($ftp_stream==false){ echo 'cannot connect to orders server'; return; }
				
				$login_stat = ftp_login($ftp_stream,'web.ordering','HaRoOn-CaN_2,ok.');
				//$login_stat = ftp_login($ftp_stream,'staging','tWG8y&ZLtZ)9E0&pQ#CSU1Zn');  // --- SWAP
				if ($login_stat==false){ echo 'cannot log in to orders server'; ftp_close($ftp_stream); return; }
				
				//$file_ul = ftp_put($ftp_stream,'epos_link_files/ordersin/'.$file_name,'/home/uws003/public_html/temp/'.$file_name,FTP_BINARY);
				$file_ul = ftp_put($ftp_stream,'epos_link_files/ordersin/'.$file_name,'/home/uws001/public_html/temp/'.$file_name,FTP_BINARY);
				//$file_ul = ftp_put($ftp_stream,'public_html/temp_live/allocations/'.$file_name,'/home/staging/public_html/temp/'.$file_name,FTP_BINARY); // --- SWAP
				if ($file_ul==false) { echo 'unable to write ORDER file '.$file_name; ftp_close($ftp_stream); return; }		
				
				ftp_close($ftp_stream);
				//////////////////////////////////////////////// --- FTP End
				
				$send_message = $Order->from_message_mail($person_id , $order_id, 1);
				
				$addr_mail = $Order->from_addr_mail();
				$mail_subject = lang('orders_email_subject').$u->username.' Presell order id : wo2-'.$order_id;
				$config_mailtype['mailtype'] = "html";
				$this->email->initialize($config_mailtype);
				$this->email->from($addr_mail['email_addr'], $addr_mail['company_name']);
				//$this->email->to($u->email);
				//$this->email->to('yasirikram@gmail.com, design@uniteduk.com'); // --- SWAP
				$this->email->to('yasirikram@gmail.com'); // --- SWAP
				//$this->email->to('yasirikram@gmail.com, vitalmughal@gmail.com'); // --- SWAP
				$this->email->subject($mail_subject);
				$this->email->message($send_message);
				$this->email->send();
				$this->email->clear();
			  }//order check
			//}//user check
		}//loop end
		
		if($n != 0){
			// Delete all Cart entries for this period reference					
			$db->table('epos_cart')
			     ->whereIn('prod_code', $n)
				 ->where('presell', '1')
			     ->delete();		
		}
		
		// send mail to admin
		$send_message = "Presell Orders of <b>".$ref."</b> <br /><br /><html><body><span style='font-family:Arial; font-size:13px;'>Orders consist of following Presell items.<span><br /><table cellspacing='1px' style='width:98%; border: 1px solid #ccc;'><thead><tr style='background-color:#11ccdd;'><th>No</th><th>Product</th><th>Description</th><th>Size</th><th>UOS</th><th>Price</th><th>Price_List</th></tr></thead><tbody>";
		$q = $db->table($this->table)->whereIn('prod_id', $n)->get();
		$nCount = 0;
		foreach($q->getResult() as $r){
			$nCount ++;
			$send_message .= "<tr><td style='width:5%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'> ".$nCount."</td>
			                  <td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$r->prod_code."</td>
							  <td style='width:45%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$r->prod_desc."</td>
							  <td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$r->prod_pack_desc."</td>
							  <td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$r->prod_uos."</td>
							  <td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$r->prod_sell."</td>
							  <td style='width:10%; border-right:1px solid #EEEEEE; border-bottom:1px solid #EEEEEE;'>".$r->price_list."</td></tr>";
		}
		$send_message .= "<tr style='background-color:#EEEEEE;'><td>&nbsp;</td><td stype='padding-top:5px'>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
</tr></tbody></table></body></html><br />Note: Each user's presell order is custom, based on band and fascia subscription.<br /><br /><b>Order IDs:</b><br />";
				
		foreach($p as $i) $send_message .= $i . ', ';		
		
		// $addr_mail = $Order->from_addr_mail();
		// $mail_subject = 'Bulk Presell Orders ['.$ref.'] - ID Range : '.(array_key_exists(0,$p) ? $p[0] : '').' to '.(array_key_exists(count($p) - 1,$p) ? $p[count($p) - 1] : '');
		// $config_mailtype['mailtype'] = "html";
		// $this->email->initialize($config_mailtype);
		// $this->email->from($addr_mail['email_addr'], $addr_mail['company_name']);
		//$this->email->to('telesales@uniteduk.com');
		//$this->email->to('yasirikram@gmail.com, design@uniteduk.com'); // --- SWAP
		// $this->email->to('yasirikram@gmail.com'); // --- SWAP
		// $this->email->subject($mail_subject);
		// $this->email->message($send_message);
		// $this->email->send();
		// $this->email->clear();
		$email = \Config\Services::email();
		$addr_mail = $Order->from_addr_mail();
		$mail_subject = 'Bulk Presell Orders ['.$ref.'] - ID Range : '.(array_key_exists(0, $p) ? $p[0] : '').' to '.(array_key_exists(count($p) - 1, $p) ? $p[count($p) - 1] : '');		
		$config = ['mailType' => 'html'];
		$email->initialize($config);
		$email->setFrom($addr_mail['email_addr'], $addr_mail['company_name']);
		$email->setTo('yasirikram@gmail.com');
		$email->setSubject($mail_subject);
		$email->setMessage($send_message);
		$email->send();
		
				
		if($n != 0){
			// Update Presell entries for this period - Mark Ordered
			$data = array('ordered' => '1');
			$db->table($this->table)->where('period_ref', $ref)->where('ordered', '0')->update($data);			
		}		
		
		//print_r($p);
		echo "Bulk Conversion Completed";
	}
	
	function add($data, $uid){			
		
		// Check for Unique ID entry in presell table
		$r = $this->presell_import->check_item( $uid );
		if($r != 0){	
			//echo count($cart_data);
			echo "Unique ID already exist";
		}
		else
		{   
			// Insert New entries in Presell Table
			$this->db->insert_batch('presell', $data);
			$Id = $this->db->insert_id();	
			
			// Grab all users from Employees Table
			$users = $this->Employee->get_all(1000,0,1);
			
			// Loop through all users
			foreach($users->result() as $u){
				
				// Get Person_id
				$person_id = $u->person_id;
				
				// Get User Band
				$band = substr($u->presell_band,0,1);
				if($band == ""){ $band = "g"; }
			
				// Inser New entries in Cart Table
				$this->db->from('presell');
				$this->db->where_in('prod_id', $Id);
				$this->db->where_in('ordered', '0');
				$q = $this->db->get();
				foreach($q->result() as $p){
					$quantity = $p->{$band."_qty"};
					$cart_data[$person_id][] = array(
						'prod_id'        => $p->prod_id ,
						'quantity'       => $quantity ,
						'person_id'      => $person_id ,
						'prod_uos'       => $p->prod_uos ,
						'is_disabled'    => $p->is_disabled ,
						'p_size'         => $p->p_size ,
						'retail'         => $p->retail ,
						'wholesale'      => $p->wholesale ,
						'prod_rrp'       => $p->prod_rrp ,
						'prod_sell'      => $p->prod_sell ,
						'prod_level3'    => $p->prod_level3 ,
						'prod_level2'    => $p->prod_level2 ,
						'prod_level1'    => $p->prod_level1 ,
						'price_list'     => $p->price_list ,
						'prod_code1'     => $p->prod_code1 ,
						'group_desc'     => $p->group_desc ,
						'prod_price'     => $p->prod_price ,
						'vat_code'       => $p->vat_code ,
						'prod_pack_desc' => $p->prod_pack_desc ,
						'prod_desc'      => $p->prod_desc ,
						'start_date'     => $p->start_date ,
						'prod_code'      => $p->prod_code ,
						'van'            => $p->van ,
						'shelf_life'     => $p->shelf_life ,
						'price_start'    => $p->price_start ,
						'price_end'      => $p->price_end ,
						'brand'          => $p->brand ,
						'epoints'        => $p->epoints ,
						'unique_id'      => $p->unique_id ,
						'presell'        => '1'
					);						
				}// loop end
				// Insert New entreis in Cart Table
				$this->db->insert_batch('cart' , $cart_data[$person_id]);
			}//loop end
			
			//echo count($cart_data);
			echo "Item Added Successfully";
			
		}// end ELSE
	}
	
	function edit($data, $uid, $pid){			
		// Check for Unique ID entry in presell table
		$this->db->where('prod_id !=',$pid);
		$this->db->where('unique_id',$uid);
		$this->db->where('ordered','0');
		$q = $this->db->get('presell');
		if($q->num_rows() > 0){
		  foreach ($q->result_array() as $row){
			$d[] = $row['prod_id'];
		  }
		  echo "Unique ID already exist ".$pid;
		}
		else{
		  // Unique ID not found in other items
		  $d = 0;
		  //print_r( $data[0] );
		}
		
		if($d==0){
			
			// Update entry in Presell Table
		    $this->db->where_in('prod_id', $pid);
			$this->db->update('presell', $data); 
			
			// Grab all users from Employees Table
			$users = $this->Employee->get_all(1000,0,1);
			
			// Loop through all users
			foreach($users->result() as $u){
				
				// Get Person_id
				$person_id = $u->person_id;
				
				// Get User Band
				$band = substr($u->presell_band,0,1);
				if($band == ""){ $band = "g"; }
			
				// Inser New entries in Cart Table
				$this->db->from('presell');
				$this->db->where_in('prod_id', $pid);
				$this->db->where_in('ordered', '0');
				$q = $this->db->get();
				foreach($q->result() as $p){
					$quantity = $p->{$band."_qty"};
					$cart_data = array(
						'prod_id'        => $p->prod_id ,
						'prod_uos'       => $p->prod_uos ,
						'is_disabled'    => $p->is_disabled ,
						'p_size'         => $p->p_size ,
						'retail'         => $p->retail ,
						'wholesale'      => $p->wholesale ,
						'prod_rrp'       => $p->prod_rrp ,
						'prod_sell'      => $p->prod_sell ,
						'prod_level3'    => $p->prod_level3 ,
						'prod_level2'    => $p->prod_level2 ,
						'prod_level1'    => $p->prod_level1 ,
						'price_list'     => $p->price_list ,
						'prod_code1'     => $p->prod_code1 ,
						'group_desc'     => $p->group_desc ,
						'prod_price'     => $p->prod_price ,
						'vat_code'       => $p->vat_code ,
						'prod_pack_desc' => $p->prod_pack_desc ,
						'prod_desc'      => $p->prod_desc ,
						'start_date'     => $p->start_date ,
						'prod_code'      => $p->prod_code ,
						'van'            => $p->van ,
						'shelf_life'     => $p->shelf_life ,
						'price_start'    => $p->price_start ,
						'price_end'      => $p->price_end ,
						'unique_id'      => $p->unique_id
					);				
					if( array_key_exists("g_qty",$data) ){
					   $cart_data['quantity'] =  $quantity;
					}
				}// loop end
				// Insert New entreis in Cart Table
				$this->db->where_in('prod_id', $pid);
				$this->db->where_in('person_id', $person_id);
				$this->db->where_in('presell', 1);
				$this->db->update('cart' , $cart_data);
			}//loop end
			
			//echo count($cart_data);
			echo "Item Updated Successfully";
		
		}// end IF
	}
	
	// function delete($uid, $pid){	
	function delete_presell($uid, $pid){	
		// Check for entry with Unique ID
		$r = $this->presell_import->check_item($uid);
		
		if($r != 0 && $r[0] == $pid){
			// Delete all Cart entries for this item					
			$this->db->where_in('prod_id', $pid);
			$this->db->where_in('presell', '1');
			$this->db->delete('cart');		
			
			// Delete Presell entry of this item	
			$this->db->where('prod_id',$pid);
			$this->db->where('ordered','0');
			$this->db->delete('presell');		
		}
	}
	
	function delete_entries($ref){			
		// Check for new entries with Period Reference
		$n = $this->presell_import->check($ref);
		
		if($n != 0){
			// Delete all Cart entries for this period					
			$this->db->where_in('prod_id', $n);
			$this->db->where_in('presell', '1');
			$this->db->delete('cart');		
			
			// Delete Presell entries for this period	
			$this->db->where('period_ref',$ref);
			$this->db->where('ordered','0');
			$this->db->delete('presell');		
		}
	}

	public static function getLowestPricePresellByCode($user_info, $prod_code) {
		$db = \Config\Database::connect();

		$query = "SELECT *, MIN(prod_sell) as prod_sell, from epos_presell WHERE prod_code=" . $prod_code;
		$query .= " AND (price_list = '999'";
		$cond = "";
		if ($user_info->price_list001) $cond .= "OR price_list = '01' ";
		if ($user_info->price_list005) $cond .= "OR price_list = '05' ";
		if ($user_info->price_list007) $cond .= "OR price_list = '07' ";
		if ($user_info->price_list009) $cond .= "OR price_list = '09' ";
		if ($user_info->price_list999) $cond .= "OR price_list = '999' ";		
		if ($user_info->price_list008) $cond .= "OR price_list = '08' ";
		if ($user_info->price_list010) $cond .= "OR price_list = '10' ";
		if ($user_info->price_list011) $cond .= "OR price_list = '11' ";
		if ($user_info->price_list012) $cond .= "OR price_list = '12' ";
		$query .= ") AND prod_sell > 0 ";
		$query .= " GROUP BY prod_code";
		
		$query = $db->query($query);
		return $query->getNumRows()>0 ? $query->getRow() : null;
	}
}
		
