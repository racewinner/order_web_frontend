<?php 
	echo view("partial/header"); 
	$sort_options = [
		['label' => 'Best Selling', 'icon' => "/images/tables/09-desc.png", 'value' => 9],
		['label' => 'Brand', 'icon' => "/images/tables/09-asc.png", 'value' => 10],
		['label' => 'Description', 'icon' => "/images/tables/az-asc.png", 'value' => 3],
		['label' => 'Description', 'icon' => "/images/tables/az-desc.png", 'value' => 4],
		['label' => 'POR%', 'icon' => "/images/tables/09-asc.png", 'value' => 5],
		['label' => 'POR%', 'icon' => "/images/tables/09-desc.png", 'value' => 6],
		['label' => 'Price', 'icon' => "/images/tables/09-asc.png", 'value' => 7],
		['label' => 'Price', 'icon' => "/images/tables/09-desc.png", 'value' => 8],
		['label' => 'SKU', 'icon' => "/images/tables/09-asc.png", 'value' => 1],
		['label' => 'SKU', 'icon' => "/images/tables/09-desc.png", 'value' => 2],
	]
?>
<script type="text/javascript">
$(document).ready(function(){
	$("#sort").val(<?= $sort_key ?>).formSelect() ;	
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
				        , url : "<?php echo base_url("$controller_name/reload_product/");?>"
				        , dataType : "text"
				        , timeout : 30000
				        , cache : false
				        , data : "person_id=0"
				        , error : function (xhr, status, error) {
							if (xhr.status == 401) {
								window.location.href = '/login'; return;
							} else {
								alert("An error occured: " + xhr.status + " " + xhr.statusText);
							}},
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

	function onWindowResized() {
		if(window.visualViewport.width > 1201) {
			$("#promo_side_filter").removeClass("sidenav");
		} else {
			$("#promo_side_filter").addClass("sidenav");
		}
	};
	window.visualViewport.addEventListener('resize', function() {
		onWindowResized();
	});
	onWindowResized();

	$("button#gridview").click(function() {
		$("#view_mode").val('grid');
		load_promos();
	})
	$("button#listview").click(function() {
		$("#view_mode").val('list');
		load_promos();
	})
	$("input[name='filter_brand']").change(function(e) {
		$("#curd_page").val(1);
		load_promos();
	})
	$("input[name='filter_priceEnd']").change(function(e) {
		$("#curd_page").val(1);
		load_promos();
	})
	$('#chk_im_new').click(function(){
		var im_new = ($('#im_new').val()==1) ? 0 : 1;
		$('#im_new').val(im_new);
		$("#curd_page").val(1);
		load_promos();
	}); 
	$('#chk_plainprofit').click(function(){
		var plan_profit = ($('#plan_profit').val()==1) ? 0 : 1;
		$('#plan_profit').val(plan_profit);
		$("#curd_page").val(1);
		load_promos();
	});
	$('#chk_own_label').click(function(){
		var own_label = ($('#own_label').val()=='Y') ? 'N' : 'Y';
		$('#own_label').val(own_label);
		$("#curd_page").val(1);
		load_promos();
	});
	$('#chk_favorite').click(function(){
		var favorite = ($('#favorite').val()==1) ? 0 : 1;
		$("#curd_page").val(1);
		$('#favorite').val(favorite);
		load_promos();
	});
	$("#chk_rrp").click(function() {
		var rrp = ($('#rrp').val()==1) ? 0 : 1;
		$("#curd_page").val(1);
		$('#rrp').val(rrp);
		load_promos();
	})
	$("#chk_pmp").click(function() {
		var pmp = ($('#pmp').val()==1) ? 0 : 1;
		$("#curd_page").val(1);
		$('#pmp').val(pmp);
		load_promos();
	})
	$("#chk_non_pmp").click(function() {
		var non_pmp = ($('#non_pmp').val()==1) ? 0 : 1;
		$("#curd_page").val(1);
		$('#non_pmp').val(non_pmp);
		load_promos();
	})

	init_pagination();
});

function inc_quantity(mode , prod_id, prod_code, prod_desc)
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
        , url : "<?php echo base_url("$controller_name/to_cart");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "prod_code=" + prod_code + "&mode=3" + "&quantity=" + Math.round(Number(qty))
        , error: function (xhr, status, error) {
            if (xhr.status == 401) {
                window.location.href = '/login'; return;
            } else {
				alert("An error occured: " + xhr.status + " " + xhr.statusText);
	    	}},
        , success : function(response, status, request) {
        	$('#how_many_qty').val('');
        	$('#how_many_qty_info').css('visibility' , 'hidden');
        	$('#prod_id').val('');
			update_cart();
        }
    });
}

function set_direct_page(e , url) {
	var result;
	const total_page = Number($('#total_page'));

	if(window.event){	 result = window.event.keyCode; 	}
	else if(e){  		 result = e.which;              	}

	if( result == 13 || $('#go_btn').data('clicked') ){
		var page_num = $('input.curd_page').val();
		if(isNaN(Number(page_num))){
			alert("You must input the numeric.");
			$('#curd_page').val("");
			return;
		}
		page_num = Number(page_num);

		if( page_num > total_page){
			alert("Page number is too big.");
			$('#curd_page').val("");
			return;
		}

		if(page_num < 1){
			alert("Page Number must be a integer.");
			$('#curd_page').val("");
			return;
		}
		if(Math.round(page_num) < 1){
			$('#curd_page').val("");
			return;
		}
		
		$("#curd_page").val(page_num);
		load_promos();
	}
}
function goto_page(page) {
	$("#curd_page").val(page);
	load_promos();
}
function goNextPage() {
	const total_page = Number($("#total_page").val());
	const curd_page = Number($("#curd_page").val());
	if(curd_page < total_page) {
		$("#curd_page").val(curd_page + 1);
		load_promos();
	}
}
function goPrevPage() {
	const curd_page = Number($("#curd_page").val());
	if(curd_page > 1) {
		$("#curd_page").val(curd_page - 1);
		load_promos();
	}
}
function select_per_page() {
	$("#curd_page").val(1);
	load_promos();
}
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
function clearFilter () {
	$("#category_id").val('');
	$("#curd_page").val(1);

	let chkBoxes = $("input[name='filter_brand']");
	for(let i=0; i<chkBoxes.length; i++) {
		chkBoxes[i].checked = false;
	}

	chkBoxes = $("input[name='filter_priceEnd']");
	for(let i=0; i<chkBoxes.length; i++) {
		chkBoxes[i].checked = false;
	}

	$("#im_new").val(0);
	$("#plan_profit").val(0);
	$("#own_label").val('N');
	$("#favorite").val(0);
	$("#rrp").val(0);
	$("#pmp").val(0);
	$("#non_pmp").val(0);

	$("#chk_im_new")[0].checked = false;
	$("#chk_plainprofit")[0].checked = false;
	$("#chk_own_label")[0].checked = false;
	$("#chk_favorite")[0].checked = false;
	$("#chk_rrp")[0].checked = false;
	$("#chk_pmp")[0].checked = false;
	$("#chk_non_pmp")[0].checked = false;

	load_promos();
}
function getFilterPriceEnds() {
	const filter_priceEnds = [];
	const peChkBoxes = $("input[name='filter_priceEnd']");
	for(let i=0; i<peChkBoxes.length; i++) {
		if(peChkBoxes[i].checked) {
			filter_priceEnds.push($(peChkBoxes[i]).data('price-end'));
		}		
	}
	return filter_priceEnds;
}
function getFilterBrands() {
	const filter_brands = [];
	const brChkBoxes = $("input[name='filter_brand']");
	for(let i=0; i<brChkBoxes.length; i++) {
		if(brChkBoxes[i].checked) {
			filter_brands.push($(brChkBoxes[i]).data('brand'));
		}		
	}
	return filter_brands;
}
function onSideSort(sort_key) {
	$('#sort_key').val(sort_key);
	$("#curd_page").val(1);
	load_promos();
}
function onSortDropbox(e) {
	$('#sort_key').val(e.target.value);
	$("#curd_page").val(1);
	load_promos();
}
function selectCategory(category_id='') {
	$("#category_id").val(category_id);
	$("#curd_page").val(1);
	load_promos();
}
function load_promos(type=null){
	if(!type) type = $("#type").val();
	const category_id = $("#category_id").val();
	const view_mode = $("#view_mode").val();
	const sort_key = $("#sort_key").val();
	const per_page = Number($("#per_page").val());
	const curd_page = Number($("#curd_page").val());
	const offset = (curd_page - 1) * per_page;
	var im_new = Number($('#im_new').val() ?? 0);
	var plan_profit = Number($("#plan_profit").val() ?? 0);
	var own_label = $("#own_label").val() ?? 'N';
	var favorite = Number($("#favorite").val() ?? 0);
	var rrp = Number($("#rrp").val() ?? 0);
	var pmp = Number($("#pmp").val() ?? 0);
	var non_pmp = Number($("#non_pmp").val() ?? 0);

	var location_site = `/promos/index/${type}?`;
	location_site += "&view_mode=" + view_mode;
	location_site += "&per_page=" + per_page;
	location_site += "&curd_page=" + curd_page;
	location_site += "&offset=" + offset;
	if(category_id) location_site += "&category_id=" + category_id;
	if(sort_key) location_site += "&sort_key=" + sort_key;
	location_site += "&mobile=" + (isMobile() ? 1 : 0);
	if(im_new) location_site += "&im_new=" + im_new;
	if(plan_profit) location_site += "&plan_profit=" + plan_profit;
	if(own_label == 'Y') location_site += "&own_label=" + own_label;
	if(favorite) location_site += "&favorite=" + favorite;
	if(rrp) location_site += "&rrp=" + rrp;
	if(pmp) location_site += "&pmp=" + pmp;
	if(non_pmp) location_site += "&non_pmp=" + non_pmp;

	// const filter_brands = getFilterBrands();
	// if(filter_brands?.length > 0) {
	// 	location_site += "&filter_brands=" + encodeURIComponent(JSON.stringify(filter_brands));
	// }

	const filter_priceEnds = getFilterPriceEnds();
	if(filter_priceEnds?.length > 0) {
		location_site += "&filter_priceEnds=" + encodeURIComponent(JSON.stringify(filter_priceEnds));
	}

	if($(".filter-by-category li").hasClass('active')) location_site += "&scf=1";
	if($(".filter-by-brand li").hasClass('active')) location_site += "&sbf=1";
	if($(".filter-by-priceEnd li").hasClass('active')) location_site += "&spf=1";
	
	location.replace(location_site);
}
</script>
<div id="content_area_wrapper">
	<div id="content_area">
		<div id="product_search_div" style="margin:-10px 0px -20px 0px; width:100%; display:none;">
			<img src="<?php echo base_url();?>images/spinner_small.gif" alt="spinner" id="spinner1">
			<form action="<?php echo base_url();?>index.php/products/index/search" method="post" accept-charset="utf-8" id="search_form" style="font-family:Arial;" onsubmit="event.preventDefault();">			<div class="large_view">
				<input type="text" name="search0" value="" id="search0" class="product_search_cell" style="width:200px; background:#fff !important;" onclick=" this.select();" onkeyup="go_search(event);">
				</div>        
				<?php echo form_label('&nbsp;', 'product_code');?>
				<input type="hidden" name="type" id="type" value="<?= $type ?>">
				<input type="hidden" name="sort_key" id="sort_key" value="<?= $sort_key ?>">
				<input type="hidden" name="total_page" id="total_page" value="<?= $total_page ?>"> 
				<input type="hidden" name="curd_page" id="curd_page" value="<?= $curd_page ?>"> 
				<input type="hidden" name="view_mode" id="view_mode" value="<?= $view_mode ?>"> 
				<input type="hidden" name="category_id" id="category_id" value="<?= $category_id ?>">
				<input type="hidden" name="im_new" id="im_new" value="<?php echo $im_new;?>">
				<input type="hidden" name="plan_profit" id="plan_profit" value="<?php echo $plan_profit;?>">
				<input type="hidden" name="own_label" id="own_label" value="<?php echo $own_label ;?>">
				<input type="hidden" name="favorite" id="favorite" value="<?php echo $favorite ;?>">
				<input type="hidden" name="rrp" id="rrp" value="<?php echo $rrp ;?>">
				<input type="hidden" name="pmp" id="pmp" value="<?php echo $pmp ;?>">
				<input type="hidden" name="non_pmp" id="non_pmp" value="<?php echo $non_pmp ;?>">
				<input type="button" value="Search" style="background:#000; padding:7px; width:80px; height:35px; border:#000 2px solid; color:#fff !important; text-transform:uppercase;" onclick="search_query();">
			</form>
		</div>
		<div id="order_total_div">
			<?php echo lang('Main.promos'); ?>
		</div> 

		<div class="d-flex">
			<div class="promo-filter-section sidenav p-4" id="promo_side_filter">
				<div id="f_links" class="promo-menu">
					<ul style="margin-bottom:0px;">
						<?php if($user_info->price_list010 == "1") { ?>
							<li id="du" class="mb-4"><a class="p-0 <?= $type=='du' ? 'active' : '' ?>" onclick="load_promos('du')" ><span id="dt">DAY-TODAY</span></a></li>
						<?php } if ($user_info->price_list012 == "1") { ?>
							<li id="us" class="mb-4"><a class="p-0 <?= $type=='us' ? 'active' : '' ?>" onclick="load_promos('us')" >USAVE</a></li>
						<?php } if($user_info->price_list999 == "1") { ?>
							<li id="cc" class="mb-4"><a class="p-0 <?= $type=='cc' ? 'active' : '' ?>" onclick="load_promos('cc')" >CASH & CARRY</a></li>
						<?php }?>
					</ul>
				</div>

				<div class="d-flex my-4 px-4">
					<div class="flex-fluid d-flex justify-content-start ps-2">Filter by:</div>
					<div><a class="text-underline me-4 cursor-pointer" onclick="clearFilter()">CLEAR</a></div>
				</div>
				
				<div class="text-left filter-by-priceEnd d-flex flex-column align-items-center">
					<ul class="collapsible w-100">
						<li class="w-100 <?= !empty($spf) ? 'active' : '' ?>">
							<div class="collapsible-header d-flex align-items-center">
								<div class="flex-fluid">Price End</div>
								<div><img src="/images/icons/line-angle-down.svg" style="width:15px; height: 15px;"/></div>
							</div>
							<div class="collapsible-body py-4 px-0">
								<?php
									foreach($priceEnds as $pe) {
								?>
								<div class="brand ms-4 mb-3">
									<label>
										<input type="checkbox" name="filter_priceEnd" data-price-end="<?= $pe ?>" <?= (in_array($pe, $filter_priceEnds) ? 'checked' : '') ?> />
										<span class='brand-label'><?= date('d/m/Y', $pe) ?></span>
									</label>
								</div>
								<?php
									}
								?>
							</div>
						</li>
					</ul>
				</div>

				<div class="text-left filter-by-category d-flex flex-column align-items-center">
					<ul class="collapsible w-100">
						<li class="w-100 <?= !empty($scf) ? 'active' : '' ?>">
							<div class="collapsible-header d-flex align-items-center">
								<div class="flex-fluid">Category</div>
								<div><img src="/images/icons/line-angle-down.svg" style="width:15px; height: 15px;"/></div>
							</div>
							<div class="collapsible-body px-0 py-4">
								<div class="ms-4 mt-2 category <?= $category_id ? '' : 'active' ?>" onclick="selectCategory('')">All</div>
								<?php
									foreach($category as $c){
										echo '<div class="ms-4 mt-2 category ' . ($c->category_id == $category_id ? "active" : "") . '" onclick="selectCategory('.$c->category_id.')">'.$c->category_name.'</div>';
									}
								?>
							</div>
						</li>
					</ul>
				</div>

				<!-- <div class="text-left filter-by-brand d-flex flex-column align-items-center">
					<ul class="collapsible w-100">
						<li class="w-100 <?= !empty($sbf) ? 'active' : '' ?>">
							<div class="collapsible-header d-flex align-items-center">
								<div class="flex-fluid">Brand</div>
								<div><img src="/images/icons/line-angle-down.svg" style="width:15px; height: 15px;"/></div>
							</div>
							<div class="collapsible-body">
								<?php
									foreach($brands as $brand) {
								?>
								<div class="brand ms-4 mb-3">
									<label>
										<input type="checkbox" name="filter_brand" data-brand="<?= $brand ?>" <?= (in_array($brand, $filter_brands) ? 'checked' : '') ?> />
										<span class='brand-label'><?= $brand ?></span>
									</label>
								</div>
								<?php
									}
								?>
								<div class="brand ms-4"><label>
									<input type="checkbox" name="filter_brand" data-brand="" <?= (in_array('', $filter_brands) ? 'checked' : '') ?>/>
									<span>Others</span>
								</label></div>
							</div>
						</li>
					</ul>
				</div> -->

				<div class="mb-4 px-2">
					<label>
						<input type="checkbox" class="" id="chk_im_new" <?= $im_new ? 'checked' : '' ?> />
						<span for='chk_im_new'>New Product</span>
					</label>
				</div>
				<div class="mb-4 px-2">
					<label>
						<input type="checkbox" class="" id="chk_plainprofit" <?= $plan_profit ? 'checked' : '' ?> />
						<span for='chk_plainprofit'>Plan Profit</span>
					</label>
				</div>
				<div class="mb-4 px-2">
					<label>
						<input type="checkbox" class="" id="chk_own_label" <?= $own_label == 'Y' ? 'checked' : '' ?> />
						<span for='chk_own_label'>Own Label</span>
					</label>
				</div>
				<div class="mb-4 px-2">
					<label>
						<input type="checkbox" class="" id="chk_favorite" <?= $favorite ? 'checked' : '' ?> />
						<span for='chk_favorite'>Favorite</span>
					</label>
				</div>
				<div class="mb-4 px-2">
					<label>
						<input type="checkbox" class="" id="chk_rrp" <?= $rrp ? 'checked' : '' ?> />
						<span for='chk_rrp'>£1.00 rrp</span>
					</label>
				</div>
				<div class="mb-4 px-2">
					<label>
						<input type="checkbox" class="" id="chk_pmp" <?= $pmp ? 'checked' : '' ?> />
						<span for='chk_pmp'>PMP</span>
					</label>
				</div>
				<div class="mb-4 px-2">
					<label>
						<input type="checkbox" class="" id="chk_non_pmp" <?= $non_pmp ? 'checked' : '' ?> />
						<span for='chk_non_pmp'>No PMP</span>
					</label>
				</div>
			</div>
			<div class="featured-products flex-fluid">
				<div class="promos-table-header d-none flex-on-extra-large align-items-center px-4">
					<div class="d-flex align-items-center mb-2" style="margin-right: 50px;" >
						<label>Showing</label> 
						<span class="mx-2"><?= $from ?> - <?= $to ?></span>
						<label>of</label>
						<span class="mx-2"><?= $total_rows ?></span>
						<label>products</label>
					</div>

					<div class="d-flex align-items-center">
						<label class="me-2">Display</label>
						<div class="per-page">
							<select name='per_page' id='per_page' onchange="select_per_page()">
								<option value="" disabled selected>Choose</option>
								<option value='30' <?php if($per_page == 30) echo "selected='true'";?>>30</option>
								<option value='40' <?php if($per_page == 40) echo "selected='true'";?>>40</option>
								<option value='50' <?php if($per_page == 50) echo "selected='true'";?>>50</option>
								<option value='75' <?php if($per_page == 75) echo "selected='true'";?>>75</option>
								<option value='100' <?php if($per_page == 100) echo "selected='true'";?>>100</option>
								<option value='150' <?php if($per_page == 150) echo "selected='true'";?>>150</option>
								<option value='200' <?php if($per_page == 200) echo "selected='true'";?>>200</option>
							</select>
						</div>
					</div>

					<div class="d-flex flex-fluid justify-content-end align-items-center">
						<label class="me-2">Sort By</label>
						<div style="width: 130px;">
							<select name='sort' id='sort' onchange="onSortDropbox(event);" style="background:#000; color:#fff;">
								<option value="" disabled selected>Choose</option>
								<?php
									foreach($sort_options as $sort) {
								?>
								<option value='<?= $sort['value'] ?>' data-icon="<?= $sort['icon'] ?>" class="circle"><?= $sort['label'] ?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>
					<div class="products-view-style d-flex align-items-center" style="margin-left: 50px;">
						<label class="me-4">View</label>
						<button id="gridview" type='button' class="me-2 cursor-pointer" style="border: 0px;">
							<img src='images/icons/grid-view.svg' style="width: 20px; height: 20px;"/>
						</button>
						<button id="listview" type='button' class="cursor-pointer" style='border: 0px;'>
							<img src='images/icons/list-view.svg' style="width: 20px; height: 20px;"/>
						</button>
					</div>
				</div>

				<div class="promos-table-header d-block hide-on-extra-large-only mb-4 px-4">
					<div class="d-flex align-items-center mb-2" style="margin-right: 50px;" >
						<label>Showing</label> 
						<span class="mx-2"><?= $from ?> - <?= $to ?></span>
						<label>of</label>
						<span class="mx-2"><?= $total_rows ?></span>
						<label>products</label>
					</div>

					<div class="d-flex justify-content-between">
						<div class="d-flex align-items-center sidenav-trigger" data-target="promo_side_filter">
							<img src="/images/icons/filter.svg" style="width: 25px; height: 25px;" />
							<label class="ms-2">Filter</label>
						</div>

						<div class="d-flex align-items-center sidenav-trigger" data-target="rside-sort">
							<label class="me-2">Sort By</label>
							<img src="/images/icons/sort.svg" style="width: 20px; height: 20px;" />
						</div>
						<div class="sidenav right-aligned p-4" id="rside-sort">
							<div class="py-4 mb-4" style="font-size: 110%; border-bottom: 1px solid #ddd;">Sort by: </div>
							<?php
								foreach($sort_options as $sort) {
							?>
							<div 
								class="d-flex align-items-center mt-4 sort-option <?= $sort_key == $sort['value'] ? 'active' : '' ?>"
									onclick="onSideSort(<?=$sort['value']?>)"
							>
								<div class="flex-fluid d-flex">
									<label class="me-4"><?=$sort['label']?></label>
									<img src="<?=$sort['icon']?>" style="width: 20px; height: 20px" />
								</div>
								<div>
									<?php
										if($sort_key == $sort['value']) {
									?>
										<svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									<?php		
										}
									?>
								</div>
							</div>
							<?php
								}
							?>
						</div>

						<div class="products-view-style d-flex align-items-center">
							<label class="me-4">View</label>
							<button id="gridview" type='button' class="me-2 cursor-pointer" style="border: 0px;">
								<img src='images/icons/grid-view.svg' style="width: 20px; height: 20px;"/>
							</button>
							<button id="listview" type='button' class="cursor-pointer" style='border: 0px;'>
								<img src='images/icons/list-view.svg' style="width: 20px; height: 20px;"/>
							</button>
						</div>
					</div>
				</div>

				<div id="table_holder" class="table_holder mt-4">
					<?php echo $manage_table; ?>
				</div>

				<div id="actions">
					<ul class="pagination">
						<li class="go_page">
							<input type="text" id="go_page" name="page" class="curd_page" value="<?php echo $curd_page;?>" size="4" onkeyup="set_direct_page(event)" onclick="this.select();" style="height:2rem; background:#fbfbfb !important;">
							<i class="material-icons go" id="go_btn">slideshow</i>
						</li>
						<li class="waves-effect" id="prev"><a href="javascript:void();" onclick="goPrevPage();"><i class="material-icons">chevron_left</i></a></li>
						<?php
							$t = $total_page; 
							for($i=1; $i<=$t; $i++){ 
								$h = intval($t/2);
								if($curd_page > 3){$h = $curd_page; }
								if( $i < 4 || ($i <= $h +1 && $i>= $h -1) || ($i == 4 && $t==6 ) ){?>
									<li class="waves-effect num" id="p<?php echo $i; ?>">
									<a href="javascript:void()" onclick="goto_page(<?php echo $i; ?>)">
										<?php echo $i; ?>
									</a>
									</li> 
								<?php } else if ( $i == $t ){ ?>
									<li class="waves-effect num" id="p<?php echo $i; ?>">
									<a href="javascript:void()" onclick="goto_page(<?php echo $i; ?>)"><?php echo $i; ?></a>
									</li> 
								<?php } else if( $i == 5 || $i == $t-1 ){ ?>
									<li class="disabled"><a><i class="material-icons">more_horiz</i></a></li> 
								<?php }
						}?>
						<li class="waves-effect" id="next"><a href="javascript:void();" onclick="goNextPage();" ><i class="material-icons">chevron_right</i></a></li>
					</ul>
				</div>
			</div>
		</div>
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
 </div>
<?php echo view("partial/footer"); ?>

