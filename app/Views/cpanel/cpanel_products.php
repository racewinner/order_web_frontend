<?php
    // First item of each category cannot be 404 image
	echo'<form name="featuredform" id="featuredform" method="post" onsubmit="return false">';
	if(!empty($prod_id)){
	  //$c = 1; \
	  echo '</div><h4 class="f_title">New Products</h4><div class="f_container">';
	  $t = 0;
	  function search($array, $key, $value) { 
		$arrIt = new RecursiveArrayIterator($array); 
		$it = new RecursiveIteratorIterator($arrIt); 
		$result = [];
		foreach ($it as $sub) { 
			$subArray = $it->getSubIterator(); 
			if ($subArray[$key] === $value) { 
				$result[] = iterator_to_array($subArray); 
			 } 
		} 
		return $result; 
	  } 
	  
	  
	  //print_r(array_map("myfunction",$prod_id));
	  for($i=0; $i<=7; $i++){
		  $j = $i+1;
		 //if( array_search($j, array_column($prod_id, 'id')) === false ){
			$disable = '';
	     if(search($prod_id, 'id', $j)){
		       echo '<div class="f_item '.$j.' '.$disable.'"><img src="/images/advertise-image.jpg"><br />
						  <div>
								  <input type="text" name="e_'.$j.'" value="'.array_search($j, array_column($prod_id, 'id')).'" onclick="this.select()"
										 style="background:#efefef; margin-bottom:5px; text-align:center;">
						  </div>
						  <span>&nbsp;</span></div>';	
						  
		  }
		  else{ 
		  
			  echo '<div class="f_item '.$j.' '.$disable.'"><img src="'.$img_host.'/product_images/100px/'.intval(substr($prod_id[$t]->prod_code,1)).'.jpg"><br />
					  <div>
							  <input type="text" name="e_'.$j.'" value="'.$prod_id[$t]->prod_code.'" onclick="this.select()"
									 style="background:#efefef; margin-bottom:5px; text-align:center;">
					  </div>
					  '.$prod_id[$t]->prod_desc.'<span>£'.$prod_id[$t]->prod_sell.'</span></div>';		
					  $t = $t+1;
		  }
	  }
	  $sid = session()->get('manager_id');
	  // permission for manager to edit only specific items
	  /*$m1 = array(1,2,8,9,15,16,22,23,29,30,36,37,43,44,50,51);
	  $m2 = array(3,4,5,10,11,12,17,18,19,24,25,26,31,32,33,38,39,40,45,46,47,52,53,54);
	  $m3 = array(6,7,13,14,20,21,27,28,34,35,41,42,48,49,55,56);*/
	  //echo print_r ($prod_id);
	  
      echo '<br style="clear:both" /><input type="submit" value="Update Featured Products" class="f_update_btn" onclick="update_featured();"></div><div class="f_line"></div><h4 class="f_title">Promotions</h4><div class="f_container">';	  
	  $t = 0;
	  for($i=8; $i<=15; $i++){
		  $j = $i+1;
		  //if( array_search($j, array_column($prod_id1, 'id')) === false ){
	      if(search($prod_id1, 'id', $j)){
		       echo '<div class="f_item '.$j.' '.$disable.'"><img src="/images/advertise-image.jpg"><br />
						  <div>
								  <input type="text" name="e_'.$j.'" value="'.array_search($j, array_column($prod_id1, 'id')).'" onclick="this.select()"
										 style="background:#efefef; margin-bottom:5px; text-align:center;">
						  </div>
						  <span>&nbsp;</span></div>';	
						  
		  }

		  else{ 
			$substr = array_key_exists($t, $prod_id1);
			  echo '<div class="f_item '.$j.' '.$disable.'">
			  <img src="'.$img_host.'/product_images/100px/'
			  .intval(substr(($substr ? $prod_id1[$t]->prod_code : ''),1)).
			  '.jpg"><br /><div><input type="text" name="e_'.$j.'" value="'.($substr ? $prod_id1[$t]->prod_code : '').'" onclick="this.select()" style="background:#efefef; margin-bottom:5px; text-align:center;"></div>'.($substr ? $prod_id1[$t]->prod_desc : '').'<span>£'.($substr ? $prod_id1[$t]->prod_sell : '').'</span></div>';		
				$t = $t+1;
		  }
	  }
	  /*foreach($prod_id as $featured){ // featured is an object.	  
	    if($featured->id <= 0){
	    $f_category = $featured->f_category;							
		   echo '<div class="f_item '.$featured->id.' '.$disable.'"><img src="/product_images/100px/'.intval(substr($featured->prod_code,1)).'.jpg"><br />
				  <div>
				          <input type="text" name="e_'.$featured->id.'" value="'.$featured->prod_code.'" onclick="this.select()"
						         style="background:#efefef; margin-bottom:5px; text-align:center;">
				  </div>
				  '.$featured->prod_desc.'<span>£'.$featured->prod_sell.' '.$featured->id.'</span></div>';			
		 }
	  }
	
	  foreach($prod_id1 as $featured){ // featured is an object.	  
	    if($featured->id <= 16){
	    $f_category = $featured->f_category;	
		   echo '<div class="f_item '.$featured->id.' '.$disable.'"><img src="/product_images/100px/'.intval(substr($featured->prod_code,1)).'.jpg"><br />
				  <div>
				          <input type="text" name="e_'.$featured->id.'" value="'.$featured->prod_code.'" onclick="this.select()"
						         style="background:#efefef; margin-bottom:5px; text-align:center;">
				  </div>
				  '.$featured->prod_desc.'<span>£'.$featured->prod_sell.' '.$featured->id.'</span></div>';			
		 }
	  }*/
			/*if( ($c == 8 || $c == 16 || $c == 42 || $c == 56) && $featured->id > $c){						
				$this->db->select('f_id,f_category,f_position'); $this->db->where('id', $i); $this->db->order_by("f_position", "asc");
				$q = $this->db->get('epos_featured_items');
				if($q->num_rows() > 0){ foreach ($q->result() as $row){	$fid = $row->f_id; } }
				if($sid==5000 || ($sid==5001 && in_array($c, $m1) ) || ($sid==5002 && in_array($c, $m2) ) || ($sid==5003 && in_array($c, $m3) )){ 
		           $disable = ''; } else { $disable = 'disable'; }
				
				echo '<div class="f_item '.$c.' '.$disable.'"><img src="/images/advertise-image.jpg"><br />
						  <div>
								  <input type="text" name="e_'.$c.'" value="'.$fid.'" onclick="this.select()"
										 style="background:#efefef; margin-bottom:5px; text-align:center;">
						  </div>
						  <span>&nbsp;</span></div>';
						  $c = $c +1;
				}	
						
		   if($featured->id == '1')  { echo '</div><h4 class="f_title">New Products</h4><div class="f_container">';}
		   if($featured->id == '15') { echo '<input type="submit" value="Update Featured Products" class="f_update_btn" onclick="update_featured();">';
			                           echo '</div><div class="f_line"></div><h4 class="f_title">Promotions</h4><div class="f_container">';}
		   if($featured->id == '29') { echo '<input type="submit" value="Update Featured Products" class="f_update_btn" onclick="update_featured();">';
			                           echo '</div><div class="f_line"></div><h4 class="f_title">Top Products</h4><div class="f_container">';}
		   if($featured->id == '43') { echo '<input type="submit" value="Update Featured Products" class="f_update_btn" onclick="update_featured();">';
			                           echo '</div><div class="f_line"></div><h4 class="f_title">Day-Today</h4><div class="f_container">';}
		   if($featured->id > $c && ($featured->id < 9 && $featured->id > 14) ){
				for($i=$c; $i<=$featured->id-1; $i++){			
				    if($featured->id !=14){
					$this->db->select('f_id,f_category,f_position'); $this->db->where('id', $i); $this->db->order_by("f_position", "asc");
					$q = $this->db->get('epos_featured_items');
					if($q->num_rows() > 0){ foreach ($q->result() as $row){	$fid = $row->f_id; } }
					if($sid==5000 || ($sid==5001 && in_array($i, $m1) ) || ($sid==5002 && in_array($i, $m2) ) || ($sid==5003 && in_array($i, $m3) )){ 
					   $disable = ''; } else { $disable = 'disable'; }
					echo '<div class="f_item '.$i.' '.$disable.'"><img src="/images/advertise-image.jpg"><br />
						  <div>
								  <input type="text" name="e_'.$i.'" value="'.$fid.'" onclick="this.select()"
										 style="background:#efefef; margin-bottom:5px; text-align:center;">
						  </div>
						  <span>&nbsp;</span></div>';
			 		$c = $featured->id+1;	
						  if($i == 15) { echo '<input type="submit" value="Update Featured Products" class="f_update_btn" onclick="update_featured();">';
							             echo '</div><div class="f_line"></div><h4 class="f_title">Promotions</h4><div class="f_container">';}
						  if($i == 29) { echo '<input type="submit" value="Update Featured Products" class="f_update_btn" onclick="update_featured();">';
							             echo '</div><div class="f_line"></div><h4 class="f_title">Top Products</h4><div class="f_container">';}
						  if($i == 43) { echo '<input type="submit" value="Update Featured Products" class="f_update_btn" onclick="update_featured();">';
							             echo '</div><div class="f_line"></div><h4 class="f_title">Day-Today</h4><div class="f_container">';}
			     	}
				}
				
						
			}else{ $c = $c + 1; }
			
		    if($sid==5000 || ($sid==5001 && in_array($featured->id, $m1) ) || ($sid==5002 && in_array($featured->id, $m2) ) || ($sid==5003 && in_array($featured->id, $m3) )){ 
		       $disable = ''; } else { $disable = 'disable'; }	
		   
			echo '<div class="f_item '.$featured->id.' '.$disable.'"><img src="/product_images/100px/'.intval(substr($featured->prod_code,1)).'.jpg"><br />
				  <div>
				          <input type="text" name="e_'.$featured->id.'" value="'.$featured->prod_code.'" onclick="this.select()"
						         style="background:#efefef; margin-bottom:5px; text-align:center;">
				  </div>
				  '.$featured->prod_desc.'<span>£'.$featured->prod_sell.' '.$featured->id.'</span></div>';
			
		 }
	  }*/
	echo '<br style="clear:both" /><input type="submit" value="Update Featured Products" class="f_update_btn" onclick="update_featured();">';
	echo '</div>';
	}
	else{ echo 'Empty'; } 
	//echo '<input type="hidden" name="f_category" value="'.$f_category.'">';	
	echo'</form>';
?>