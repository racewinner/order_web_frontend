<?php
	use App\Controllers\Home;
	$home = new Home();
	/* first check for empty. $featured variable is $data['prod_id'] that we sent from the controller to the view... */
	if(!empty($products)){
	  $c =1;
	  foreach($products as $featured){ // featured is an object.	  
			if($featured->f_position > $c){
				for($i=$c; $i<=$featured->f_position-1; $i++){
					echo '<div class="f_item '.$i.'"><img src="/images/advertise-image.jpg"><br />
					  <div>&nbsp;
					  </div>
					  <span>&nbsp;</span></div>';
			     	}
			 	$c = $featured->f_position+1;
	           }
	        else{ $c = $c + 1; }
			
			$cart_prod_quantity = $home->get_cart_quantities($featured->prod_code);	
		    echo '<div class="f_item '.$featured->f_position.'"><img src="'.$img_host.'/product_images/'.$featured->image_url.'?v='.$featured->image_version.'"><br />
		      <div><a href="javascript:void()" onclick="inc_quantity(2,'.$featured->prod_id.', \''.$featured->prod_code.'\', \''.$featured->prod_desc.'\');">
			          <i class="material-icons remove">remove_circle</i></a>
			       <a href="javascript:void()" id ="pid_'.$featured->prod_code.'"><span style="display: inline-block;font-size:13px;top:-7px;position:relative;color:#666; padding:0px 5px; border-bottom:#eee 1px solid; background:#f9f9f9; min-width:25px;">'.$cart_prod_quantity.'</span></a>
			       <a href="javascript:void()" onclick="inc_quantity(1,'.$featured->prod_id.', \''.$featured->prod_code.'\', \''.$featured->prod_desc.'\');">
				      <i class="material-icons">add_circle</i></a>
		      </div>
		      '.$featured->prod_desc;
			  if( !$home->is_guest() ){
			  	  echo '<span>£'.$featured->prod_sell.'</span>';
			  }
			  echo '</div>';
	  }
	  if($c==14){
		  echo '<div class="f_item '.$c.'"><img src="/images/advertise-image.jpg"><br />
					  <div>&nbsp;
					  </div>
					  <span>&nbsp;</span></div>';
		  }
	}
	else{ echo 'Empty'; } 
?>