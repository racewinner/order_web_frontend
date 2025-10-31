<?php echo view("partial/header"); ?>
<script type="text/javascript">
$(document).ready(function(){

	$("#sort").val('6').formSelect() ;	
	/*$("#search0").on('keyup', function (e) {
		if (e.keyCode == 13) search_query();
	}); */

	$( "#dialog_form" ).dialog
	(
		{
			autoOpen:false ,
			height: 250 ,
			width: 480 ,
			modal: true ,
			buttons:
			{
				"Start": function()
				{
					$('#img_wait').css('visibility' , 'visible');
					$(this).dialog({buttons: {}});

				    $.ajax({
				        type : "POST"
				        , async : true
				        , url : "<?php echo site_url("$controller_name/reload_product/");?>"
				        , dataType : "text"
				        , timeout : 30000
				        , cache : false
				        , data : "person_id=0"
				        , error : function (xhr, status, error) {
							if (xhr.status == 401) {
								window.location.href = '/login'; return;
							} else {
								alert("An error occured: " + xhr.status + " " + xhr.statusText);
							}}
				        , success : function(response, status, request) {
					        alert(response);
				        }
				    });
		        	$(this).dialog('close');
		        	post_product_form_submit();
				}
			}
		}
	);
});

function change_category(){
	var cat = $('#cat').val();
	$('#category').val(cat);
    if(cat != ''){
	    $.ajax({
          url:"<?php echo site_url("$controller_name/fetch_subcategory/");?>",
          method:"POST",
          data: {cat:cat},
		  error: function (xhr, status, error) {
            if (xhr.status == 401) {
                window.location.href = '/login'; return;
            } else {
				alert("An error occured: " + xhr.status + " " + xhr.statusText);
			}},
          success:function(data){
              $('#subcat').html(data);
			  $('#subcat').formSelect();
          }
    	});
    }
	sort_product('menu');
}
function change_subcategory(){
	var subcat = $('#subcat').val();
	$('#category').val(subcat);	
	sort_product('menu');
}

function sort_product(link){
	var nCurrentSortKey = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search0 = $('#search0').val();
	var search1 = $('#search1').val();
	var search2 = $('#search2').val();
	var category_id = $('#category').val();
	var per_page = 2000;
	
	if(link == 'menu'){
	   	var s = $('#sort').val();
		if(s == 1 || s == 2){ link = $("#m-sku");   link1 = $("#m--sku"); }
		if(s == 3 || s == 4){ link = $("#m-desc");  link1 = $("#m--desc"); }
		if(s == 5 || s == 6){ link = $("#m-por");   link1 = $("#m--por"); }
		if(s == 7 || s == 8){ link = $("#m-price"); link1 = $("#m--price"); }
		if(s == 9 || s == 10){ link = $("#m-pdesc"); link1 = $("#m--pdesc"); }
		if(s == 11 || s == 12){ link = $("#m-uos"); link1 = $("#m--uos"); }
	}
	
	var i = $(link).parent().children().index($(link));
	if($(link).parent().parent().attr('class') == 'small-screen'){
		if(i==0){ var i1=1; var i2=2;  link1 = $("#m--sku"); } 
		else if(i==1){ var i1=3; var i2=4;  link1 = $("#m--desc"); } 
		else if(i==3){ var i1=7; var i2=8; link1 = $("#m--price"); }	
	}
	else {
		if(i==0){ var i1=1; var i2=2; } 
		else if(i==2){ var i1=3; var i2=4; }
		else if(i==3){ var i1=9; var i2=10; }
		else if(i==4){ var i1=11; var i2=12; } 
		else if(i==6){ var i1=5; var i2=6; } 
		else if(i==7){ var i1=7; var i2=8; }
	}
	
	$(link).parent().children().each(function(n){
				if($(link).parent().parent().attr('class') == 'small-screen'){ if(n > 3) return; }
				else { if(n == 1 || (n > 4 && n != 6 && n != 7)) return; }
					
				if(n == i){
					classStr = $(this).attr('class');
					idStr = $(this).attr('id');
					if(classStr == 'header'   || classStr == 'headerSortUp'){
						if(idStr == 'm-sku'   || idStr == 'm--sku'){   $("#sort").val('1'); $("#sort").formSelect() ; }
						if(idStr == 'm-desc'  || idStr == 'm--desc'){  $("#sort").val('3'); $("#sort").formSelect() ; }
						if(idStr == 'm-por'   || idStr == 'm--por'){   $("#sort").val('5'); $("#sort").formSelect() ; }
						if(idStr == 'm-price' || idStr == 'm--price'){ $("#sort").val('7'); $("#sort").formSelect() ; }
						if(idStr == 'm-pdesc' || idStr == 'm--pdesc'){ $("#sort").val('9'); $("#sort").formSelect() ; }
						if(idStr == 'm-uos'   || idStr == 'm--uos'){   $("#sort").val('11'); $("#sort").formSelect() ; }
						$(this).attr('class' , 'headerSortDown');
						nCurrentSortKey = i1;
					}
					else if(classStr == 'headerSortDown'){
						if(idStr == 'm-sku'   || idStr == 'm--sku'){   $("#sort").val('2'); $("#sort").formSelect() ; }
						if(idStr == 'm-desc'  || idStr == 'm--desc'){  $("#sort").val('4'); $("#sort").formSelect() ; }
						if(idStr == 'm-por'   || idStr == 'm--por'){   $("#sort").val('6'); $("#sort").formSelect() ; }
						if(idStr == 'm-price' || idStr == 'm--price'){ $("#sort").val('8'); $("#sort").formSelect() ; }
						if(idStr == 'm-pdesc' || idStr == 'm--pdesc'){ $("#sort").val('10'); $("#sort").formSelect() ; }
						if(idStr == 'm-uos'   || idStr == 'm--uos'){   $("#sort").val('12'); $("#sort").formSelect() ; }
						$(this).attr('class' , 'headerSortUp');
						nCurrentSortKey = i2;
					}
				}
				else $(this).attr('class' , 'header');
		});
		
	if(category_id == 0 || i > 2){
		$(link1).parent().children().each(function(n){
			if($(link1).parent().attr('class') == 'small-screen'){ if(n > 3) return; }
					
				if(n == i){
					classStr = $(this).attr('class');
					idStr = $(this).attr('id');
					if(classStr == 'header'   || classStr == 'headerSortUp'){
						if(idStr == 'm-sku'   || idStr == 'm--sku'){   $("#sort").val('1'); $("#sort").formSelect() ; }
						if(idStr == 'm-desc'  || idStr == 'm--desc'){  $("#sort").val('3'); $("#sort").formSelect() ; }
						if(idStr == 'm-por'   || idStr == 'm--por'){   $("#sort").val('5'); $("#sort").formSelect() ; }
						if(idStr == 'm-price' || idStr == 'm--price'){ $("#sort").val('7'); $("#sort").formSelect() ; }
						if(idStr == 'm-pdesc' || idStr == 'm--pdesc'){ $("#sort").val('9'); $("#sort").formSelect() ; }
						if(idStr == 'm-uos'   || idStr == 'm--uos'){   $("#sort").val('11'); $("#sort").formSelect() ; }
						$(this).attr('class' , 'headerSortDown');
						nCurrentSortKey = i1;
					}
					else if(classStr == 'headerSortDown'){
						if(idStr == 'm-sku'   || idStr == 'm--sku'){   $("#sort").val('2'); $("#sort").formSelect() ; }
						if(idStr == 'm-desc'  || idStr == 'm--desc'){  $("#sort").val('4'); $("#sort").formSelect() ; }
						if(idStr == 'm-por'   || idStr == 'm--por'){   $("#sort").val('6'); $("#sort").formSelect() ; }
						if(idStr == 'm-price' || idStr == 'm--price'){ $("#sort").val('8'); $("#sort").formSelect() ; }
						if(idStr == 'm-pdesc' || idStr == 'm--pdesc'){ $("#sort").val('10'); $("#sort").formSelect() ; }
						if(idStr == 'm-uos'   || idStr == 'm--uos'){   $("#sort").val('12'); $("#sort").formSelect() ; }
						$(this).attr('class' , 'headerSortUp');
						nCurrentSortKey = i2;
					}
				}
				else { $(this).attr('class' , 'header'); }
		});
	}else{
	}
	
	if($.isNumeric(s)){
		if(s == 1 || s == 3 || s == 5 || s == 7 || s == 9 || s == 11){ $(link).attr('class' , 'headerSortDown'); $(link1).attr('class' , 'headerSortDown'); }
		if(s == 2 || s == 4 || s == 6 || s == 8 || s == 10 || s == 12){ $(link).attr('class' , 'headerSortUp'); $(link1).attr('class' , 'headerSortUp'); }
		nCurrentSortKey = s;
	}
	//$('#sort').val(nCurrentSortKey);		
	$('#sort_key').val(nCurrentSortKey);
    $('#sortable_table tbody').html('<tr><td colspan="12" height="100"><div class="progress" id="loading" style="display:block; width:50%; left:25%; margin-top:0px; position:absolute;"><div class="indeterminate"></div></div></td></tr><tr><td colspan="12" style="padding:50px 0px 50px 0px; background:#efefef; text-align:center;">Sorting products please wait few seconds.</td></tr><tr><td colspan="12" height="100">&nbsp;</td></tr>;');

    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/sort_product/".request()->uri->getSegment(2)."/");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "sort_key=" + nCurrentSortKey + "&search0=" + search0 + "&search1=" + search1 + "&search2=" + search2 + "&search_mode=" + search_mode + "&category_id=" + category_id + "&per_page=" + per_page
        , error : function (xhr, status, error) {
            if (xhr.status == 401) {
                window.location.href = '/login'; return;
            } else {
				alert("An error occured: " + xhr.status + " " + xhr.statusText);
			}}
		, success : function(response, status, request) {
            var strArray = response.split('********************');
            //$('#search_mode').val(strArray[0]);
            //$('#product_pagination_div').html(strArray[0]);
            //$('#product_pagination_div1').html(strArray[0]);
            $('#sortable_table tbody').html(strArray[1]);
        }
    });
	return;
}

function inc_quantity(mode, prod_id, prod_code, prod_desc)
{
	cart_inc_quantity(mode, prod_id, prod_code, 0, prod_desc);
}

function go_quantity(e){
	var result;
	if(window.event){	 result = window.event.keyCode;  	}
	else if(e){  		 result = e.which;                  }
	if(result == 13){  	 set_qty_trolley();              	}
}

function edit_quantity(prod_id, prod_code) {
	cart_edit_quantity(prod_id, prod_code);
}

function change_quantity(prod_id, prod_code, e){
	cart_change_quantity(prod_id, prod_code, e);
}

function set_qty(link , prod_id, prod_code){
	$('#prod_id').val(prod_id);
	$('#prod_code').val(prod_code);
	var x = $(link).position();
    if($('#how_many_qty_info').css('visibility') == 'visible')
        $('#how_many_qty_info').css('visibility' , 'hidden');
    else
        DisplayPad(x.left , x.top);
}

function DisplayPad(gx , gy){
	var tp = 345;
	if($('.small-screen').css('display') == "none"){ tp = 255;}
    $('#how_many_qty_info').css('left' , gx - 430);
    $('#how_many_qty_info').css('top' , gy + tp);
    $('#how_many_qty_info').css('visibility' , 'visible');
    $('#how_many_qty').attr('value' , '');
    $('#how_many_qty').focus();
}

function set_qty_trolley(){
	var qty = $('#how_many_qty').val();
	var prod_id = $('#prod_id').val();
	var prod_code = $('#prod_code').val();
	var num;
	var input_id , span_id;
	if(isNaN(Number(qty))){
		$('#how_many_qty').val('');
		 return;
	}

	if(Math.round(Number(qty)) < 1){
		$('#how_many_qty').val('');
		return;
	}

	num = Math.round(Number(qty));
	input_id = "#input_" + prod_id;
	span_id = "#span_" + prod_id + ", #span__" + prod_id;
	$(span_id).text(Math.round(Number(qty)));
	$(span_id).css('display' , '');
	$(input_id).css('display' , 'none');
	if(Number(qty) != 0) $(span_id).parent().attr('class' , 'price_per_pack');
	else $(span_id).parent().attr('class' , 'price_per_pack_empty');

    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/to_cart");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "prod_code=" + prod_code + "&mode=3" + "&quantity=" + Math.round(Number(qty))
        , error: function (xhr, status, error) {
            if (xhr.status == 401) {
                window.location.href = '/login'; return;
            } else {
				alert("An error occured: " + xhr.status + " " + xhr.statusText);
			}}
		, success : function(response, status, request) {
        	$('#how_many_qty').val('');
        	$('#how_many_qty_info').css('visibility' , 'hidden');
        	$('#prod_id').val('');
			update_cart();
        }
    });
}

function resync_favorites(){
	console.log('running resync favorites');
	$.ajax({
		url:"<?php echo site_url("favorites/resync_favorites/");?>",
		method:"POST",
		error: function (xhr, status, error) {
		if (xhr.status == 401) {
			window.location.href = '/login'; return;
		} else {
			alert("An error occured: " + xhr.status + " " + xhr.statusText);
		}},
		success:function(data){
			$('#table_holder').html(data);
			update_cart();
		}		  
	});
}

function bulk_favorites(){
	console.log('running bulk add favorites to trolley');
	$.ajax({
		url:"<?php echo base_url("favorites/bulk_favorites") ?>",
		method:"POST",
		error: function (xhr, status, error) {
			if (xhr.status == 401) {
				window.location.href = '/login'; return;
			} else {
				alert("An error occured: " + xhr.status + " " + xhr.statusText);
			}},
		success:function(data){
			$('#table_holder').html(data);
			update_cart();
			console.log('Redirecting to Cart');
			location.replace('<?php echo base_url("pastorders");?>');	
		}
	});
}
		

/*
function search_query(){
	if( $('#product_search_div .large_view').css('display') == 'none' ){
	    $('#product_search_div .large_view #search0').attr("id","search00");
	    $('#product_search_div .small_view #search0').attr("id","search0");
	}else{
	    $('#product_search_div .large_view #search0').attr("id","search0");
		$('#product_search_div .small_view #search0').attr("id","search00");
	}
	var location_site = "<?php echo site_url("products/index");?>";
	if ($('#search0').val()=='') 
		var search_mode = 'default' + "/";
	else 
		var search_mode = 'search' + "/" + encodeURIComponent($('#search0').val().replace(/[\/()|'*]/g, ' ')) + "/0/0/";
	
	var nCurrentSortKey = 6;
	var category_id = 0;
	var uri_segment = 0;
	var per_page = 30;
	var promo = '<?php echo request()->uri->getSegment(2); ?>';
	
	location_site = location_site + "/" + search_mode;
	location_site = location_site + nCurrentSortKey + "/" + category_id + "/" + uri_segment + "/" + per_page + "/" + promo;
	location.replace(location_site);
	//alert(location_site);
	
} */
</script>
<style>
header{min-height:0px !important; margin:0px !important;}
main{background:url('images/diagonal_tile_bg3_light.png');}
#main-container .grid-wrapper .grid-item{background:white; border:gainsboro 1px solid; }
</style>
<div id="product_search_div" style="margin:-10px 0px -20px 0px; width:100%; display:none;">
    <img src="<?php echo base_url();?>images/spinner_small.gif" alt="spinner" id="spinner1">
    <form action="<?php echo base_url();?>index.php/products/index/search" method="post" accept-charset="utf-8" id="search_form" style="font-family:Arial;" onsubmit="event.preventDefault();">			
		<div class="large_view">
        	<input type="text" name="search0" value="" id="search0" class="product_search_cell" style="width:200px; background:#fff !important;" onclick=" this.select();" onkeyup="go_search(event);">
        </div>        
        <?php echo form_label('&nbsp;', 'product_code');?>
        <input type="hidden" name="sort_key" id="sort_key" value="3">
        <input type="hidden" name="search_mode" id="search_mode" value="default">
        <input type="hidden" name="per_page" id="per_page1" value="30"> 
        <input type="hidden" name="uri_segment" id="uri_segment" value="6">
        <input type="hidden" name="category" id="category" value="0">
        <input type="hidden" name="subcategory" id="subcategory" value="0">
        <input type="hidden" name="current_id" id="current_id" value="0">
        <input type="hidden" id="refresh" value="no">
        <input type="button" value="Search" style="background:#000; padding:7px; width:80px; height:35px; border:#000 2px solid; color:#fff !important; text-transform:uppercase;" onclick="search_query();">
        <!--
		<?php if(request()->uri->getSegment(2)=='index'){ 
		         $checked = '';
		         if($this->uri->segment(3)=='search' && $this->uri->segment(8)!=0){ $checked = 'checked';}
		?>
        <div style="margin-left:-100px; margin-top:-5px;">
        <label>
        <input type="checkbox" class="filled-in" name="s-cat" <?php echo $checked; ?> />
        <span>Current Promos Only</span>
        </label>
        </div>
        <?php } ?>
        -->
    </form>
</div>
<div id="order_total_div" style="height:140px;">
	<div class="shopping__cart-page-header text-center">
        <h2>Favorites</h2>
        <a href="javascript:void();" onclick="bulk_favorites()" style="background:#000; color:white; padding:5px 10px; border-radius:5px;"><i class="material-icons" style="position:absolute; margin-top:2px;">shopping_cart</i> <span style="padding-left:30px; font-size:12px; vertical-align:top;">Bulk Add Items to Trolley</span></a>
	</div>	
</div> 
<div class="featured-products">
	<div id="table_holder" class="table_holder">
	<?php echo $manage_table; ?>
	</div>
</div>

<div id="dialog-data" >
	<div id="feedback_bar" style="display:none;"></div>
	<fieldset id="how_many_qty_info" style='background-color:#FFFFFF; position: absolute; width:400px; height:40px;padding-top:10px; padding-right:10px; text-align:right; visibility:hidden; z-index:1000;'>
		<?php echo form_label(lang('Main.pastorders_how_many').':', 'how_many',array('class'=>'required')); ?>
		<?php echo form_input(array('name'=>'how_many_qty' , 'id'=>'how_many_qty' , 'class'=>'product_search_cell' , 'style'=>'width:115px; height:18px;' , 'onkeyup'=>'go_quantity(event);'));?>
		&nbsp;&nbsp;&nbsp;<div class='tiny_button' style='float: right;' onmouseover="this.className='tiny_button_over'" onmouseout="this.className='tiny_button'" onclick="$('#how_many_qty_info').css('visibility' , 'hidden');"><span>Cancel</span></div>
	&nbsp;&nbsp;&nbsp;<div class='tiny_button' style='float: right; margin-right:20px;' onmouseover="this.className='tiny_button_over'" onmouseout="this.className='tiny_button'" onclick="set_qty_trolley();"><span>OK</span></div>
		<input type='hidden' name='prod_id' id='prod_id' value='0'>
	</fieldset>
	<div id="dialog_form" title="Reload Products" style="font-family:Arial; font-size:12px;">
		<?php echo form_open('#' , array('id'=>'reload_form'));?>
			<fieldset id="ftp_location_info">
				<div class="field_row clearfix">
					<div id="please_wait">
						<img src="<?php echo base_url("/images/spinner_load.gif");?>" style="width:100px; height:100px; visibility:hidden;" id="img_wait">
					</div>
				</div>
			</fieldset>
		<?php echo form_close(); ?>
	</div>
    <input type='hidden' name='prod_id' id='prod_id' value='0'>
    <input type="hidden" name="curd_page" id="curd_page" value="<?php echo $curd_page;?>" size="4" >
    <input type="hidden" name="page" id="total_page" value="<?php echo $total_page;?>" size="4" >
    <input type="hidden" name="subcat_title" id="subcat_title" value="" >
    <input type="hidden" name="subcat_active" id="subcat_active" value="" >
    <span id="last_page_number" style="display:none;"><?php echo $total_page;?></span>
 </div>
<?php echo view("partial/footer"); ?>

