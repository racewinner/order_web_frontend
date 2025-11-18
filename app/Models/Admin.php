<?php
namespace App\Models;
use CodeIgniter\Model;
use Config\Database;

class Admin extends Model { 

	// protected $table            = 'epos_employees';
    // protected $primaryKey       = 'person_id';

  // we will use the function get_featured
  function get_featured($where_arr,$type){
	// all the queries relating to the data we want to retrieve will go in here
	$db = Database::connect();
	$porformula = "(CASE WHEN (prod_rrp * prod_uos > 0) 
					THEN ROUND((1-((prod_sell * (1.00 + (CASE WHEN vat_code='A' THEN 0.2 WHEN vat_code='C' THEN 0.05 ELSE 0 END))) / (prod_rrp * prod_uos)))*100, 1)
					ELSE 0 	END) AS por";
	$select = "f.*, prod_id, p.prod_code, prod_uos, prod_desc, prod_pack_desc, price_start, price_end, brand, epoints,
				vat_code, prod_price, group_desc, prod_code1, price_list, prod_level1, prod_level2, prod_level3,
				pi.url as image_url, pi.version as image_version, prod_sell, prod_rrp, wholesale, 
				retail, p_size, is_disabled, promo, van, shelf_life, availability, pfp, " . $porformula;
	$q = $db->table('epos_product p')
		->select($select)
		->where('p.price_list', '999')
		->whereIn('p.prod_code', $where_arr)
		->join('epos_featured_items f', 'f.f_id = p.prod_code', 'left') // corrected join clause
		->join('epos_product_images pi', 'CAST(SUBSTRING(p.prod_code, 2, 6) AS UNSIGNED)=pi.prod_code', 'left')
		->where('f.f_category', $type) // additional condition for the join
		->orderBy('f.f_position', 'asc')
		->get();

	return $q;
  }
  
  function get_featured_codes($type){
	$db = Database::connect();
	   $q = $db->table('epos_featured_items')
		->select('f_id,f_category,f_position')      // the select statement
		->where('f_category', $type)
		->orderBy("f_position", "asc")
		->get();           // the table
		 
		// after we've made the queries from the database, we will store them inside a variable called $data, and return the variable to the controller 
		if($q->getNumRows() > 0){
		// we will store the results in the form of class methods by using $q->result(), if  store as an array use $q->result_array()
			foreach ($q->getResult() as $row){
				$codes[] = $row->f_id;
			}
		  return $codes;

		}
		
  }
  
  function update_featured($data){
	  for($i=0; $i<(count($data['fid'])); $i++){
		  $batch[] = array( 'id' => $data['id'][$i], 'f_id' => $data['fid'][$i] );
		}
		
		//echo json_encode($batch);
		$db = Database::connect();
		$builder = $db->table("epos_featured_items");
		$success = $builder->updateBatch($batch, 'id');		
		 if($success){
			  echo "working update";
			}
			else{
				$data = 'worked';
				return true;
			}		  
		/*
		for($i=0; $i<56; $i++){		  
		  $f_id = $batch[$i]['id'];
		  $f_fid = $batch[$i]['fid'];   
		  $fid = array('f_id'=>$f_fid);
		  $this->db->where('id', $f_id);
		  $success = $this->db->update('epos_featured_items',$fid);
		   if($success){
			  //$this->load->view('profile_view');
			  echo "working update";
			}
			else{
			  echo "something is wrong";
			}		  
		}
		*/
  }
  
  
  function check_both_promos($pid){
	$db = Database::connect();
		$q = $db->table('epos_employees')
		     ->select('price_list008,price_list010,price_list011,price_list012')
		    ->where('person_id', $pid)
		    ->get();
		if ($q->getNumRows() > 0){
			$r = $q->getRow();
		    if( $r->price_list008 == "1" || $r->price_list010 == "1" || $r->price_list011 == "1" || $r->price_list012 == "1" ){
		      $data = "du";
		    }
			else { $data = "cc";	 }
		}else { $data = "No Rows Found";}
		 echo $data;
  }
  
  function check_daytoday($pid){
		$db = Database::connect();
		$q = $db->table('epos_employees')
		        ->select('price_list008,price_list010,price_list011')
				->where('person_id', $pid)
				->get();

		if ($q->getNumRows() > 0){
			$r = $q->getRow();
		    if( $r->price_list008 == "1" || $r->price_list010 == "1" || $r->price_list011 == "1" ){
		      $data = "Yes";
		    }
			else { $data = "No";	 }
		}else { $data = "No Rows Found";}
		 echo $data;
  }
  
  function check_usave($pid){
	$db = Database::connect();
		$q = $db->table('epos_employees')
		        ->select('price_list012')
		        ->where('person_id', $pid)
		        ->get(); 

		if ($q->getNumRows() > 0){
			$r = $q->getRow();
			
		    if( $r->price_list012 == "1" ){
		      $data = "Yes";
		    }
			else { $data = "No";	 }
		}else { $data = "No Rows Found";}
		 echo $data;
  }
  
  function get_scount($t){
  	
	$db = Database::connect();

	$query = $db->table('epos_app_config')
		->select('value')
		->where('key', $t)
		->get();

	if ($query->getNumRows() > 0) {
		$row = $query->getRow();
		return $row->value;
	}

	return null;

  }
  function get_plink($link){

	    $db = Database::connect();

        $query = $db->table('epos_app_config')
            ->select('value')
            ->where('key', $link)
            ->get();

        if ($query->getNumRows() > 0) {
            $row = $query->getRow();
            return $row->value;
        }

        return null;
   }
  
  function push_scount($s1, $s2){
	$db = Database::connect();
	$value = array('value'=>$s1);
	$db->table('epos_app_config')->where('key','slides')->update($value);
	
	$value=array('value'=>$s2);
	$db->table('epos_app_config')->where('key','sponsors')->update($value);
	
    return true;
  }
  function push_plink($l, $v){
	$db = Database::connect();
	$value=array('value'=>$v);
	$db->table('epos_app_config')->where('key',$l)->update($value);
	
    return true;
  }
  
  function get_all_users(){
  	$this->db->select('*');
	$this->db->where('deleted', 0);   
	$q = $this->db->get('epos_employees'); 
	if ($q->num_rows() > 0){
		foreach ($q->result() as $row){
				$r[] = $row;
			}
	}
	return $r;  
  }
  
  function get_total_orders($user_id){
  	$this->db->select('*');
	$date =  (date("Y")-1) .date("md");
	$this->db->where('order_date >', $date);   	
	$this->db->where('person_id', $user_id);   
	$this->db->where('completed', 1);   
	$q = $this->db->get('epos_orders'); 
	$o = $q->num_rows();	
	return $o;  
  }
  
  function get_period($today, $start, $end){
	if($end == '10-01'){$year = date('Y', strtotime("+1 year")); } else{ $year = date('Y'); }
	$start = strtotime( $start."-".date('Y')." 00:00:00");
	$end   = strtotime( $end."-".$year." 23:59:59");
	if (($today >= $start) && ($today <= $end)){ return true; }else{ return false; }
  }
  
  // Add Tracking data into database
  function add_tracking($user, $tid, $tlink, $tperiod){
	$db = Database::connect();
	if(strpos(strtok($tid, '-'), 'sp') !== false){ $type = "Sponsor"; } 
	else if(strpos(strtok($tid, '-'), 'p') !== false) { $type = "Promotion"; } 
	else { $type = "Header"; }
	$cperiod = $this->get_current_period();
	if($tperiod == "auto"){
		$today = strtotime("now");
		if( $this->get_period($today, '06-01', '26-01') ){ $tperiod = 1; }else
		if( $this->get_period($today, '27-01', '16-02') ){ $tperiod = 2; }else
		if( $this->get_period($today, '17-02', '08-03') ){ $tperiod = 3; }else
		if( $this->get_period($today, '09-03', '29-03') ){ $tperiod = 4; }else
		if( $this->get_period($today, '30-03', '19-04') ){ $tperiod = 5; }else
		if( $this->get_period($today, '20-04', '10-05') ){ $tperiod = 6; }else
		if( $this->get_period($today, '11-05', '31-05') ){ $tperiod = 7; }else
		if( $this->get_period($today, '01-06', '21-06') ){ $tperiod = 8; }else
		if( $this->get_period($today, '22-06', '12-07') ){ $tperiod = 9; }else
		if( $this->get_period($today, '13-07', '02-08') ){ $tperiod = 10; }else
		if( $this->get_period($today, '03-08', '23-08') ){ $tperiod = 11; }else
		if( $this->get_period($today, '24-08', '13-09') ){ $tperiod = 12; }else
		if( $this->get_period($today, '14-09', '04-10') ){ $tperiod = 13; }else
		if( $this->get_period($today, '05-10', '25-10') ){ $tperiod = 14; }else
		if( $this->get_period($today, '26-10', '15-11') ){ $tperiod = 15; }else
		if( $this->get_period($today, '16-11', '06-12') ){ $tperiod = 16; }else
		if( $this->get_period($today, '07-12', '10-01') ){ $tperiod = 17; }
	}
	else if($tperiod < $cperiod){
		$tperiod = $cperiod;	     	
	}
	$tracking_data = array(
			'person_id' => $user,
			'type' => $type,
			'period' => $tperiod,
			'supplier' => substr($tid, strpos($tid, "-") + 1),
			'name' => $tid,
			'link' => $tlink,
			'tracking_date' => date("Ymd",time()),
			'tracking_time' => date("His",time())
	);
	$db->insert('epos_tracking' , $tracking_data);
    return true;
  }
  
  
//   // Get Tracking data from database
  function get_tracking($period){ 

	$db = Database::connect();

	// $results = $db->table('epos_tracking')->get();
	$results = $db->table('epos_tracking')->get()->getResultArray();

	

	foreach ( $results as $r ){
		// echo "<pre>";
		// print_r($r['tracking_date']);	
		// exit;

		$q = $db->table('epos_orders')->where('order_date', $r['tracking_date'])->get()->getNumRows();	  	  
	  //$todate = date('Y/m/d | h:i:s',$ord->datetime);
	   
	  $d =  "{";
	  //echo 'order:"'.$oid.' - '.$todate.' - $'.$ord->total.' - '.$ord->retailer.' ['.$ord->account.'] '.$ord->email.' '.$ord->address.' '.$ord->contact.'", ';
	  
	  $d .= 'id:"'.$r['tracking_id'].'", ';
	  $d .= 'person_id:"'.$r['person_id'].'", ';
	  $d .= 'tracking_date:"'.substr($r['tracking_date'], 0, 4).'/'.substr($r['tracking_date'], 4, 2).'/'.substr($r['tracking_date'], 6).'", ';
	  $d .= 'tracking_time:"'.substr($r['tracking_date'], 0, 2).':'.substr($r['tracking_date'], 2, 2).':'.substr($r['tracking_date'], 4).'", ';
	  $d .= 'period:"'.$r['period'].'", ';
	  $d .= 'type:"'.$r['type'].'", ';
	  $d .= 'supplier:"'.$r['supplier'].'", ';
	  $d .= 'name:"'.$r['name'].'", ';
	  $d .= 'link:"'.$r['link'].'", ';
	  $d .= 'order:"'.$q.'", ';
	  
	  $d .= "},";
  
	}    
    return $d;
  }

//   public function get_tracking($period)
//   {
// 	  $db = Database::connect();

// 	  $results = $db->table('epos_tracking')->get()->getResultArray();

// 	//   print_r($results);
// 	//   exit;
// 	  $d = [];
// 	  foreach ($results as $r) {
// 		  // Your existing logic

// 	//   exit;

// 	$var_order = $db->table('epos_orders')->where('order_date',$r['tracking_date'])->get()->getNumRows();
	
// 		  $d[] = [
// 			  'id' => $r['tracking_id'],
// 			  'person_id' => $r['person_id'],
// 			  'tracking_date' => substr($r['tracking_date'], 0, 4) . '/' . substr($r['tracking_date'], 4, 2) . '/' . substr($r['tracking_date'], 6),
// 			  'tracking_time' => substr($r['tracking_time'], 0, 2) . ':' . substr($r['tracking_time'], 2, 2) . ':' . substr($r['tracking_time'], 4),
// 			  'period' => $r['period'],
// 			  'type' => $r['type'],
// 			  'supplier' => $r['supplier'],
// 			  'name' => $r['name'],
// 			  'link' => $r['link'],
// 			//   'order' => $this->getOrderCount($r['tracking_date']), // Assuming getOrderCount is a separate function
// 			  'order' => $var_order,
// 		  ];
// 	// 	  echo "<pre>";
// 	// print_r($d);
// 	  }

// 	  return json_encode($d); // Assuming you want to return JSON
//   }

//   private function getOrderCount($trackingDate)
//   {
// 	  $db = Database::connect();

// 	  $result = $db->table('epos_orders')->where('order_date', $r->tracking_date)->get()->getNumRows();
// 	//   $result = $db->table('epos_orders')->where('order_date', $trackingDate)->countAllResults();

// 	  return $result;
//   }

 
  
  // Get Current Period from database
  function get_current_period(){ 
	$db = Database::connect();

	return $db->table('epos_tracking')->orderBy('period','desc')->limit('1')->get()->getRow()->period;
  }
  
  
	
	/////////////////////////////////// HOME Category Nav ----- START
	function fetch_all_categories($view_mode='grid') 
	{
		$db = Database::connect();
		$branch = session()->get('branch');

		$query = "SELECT * FROM epos_categories WHERE parent_id=0 AND display=1 AND alias!=''";
		if(!empty($branch)) {
			$query .= " AND FIND_IN_SET($branch,branches)>0";
		}
		$query .= " ORDER BY category_name ASC";
	    $results = $db->query($query);
        $c=0;

		$data = "";
		$data2 = "";

		foreach($results->getResult() as $res){
		  $catname = strtolower($res->alias);			    
		  //$catname = preg_replace("/[.,&\s\/_]+/", "-", $catname);
		  $data .= '<li><a class="dropdown-trigger" href="'.base_url().'products/index?search_mode=default&sort_key=3&category_id='.$res->category_id.'&offset=0&per_page=30&view_mode='.$view_mode.'" data-target="c_'.$catname.'">'
		  		. $catname . '<i class="material-icons m-0 expand_more">expand_more</i>'
				. '</a></li>';
		  
		  $data2 .= '<ul id="c_'.$catname.'" class="dropdown-content">';

		  $query2 = "SELECT * FROM epos_categories WHERE parent_id=".$res->category_id . " AND display=1 ";
		  if(!empty($branch)) {
			$query2 .= "AND FIND_IN_SET($branch,branches)>0 ";
		  }
		  $query2 .= "ORDER BY category_name ASC";
		  $results2 = $db->query($query2);

		  foreach($results2->getResult() as $res2){
		  	$data2 .= '<li><a href="'.base_url().'products/index?search_mode=default&sort_key=3&category_id='.$res2->category_id.'&offset=0&per_page=50&view_mode='.$view_mode.'">'.$res2->category_name.'</a></li>';
		  }
		  $data2 .='</ul>';
		  
		}
		
		return $data.$data2;
	}
	/////////////////////////////////// HOME Category Nav ----- END
  
}