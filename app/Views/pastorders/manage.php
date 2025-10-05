<?php
 echo view("partial/header"); ?>
<style type="text/css">
	.ui-dialog {font-family:Arial; font-size:12px;}
</style>

<script type="text/javascript">
$(document).ready(function(){ 

    //set pagination
    var t = $('#total_page').val();
	var p = $('#curd_page').val();
	if(p==t){ $("#next").removeClass().addClass("disabled"); }
	if(p==1){ $("#prev").removeClass().addClass("disabled"); }
	
	$( "ul.pagination li.num" ).each(function( index, element ) {
	  if(index > t){ return false; }
      if($( this ).is( "#p"+index )){ 
	      if(index==p){ $( this ).removeClass().addClass("active"); }
         }
     });
	
	init_pagination();
	//set pagination
	function init_pagination(){
		var t = Number($('#total_page').val());
		var p = $('#curd_page').val();
		if(p==t){ $("#next").removeClass().addClass("disabled"); }
		if(p==1){ $("#prev").removeClass().addClass("disabled"); }
			
		$( "ul.pagination li.num" ).each(function( index ) {
			  var cn = $(this).attr('id').substring(1);
			  if(cn==p){ $( this ).removeClass().addClass("active"); }
		});
	}
	
	$("#go_btn").click(function(){
		$(this).data('clicked', true);
		$("#go_page").trigger("keyup");
	});

	$( "#dialog_form" ).dialog
	(
		{
			autoOpen:false ,
			height: 470 ,
			width: Math.min(520, $(window).width() * .95),
			modal: true ,
			open:function()
			{
				var order_id = $('#order_id').val();
			    $.ajax({
			        type : "POST"
			        , async : true
			        , url : "<?php echo base_url("$controller_name/get_order");?>"
			        , dataType : "json"
			        , timeout : 30000
			        , cache : false
			        , data : "order_id=" + order_id
			        , error : function(request, status, error) {
						alert("Order data is not readable. Request: "+request+" Status: "+status+" Error: "+error);
						$(this).dialog("close");
			        }
			        , success : function(response, status, request) {
			        	var contents = "<legend><?php echo lang("Main.pastorders_product_info"); ?></legend>";
			        	contents = contents + response.manage_table;
						$('#order_info').html(contents);
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
});

function popup_dialog(order_id , order_type='General', completed, presell)
{
	$('#order_id').val(order_id);
	$('#order_type').val(order_type);
	$('#ui-id-1').html(order_type + " Order: wo2-" + order_id);
	
	var buttons = $( "#dialog_form" ).dialog('option' , 'buttons');
	
	if(completed == 0)
	{ 
		$('.ui-dialog-titlebar').removeClass('sent');
		$.extend(buttons ,
			{
				'<?php echo lang('Main.pastorders_continue_this_order');?>': function (){
					continue_order(order_id , 1);
				}/*,
				'<?php echo lang('Main.pastorders_go_back');?>': function(){
					go_back();
				}*/
			}
		);
	}
	else
	{		
		$('.ui-dialog-titlebar').addClass('sent');
		$.extend(buttons ,
			{
				'<?php echo "View Cart";?>': function (){
					continue_order(order_id , 2);
				},
				'<?php echo "Reuse - Add Items to Trolley"; ?>': function (){
					reuse_order(order_id);
				}
			}
		);
	}
	
	$( "#dialog_form" ).dialog("option", "buttons", buttons);
	if( $( '.ui-dialog .ui-dialog-buttonpane .ui-button' ).size() >= 1 ){
		if(completed==1){			
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(1) .ui-button-text').text() == "Continue this order"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(1)').hide();
			}
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(2) .ui-button-text').text() == "Continue this order"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(2)').hide();
			}		
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(3) .ui-button-text').text() == "Continue this order"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(3)').hide();
			}			
		}else{	
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(1) .ui-button-text').text() == "View Cart"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(1)').hide();
			}
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(2) .ui-button-text').text() == "View Cart"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(2)').hide();
			}	
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(3) .ui-button-text').text() == "View Cart"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(3)').hide();
			}			
		}
		
		if(presell==1 || completed==0){			
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(1) .ui-button-text').text() == "Reuse - Add Items to Trolley"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(1)').hide();
			}
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(2) .ui-button-text').text() == "Reuse - Add Items to Trolley"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(2)').hide();
			}
			if ($('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(3) .ui-button-text').text() == "Reuse - Add Items to Trolley"){
				$('.ui-dialog .ui-dialog-buttonpane .ui-button:nth-child(3)').hide();
			}
		}
		
	}
	$( "#dialog_form" ).dialog("open");
}


function continue_order(order_id , mode)
{
	var vis = $('#how_many_qty_info').css('visibility');
	if(vis == "visible")
	{
		$('#how_many_qty').focus();
		return;
	}
	location.replace("<?php echo base_url("$controller_name/continue_order")?>" + "/" + mode + "/" + order_id);

}


function reuse_order(order_id)
{
	location.replace("<?php echo base_url("$controller_name/reuse_order")?>" + "/" + order_id);		
}

function resend_order(order_id)
{
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo base_url("orders/resend_orders");?>" + "/" + order_id
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , error : function(request, status, error) {
        	alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
			alert("Duplicate order (# "+ order_id +") email sent to telesales");
			console.log("WORKED: " + request.status);
        }
    });
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
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


function go_back()
{
	var vis = $('#how_many_qty_info').css('visibility');
	if(vis == "visible")
	{
		$('#how_many_qty').focus();
		return;
	}

	$('#prod_id').val('');
	$('#dialog_form').dialog('close');
}

function DisplayPad(gx , gy){
	var tp = 3;
	if($('.small-screen').css('display') == "none"){ tp = 8;}
    $('#how_many_qty_info').css('left' , gx - 320);
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
        , url : "<?php echo base_url("$controller_name/to_cart");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "prod_code=" + prod_code + "&mode=3" + "&quantity=" + Math.round(Number(qty))
        , error : function(request, status, error) {

         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
        	$('#how_many_qty').val('');
        	$('#how_many_qty_info').css('visibility' , 'hidden');
        	$('#prod_id').val('');
			update_cart();
        }
    });
}

/*function set_qty_trolley()
{
	var order_id = $('#order_id').val();

	var qty = $('#how_many_qty').val();
	var prod_id = $('#prod_id').val();
	var num;
	if(isNaN(Number(qty)))
	{
		$('#how_many_qty').val('');
		 return;
	}

	if(Math.round(Number(qty)) < 1)
	{
		$('#how_many_qty').val('');
		return;
	}

	num = Math.round(Number(qty));

    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/add_to_cart");?>"
        , dataType : "json"
        , timeout : 30000
        , cache : false
        , data : "prod_id=" + prod_id + "&quantity=" + qty + "&order_id=" + order_id
        , error : function(request, status, error) {

         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
            if(response == -1 || response == -3 || response == -4) alert("Order product is empty.");
        	else if(response == -2) alert("There is not this product.");

            $('#how_many_qty').val('');
        	$('#how_many_qty_info').css('visibility' , 'hidden');
        	$('#prod_id').val('');
			update_cart();
        }
    });
}*/

function select_per_page(url){

	var nCurrentSortKey = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search = $('#search').val();
	var per_page = $('#per_page').val();
	var uri_segment;
	var location_site = url;
	var page_num = $('#curd_page').val();

	location_site = location_site + "/" + search_mode + "/";
	//uri_segment = (Number(page_num) - 1) * Number(per_page);
	uri_segment = 0;
	if(search_mode == 'default')
		location_site = location_site + nCurrentSortKey + "/" + per_page + "/" + uri_segment;
	else if(search_mode == 'search'){
		if(search == '') search = "12345678901234567890";
		location_site = location_site + search + "/" + nCurrentSortKey + "/" + per_page + "/" + uri_segment;
	}
	location.replace(location_site);
}

function first_page(url)
{
	var sort_key = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search = $('#search').val();
	var per_page = $('#per_page').val();
	var uri_segment;
	var curd_page = $('#curd_page').val();

	if(curd_page == '1') return;
	else curd_page = 1;
	var location_site = url;

	location_site = location_site + "/" + search_mode + "/";
	uri_segment = (Number(curd_page) - 1) * Number(per_page);
	if(search_mode == 'default')
		location_site = location_site + sort_key + "/" + per_page + "/" + uri_segment;
	else if(search_mode == 'search')
	{
		if(search == '') search = "12345678901234567890";
		location_site = location_site + search + "/" + sort_key + "/" + per_page + "/" + uri_segment;
	}
	location.replace(location_site);
}

function prev_page(url)
{
	var sort_key = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search = $('#search').val();
	var per_page = $('#per_page').val();
	var uri_segment;
	var curd_page = $('#curd_page').val();
	var location_site = url;

	if(curd_page == '1') return;
	location_site = location_site + "/" + search_mode + "/";
	uri_segment = (Number(curd_page) - 2) * Number(per_page);
	location_site = location_site + sort_key + "/" + per_page + "/" + uri_segment;

	location.replace(location_site);
}

function next_page(url)
{
	var sort_key = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search = $('#search').val();
	var per_page = $('#per_page').val();
	var uri_segment;
	var curd_page = $('#curd_page').val();
	var total_page = $('#total_page').val();
	var location_site = url;

	if(curd_page == total_page) return;
	location_site = location_site + "/" + search_mode + "/";
	uri_segment = Number(curd_page) * Number(per_page);
	location_site = location_site + sort_key + "/" + per_page + "/" + uri_segment;
	location.replace(location_site);
}

function last_page(url)
{
	var sort_key = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search = $('#search').val();
	var per_page = $('#per_page').val();
	var uri_segment;
	var curd_page = $('#curd_page').val();
	var total_page = $('#last_page_number').text();
	var location_site = url;

	if(curd_page == total_page) return;
	else curd_page = total_page;
	location_site = location_site + "/" + search_mode + "/";
	uri_segment = (Number(curd_page) - 1) * Number(per_page);

	location_site = location_site + sort_key + "/" + per_page + "/" + uri_segment;

	location.replace(location_site);
}

<?php
if($user_info->username == "admin")
{
?>
function sort_product(link)
{
	var nCurrentSortKey = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search = $('#search').val();
	var per_page = $('#per_page').val();
	var nSortIndex = $(link).parent().children().index($(link));
	var nSortIndex1 = -1;
	var nSortIndex2;
	var classStr;

	$(link).parent().children().each(function(nIndex){

			if(nIndex > 5) return;
			if(nIndex == 0 || nIndex == 3 || nIndex == 4) return;
			if($(this).attr('class') != undefined)
			{
				nSortIndex1 += 2;
				nSortIndex2 = nSortIndex1 + 1;
			}
			if(nIndex == nSortIndex)
			{
				classStr = $(this).attr('class');
				if(classStr == 'header' || classStr == 'headerSortUp')
				{
					$(this).attr('class' , 'headerSortDown');
					nCurrentSortKey = nSortIndex1;
				}
				else if(classStr == 'headerSortDown')
				{
					$(this).attr('class' , 'headerSortUp');
					nCurrentSortKey = nSortIndex2;
				}
			}
			else
			{
				if($(this).attr('class') != undefined )
					$(this).attr('class' , 'header');
			}
		});
	$('#sort_key').val(nCurrentSortKey);



    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/sort_order");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "sort_key=" + nCurrentSortKey + "&search=" + search + "&search_mode=" + search_mode + "&per_page=" + per_page
        , error : function(request, status, error) {
                     alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
            var strArray = response.split('********************');
            $('#product_pagination_div').html(strArray[0]);
            $('#product_pagination_div1').html(strArray[0]);
            $('#sortable_table tbody').html(strArray[1]);
    		tb_init('#sortable_table a.thickbox');
//    		update_sortable_table();

        }
    });

	return;
}
<?php
}
else
{
?>
function sort_product(link)
{
	var nCurrentSortKey = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search = $('#search').val();
	var per_page = $('#per_page').val();
	var nSortIndex = $(link).parent().children().index($(link));
	var nSortIndex1 = -1;
	var nSortIndex2;
	var classStr;

	$(link).parent().children().each(function(nIndex){

			if(nIndex > 5) return;
			if($(this).attr('class') != undefined)
			{
				nSortIndex1 += 2;
				nSortIndex2 = nSortIndex1 + 1;
			}
			if(nIndex == nSortIndex)
			{
				classStr = $(this).attr('class');
				if(classStr == 'header' || classStr == 'headerSortUp')
				{
					$(this).attr('class' , 'headerSortDown');
					nCurrentSortKey = nSortIndex1;
				}
				else if(classStr == 'headerSortDown')
				{
					$(this).attr('class' , 'headerSortUp');
					nCurrentSortKey = nSortIndex2;
				}
			}
			else
			{
				if($(this).attr('class') != undefined)
					$(this).attr('class' , 'header');
			}
		});
	$('#sort_key').val(nCurrentSortKey);
//alert(nCurrentSortKey);


    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/sort_order");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "sort_key=" + nCurrentSortKey + "&search=" + search + "&search_mode=" + search_mode + "&per_page=" + per_page
        , error : function(request, status, error) {

         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
            var strArray = response.split('********************');
            $('#product_pagination_div').html(strArray[0]);
            $('#product_pagination_div1').html(strArray[0]);
            $('#sortable_table tbody').html(strArray[1]);
    		tb_init('#sortable_table a.thickbox');
//    		update_sortable_table();

        }
    });

	return;
}
<?php
}
?>
function set_direct_page(e , url)
{
	var result;
	if(window.event) result = window.event.keyCode;
	else if(e) result = e.which;
	if(result == 13 || $('#go_btn').data('clicked') ){
		var page_num = $('input.curd_page').val();
		var total_page = $('#total_page').val();
		if(isNaN(Number(page_num)))
		{
			alert("You must input the numeric.");
			$('#curd_page').val("");
			return;
		}
		if(Number(page_num) > Number(total_page))
		{
			alert("Page number is too big.");
			$('#curd_page').val("");
			return;
		}
		if(Number(page_num) < 1)
		{
			alert("Page Number must be a integer.");
			$('#curd_page').val("");
			return;
		}
		if(Math.round(Number(page_num)) < 1)
		{
			$('#curd_page').val("1");
			return;
		}
		var sort_key = $('#sort_key').val();
		var search_mode = $('#search_mode').val();
		var per_page = $('#per_page').val();
		var uri_segment = 0;
		var category_id = Number(category_id);
		var location_site = url;
		if($('#go_btn').data('clicked')){ page_num = $('#go_page').val(); }
		location_site = location_site + "/" + search_mode + "/";
		uri_segment = ( Math.round(Number(page_num)) - 1) * Number(per_page);
		location_site = location_site + sort_key + "/" + per_page + "/" + uri_segment;
		location.replace(location_site);
	}
}

function goto_page(p , url)
{
	var total_page = $('#last_page_number').text();
	var sort_key = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search = $('#search').val();
	var page_num = $('#curd_page').val();
	var per_page = $('#per_page').val();
	var category_id = Number(category_id);
	var location_site = url + "/" + search_mode + "/";
	var uri_segment = ( Math.round(Number(p)) - 1) * Number(per_page);
	location_site = location_site + sort_key + "/" + per_page + "/" + uri_segment;
	location.replace(location_site);
	//alert(location_site);
}

function go_quantity(e)
{
	var result;

	if(window.event){   result = window.event.keyCode;  }
	else if(e){         result = e.which; 	            }

	if(result == 13){	set_qty_trolley();	            }

}
</script>
<div id="product_search_div" style="margin-bottom:-20px; width:100%; display:none">
    <img src="<?php echo base_url();?>images/spinner_small.gif" alt="spinner" id="spinner1">
    <form action="<?php echo base_url();?>index.php/products/index/search" method="post" accept-charset="utf-8" id="search_form" style="font-family:Arial;" onsubmit="event.preventDefault();">			<div class="large_view">
        <input type="text" name="search0" value="" id="search0" class="product_search_cell" style="width:200px; background:#fff !important;" onclick=" this.select();" onkeyup="go_search(event);">
        </div>        
        <?php echo form_label('&nbsp;', 'product_code');?>
        <input type="hidden" name="sort_key" id="sort_key" value="4">
        <input type="hidden" name="search_mode" id="search_mode" value="default">
        <input type="hidden" name="per_page" id="per_page1" value="30"> 
        <input type="hidden" name="uri_segment" id="uri_segment" value="6">
        <input type="hidden" name="category" id="category" value="0">
        <input type="hidden" name="current_id" id="current_id" value="0">
        <input type="hidden" id="refresh" value="no">
        <input type="button" value="Search" style="background:#000; padding:7px; width:80px; height:35px; border:#000 2px solid; color:#fff !important; text-transform:uppercase;" onclick="search_query();">
    </form>
</div>
<div id="order_total_div">
	<div>
        <h2>Past Orders</h2>
        <p class="desc">Orders sent/ saved by <span><?php echo $user_info->username; ?></span></p>
	</div>	
</div>
<div id="title_bar">&nbsp;</div>
<input type="hidden" name="sort_key" id="sort_key" value="<?php echo $sort_key;?>">
<input type="hidden" name="search_mode" id="search_mode" value="<?php echo $search_mode;?>">
<input type="hidden" name="uri_segment" id="uri_segment" value="<?php echo $uri_segment;?>">
<input type="hidden" name="order_id" id="order_id" value="">
<input type="hidden" name="order_type" id="order_type" value="">
   
<div id="table_holder" class="table_holder">
<?php echo $manage_table; ?>
</div>

<div id="actions">
  <div class="input-field col s12">
    <select name='per_page' id='per_page' onchange="select_per_page('<? echo base_url("$controller_name/index");?>');">
      <option value="" disabled selected>Choose</option>
      <option value='30' <?php if($per_page == 30) echo "selected='true'";?>>30</option>
      <option value='40' <?php if($per_page == 40) echo "selected='true'";?>>40</option>
      <option value='50' <?php if($per_page == 50) echo "selected='true'";?>>50</option>
      <option value='75' <?php if($per_page == 75) echo "selected='true'";?>>75</option>
      <option value='100' <?php if($per_page == 100) echo "selected='true'";?>>100</option>
      <option value='150' <?php if($per_page == 150) echo "selected='true'";?>>150</option>
      <option value='200' <?php if($per_page == 200) echo "selected='true'";?>>200</option>
    </select>
    <label>Number of rows per page</label>
  </div>  
  <ul class="pagination">
     <li class="go_page" style="margin-top:-15px;"><label>Go to page #</label><br /><input type="text" id="go_page" name="page" class="curd_page" value="<?php echo $curd_page;?>" size="4" onkeyup="set_direct_page(event , '<?php  echo base_url("$controller_name/index");?>');" onclick="this.select();" style="width:inherit; height:2rem; background:#fbfbfb !important;"><i class="material-icons go" id="go_btn">slideshow</i></li>
     <li class="waves-effect" id="prev"><a href="javascript:void();" onclick="pPrev('<?php echo base_url("$controller_name/index");?>');"><i class="material-icons">chevron_left</i></a></li>
     <?php $t = $total_page; 
           for($i=1; $i<=$t; $i++){ 
               $h = intval($t/2);
               if($curd_page > 3){$h = $curd_page; }
               if( $i < 4 || ($i <= $h +1 && $i>= $h -1) || ($i == 4 && $t==6 ) ){?>
                   <li class="waves-effect num" id="p<?php echo $i; ?>">
                    <a href="javascript:void()" onclick="goto_page(<?php echo $i; ?>,'<?php echo base_url("$controller_name/index");?>');"><?php echo $i; ?></a>
                   </li> 
               <?php } else if ( $i == $t ){ ?>
                   <li class="waves-effect num" id="p<?php echo $i; ?>">
                    <a href="javascript:void()" onclick="goto_page(<?php echo $i; ?>,'<?php echo base_url("$controller_name/index");?>');"><?php echo $i; ?></a>
                   </li> 
               <?php } else if( $i == 5 || $i == $t-1 ){ ?>
                   <li class="disabled"><a><i class="material-icons">more_horiz</i></a></li> 
               <?php }
           }?>
     <!-- <li class="waves-effect" id="next"><a href="javascript:void();" onclick="pNext('<?php echo base_url("$controller_name/index");?>');"><i class="material-icons">chevron_right</i></a></li> -->
	 <li class="waves-effect" id="next"><a href="javascript:void();" ><i class="material-icons">chevron_right</i></a></li>
  </ul>
  <!--
  <ul class="pagination">
     <li class="waves-effect" id="prev"><a href="javascript:void();" onclick="prev_page('<? echo base_url("$controller_name/index");?>');"><i class="material-icons">chevron_left</i></a></li>-->
     <!--<li class="active" id="p1"><a href="javascript:void()" onclick="first_page('<? echo base_url("$controller_name/index");?>');">1</a></li>-->
     <!--<?php $t = $total_page; for($i=1; $i<=$t; $i++){ ?>
	 <li class="waves-effect" id="p<?php echo $i; ?>">
         <a href="javascript:void()" onclick="goto_page(<?php echo $i; ?>,'<? echo base_url("$controller_name/index");?>');"><?php echo $i; ?></a>
     </li> 
	 <?php }?>
     <li class="waves-effect" id="next"><a href="javascript:void();" onclick="next_page('<? echo base_url("$controller_name/index");?>');"><i class="material-icons">chevron_right</i></a></li>
  </ul>
  -->
  <br style="clear:both;">
</div>
<?php echo view("partial/footer"); ?>
<div id="feedback_bar"></div>

<div id="dialog_form" title="View Order" style="font-family:Arial; font-size:12px;" class="">
	<fieldset id="order_info">
	</fieldset>
	<fieldset id="how_many_qty_info" style='background-color:#FFFFFF; position:absolute; width:320px !important; height:40px !important; padding:5px 10px 0px 0px !important; text-align:right; visibility:hidden;'>
		<?php echo form_label(lang('Main.pastorders_how_many').':', 'how_many',array('class'=>'required')); ?>
		<?php echo form_input(array('name'=>'how_many_qty' , 'id'=>'how_many_qty' , 'class'=>'product_search_cell' , 'style'=>'width:50px !important;  height:18px;' , 'onkeyup'=>'go_quantity(event);'));?>
		&nbsp;&nbsp;&nbsp;<div class='tiny_button' style='float: right;' onmouseover="this.className='tiny_button_over'" onmouseout="this.className='tiny_button'" onclick="$('#how_many_qty_info').css('visibility' , 'hidden');"><span>Cancel</span></div>
	&nbsp;&nbsp;&nbsp;<div class='tiny_button' style='float: right; margin-right:20px;' onmouseover="this.className='tiny_button_over'" onmouseout="this.className='tiny_button'" onclick="set_qty_trolley();"><span>OK</span></div>
	</fieldset>
	<input type='hidden' name='prod_id' id='prod_id' value='0'>
	<input type='hidden' name='prod_code' id='prod_code' value=''>
	<input type="hidden" name="page" id="curd_page" value="<?php echo $curd_page;?>" size="4" >
	<input type="hidden" name="page" id="total_page" value="<?php echo $total_page;?>" size="4" >
</div>
</div>
</div>
