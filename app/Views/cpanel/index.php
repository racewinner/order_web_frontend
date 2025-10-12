<?php echo view("partial/header"); ?>
<style>
.tabs{border-bottom:#ff3535 1px solid;}
.tabs li{padding:0px 5px !important;}
.tabs .active{background:#ff3535 !important; color:#ffffff !important;}
.tabs a{background:#f7f7f7 !important; color:#999999 !important;}
.ui-dialog .add, .ui-dialog .ui-widget-header, #add_new_item, #edit_item{background:#039be5 !important;}
#delete_item{background:#ff3535 !important;}
#presell_item_data .dropdown-content{max-height:300px !important;}
#presell_item_data .dropdown-content li{min-height:20px !important;}
#presell_item_data .dropdown-content li > span{ padding:3px !important; font-size:11px !important; line-height:12px;}
#presell_item_data input:disabled { background: #dddddd;}
.redhead{background:#ff3535; color:#fff !important; font-size:18px !important; padding:10px !important; margin:15px !important; width:fit-content;}
.switch{margin: 20px 0px -55px 300px !important;}
button.reorder{ background:lightseagreen; margin-left:10px;}

@keyframes spinner { to {transform: rotate(360deg);} }
.spinner:before {
  content: '';
  box-sizing: border-box;
  position: absolute;
  top: 50%;
  left: 50%;
  width: 20px;
  height: 20px;
  margin-top: -10px;
  margin-left: -10px;
  border-radius: 50%;
  border: 2px solid #ccc;
  border-top-color: #000;
  animation: spinner .6s linear infinite;
}
#loader, #loading{
  display: block;
  position: relative;
  padding: 20px 15px;
  margin: 15px 0 -30px 0;
  overflow: auto;	
}
.tabs .tab a i{margin-top:10px !important;}
span{border-right:none !important;}
.print_date{display:none; font-size:15px;}

.print_buttons{padding:0px 17px;}
.print_buttons input{
	margin:0px 10px 0px 0px;
	padding: 5px 10px;
    border: 1px solid #25682a;
    background: #3FB449;
    background: -webkit-gradient(linear, left top, left bottom, from(#3FB449), to(#25682a));
    background: linear-gradient(to bottom, #3FB449 0%, #25682a 100%);
    color: #fff;
    font-weight: bold;}
   
   
@media only screen and (max-width: 990px){
	.tabs, .tabs .tab{height:fit-content !important;}
	.tabs .tab a{padding:3px 0px !important;}
    .tabs .tab a i{margin-top:0px !important;}
	.stats_details{display:block !important;}
}

@media print {
   header, footer, nav, .tabs, .print_buttons, .table-controls-legend, .table-controls{display:none !important;}
   .stats_details{display:block !important;}
   .select-wrapper ul, .select-wrapper select{display:none !important;}
   .print_date{display:inline-block;}
}
</style>

<?php 
   
    echo "<div>";
	echo "<script type='text/javascript'>";		
	echo "var tabledata = [";			
    
    echo $tabulator;
   
    echo"];";
    echo "</script></div>";    

?>

<script type="text/javascript">
$(document).ready(function(){ 

	$( "#dialog_form" ).dialog
	(
		{
			autoOpen:false ,
			height: 470 ,
			width: Math.min(520, $(window).width() * .95),
			modal: true ,
			open:function()
			{
				var prod_id = $('#prod_id_field').val();
			    $.ajax({
			        type : "POST"
			        , async : true
			        , url : "<?php echo site_url("$controller_name/get_new");?>"
					, dataType : "json"
			        , timeout : 30000
			        , cache : false
			        , data : "prod_id=" + prod_id
			        , error : function(request, status, error) {
						//alert("Presell data is not readable.");
						//$(this).dialog("close");
			        }
			        , success : function(response, status, request) {
			        	var contents = "<legend><?php echo lang("Main.presell_info"); ?></legend>";
			        	contents = contents + response.manage_table;
						//$('#presell_info').html(contents);
			        	if(response.completed == 0)
			        	{
//							var buttons = $(this).dialog('option' , 'buttons');
//							$.extend(buttons, { 'foo': function () { alert('foo'); } });
//							$(this).dialog("option", "buttons", buttons);
			        	}
			        }
			    });
			} ,
			buttons:{}

		}
	);
		
	//$( "#dialog_form" ).dialog("open");
	
	/*
	setTimeout(function(){
	  popup_dialog(1,1);
	}, 2000);	
	*/
	
	// Reordering header banners; update position via Up and Down arrows
	$("button.reorder").click(function(){
		bid = $(this).attr("id");
		action = bid.substr(0,2);
		pos = bid.substr(2);
		console.log( "Button ID:  " + bid );
		console.log( "Position:  " + pos );
		
		
	});
	

});

function popup_dialog(prod_id , add)
{
	$('#prod_id_field').val(prod_id);
	var buttons = $( "#dialog_form" ).dialog('option' , 'buttons');
		
	if(add == 0)
	{ 
		$('.ui-dialog-titlebar').removeClass('add');
		$.extend(buttons ,
			{
				'Edit Item': function (){
					//alert( $("#presell_form").serialize() );
					var empty = false;
					$('#presell_form').find(':input').each(function(){
					   if($(this).attr('name')!="shelf_life"){
						   if($(this).val()==""){
							  empty = true;
							  alert ("Please enter information in missing fields \nAll fields are mandatory");
							  //alert( $("#presell_form").serialize() );
							  return false;
							}
					   }
     				});
					if(empty == false){
						$("#presell_form input[name='unique_id'], #presell_form input[name='prod_code1'], #prod_level3, #is_disabled, #promo").removeAttr('disabled');
						$.ajax({
							url:"<?php echo base_url(); ?>presells_import/edit",
							method:"POST",
							data:$("#presell_form").serialize(),
							cache:false,
							processData:false,
							beforeSend:function(){
								$('#edit_item .ui-button-text').html('Editing Item...');
							},
							error: function (request, status, error) {
								alert(request.responseText);
							},					
							success:function(data)
							{
								if(data == "Unique ID already exist"){
									alert("Unique ID already exist");
									$('#edit_item .ui-button-text').html('Edit Item');
								}else{
									$('#edit_item .ui-button-text').html('Item Edited');
									location.reload();
								}
							}
						});					
						$("#presell_form input[name='unique_id'], #presell_form input[name='prod_code1'], #prod_level3, #is_disabled, #promo").prop("disabled", true);	 
					}
				},
				'Delete Item': function(){
					//alert( $("#presell_form").serialize() );
					var empty = false;
					$('#presell_form').find(':input').each(function(){
					   if($(this).attr('name')!="shelf_life"){
						   if($(this).val()==""){
							  empty = true;
							  //alert ("Please enter information in missing fields \nAll field are mandatory");
							  alert( $("#presell_form").serialize() );
							  return false;
							}
					   }
     				});
					if(empty == false){
						$.ajax({
							url:"<?php echo base_url(); ?>presells_import/delete",
							method:"POST",
							data:$("#presell_form").serialize(),
							cache:false,
							processData:false,
							beforeSend:function(){
								$('#delete_item .ui-button-text').html('Adding Item...');
							},
							error: function (request, status, error) {
								alert(request.responseText);
							},					
							success:function(data)
							{
								//alert("Delete response: " + data);
								//$('#add_presell')[0].reset();
								//$('#add_new_item').attr('disabled', false);
								$('#delete_item .ui-button-text').html('Item Deleted');
								location.reload();
							}
						})
					}
				}
			}
		);
	}
	else
	{		
		$('.ui-dialog-titlebar').addClass('add');
		$.extend( buttons, 
		    {   
				'Add New Item': function (){
					//alert( $("#presell_form").serialize() );
					var empty = false;
					$('#presell_form').find(':input').each(function(){
					   if($(this).attr('name')!="shelf_life" && $(this).attr('name')!="prod_id"){
						   if($(this).val()==""){
							  empty = true;
							  alert ("Please enter information in missing fields \nAll fields are mandatory");
							  //alert( $("#presell_form").serialize() );
							  return false;
							}
					   }
     				});
					if(empty == false){
						$("#presell_form input[name='unique_id'], #presell_form input[name='prod_code1'], #prod_level3, #is_disabled, #promo").removeAttr('disabled');
						$.ajax({
							url:"<?php echo base_url(); ?>presells_import/add",
							method:"POST",
							data:$("#presell_form").serialize(),
							cache:false,
							processData:false,
							beforeSend:function(){
								$('#add_new_item .ui-button-text').html('Adding Item...');
							},
							error: function (request, status, error) {
								alert(request.responseText);
							},					
							success:function(data)
							{								
								if(data == "Unique ID already exist"){
									alert("Unique ID already exist");
									$('#add_new_item .ui-button-text').html('Add New Item');
								}else{
									$('#add_new_item .ui-button-text').html('Item Added [-]');
									location.reload();
								}
							}
						});
						$("#presell_form input[name='unique_id'], #presell_form input[name='prod_code1'], #prod_level3, #is_disabled, #promo").prop("disabled", true);	 
					}
				}
			}
		);
	}
	$( "#dialog_form" ).dialog("option", "buttons", buttons);
	$('.ui-dialog-buttonpane button:contains(Add New Item)').attr("id","add_new_item");  
	$('.ui-dialog-buttonpane button:contains(Edit Item)').attr("id","edit_item");  
	$('.ui-dialog-buttonpane button:contains(Delete Item)').attr("id","delete_item");  
	
		
	if( $( '.ui-dialog .ui-dialog-buttonpane .ui-button' ).size() >= 1 ){	
	    $("#presell_form input[name='unique_id'], #presell_form input[name='prod_code1'], #prod_level3, #is_disabled, #promo").prop("disabled", true);	 
		$("#presell_form input[name='prod_code'], #price_list").change(function() {
			recalibrate_unique_id();
		});
		function recalibrate_unique_id(){
      debugger
			p = $("#presell_form input[name='period_reference']").val();
			c = $("#presell_form input[name='prod_code']").val();
			t = $("#price_list").val();
			d = p.substr(-2,2);
			d = d + p.substring(1, p.lastIndexOf("-") );
			
			if(t == "08" || t == "10" || t == "11"){ d = d + "dd";} else
			if(t == "05"){ d = d + "q5";} else
			if(t == "07"){ d = d + "sp";} else
			if(t == "09"){ d = d + "ch";} else
			if(t == "12"){ d = d + "us";} else
			if(t == "999"){ d = d + "cc";}
			d = d + t.padStart(3, '0') + c;
			
			$("#presell_form input[name='unique_id']").val(d);
			$("#presell_form input[name='prod_code1']").val( parseInt(c.substr(1)) );
			$("#prod_level3").val(c.substring(0,1));
		}
		if(add==1){		
			$( "#ui-id-1" ).html("Add Presell Item");	
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(1) .ui-button-text').text() == "Edit Item"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(1)').hide();
			}	
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(2) .ui-button-text').text() == "Edit Item"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(2)').hide();
			}
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(3) .ui-button-text').text() == "Edit Item"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(3)').hide();
			}
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(1) .ui-button-text').text() == "Delete Item"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(1)').hide();
			}	
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(2) .ui-button-text').text() == "Delete Item"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(2)').hide();
			}			
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(3) .ui-button-text').text() == "Delete Item"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(3)').hide();
			}			
			
			
			$("#is_disabled").val('N').formSelect();
			$("#promo").val('Y').formSelect();
			$("#presell_form input[name='g_qty']").val(0);
			$("#presell_form input[name='g_min']").val(0);
			$("#presell_form input[name='g_max']").val(999);
			$("#presell_form input[name='s_qty']").val(0);
			$("#presell_form input[name='s_min']").val(0);
			$("#presell_form input[name='s_max']").val(999);
			$("#presell_form input[name='m_qty']").val(0);
			$("#presell_form input[name='m_min']").val(0);
			$("#presell_form input[name='m_max']").val(999);
			$("#presell_form input[name='l_qty']").val(0);
			$("#presell_form input[name='l_min']").val(0);
			$("#presell_form input[name='l_max']").val(999);
			$("#presell_form input[name='e_qty']").val(0);
			$("#presell_form input[name='e_min']").val(0);
			$("#presell_form input[name='e_max']").val(999);
			
		}else{	
	        $( "#ui-id-1" ).html("Edit Presell Item");
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(1) .ui-button-text').text() == "Add New Item"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(1)').hide();
			}
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(2) .ui-button-text').text() == "Add New Item"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(2)').hide();
			}	
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(3) .ui-button-text').text() == "Add New Item"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(3)').hide();
			}		
			$("#presell_form :input").css('opacity',0.3);
			$.ajax({
				url:"<?php echo base_url(); ?>presells_import/get",
				method:"POST",
				data: "prod_id=" + $("#prod_id_field").val(),
				error: function (request, status, error) {
					alert(request.responseText);
				},					
				success:function(data)
				{
					var d = $.parseJSON( data );
					$(".ui-dialog-buttonpane > div > span:first-child").text(d.period_ref);
					$("#presell_form input[name='period_reference']").val(d.period_ref);
					$("#presell_form input[name='unique_id']").val(d.unique_id);
					$("#presell_form input[name='prod_code']").val(d.prod_code);
					$("#presell_form input[name='prod_code1']").val(d.prod_code1);
					$("#price_list").val(d.price_list).formSelect();
					$("#presell_form input[name='wholesale']").val(d.wholesale);
					$("#presell_form input[name='retail']").val(d.retail);
					$("#presell_form input[name='prod_sell']").val(d.prod_sell);
					$("#presell_form input[name='prod_rrp']").val(d.prod_rrp);
					$("#group_desc").val(d.group_desc).formSelect();
					$("#presell_form input[name='prod_desc']").val(d.prod_desc);
					$("#presell_form input[name='prod_pack_desc']").val(d.prod_pack_desc);
					$("#presell_form input[name='shelf_life']").val(d.shelf_life);
					$("#presell_form input[name='prod_uos']").val(d.prod_uos);
					$("#vat_code").val(d.vat_code).formSelect();
					$("#presell_form input[name='prod_level3']").val(d.prod_level3);
					$("#presell_form input[name='p_size']").val(d.p_size);
					$("#is_disabled").val(d.is_disabled).formSelect();
					$("#promo").val(d.promo).formSelect();
					$("#van").val(d.van).formSelect();
					$("#presell_form input[name='g_qty']").val(d.g_qty);
					$("#presell_form input[name='g_min']").val(d.g_min);
					$("#presell_form input[name='g_max']").val(d.g_max);
					$("#presell_form input[name='s_qty']").val(d.s_qty);
					$("#presell_form input[name='s_min']").val(d.s_min);
					$("#presell_form input[name='s_max']").val(d.s_max);
					$("#presell_form input[name='m_qty']").val(d.m_qty);
					$("#presell_form input[name='m_min']").val(d.m_min);
					$("#presell_form input[name='m_max']").val(d.m_max);
					$("#presell_form input[name='l_qty']").val(d.l_qty);
					$("#presell_form input[name='l_min']").val(d.l_min);
					$("#presell_form input[name='l_max']").val(d.l_max);
					$("#presell_form input[name='e_qty']").val(d.e_qty);
					$("#presell_form input[name='e_min']").val(d.e_min);
					$("#presell_form input[name='e_max']").val(d.e_max);
					$("#presell_form :input").css('opacity',1);
				}
			})
		}
	}
	
	
	$( ".infoText" ).remove();
    $(".ui-dialog-buttonpane").append("<div class='infoText' style='padding:10px 0px 0px 15px; font-size:12px; display:inline-block;'>Period Reference: <span></span><br /><span style='font-size:10px;color:#ff0000;word-spacing:5px;'>G:Group S:Small M:Medium L:Large E:Elite</span></div>");
	$(".ui-dialog-buttonpane > div > span:first-child").text($("#period_reference_add").val());
	$("#period_reference_field").val($("#period_reference_add").val());
	
	$( "#dialog_form" ).dialog("open");
}


</script>
<div class="row">
<div style="display:none;">
<a id="l_1" href='<?php echo base_url()."cpanel";?>#feature'>Feature</a> | 
<a id="l_2" href='<?php echo base_url()."cpanel";?>#slider'>Slider</a> | 
<a id="l_3" href='<?php echo base_url()."cpanel";?>#slider'>Sponsors</a> | 
<a id="l_4" href='<?php echo base_url()."cpanel";?>#banners'>Banners</a> | 
<a id="l_5" href='<?php echo base_url()."cpanel";?>#slider'>Promos</a> | 
<a id="l_6" href='<?php echo base_url()."cpanel";?>#presell'>Presell</a> | 
<a id="l_7" href='<?php echo base_url()."cpanel";?>#presell'>Settings</a> | 
<a id="l_8" href='<?php echo base_url()."cpanel";?>#tracking'>Tracking</a> | 
<a id="l_9" href='<?php echo base_url()."cpanel";?>#keydates'>Key Dates</a>
</div>
<div class="col s12">
  <ul class="tabs">
    <li class="tab col s1"><a id="t_1" href="<?php echo base_url()."cpanel";?>#feature" ><i class="material-icons" style="margin-top:10px;">view_module</i></a></li>
    <li class="tab col s1"><a id="t_2" href="<?php echo base_url()."cpanel";?>#slider" ><i class="material-icons" style="margin-top:10px;">slideshow</i></a></li>
    <li class="tab col s1"><a id="t_3" href="<?php echo base_url()."cpanel";?>#sponsors" ><i class="material-icons" style="margin-top:10px;">view_week</i></a></li>
    <li class="tab col s1"><a id="t_4" href="<?php echo base_url()."cpanel";?>#banners" ><i class="material-icons" style="margin-top:10px;">burst_mode</i></a></li>
    <li class="tab col s1"><a id="t_5" href="<?php echo base_url()."cpanel";?>#promotions" ><i class="material-icons" style="margin-top:10px;">import_contacts</i></a></li>
    <li class="tab col s1"><a id="t_6" href="<?php echo base_url()."cpanel";?>#presell" ><i class="material-icons" style="margin-top:10px;">local_parking</i></a></li>
    <li class="tab col s1"><a id="t_7" href="<?php echo base_url()."cpanel";?>#settings" ><i class="material-icons" style="margin-top:10px;">settings</i></a></li>
    <li class="tab col s1"><a id="t_8" href="<?php echo base_url()."cpanel";?>#tracking" ><i class="material-icons" style="margin-top:10px;">equalizer</i></a></li>
    <li class="tab col s1"><a id="t_9" href="<?php echo base_url()."cpanel";?>#keydates" ><i class="material-icons" style="margin-top:10px;">date_range</i></a></li>

    <li class="tab col s1"><a id="t_10" href="<?php echo base_url()."cpanel";?>#top_ribbon" ><i class="material-icons" style="margin-top:10px;"></i></a></li>
    <li class="tab col s1"><a id="t_11" href="<?php echo base_url()."cpanel";?>#promotion_products" ><i class="material-icons" style="margin-top:10px;"></i></a></li>
    <li class="tab col s1"><a id="t_12" href="<?php echo base_url()."cpanel";?>#bottom_banner" ><i class="material-icons" style="margin-top:10px;"></i></a></li>
  </ul>
  
</div>
<div id="feature" class="col s12">
    <div id="order_total_div">
        <div><h2>Featured Products</h2></div>	
    </div>
    <div class="featured-products">
         <div id="f_links">
             <ul>
                 <li><a href="javascript:void();" onclick="load_all_products()">Home</a></li>
             </ul>
         </div>
        <div id="featured" class="f_container"></div>
    </div>
</div>
<div id="slider" class="col s12">
	<div class="fullwidth-container">
     	<div class="inner-wrapper">
		  <?php if( session()->get('manager_id')==5000){ ?>	              
              <h4>Header Slider</h4>
              <p id="msg2"></p>
              <form name="sliders" id="sliders" onsubmit="return false;" enctype="multipart/form-data" method="post" accept-charset="utf-8">   
                  <div class="featured">
					  <?php for($i=1; $i<=$slides; $i++){
              			if(file_get_contents($img_host.'/images/banner/'.$i.'.jpg') !== false){  $ext = "jpg";  }else{ $ext  = "gif"; }	
			          ?>
                      <div class="block b"  style="background-image:url('<?php echo $img_host; ?>/images/banner/<?php echo $i; ?>.<?php echo $ext; ?>?<?php date('mdY H'); ?>')">&nbsp;</div>      
                      <div class="row">
                           <div class = "input-field col s6 m6 l3" style="margin-bottom:-10px">
                              <i class="material-icons prefix">beenhere</i>
                              <input id="s<?php echo $i; ?>_name" type="text" class="validate" value="<?php echo ${"s".$i."_name"}; ?>">
                              <label for="s<?php echo $i; ?>_name">Slide<?php echo $i; ?> Supplier</label>
                           </div>
                           <div class = "input-field col s6 m6 l3 period" style="margin-bottom:-10px">
                              <i class="material-icons prefix">gps_fixed</i>
                                <select id="s<?php echo $i; ?>_period">
								  <option value="auto" <?php if(${"s".$i."_period"} == "auto"){ echo 'selected';} ?>>Auto</option>
								  <?php for($j=1; $j<=17; $j++){ ?>
                                  <option value="<?php echo $j ?>" <?php if(${"s".$i."_period"} == $j){ echo 'selected';} ?>><?php echo $j ?></option>
                                  <?php } ?>
                                </select>
                                <label>Slide<?php echo $i; ?> Period</label>
                           </div>
                           <div class = "file-field input-field col s12 m12 l6">
                              <div class = "btn black"><span>Browse</span><input type="file" id="slider<?php echo $i; ?>" name="slider<?php echo $i; ?>"/></div>        
                              <div class = "file-path-wrapper"><input class = "file-path validate" type = "text" placeholder = "Upload file" /></div>
                           </div>
                           <div class="input-field col s12 m12 l6" style="margin-top:0px;">
                              <i class="material-icons prefix">storage</i>
                              <input id="s<?php echo $i; ?>_ids" type="text" class="validate" value="<?php echo ${"s".$i."_ids"}; ?>">
                              <label for="s<?php echo $i; ?>_ids">Slide<?php echo $i; ?> Products</label>
                              <div class="input-field">
                              <i class="material-icons prefix">date_range</i><input id="s<?php echo $i; ?>_date" name="s<?php echo $i; ?>_date" type="text" class="datepicker" value="<?php echo ${"s".$i."_date"}; ?>">
                              <label for="s<?php echo $i; ?>_date" style="margin-left:2.3rem !important;">Expiry Date</label>
                              </div>
                              <div style="margin-top:20px;">
                                <button class="btn black waves-effect waves-light" type="submit" name="action" onclick="do_image_uploader(<?php echo $i; ?>, 1);">Upload Slide <?php echo $i; ?>
                                    <i class="material-icons right">send</i>
                                </button> <button class="reorder btn" id="up<?php echo $i; ?>" <?php if($i == 1){ echo "disabled";} ?>><i class="material-icons">arrow_upward</i></button> <button class="reorder btn" id="dn<?php echo $i; ?>" <?php if($i == $slides){ echo "disabled";} ?>><i class="material-icons">arrow_downward</i></button>
                    		  </div>
                              <div class="red-text accent-3-text"><br />5760px X 750px</div>
                           </div>
                      </div>
              <?php } ?>
              <br style="clear:both;">
              </div>
              </form>
   	 	</div>
	</div>
    
</div>

<div id="sponsors" class="col s12">
	<div class="fullwidth-container" style="background:#fff;">
    	 <div class="inner-wrapper">
          <h4>United Sponsors</h4>
              <p id="msg"></p>
                  <form name="sponsors" id="sponsors" onsubmit="return false;" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                  <div class="featured">                  
					<?php for($i=1; $i<=$sponsors; $i++){ 
               $imagePath = $img_host . '/images/featured/large/' . $i . '.jpg';
               if (file_exists($imagePath)) {
			  			if(file_get_contents($img_host.'/images/featured/large/'.$i.'.jpg') !== false){  $ext = "jpg";  }else{ $ext  = "gif"; }	  }
		            ?>
                    <div class="block b"   style="background-image:url('<?php echo $img_host; ?>/images/featured/large/<?php echo $i; ?>.<?php echo $ext; ?>')">&nbsp;</div>
                      <div class="row">
                           <div class = "input-field col s6 m6 l3" style="margin-bottom:-10px">
                              <i class="material-icons prefix">beenhere</i>
                              <input id="sp<?php echo $i; ?>_name" type="text" class="validate" value="<?php echo ${"sp".$i."_name"}; ?>">
                              <label for="sp<?php echo $i; ?>_name">Slide<?php echo $i; ?> Supplier</label>
                           </div>
                           <div class = "input-field col s6 m6 l3 period" style="margin-bottom:-10px">
                              <i class="material-icons prefix">gps_fixed</i>
                                <select id="sp<?php echo $i; ?>_period">
								  <option value="auto" <?php if(${"sp".$i."_period"} == "auto"){ echo 'selected';} ?>>Auto</option>
								  <?php for($j=1; $j<=17; $j++){ ?>
                                  <option value="<?php echo $j ?>" <?php if(${"sp".$i."_period"} == $j){ echo 'selected';} ?>><?php echo $j ?></option>
                                  <?php } ?>
                                </select>
                                <label>Slide<?php echo $i; ?> Period</label>
                           </div>
                           <div class = "file-field input-field col s12 m12 l6">
                              <div class = "btn black"><span>Browse</span><input type="file" id="sponsor<?php echo $i; ?>" name="sponsor<?php echo $i; ?>"/></div>                              <div class = "file-path-wrapper"><input class = "file-path validate" type = "text" placeholder = "Upload file" /></div>
                           </div>
                           <div class="input-field col s12 m12 l6" style="margin-top:0px;">
                           <i class="material-icons prefix">storage</i>
                           <input id="sp<?php echo $i; ?>_ids" type="text" class="validate" value="<?php echo ${"sp".$i."_ids"}; ?>">
                           <label for="sp<?php echo $i; ?>_ids">Sponsor<?php echo $i; ?> Products</label>
                           <div style="margin-top:20px">
                           <button class="btn black waves-effect waves-light" type="submit" name="action" onclick="do_image_uploader(<?php echo $i; ?>, 2);">Upload Sponsor <?php echo $i; ?>
                                <i class="material-icons right">send</i>
                            </button>
                            </div>
                            <div class="red-text accent-3-text"><br />500px X 357px</div>
                        </div>
                      </div>
                      <?php } ?>
                      <br style="clear:both;">
                  </div>
                  </form>                  
    	 </div>
	</div>
</div>



<div id="banners" class="col s12">
	<div class="fullwidth-container">
     	<div class="inner-wrapper">		              
              <h4>Category Banners</h4>     
              <form name="banners" id="banners" onsubmit="return false;" enctype="multipart/form-data" method="post" accept-charset="utf-8">   
                  <div class="featured">
                  <?php
                      foreach($group as $r){
						    $name = $r->category_name;
							$id = $r->category_id;
							//if(file_get_contents($img_host.'/images/category/'.$id.'.jpg') !== false){  $ext = "jpg";  }else{ $ext  = "gif"; }
							if(getRemoteFilesize($img_host.'/images/category/'.$id.'.jpg') != -1){  $ext = "jpg";  }else{ $ext  = "gif"; }	
				      ?>                  
                      <div class="block b"  style="background-image:url('<?php echo $img_host; ?>/images/category/<?php echo $id; ?>.<?php echo $ext; ?>')">&nbsp;</div>      
                      <div class="row">
                           <div class = "input-field col s6 m6 l3" style="margin-bottom:-10px">
                              <i class="material-icons prefix">beenhere</i>
                              <input id="c<?php echo $id; ?>_name" type="text" class="validate" value="<?php echo strtoupper($name); ?>">
                              <label for="c<?php echo $id; ?>_name">Cat<?php echo $id; ?> Supplier</label>
                           </div>
                           <div class = "input-field col s6 m6 l3 period" style="margin-bottom:-10px">
                              <i class="material-icons prefix">gps_fixed</i>
                                <select id="c<?php echo $id; ?>_period">
								  <option value="auto" <?php if(isset(${"c".$id."_period"}) && ${"c".$id."_period"} == "auto"){ echo 'selected';} ?>>Auto</option>
								  <?php for($j=1; $j<=17; $j++){ ?>
                                  <option value="<?php echo $j ?>" <?php if(isset(${"c".$id."_period"}) && ${"c".$id."_period"} == $j){ echo 'selected';} ?>><?php echo $j ?></option>
                                  <?php } ?>
                                </select>
                                <label>Cat<?php echo $id; ?> Period</label>
                           </div>
                           <div class = "file-field input-field col s12 m12 l6">
                              <div class = "btn black"><span>Browse</span><input type="file" id="cat<?php echo $id; ?>" name="cat<?php echo $id; ?>"/></div>        
                              <div class = "file-path-wrapper"><input class = "file-path validate" type = "text" placeholder = "Upload file" /></div>
                           </div>
                           <div class="input-field col s12 m12 l6" style="margin-top:0px;">
                              <i class="material-icons prefix">storage</i>
                              <input id="c<?php echo $id; ?>_ids" type="text" class="validate" value="<?php echo ${"c".$id."_ids"}; ?>">
                              <label for="c<?php echo $id; ?>_ids">Cat<?php echo $id; ?> Products</label>
                              <div style="margin-top:20px;">
                                <button class="btn black waves-effect waves-light" type="submit" name="action" onclick="do_image_uploader(<?php echo $id; ?>, 4);">Upload Cat<?php echo $id; ?> Banner
                                    <i class="material-icons right">send</i>
                                </button>
                    		  </div>
                              <div class="red-text accent-3-text"><br />5760px X 750px</div>
                           </div>
                      </div>
                      <?php } ?>
                    <br style="clear:both;">
                 </div>
              </form>
   	 	</div>
	</div>
</div>

<div id="promotions" class="col s12">
    <div class="fullwidth-container">
         <div class="inner-wrapper">                  
              <h4>United Promotions</h4>
                  <p id="msg2"></p>
                  <?php 
				   /*
				   if(file_get_contents($img_host.'/images/promotion/1.jpg') !== false)   { $ext1   = "jpg";  }else{ $ext1   = "gif"; }
				   if(file_get_contents($img_host.'/images/promotion/1_1.jpg') !== false) { $ext1_1 = "jpg";  }else{ $ext1_1 = "gif"; }
				   if(file_get_contents($img_host.'/images/promotion/2.jpg') !== false)   { $ext2   = "jpg";  }else{ $ext2   = "gif"; }
				   if(file_get_contents($img_host.'/images/promotion/2_1.jpg') !== false) { $ext2_1 = "jpg";  }else{ $ext2_1 = "gif"; }
				   if(file_get_contents($img_host.'/images/promotion/3.jpg') !== false)   { $ext3   = "jpg";  }else{ $ext3   = "gif"; }
				   if(file_get_contents($img_host.'/images/promotion/3_1.jpg') !== false) { $ext3_1 = "jpg";  }else{ $ext3_1 = "gif"; }
				   if(file_get_contents($img_host.'/images/promotion/3a.jpg') !== false)  { $ext3a  = "jpg";  }else{ $ext3a  = "gif"; }
				   if(file_get_contents($img_host.'/images/promotion/3a_1.jpg') !== false){ $ext3a_1= "jpg";  }else{ $ext3a_1= "gif"; }
				   if(file_get_contents($img_host.'/images/promotion/4.jpg') !== false)   { $ext4 = "jpg";    }else{ $ext4   = "gif"; }
				   if(file_get_contents($img_host.'/images/promotion/4_1.jpg') !== false) { $ext4_1 = "jpg";  }else{ $ext4_1 = "gif"; }
				   if(file_get_contents($img_host.'/images/promotion/4a.jpg') !== false)  { $ext4a= "jpg";    }else{ $ext4a  = "gif"; }
				   if(file_get_contents($img_host.'/images/promotion/4a_1.jpg') !== false){ $ext4a_1= "jpg";  }else{ $ext4a_1= "gif"; }
				   if(file_get_contents($img_host.'/images/promotion/5.jpg') !== false)   { $ext5 = "jpg";    }else{ $ext5  = "gif";  }
				   if(file_get_contents($img_host.'/images/promotion/5_1.jpg') !== false) { $ext5_1 = "jpg";  }else{ $ext5_1 = "gif"; }
	          	   */
				   
				   if(pathinfo(parse_url($img_host.'/images/promotion/1.jpg', PHP_URL_PATH), PATHINFO_EXTENSION) == "jpg")   { $ext1   = "jpg";  }else{ $ext1   = "gif"; }
				   if(pathinfo(parse_url($img_host.'/images/promotion/1_1.jpg', PHP_URL_PATH), PATHINFO_EXTENSION) == "jpg") { $ext1_1 = "jpg";  }else{ $ext1_1 = "gif"; }
				   if(pathinfo(parse_url($img_host.'/images/promotion/2.jpg', PHP_URL_PATH), PATHINFO_EXTENSION) == "jpg")   { $ext2   = "jpg";  }else{ $ext2   = "gif"; }
				   if(pathinfo(parse_url($img_host.'/images/promotion/2_1.jpg', PHP_URL_PATH), PATHINFO_EXTENSION) == "jpg") { $ext2_1 = "jpg";  }else{ $ext2_1 = "gif"; }
				   if(pathinfo(parse_url($img_host.'/images/promotion/3.jpg', PHP_URL_PATH), PATHINFO_EXTENSION) == "jpg")   { $ext3   = "jpg";  }else{ $ext3   = "gif"; }
				   if(pathinfo(parse_url($img_host.'/images/promotion/3_1.jpg', PHP_URL_PATH), PATHINFO_EXTENSION) == "jpg") { $ext3_1 = "jpg";  }else{ $ext3_1 = "gif"; }
				   if(pathinfo(parse_url($img_host.'/images/promotion/3a.jpg', PHP_URL_PATH), PATHINFO_EXTENSION) == "jpg")  { $ext3a  = "jpg";  }else{ $ext3a  = "gif"; }
				   if(pathinfo(parse_url($img_host.'/images/promotion/3a_1.jpg', PHP_URL_PATH), PATHINFO_EXTENSION) == "jpg"){ $ext3a_1= "jpg";  }else{ $ext3a_1= "gif"; }
				   if(pathinfo(parse_url($img_host.'/images/promotion/4.jpg', PHP_URL_PATH), PATHINFO_EXTENSION) == "jpg")   { $ext4 = "jpg";    }else{ $ext4   = "gif"; }
				   if(pathinfo(parse_url($img_host.'/images/promotion/4_1.jpg', PHP_URL_PATH), PATHINFO_EXTENSION) == "jpg") { $ext4_1 = "jpg";  }else{ $ext4_1 = "gif"; }
				   if(pathinfo(parse_url($img_host.'/images/promotion/4a.jpg', PHP_URL_PATH), PATHINFO_EXTENSION) == "jpg")  { $ext4a= "jpg";    }else{ $ext4a  = "gif"; }
				   if(pathinfo(parse_url($img_host.'/images/promotion/4a_1.jpg', PHP_URL_PATH), PATHINFO_EXTENSION) == "jpg"){ $ext4a_1= "jpg";  }else{ $ext4a_1= "gif"; }
				   if(pathinfo(parse_url($img_host.'/images/promotion/5.jpg', PHP_URL_PATH), PATHINFO_EXTENSION) == "jpg")   { $ext5 = "jpg";    }else{ $ext5  = "gif";  }
				   if(pathinfo(parse_url($img_host.'/images/promotion/5_1.jpg', PHP_URL_PATH), PATHINFO_EXTENSION) == "jpg") { $ext5_1 = "jpg";  }else{ $ext5_1 = "gif"; }
				  ?>
                  <form name="promotions" id="promotions" onsubmit="return false;" enctype="multipart/form-data" method="post" accept-charset="utf-8"> 
                  <div class="featured">
                      <h4 class="redhead">Newsletter</h4>
                      <div class="block b" style="margin-bottom:170px; background-image:url('<?php echo $img_host; ?>/images/promotion/1.<?php echo $ext1; ?>?<?php echo date('mdy-H'); //substr(date('mdy-H-i'),0,-1); ?>')">
                           <div class="b_1" style="background-image:url('<?php echo $img_host; ?>/images/promotion/1_1.<?php echo $ext1_1; ?>')">&nbsp;</div>
                      </div> 
                      <div class="row">
                           <div class = "file-field input-field col s6 m6 l3">
                              <div class = "btn red"><span>Browse</span><input type="file" id="promotion1" name="promotion1"/></div>        
                              <div class = "file-path-wrapper"><input class = "file-path validate" type = "text" placeholder = "Upload file" /></div>
                           </div> 
                           <div class = "input-field col s6 m6 l3 period" style="margin-bottom:-10px;">
                              <i class="material-icons prefix">gps_fixed</i>
                                <select id="period1">
								  <option value="auto" <?php if($period1 == "auto"){ echo 'selected';} ?>>Auto</option>
								  <?php for($j=1; $j<=17; $j++){ ?>
                                  <option value="<?php echo $j ?>" <?php if($period1 == $j){ echo 'selected';} ?>><?php echo $j ?></option>
                                  <?php } ?>
                                </select>
                                <label>Newsletter Link Period</label>
                           </div>
                           <div class="input-field col s12 m12 l6">
                              <i class="material-icons prefix">insert_link</i>
                              <input id="link1" type="text" class="validate" value="<?php echo $link1; ?>">
                              <label for="link1">Newsletter Link</label>
                           </div>
                           <div class = "file-field input-field col s6 m6 l3" style="margin:0px;">
                              <div class = "btn black"><span>Browse</span><input type="file" id="promotion1_1" name="promotion1_1"/></div>        
                              <div class = "file-path-wrapper"><input class = "file-path validate" type = "text" placeholder = "Upload file" /></div>
                           </div> 
                           <div class = "input-field col s6 m6 l3 period" style="margin-bottom:-10px;">
                              <i class="material-icons prefix">gps_fixed</i>
                                <select id="period1_1">
								  <option value="auto" <?php if($period1_1 == "auto"){ echo 'selected';} ?>>Auto</option>
								  <?php for($j=1; $j<=17; $j++){ ?>
                                  <option value="<?php echo $j ?>" <?php if($period1_1 == $j){ echo 'selected';} ?>><?php echo $j ?></option>
                                  <?php } ?>
                                </select>
                                <label>Newsletter Link2 Period</label>
                           </div>
                           <div class="input-field col s12 m12 l6" style="margin-bottom:0px;">
                              <i class="material-icons prefix">insert_link</i>
                              <input id="link1_1" type="text" class="validate" value="<?php echo $link1_1; ?>">
                              <label for="link1_1">Newsletter Link 2</label>
                           </div>   
                           <div class="input-field col s6">
                              <i class="material-icons prefix">date_range</i><input id="p1_date" name="p1_date" type="text" class="datepicker" value="<?php echo $p1_date; ?>">
                              <label for="p1_date" class=""> Schedule Date</label>
                              <br /><br />
                               <div class="switch">
                                <label>
                                  Off
                                  <input id="switch2" name="switch2" type="checkbox" <?php if($switch2 == 1){ echo 'checked'; } ?>>
                                  <span class="lever"></span>
                                  On
                                </label>
                               </div>   
                           </div>   
                           <div class="input-field col s6 left-align">
                               <button class="btn black waves-effect waves-light" type="submit" name="action" onclick="do_image_uploader(1, 3);">Upload Newsletter
                                    <i class="material-icons right">send</i>
                                </button>
                                <div class="red-text accent-3-text"><br />170px X 220px</div>
                            </div>
                      </div>
                      <h4 class="redhead">Cash And Carry</h4>
                      <div class="block b" style="margin-bottom:150px; background-image:url('<?php echo $img_host; ?>/images/promotion/2.<?php echo $ext2; ?>?<?php echo date('mdy-H'); //substr(date('mdy-H-i'),0,-1); ?>')">
                           <div class="b_1" style="background-image:url('<?php echo $img_host; ?>/images/promotion/2_1.<?php echo $ext2_1; ?>')">&nbsp;</div>
                      </div>
                      <div class="row">
                           <div class = "file-field input-field col s6 m6 l3" style="margin:0px;">
                              <div class = "btn red"><span>Browse</span><input type="file" id="promotion2" name="promotion2"/></div>        
                              <div class = "file-path-wrapper"><input class = "file-path validate" type = "text" placeholder = "Upload file" /></div>
                           </div> 
                           <div class = "input-field col s6 m6 l3 period" style="margin-bottom:-10px;">
                              <i class="material-icons prefix">gps_fixed</i>
                                <select id="period2">
								  <option value="auto" <?php if($period2 == "auto"){ echo 'selected';} ?>>Auto</option>
								  <?php for($j=1; $j<=17; $j++){ ?>
                                  <option value="<?php echo $j ?>" <?php if($period2 == $j){ echo 'selected';} ?>><?php echo $j ?></option>
                                  <?php } ?>
                                </select>
                                <label>Cash & Carry Period</label>
                           </div>
                           <div class="input-field col s12 m12 l6" style="margin-bottom:5px;">
                              <i class="material-icons prefix">insert_link</i>
                              <input id="link2" type="text" class="validate" value="<?php echo $link2; ?>">
                              <label for="link2">Cash & Carry Link</label>
                           </div>  
                           <div class = "file-field input-field col s6 m6 l3" style="margin:0px;">
                              <div class = "btn black"><span>Browse</span><input type="file" id="promotion2_1" name="promotion2_1"/></div>        
                              <div class = "file-path-wrapper"><input class = "file-path validate" type = "text" placeholder = "Upload file 2" /></div>
                           </div> 
                           <div class = "input-field col s6 m6 l3 period" style="margin-bottom:-10px;">
                              <i class="material-icons prefix">gps_fixed</i>
                                <select id="period2_1">
								  <option value="auto" <?php if($period2_1 == "auto"){ echo 'selected';} ?>>Auto</option>
								  <?php for($j=1; $j<=17; $j++){ ?>
                                  <option value="<?php echo $j ?>" <?php if($period2_1 == $j){ echo 'selected';} ?>><?php echo $j ?></option>
                                  <?php } ?>
                                </select>
                                <label>Cash & Carry2 Period</label>
                           </div>
                           <div class="input-field col s12 m12 l6" style="margin-bottom:0px;">
                              <i class="material-icons prefix">insert_link</i>
                              <input id="link2_1" type="text" class="validate" value="<?php echo $link2_1; ?>">
                              <label for="link2_1">Cash & Carry Link 2</label>
                           </div>   
                           <div class="input-field col s6">
                              <i class="material-icons prefix">date_range</i><input id="p2_date" name="p2_date" type="text" class="datepicker" value="<?php echo $p2_date; ?>">
                              <label for="p2_date" class=""> Schedule Date</label>
                           </div>
                           <div class="input-field col s6 left-align">
                               <button class="btn black waves-effect waves-light" type="submit" name="action" onclick="do_image_uploader(2, 3);">Upload Cash & Carry
                                    <i class="material-icons right">send</i>
                                </button>
                                <div class="red-text accent-3-text"><br />170px X 220px</div>
                            </div>
                      </div>
                      <h4 class="redhead">Day-Today</h4>
                      <div class="block b" style="margin-bottom:150px; background-image:url('<?php echo $img_host; ?>/images/promotion/3.<?php echo $ext3; ?>?<?php echo date('mdy-H'); //substr(date('mdy-H-i'),0,-1); ?>')">
                           <div class="b_1" style="background-image:url('<?php echo $img_host; ?>/images/promotion/3_1.<?php echo $ext3_1; ?>')">&nbsp;</div>
                      </div>
                      <div class="row">
                           <div class = "file-field input-field col s6 m6 l3" style="margin:0px;">
                              <div class = "btn red"><span>Browse</span><input type="file" id="promotion3" name="promotion3"/></div>        
                              <div class = "file-path-wrapper"><input class = "file-path validate" type = "text" placeholder = "Upload file" /></div>
                           </div> 
                           <div class = "input-field col s6 m6 l3 period" style="margin-bottom:-10px;">
                              <i class="material-icons prefix">gps_fixed</i>
                                <select id="period3">
								  <option value="auto" <?php if($period3 == "auto"){ echo 'selected';} ?>>Auto</option>
								  <?php for($j=1; $j<=17; $j++){ ?>
                                  <option value="<?php echo $j ?>" <?php if($period3 == $j){ echo 'selected';} ?>><?php echo $j ?></option>
                                  <?php } ?>
                                </select>
                                <label>Day-Today Link Period</label>
                           </div>
                           <div class="input-field col s12 m12 l6" style="margin-bottom:5px;">
                              <i class="material-icons prefix">insert_link</i>
                              <input id="link3" type="text" class="validate" value="<?php echo $link3; ?>">
                              <label for="link3">Day-Today Link</label>
                           </div>    
                           <div class = "file-field input-field col s6 m6 l3" style="margin:0px;">
                              <div class = "btn black"><span>Browse</span><input type="file" id="promotion3_1" name="promotion3_1"/></div>        
                              <div class = "file-path-wrapper"><input class = "file-path validate" type = "text" placeholder = "Upload file 2" /></div>
                           </div> 

                           <div class = "input-field col s6 m6 l3 period" style="margin-bottom:-10px;">
                              <i class="material-icons prefix">gps_fixed</i>
                                <select id="period3_1">
								  <option value="auto" <?php if($period3_1 == "auto"){ echo 'selected';} ?>>Auto</option>
								  <?php for($j=1; $j<=17; $j++){ ?>
                                  <option value="<?php echo $j ?>" <?php if($period3_1 == $j){ echo 'selected';} ?>><?php echo $j ?></option>
                                  <?php } ?>
                                </select>
                                <label>Day-Today Link2 Period</label>
                           </div>
                           <div class="input-field col s12 m12 l6" style="margin-bottom:0px;">
                              <i class="material-icons prefix">insert_link</i>
                              <input id="link3_1" type="text" class="validate" value="<?php echo $link3_1; ?>">
                              <label for="link3_1">Day-Today Link 2</label>
                           </div> 
                           <div class="input-field col s6">
                              <i class="material-icons prefix">date_range</i><input id="p3_date" name="p3_date" type="text" class="datepicker" value="<?php echo $p3_date; ?>">
                              <label for="p3_date" class="">Schedule Date</label>
                           </div>
                           <div class="input-field col s6 left-align">
                               <button class="btn black waves-effect waves-light" type="submit" name="action" onclick="do_image_uploader(3, 3);">Upload Day-Today
                                    <i class="material-icons right">send</i>
                                </button>
                                <div class="red-text accent-3-text"><br />170px X 220px</div>
                            </div>
                      </div>
                      
                      <!--
                      <div class="block b" style="margin-bottom:150px; background-image:url('<?php echo $img_host; ?>/images/promotion/3a.<?php echo $ext3a; ?>?<?php echo date('mdy-H'); //substr(date('mdy-H-i'),0,-1); ?>')">
                           <div class="b_1" style="background-image:url('<?php echo $img_host; ?>/images/promotion/3a_1.<?php echo $ext3a_1; ?>')">&nbsp;</div>
                      </div>
                      <div class="row">
                           <div class = "file-field input-field col s6 m6 l3" style="margin:0px;">
                              <div class = "btn black"><span>Browse</span><input type="file" id="promotion3a" name="promotion3a"/></div>        
                              <div class = "file-path-wrapper"><input class = "file-path validate" type = "text" placeholder = "Upload file" /></div>
                           </div> 
                           <div class = "input-field col s6 m6 l3 period" style="margin-bottom:-10px;">
                              <i class="material-icons prefix">gps_fixed</i>
                                <select id="period3a">
								  <option value="auto" <?php if($period3a == "auto"){ echo 'selected';} ?>>Auto</option>
								  <?php for($j=1; $j<=17; $j++){ ?>
                                  <option value="<?php echo $j ?>" <?php if($period3a == $j){ echo 'selected';} ?>><?php echo $j ?></option>
                                  <?php } ?>
                                </select>
                                <label>Day-Today Upcoming Link Period</label>
                           </div>
                           <div class="input-field col s12 m12 l6" style="margin-bottom:5px;">
                              <i class="material-icons prefix">insert_link</i>
                              <input id="link3a" type="text" class="validate" value="<?php echo $link3a; ?>">
                              <label for="link3a">Day-Today Upcoming Link</label>
                           </div>   
                           <div class = "file-field input-field col s6 m6 l3" style="margin:0px;">
                              <div class = "btn black"><span>Browse</span><input type="file" id="promotion3a_1" name="promotion3a_1"/></div>        
                              <div class = "file-path-wrapper"><input class = "file-path validate" type = "text" placeholder = "Upload file 2" /></div>
                           </div> 
                           <div class = "input-field col s6 m6 l3 period" style="margin-bottom:-10px;">
                              <i class="material-icons prefix">gps_fixed</i>
                                <select id="period3a_1">
								  <option value="auto" <?php if($period3a_1 == "auto"){ echo 'selected';} ?>>Auto</option>
								  <?php for($j=1; $j<=17; $j++){ ?>
                                  <option value="<?php echo $j ?>" <?php if($period3a_1 == $j){ echo 'selected';} ?>><?php echo $j ?></option>
                                  <?php } ?>
                                </select>
                                <label>Day-Today Upcoming Link2 Period</label>
                           </div>
                           <div class="input-field col s12 m12 l6" style="margin-bottom:0px;">
                              <i class="material-icons prefix">insert_link</i>
                              <input id="link3a_1" type="text" class="validate" value="<?php echo $link3a_1; ?>">
                              <label for="link3a_1">Day-Today Upcoming Link 2</label>
                           </div> 
                           <div class="input-field col s6">
                              <i class="material-icons prefix">date_range</i><input id="p3a_date" name="p3a_date" type="text" class="datepicker" value="<?php echo $p3a_date; ?>">
                              <label for="p3a_date" class="">Schedule Date</label>
                              <br /><br />
                               <div class="switch">
                                <label>
                                  Off
                                  <input id="dt_switch" name="dt_switch" type="checkbox" <//?php if($dt_switch == 1){ echo 'checked'; } ?>>
                                  <span class="lever"></span>
                                  On
                                </label>
                               </div>   
                           </div>
                           <div class="input-field col s6 left-align">
                               <button class="btn black waves-effect waves-light" type="submit" name="action" onclick="do_image_uploader('3a', 3);">Upload Day-Today Upcoming
                                    <i class="material-icons right">send</i>
                                </button>
                                <div class="red-text accent-3-text"><br />170px X 220px</div>
                            </div>
                      </div>
                      -->
                      
                      <h4 class="redhead">USave</h4>
                      <div class="block b" style="margin-bottom:150px; background-image:url('<?php echo $img_host; ?>/images/promotion/4.<?php echo $ext4; ?>?<?php echo date('mdy-H'); //substr(date('mdy-H-i'),0,-1); ?>')">
                           <div class="b_1" style="background-image:url('<?php echo $img_host; ?>/images/promotion/4_1.<?php echo $ext4_1; ?>')">&nbsp;</div>
                      </div>
                      <div class="row">
                           <div class = "file-field input-field col s6 m6 l3" style="margin:0px;">
                              <div class = "btn red"><span>Browse</span><input type="file" id="promotion4" name="promotion4"/></div>        
                              <div class = "file-path-wrapper"><input class = "file-path validate" type = "text" placeholder = "Upload file" /></div>
                           </div>               
                           <div class = "input-field col s6 m6 l3 period" style="margin-bottom:-10px;">
                              <i class="material-icons prefix">gps_fixed</i>
                                <select id="period4">
								  <option value="auto" <?php if($period4 == "auto"){ echo 'selected';} ?>>Auto</option>
								  <?php for($j=1; $j<=17; $j++){ ?>
                                  <option value="<?php echo $j ?>" <?php if($period4 == $j){ echo 'selected';} ?>><?php echo $j ?></option>
                                  <?php } ?>
                                </select>
                                <label>Usave Link Period</label>
                           </div>
                           <div class="input-field col s12 m12 l6" style="margin-bottom:5px;">
                              <i class="material-icons prefix">insert_link</i>
                              <input id="link4" type="text" class="validate" value="<?php echo $link4; ?>">
                              <label for="link4">Usave Link</label>
                           </div>   
                           <div class = "file-field input-field col s6 m6 l3" style="margin:0px;">
                              <div class = "btn black"><span>Browse</span><input type="file" id="promotion4_1" name="promotion4_1"/></div>        
                              <div class = "file-path-wrapper"><input class = "file-path validate" type = "text" placeholder = "Upload file 2" /></div>
                           </div>               
                           <div class = "input-field col s6 m6 l3 period" style="margin-bottom:-10px;">
                              <i class="material-icons prefix">gps_fixed</i>
                                <select id="period4_1">
								  <option value="auto" <?php if($period4_1 == "auto"){ echo 'selected';} ?>>Auto</option>
								  <?php for($j=1; $j<=17; $j++){ ?>
                                  <option value="<?php echo $j ?>" <?php if($period4_1 == $j){ echo 'selected';} ?>><?php echo $j ?></option>
                                  <?php } ?>
                                </select>
                                <label>Usave Link2 Period</label>
                           </div>
                           <div class="input-field col s12 m12 l6" style="margin-bottom:0px;">
                              <i class="material-icons prefix">insert_link</i>
                              <input id="link4_1" type="text" class="validate" value="<?php echo $link4_1; ?>">
                              <label for="link4_1">Usave Link 2</label>
                           </div>  
                           <div class="input-field col s6">
                              <i class="material-icons prefix">date_range</i><input id="p4_date" name="p4_date" type="text" class="datepicker" value="<?php echo $p4_date; ?>">
                              <label for="p4_date" class="">Schedule Date</label>
                           </div> 
                           <div class="input-field col s6 left-align">
                               <button class="btn black waves-effect waves-light" type="submit" name="action" onclick="do_image_uploader(4, 3);">Upload Usave
                                    <i class="material-icons right">send</i>
                                </button>
                                <div class="red-text accent-3-text"><br />170px X 220px</div>
                            </div>
                      </div>
                      
                      <!--
                      <div class="block b" style="margin-bottom:150px; background-image:url('<?php echo $img_host; ?>/images/promotion/4a.<?php echo $ext4a; ?>?<?php echo date('mdy-H'); //substr(date('mdy-H-i'),0,-1); ?>')">
                           <div class="b_1" style="background-image:url('<?php echo $img_host; ?>/images/promotion/4a_1.<?php echo $ext4a_1; ?>')">&nbsp;</div>
                      </div>
                      <div class="row">
                           <div class = "file-field input-field col s6 m6 l3" style="margin:0px;">
                              <div class = "btn black"><span>Browse</span><input type="file" id="promotion4a" name="promotion4a"/></div>        
                              <div class = "file-path-wrapper"><input class = "file-path validate" type = "text" placeholder = "Upload file" /></div>
                           </div>               
                           <div class = "input-field col s6 m6 l3 period" style="margin-bottom:-10px;">
                              <i class="material-icons prefix">gps_fixed</i>
                                <select id="period4a">
								  <option value="auto" <?php if($period4a == "auto"){ echo 'selected';} ?>>Auto</option>
								  <?php for($j=1; $j<=17; $j++){ ?>
                                  <option value="<?php echo $j ?>" <?php if($period4a == $j){ echo 'selected';} ?>><?php echo $j ?></option>
                                  <?php } ?>
                                </select>
                                <label>Usave Upcoming Link Period</label>
                           </div>
                           <div class="input-field col s12 m12 l6" style="margin-bottom:5px;">
                              <i class="material-icons prefix">insert_link</i>
                              <input id="link4a" type="text" class="validate" value="<?php echo $link4a; ?>">
                              <label for="link4a">Usave Upcoming Link</label>
                           </div>   
                           <div class = "file-field input-field col s6 m6 l3" style="margin:0px;">
                              <div class = "btn black"><span>Browse</span><input type="file" id="promotion4a_1" name="promotion4a_1"/></div>        
                              <div class = "file-path-wrapper"><input class = "file-path validate" type = "text" placeholder = "Upload file 2" /></div>
                           </div>               
                           <div class = "input-field col s6 m6 l3 period" style="margin-bottom:-10px;">
                              <i class="material-icons prefix">gps_fixed</i>
                                <select id="period4a_1">
								  <option value="auto" <?php if($period4a_1 == "auto"){ echo 'selected';} ?>>Auto</option>
								  <?php for($j=1; $j<=17; $j++){ ?>
                                  <option value="<?php echo $j ?>" <?php if($period4a_1 == $j){ echo 'selected';} ?>><?php echo $j ?></option>
                                  <?php } ?>
                                </select>
                                <label>Usave Upcoming Link2 Period</label>
                           </div>
                           <div class="input-field col s12 m12 l6" style="margin-bottom:0px;">
                              <i class="material-icons prefix">insert_link</i>
                              <input id="link4a_1" type="text" class="validate" value="<?php echo $link4a_1; ?>">
                              <label for="link4a_1">Usave Upcoming Link</label>
                           </div>   
                           <div class="input-field col s6">
                              <i class="material-icons prefix">date_range</i><input id="p4a_date" name="p4a_date" type="text" class="datepicker" value="<?php echo $p4a_date; ?>">
                              <label for="p4a_date" class="">Schedule Date</label>
                              <br /><br />
                               <div class="switch">
                                <label>
                                  Off
                                  <input id="us_switch" name="us_switch" type="checkbox" <//?php if($us_switch == 1){ echo 'checked'; } ?>>
                                  <span class="lever"></span>
                                  On
                                </label>
                               </div>   
                           </div> 
                           <div class="input-field col s6 left-align">
                               <button class="btn black waves-effect waves-light" type="submit" name="action" onclick="do_image_uploader('4a', 3);">Upload Usave Upcoming
                                    <i class="material-icons right">send</i>
                                </button>
                                <div class="red-text accent-3-text"><br />170px X 220px</div>
                            </div>
                      </div>
                      -->
                      
                      
                      <h4 class="redhead">Special Event</h4>
                      <div class="block b" style="margin-bottom:170px; background-image:url('<?php echo $img_host; ?>/images/promotion/5.<?php echo $ext5; ?><?php echo date('mdy-H'); //substr(date('mdy-H-i'),0,-1); ?>')">
                           <div class="b_1" style="background-image:url('<?php echo $img_host; ?>/images/promotion/5_1.<?php echo $ext5_1; ?>')">&nbsp;</div>
                      </div>
                      <div class="row">
                           <div class = "file-field input-field col s6 m6 l3">
                              <div class = "btn red"><span>Browse</span><input type="file" id="promotion5" name="promotion5"/></div>  
                              <div class = "file-path-wrapper"><input class = "file-path validate" type = "text" placeholder = "Upload file" /></div>
                           </div>                  
                           <div class = "input-field col s6 m6 l3 period" style="margin-bottom:-10px;">
                              <i class="material-icons prefix">gps_fixed</i>
                                <select id="period5">
								  <option value="auto" <?php if($period5 == "auto"){ echo 'selected';} ?>>Auto</option>
								  <?php for($j=1; $j<=17; $j++){ ?>
                                  <option value="<?php echo $j ?>" <?php if($period5 == $j){ echo 'selected';} ?>><?php echo $j ?></option>
                                  <?php } ?>
                                </select>
                                <label>Special Event Link Period</label>
                           </div>
                           <div class="input-field col s12 m12 l6">
                              <i class="material-icons prefix">insert_link</i>
                              <input id="link5" type="text" class="validate" value="<?php echo $link5; ?>">
                              <label for="link5">Special Event Link</label>
                           </div>
                           <div class = "file-field input-field col s6 m6 l3" style="margin:0px;">
                              <div class = "btn black"><span>Browse</span><input type="file" id="promotion5_1" name="promotion5_1"/></div>        
                              <div class = "file-path-wrapper"><input class = "file-path validate" type = "text" placeholder = "Upload file 2" /></div>
                           </div> 
                           <div class = "input-field col s6 m6 l3 period" style="margin-bottom:-10px;">
                              <i class="material-icons prefix">gps_fixed</i>
                                <select id="period5_1">
								  <option value="auto" <?php if($period5_1 == "auto"){ echo 'selected';} ?>>Auto</option>
								  <?php for($j=1; $j<=17; $j++){ ?>
                                  <option value="<?php echo $j ?>" <?php if($period5_1 == $j){ echo 'selected';} ?>><?php echo $j ?></option>
                                  <?php } ?>
                                </select>
                                <label>Special Event Link2 Period</label>
                           </div>
                           <div class="input-field col s12 m12 l6" style="margin-bottom:0px;">
                              <i class="material-icons prefix">insert_link</i>
                              <input id="link5_1" type="text" class="validate" value="<?php echo $link5_1; ?>">
                              <label for="link5_1">Special Event Link 2</label>
                           </div>   
                           <div class="input-field col s6">
                              <i class="material-icons prefix">date_range</i><input id="p5_date" name="p5_date" type="text" class="datepicker" value="<?php echo $p5_date; ?>">
                              <label for="p5_date" class=""> Schedule Date</label>
                              <br /><br />
                               <div class="switch">
                                <label>
                                  Off
                                  <input id="switch" name="switch" type="checkbox" <?php if($switch == 1){ echo 'checked'; } ?>>
                                  <span class="lever"></span>
                                  On
                                </label>
                               </div>   
                           </div>       
                           <div class="input-field col s6 left-align">
                               <button class="btn black waves-effect waves-light" type="submit" name="action" onclick="do_image_uploader(5, 3);">Upload Special Event
                                    <i class="material-icons right">send</i>
                                </button>
                                <div class="red-text accent-3-text"><br />170px X 220px</div>
                            </div>
                      </div>
                      <br style="clear:both;">  
                  </div>
                  </form>
           </div>
     </div>
</div>
<div id="presell" class="col s12">
	<div class="fullwidth-container" style="background:#fff;">
    	<div class="inner-wrapper">
        	<h4>Presell Orders</h4>
            <div style="min-height:300px; text-align:center">
            <?php
			// Load Preslls View
			echo view('presells_import');
			?>
            </div>
    	</div>
    </div>
</div>
<div id="settings" class="col s12">
	<div class="fullwidth-container" style="background:#fff;">
    	<div class="inner-wrapper">
          <h4>Settings</h4>
              <div class="block" style="text-align:center"><br /><br /><i class="material-icons medium">slideshow</i><br /><br /><br />
                  <div class="input-field inline period" style="background:none; width:150px;">
             	 <select id="slides_count">
                      <?php for($i=1; $i<=4; $i++){ 
                        ?>
                      <option value="<?php echo $i ?>" <?php if($i == $slides){ echo 'selected'; } ?>><?php echo $i ?></option>
                      <?php } ?>
                    </select>
                    <label>Number of Slides</label>
                  </div>
              </div>       
              <div class="block" style="text-align:center"><br /><br /><i class="material-icons medium">view_week</i><br /><br /><br />
                  <div class="input-field inline period" style="background:none; width:150px;">
             	 <select id="sponsors_count">
                      <?php for($i=1; $i<=3; $i++){ ?>
                      <option value="<?php echo $i ?>" <?php if($i == $sponsors){ echo 'selected';} ?>><?php echo $i ?></option>
                      <?php } ?>
                    </select>
                    <label>Number of Sponsors</label>
                  </div>
              </div>       
              <br style="clear:both;">
	      <?php } ?>
       </div>
	</div>
 </div>

<!---- -->
<div id="tracking" class="col s12">
	<div class="fullwidth-container" style="background:#fff;">
    	<div class="inner-wrapper">
          <h4>Tracking</h4>
              <div class="block" style="text-align:center"><br /><br /><i class="material-icons medium">date_range</i><br /><br /><br />
                  <div class="input-field inline" style="background:none; width:150px;">
             	 <select id="track_year" onchange="javascript:change_filters()">
                      <?php for($i=2020; $i<=2025; $i++){ ?>
                      <option value="<?php echo $i ?>" <?php if($i == $year){echo "selected";} ?> ><?php echo $i ?></option>
                      <?php } ?>
                    </select>
                    <label>Year</label>
                  </div>
              </div>    
              <div class="block" style="text-align:center"><br /><br /><i class="material-icons medium">gps_fixed</i><br /><br /><br />
                  <div class="input-field inline period" style="background:none; width:150px;">
             	 <select id="track_period" onchange="javascript:change_filters()">
                      <?php for($i=1; $i<=17; $i++){ ?>
                      <option value="<?php echo $i ?>" <?php if($i == $period){echo "selected";} ?> ><?php echo $i ?></option>
                      <?php } ?>
                    </select>
                    <label>Period</label>
                  </div>
              </div>       
              <div class="block" style="text-align:center"><br /><br /><i class="material-icons medium">art_track</i><br /><br /><br />
                  <div class="input-field inline" style="background:none; width:150px;">
             	 <select id="track_type" onchange="javascript:change_filters()">
                      <option value="all" >All</option>
                      <option value="Header" >Header Sliders</option>
                      <option value="Sponsor" >Sponsors</option>
                      <option value="Promotion" >Promotions</option>
                    </select>
                    <label>Type</label>
                  </div>
              </div>       
              <br style="clear:both;">
              <div>
              		
                    <div>
						<script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.4.3/dist/js/tabulator.min.js"></script>
                        <script type="text/javascript" src="https://oss.sheetjs.com/sheetjs/xlsx.full.min.js"></script>
                        
                        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
                        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.0.5/jspdf.plugin.autotable.js"></script>
                        
                        <link href="https://unpkg.com/tabulator-tables@4.4.3/dist/css/tabulator.min.css" rel="stylesheet">
                           <br />
                           <h4><center>Performance Report</center></h4>
                            <div class="table-controls-legend">Filter Tracking Data</div>
                            <div class="table-controls period">
                                  <span>
                                  <label>Field: </label>
                                  <select id="filter-field">
                                      <option></option>
                                      <option value="name">Ad Unit</option>
                                      <option value="person_id">User</option>
                                      <option value="supplier">Supplier</option>
                                      <option value="link">Link</option>
                                      <option value="tracking_date">Date</option>
                                  </select>
                                  </span>
                        
                                  <span>
                                  <label>Type: </label>
                                  <select id="filter-type">
                                      <option value="like">like</option>
                                      <option value="=">equal</option>
                                      <option value="!=">not equal</option>
                                      <option value=">">greater than</option>
                                      <option value="<">less than</option>
                                  </select>
                                  </span>
                        
                                  <span><label>Value: </label> <input id="filter-value" type="text" placeholder="value"></span>
                        
                        
                                  <button id="filter-clear">Clear Filters</button>
                                  <button id="toggle-groups">+/- Groups</button>
                                  <button id="toggle-pages">+/- Pages</button>
                            </div>
                            <div id="example-table"></div>
                            <div class="table-controls">
                                  <button id="print-table">Print Report</button>
                                  <button id="export-pdf">Export PDF</button>
                                  <button id="export-xlsx">Export XLXS</button>
                            </div>
                            <input id="group" name="group" type="hidden" value="hide">
                        
                            <script type="text/javascript">		
							                        
                                    var table = new Tabulator("#example-table", {
                                        height:"600px",
                                        layout:"fitColumns",
                                        movableRows:true,
                                        data:tabledata,
                                        groupBy:["type", "supplier", "link"],
										groupToggleElement:"header",
										groupStartOpen:false,
										groupHeader: [ function(value, count, data){   
																	  if(count==1){ return value + "<span style='color:#d00;'> - " + count + " click</span>"; }
																	   else{ return value + "<span style='color:#d00;'> - " + count + " clicks</span>"; }
															   }
													],
										pagination:"local",
										paginationSize:25, 
										downloadConfig:{
											 columnHeaders:true, 
											 columnGroups:true, 
											 rowGroups:true, 
											 columnCalcs:true, 
											 dataTree:true, 
										},
										printAsHtml:true, //enable html table printing
										printStyled:true,
                                        columns:[
                                            {title:"Period", field:"period", align:"center", width:"80"},
                                            {title:"User", field:"person_id", align:"center", width:"80"},
                                            {title:"Date", field:"tracking_date", align:"center", width:"100"},
                                            {title:"Time", field:"tracking_time", align:"center", width:"90"},
                                            {title:"Ad-Supplier", field:"name", align:"left", width:"250"},
                                            {title:"Link", field:"link", align:"left"},
                                            /*{title:"Order", field:"order", align:"center", width:"80"},
                                            {title:"Order", field:"order", formatter:function(cell, formatterParams, onRendered){ return cell.getValue();}, bottomCalc:"sum", width:"90"}*/
                                        ],
										columnCalcs:"both", //show column calculations at top and bottom of table and in groups
									});
                                    
									change_filters();
									
									function change_filters(){
										table.clearFilter();
										table.addFilter('tracking_date', 'like', jQuery("#track_year").val());
										table.addFilter('period', '=', jQuery("#track_period").val());
										if(jQuery("#track_type").val() != "all"){
											table.addFilter('type', '=', jQuery("#track_type").val());											
										}
									}
									
                                    //print button
                                    jQuery("#print-table").on("click", function(){
                                       table.print(false, true);
                                    });
                        
                                    jQuery("#toggle-groups").on("click", function(){
                                                      var groups = table.getGroups();
                                                      if(jQuery("#group").val() == "hide"){ state = "show"; }else{ state = "hide"; }                                      
                                                      jQuery("#group").val(state);
                        
                                                      groups.forEach(function(group){ 
                                                                  if(state == "show") { group.show(); } else{ group.hide(); }
                                                       });
                                    });
                        
                                    jQuery("#toggle-pages").on("click", function(){
                                                     if(table.getPageSize() == 10000){size = 25;}else{size =10000;}
                                                     table.setPageSize(size);
                                    });
                        
                            
                                //Custom filter example
                                function customFilter(data){
                                    return data.order_id;
                                }
                        
                                //Trigger setFilter function with correct parameters
                                function updateFilter(){
                        
                                    var filter = jQuery("#filter-field").val() == "function" ? customFilter : jQuery("#filter-field").val();
                        
                                    if(jQuery("#filter-field").val() == "function" ){
                                         jQuery("#filter-type").prop("disabled", true);
                                         jQuery("#filter-value").prop("disabled", true);
                                     }else{
                                        jQuery("#filter-type").prop("disabled", false);
                                        jQuery("#filter-value").prop("disabled", false);
                                    }
                        
                                   table.setFilter(filter, jQuery("#filter-type").val(), jQuery("#filter-value").val());
                                
                                }
                        
                                   //Update filters on value change
                                   jQuery("#filter-field, #filter-type").change(updateFilter);
                                   jQuery("#filter-value").keyup(updateFilter);
                                   
                                   //Clear filters on "Clear Filters" button click
                                   jQuery("#filter-clear").on("click", function(){
                                       jQuery("#filter-field").val("");
                                       jQuery("#filter-type").val("like");
                                       jQuery("#filter-value").val("");
                                       table.clearFilter();
                                  });
                        
                        //trigger download of data.xlsx file
                        $("#export-xlsx").click(function(){
                            table.download("xlsx", "apparel-orders.xlsx", {sheetName:"Apparel Order Report"});
                        });
                        
                        //trigger download of data.pdf file
                        $("#export-pdf").click(function(){
                            table.download("pdf", "apparel-orders.pdf", {
                                orientation:"portrait", //set page orientation to portrait
                                title:"Apparel Orders Report", //add title to report
                            });
                        });
                        
                                </script>
                        <style>
                        .table-controls-legend { margin: 10px 0 5px 0; color: #3FB449; font-weight: bold; font-size: 16px;}
                        .table-controls {margin-bottom: 10px; padding: 10px 15px; background: #eee; font-size: 14px;}
                        .table-controls input, .table-controls select, .table-controls button, .table-controls > span {margin: 0 4px 0px 2px; display:inline-block !important;}
                        .table-controls > span {width:148px;}
                        .table-controls label {margin-right:5px; margin-bottom: 5px; font-size:14px; font-weight: 700; color:#000;}
                        .table-controls select, .table-controls input{width:104px !important; margin-bottom:0px !important;}
                        #filter-clear, #toggle-groups, #toggle-pages, #export-pdf, #export-xlsx, #print-table{padding: 5px 10px; border: 1px solid #25682a; background: #3FB449; background: -webkit-gradient(linear, left top, left bottom, from(#3FB449), to(#25682a)); background: linear-gradient(to bottom, #3FB449 0%, #25682a 100%);  color: #fff; font-weight: bold;}
                        .tabulator-table{word-spacing:3px;}
                        .tabulator-table span{word-spacing:0px;}
                        .tabulator-table .nospace{color:inherit !important; margin:0px !important; min-width:100px; display:inline-block}
                        .tabulator-table .small{color:inherit !important; margin:0px !important; min-width:55px; display:inline-block}
                        .tabulator-col-title{text-align: center;}
                        .tabulator-row.tabulator-group:hover{background-color:lightgreen !important;}
                        .tabulator-row.tabulator-group{font-size:13px !important;}
                        .tabulator-row.tabulator-group span{margin-left:3px !important;}
                        .tabulator-row.tabulator-group .tabulator-arrow{margin-right:10px !important;}
                        </style>
                    </div>
                    
              </div>
              </div>
       </div>
      </div>
	</div>
</div>
<!---- -->

<div id="keydates" class="col s12">
	<div class="fullwidth-container" style="background:#fff;">
    	<div class="inner-wrapper">
          <h4>Settings</h4>
              <div class="block" style="text-align:center"><br /><br /><i class="material-icons medium">shopping_cart</i><br /><br /><br />
                  <div class="input-field inline period" style="background:none; width:150px;">
             	 <select id="slides_count">
                      <?php for($i=1; $i<=10; $i++){ ?>
                      <option value="<?php echo $i ?>" <?php if($i == $slides){ echo 'selected';} ?>><?php echo $i ?></option>
                      <?php } ?>
                    </select>
                    <label>Number of Slides</label>
                  </div>
              </div>       
              <div class="block" style="text-align:center"><br /><br /><i class="material-icons medium">local_offer</i><br /><br /><br />
                  <div class="input-field inline period" style="background:none; width:150px;">
             	 <select id="sponsors_count">
                      <?php for($i=1; $i<=6; $i++){ ?>
                      <option value="<?php echo $i ?>" <?php if($i == $sponsors){ echo 'selected';} ?>><?php echo $i ?></option>
                      <?php } ?>
                    </select>
                    <label>Number of Sponsors</label>
                  </div>
              </div>       
              <br style="clear:both;">
       </div>
	</div>
 </div>

  <div id="tracking" class="col s12">
  top_ribbon
  </div>
<?php echo view("partial/footer"); ?>

<div id="dialog_form" title="Manage Presell Item" style="font-family:Arial; font-size:12px;">
	<?php echo form_open('#' , array('id'=>'presell_form'));?>
	<ul id="error_message_box"></ul>
    
	<fieldset id="presell_item_data" style="width:32%; padding:5px 5px 0px 5px; border:#ccc 1px solid">
		<legend><i class="tiny material-icons">menu</i></legend>
		<div class="field_row clearfix">
				<?php echo form_label('Unique ID:', 'unique_id' , array('class'=>'required')); ?>
			<div class='form_field'>
				<?php echo form_input(array('name' => 'unique_id', 'id' => 'unique_id', 'value' => '')); ?>
            </div>
		</div>
		<div class="field_row clearfix">
				<?php echo form_label('Product Code:', 'prod_code' , array('class'=>'required')); ?>
			<div class='form_field'>
                <?php echo form_input(array('name' => 'prod_code' , 'id' => 'prod_code' , 'value' => '', 'type'=>'number')); ?>
            </div>
		</div>
		<div class="field_row clearfix">
			<?php echo form_label('Product Code 1:', 'prod_code1',array('class'=>'required')); ?>
			<div class='form_field'><?php echo form_input(array('name' => 'prod_code1' , 'id' => 'prod_code1' , 'value' => '', 'type'=>'number')); ?></div>
		</div>
		<div class="field_row clearfix">
			<?php echo form_label('Price List:', 'price_list' , array('class'=>'required')); ?>
			<div class='form_field'><?php $options=array('999'=>'999', '05' => '05', '07' => '07', '08' => '08', '09' => '09', '10'=>'10', '11'=>'11','12'=>'12'); echo form_dropdown('price_list',$options,'None','id="price_list"'); ?></div>
		</div>
		<div class="field_row clearfix">
			<?php echo form_label('Wholesale:', 'wholesale' , array('class'=>'required')); ?>
			<div class='form_field'><?php echo form_input(array('name' => 'wholesale' , 'id' => 'wholesale' , 'value' => '')); ?></div>
		</div>
		<div class="field_row clearfix">
			<?php echo form_label('Retail:', 'retail' , array('class'=>'required')); ?>
			<div class='form_field'><?php echo form_input(array('name' => 'retail' , 'id' => 'retail' , 'value' => '')); ?></div>
		</div>
		<div class="field_row clearfix">
			<?php echo form_label('Sell:', 'prod_sell' , array('class'=>'required')); ?>
			<div class='form_field'><?php echo form_input(array('name' => 'prod_sell' , 'id' => 'prod_sell' , 'value' => '', 'onclick'=>'this.select()', 'type'=>'number')); ?></div>
		</div>
		<div class="field_row clearfix">
			<?php echo form_label('RRP:', 'prod_rrp' , array('class'=>'required')); ?>
			<div class='form_field' style='margin-bottom:0px;'><?php echo form_input(array('name' => 'prod_rrp' , 'id' => 'prod_rrp' , 'value' => '', 'onclick'=>'this.select()', 'type'=>'number')); ?></div>
		</div>
        
	</fieldset>

	<fieldset id="presell_item_data" style="width:32%; padding:5px 5px 0px 5px; border:#ccc 1px solid">
		<legend><i class="tiny material-icons">menu</i></legend>
		<div class="field_row clearfix">
			<?php echo form_label('Group Description:', 'group_desc' , array('class'=>'required')); ?>
            <div class='form_field'>
            <select name='group_desc' id='group_desc' onchange="change_category();" style="background:#000; color:#fff; font-size:9px;">
            <?php 
				foreach($group as $r){
					echo '<option value="'.$r->category_name.'">'.strtoupper($r->category_name).'</option>';
				}
    	    ?>
            </select>
            </div>
		</div>
		<div class="field_row clearfix">
			<?php echo form_label('Description:', 'prod_desc' , array('class'=>'required')); ?>
			<div class='form_field'><?php echo form_input(array('name' => 'prod_desc' , 'id' => 'prod_desc' , 'value' => '')); ?></div>
		</div>
		<div class="field_row clearfix">
			<?php echo form_label('Pack Description:', 'prod_pack_desc' , array('class'=>'required')); ?>
			<div class='form_field'><?php echo form_input(array('name' => 'prod_pack_desc' , 'id' => 'prod_pack_desc' , 'value' => '', 'onclick'=>'this.select()')); ?></div>
		</div>
		<div class="field_row clearfix">
			<?php echo form_label('Shelf Life:', 'shelf_life' , array('class'=>'required')); ?>
			<div class='form_field'><?php echo form_input(array('name' => 'shelf_life' , 'id' => 'shelf_life' , 'value' => '', 'onclick'=>'this.select()')); ?></div>
		</div>
		<div class="field_row clearfix">
			<?php echo form_label('UOS:', 'prod_uos' , array('class'=>'required')); ?>
			<div class='form_field'><?php echo form_input(array('name' => 'prod_uos' , 'id' => 'prod_uos' , 'value' => '', 'onclick'=>'this.select()', 'type'=>'number')); ?></div>
		</div>
		<div class="field_row clearfix">
			<?php echo form_label('VAT:', 'vat_code' , array('class'=>'required')); ?>
			<div class='form_field'><?php $options = array('A' => 'A', 'C' => 'C', 'Z' => 'Z'); echo form_dropdown('vat_code', $options, 'None', 'id="vat_code"'); ?></div>
		</div>
		<div class="field_row clearfix">
			<?php echo form_label('Level 3:', 'prod_level3' , array('class'=>'required')); ?>
			<div class='form_field'><?php echo form_input(array('name' => 'prod_level3' , 'id' => 'prod_level3' , 'value' => '', 'onclick'=>'this.select()', 'type'=>'number')); ?></div>
		</div>
		<div class="field_row clearfix">
			<?php echo form_label('P Size:', 'p_size' , array('class'=>'required')); ?>
			<div class='form_field' style='margin-bottom:0px;'><?php echo form_input(array('name' => 'p_size' , 'id' => 'p_size' , 'value' => '', 'onclick'=>'this.select()', 'type'=>'number')); ?></div>
		</div>
	</fieldset>
    
	<fieldset id="presell_item_data" style="width:32%; padding:5px 5px 0px 5px; border:#ccc 1px solid">
		<legend><i class="tiny material-icons">menu</i></legend>
		<div class="field_row clearfix">
			<?php echo form_label('VAN:', 'van' , array('class'=>'required')); ?>
			<div class='form_field'><?php $options = array('None' => 'None','Ambient'=>'Ambient','Both'=>'Both','Chilled'=>'Chilled'); echo form_dropdown('van', $options, 'None', 'id="van"'); ?></div>
		</div>
		<div class="field_row clearfix">
			<?php echo form_label('Disabled:', 'is_disabled' , array('class'=>'required')); ?>
			<div class='form_field'><?php $options = array('N' => 'N',	'Y' => 'Y'); echo form_dropdown('is_disabled', $options, 'None', 'id="is_disabled"'); ?></div>
		</div>
		<div class="field_row clearfix">
			<?php echo form_label('Promo:', 'promo' , array('class'=>'required')); ?>
			<div class='form_field'><?php $options = array('Y' => 'Y', 'N' => 'N'); echo form_dropdown('promo', $options, 'None', 'id="promo"'); ?></div>
		</div>
		<div class="field_row qty clearfix">
			<?php echo form_label('G Qty:', 'g_qty' , array('class'=>'required')); ?>
			<?php echo form_label('G Min:', 'g_min' , array('class'=>'required')); ?>
			<?php echo form_label('G Max:', 'g_max' , array('class'=>'required')); ?>
			<div class='form_field'>
				<?php echo form_input(array('name'=>'g_qty', 'id'=>'g_qty', 'value'=>'', 'onclick'=>'this.select()', 'type'=>'number')); ?>
				<?php echo form_input(array('name'=>'g_min', 'id'=>'g_min', 'value'=>'', 'onclick'=>'this.select()', 'type'=>'number')); ?>
				<?php echo form_input(array('name'=>'g_max', 'id'=>'g_max', 'value'=>'', 'onclick'=>'this.select()', 'type'=>'number')); ?>
            </div>
		</div>
		<div class="field_row qty clearfix">
			<?php echo form_label('S Qty:', 's_qty' , array('class'=>'required')); ?>
			<?php echo form_label('S Min:', 's_min' , array('class'=>'required')); ?>
			<?php echo form_label('S Max:', 's_max' , array('class'=>'required')); ?>
			<div class='form_field'>
				<?php echo form_input(array('name'=>'s_qty', 'id'=>'s_qty', 'value'=>'', 'onclick'=>'this.select()', 'type'=>'number')); ?>
				<?php echo form_input(array('name'=>'s_min', 'id'=>'s_min', 'value'=>'', 'onclick'=>'this.select()', 'type'=>'number')); ?>
				<?php echo form_input(array('name'=>'s_max', 'id'=>'s_max', 'value'=>'', 'onclick'=>'this.select()', 'type'=>'number')); ?>
            </div>
		</div>
		<div class="field_row qty clearfix">
			<?php echo form_label('M Qty:', 'm_qty' , array('class'=>'required')); ?>
			<?php echo form_label('M Min:', 'm_min' , array('class'=>'required')); ?>
			<?php echo form_label('M Max:', 'm_max' , array('class'=>'required')); ?>
			<div class='form_field'>
				<?php echo form_input(array('name'=>'m_qty', 'id'=>'m_qty', 'value'=>'', 'onclick'=>'this.select()', 'type'=>'number')); ?>
				<?php echo form_input(array('name'=>'m_min', 'id'=>'m_min', 'value'=>'', 'onclick'=>'this.select()', 'type'=>'number')); ?>
				<?php echo form_input(array('name'=>'m_max', 'id'=>'m_max', 'value'=>'', 'onclick'=>'this.select()', 'type'=>'number')); ?>
            </div>
		</div>
		<div class="field_row qty clearfix">
			<?php echo form_label('L Qty:', 'l_qty' , array('class'=>'required')); ?>
			<?php echo form_label('L Min:', 'l_min' , array('class'=>'required')); ?>
			<?php echo form_label('L Max:', 'l_max' , array('class'=>'required')); ?>
			<div class='form_field'>
				<?php echo form_input(array('name'=>'l_qty', 'id'=>'l_qty', 'value'=>'', 'onclick'=>'this.select()', 'type'=>'number')); ?>
				<?php echo form_input(array('name'=>'l_min', 'id'=>'l_min', 'value'=>'', 'onclick'=>'this.select()', 'type'=>'number')); ?>
				<?php echo form_input(array('name'=>'l_max', 'id'=>'l_max', 'value'=>'', 'onclick'=>'this.select()', 'type'=>'number')); ?>
            </div>
		</div>
		<div class="field_row qty clearfix" style="margin-bottom:2px !important;">
			<?php echo form_label('E Qty:', 'e_qty' , array('class'=>'required')); ?>
			<?php echo form_label('E Min:', 'e_min' , array('class'=>'required')); ?>
			<?php echo form_label('E Max:', 'e_max' , array('class'=>'required')); ?>
			<div class='form_field'>
				<?php echo form_input(array('name'=>'e_qty', 'id'=>'e_qty', 'value'=>'', 'onclick'=>'this.select()', 'type'=>'number')); ?>
				<?php echo form_input(array('name'=>'e_min', 'id'=>'e_min', 'value'=>'', 'onclick'=>'this.select()', 'type'=>'number')); ?>
				<?php echo form_input(array('name'=>'e_max', 'id'=>'e_max', 'value'=>'', 'onclick'=>'this.select()', 'type'=>'number')); ?>
            </div>
		</div>
	</fieldset>
    <input type="hidden" name="period_reference" id="period_reference_field" value="">
    <input type="hidden" name="prod_id" id="prod_id_field" value="">
	<?php echo form_close();?>
</div>
