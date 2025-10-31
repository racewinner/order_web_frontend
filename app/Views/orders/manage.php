<?php echo view("partial/header"); ?>
<style type="text/css">
	.ui-dialog {font-family:Arial; font-size:12px;}
	.tlinks{text-align:center; padding-bottom:15px;}
	.tlinks a{display:inline-block; min-width:120px; padding:5px 10px; margin:0px 5px; background:#eee; line-height: 1.3; border-radius:5px;}
	.tlinks a:hover, .tlinks a.active{background:lightseagreen; color:#fff !important;}
	.tlinks a span{font-size:11px;}
	.unknown-products {
		position: relative;
		width: 100%;
		padding: 10px;
		border-radius: 5px;
		display: flex;
		justify-content: center;
		align-items: center;
		background: #ffffe3;
		table {
			tbody tr {
				&:hover {
					background: #fefece;
				}
			}
		}
		h5 { 
			color: red;
		}
		p { 
			color: red; 
		}
		img.loading {
			width: 30px;
			height: 30px;
		}
		.action {
			i {
				cursor: pointer;
				&.search {
					color: var(--primary-blue);
				}
			}
		}
	}
	.spinner {
		animation: spin 1s linear infinite; /* Spins forever */
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}
		100% {
			transform: rotate(360deg);
		}
	}
</style>
<script type="text/javascript">
$(document).ready(function(){
	$("#dialog_form" ).dialog
	(
		{
			autoOpen:false ,
			height: 250 ,
			width: 480 ,
			modal: true ,
			buttons:
			{
				"Go": function()
				{
					if($('#file_path').val() == '')
					{
						alert('You must select the CSV file.');
						return;
					}
					$(this).dialog('close');
					$('#product_form').submit();
				}
			}
		}
	);
	console.log("TYPE: <?php echo $type; ?>");
	//alert($('.tlinks').find('.<?php echo $type; ?>').text());
	$('.tlinks').find('.<?php echo $type; ?>').toggleClass('active');
	$('#order_total_div h2').text('<?php echo ucfirst($type=='spresell' ? 'Seasonal Presell' : $type ); ?> Order');

	$(".unknown-products").on("click", "table tbody .action i.delete", remove_unknown_product);
	$(".unknown-products").on("click", "table tbody .action i.search", search_unknown_product);
});

function remove_unknown_product(e)
{
	const target = $(e.currentTarget);
	target.removeClass("delete");
	target.addClass("spinner");
	target.text("autorenew");
	const id = target.data("id");
	$.ajax({
        type : "DELETE"
        , async : true
        , url : `<?php echo base_url("unknown_products");?>/${id}`
        , dataType : "html"
        , cache : false
		, error : function (xhr, status, error) {
			if (xhr.status == 401) {
				window.location.href = '/login'; return;
			} else {
				alert("An error occured: " + xhr.status + " " + xhr.statusText);
			}}
        , success : function(response, status, request) {
			get_unknown_products();
        }
    });	
}

function search_unknown_product(e)
{
	const target = $(e.currentTarget);
	let prod_desc = target.data("description");
	prod_desc = removeNumberSymbols(prod_desc);
	prod_desc = encodeURIComponent(prod_desc);
	let url = `<?php echo base_url("");?>/products/index?&sort_key=6&category_id=0&offset=0&per_page=30&view_mode=grid&search0=${prod_desc}`;
	window.location.href = url;
}

function get_unknown_products()
{
    $.ajax({
        type : "GET"
        , async : true
        , url : "<?php echo base_url("unknown_products");?>"
        , dataType : "html"
        , cache : false
		, error : function (xhr, status, error) {
			if (xhr.status == 401) {
				window.location.href = '/login'; return;
			} else {
				alert("An error occured: " + xhr.status + " " + xhr.statusText);
			}}
        , success : function(response, status, request) {
			if(response) {
				$(".unknown-products").html(response);
				$(".unknown-products").removeClass("hidden");
				$(".unknown-products").addClass("yellow-bg");
			} else {
				$(".unknown-products").addClass("hidden");
				$(".send-order").removeClass("disabled");
				$(".check-out").removeClass("disabled");
			}
        }
    });	
}

function popup_dialog(user_id)
{
	$('#dialog_form').dialog('open');
}

function inc_quantity(mode , prod_id, prod_code, prod_desc)
{
	var post_data = "type=<?= $type ?>&prod_code=" + prod_code + "&mode=" + mode + "&quantity=1";

    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo base_url("$controller_name/to_cart_quantity");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : post_data
		, error : function (xhr, status, error) {
			if (xhr.status == 401) {
				window.location.href = '/login'; return;
			} else {
				alert("An error occured: " + xhr.status + " " + xhr.statusText);
			}}
        , success : function(response, status, request) {
			if(mode==1) { 
				M.toast({html: '<strong>Added&nbsp;</strong> "'+prod_desc+'"'}); 
			} else { 
				M.toast({html: '<strong>Removed&nbsp;</strong> "'+prod_desc+'"'}); 
			}
			window.location.reload();
        }
    });
}


function edit_quantity(prod_id, prod_code)
{
	var input_id = "#input_" + prod_id;
	var span_id = "#span_" + prod_id;
	var current_id = $('#current_id').val();
	var current_input = "#input_" + current_id;
	var current_span = "#span_" + current_id;
	if($(current_input).css('display') != 'none')
	{
		$(current_input).css('display' , 'none');
		$(current_span).css('display' , '');
	}

	$(span_id).css('display' , 'none');
	$(input_id).css('display' , 'block');
	$(input_id).val($(span_id).text());
	$('#current_id').val(prod_id);
	$(input_id).focus();
}


function change_quantity(prod_id, prod_code, e)
{
	var result , span_id;
	var input_id , current_qty , post_data;
	if(window.event) result = window.event.keyCode;
	else if(e) result = e.which;

	if(result == 13)
	{
		input_id = "#input_" + prod_id;
		span_id = "#span_" + prod_id;
		current_qty = $(input_id).val();
		if(isNaN(Number(current_qty)))
		{
			$(input_id).val('');
			return;
		}
		$('#current_id').val('0');
		$(span_id).text(Math.round(Number(current_qty)));
		$(span_id).css('display' , '');
		$(input_id).css('display' , 'none');

		post_data = "prod_code=<?=$type?>" + prod_code + "&mode=4" + "&quantity=" + Math.round(Number(current_qty));
	    $.ajax({
	        type : "POST"
	        , async : true
	        , url : "<?php echo base_url("$controller_name/to_cart_quantity");?>"
	        , dataType : "html"
	        , timeout : 30000
	        , cache : false
	        , data : post_data
			, error : function (xhr, status, error) {
				if (xhr.status == 401) {
					window.location.href = '/login'; return;
				} else {
					alert("An error occured: " + xhr.status + " " + xhr.statusText);
				}}
	        , success : function(response, status, request) {
				var strArray = response.split('********************');
				$('#table_holder').html(strArray[0]);
				$('#total_quantity').text(strArray[1]);
				$('#total_amount').text(strArray[2]);
				$('#total_quantity1').text(strArray[1]);
			    $('#total_amount1').text('£'+strArray[2]);
			    $('#total_epoints1').text(strArray[3]);
			    update_cart();
	        }
	    });
	}
}

function save_for_later()
{
	var post_data = "order_action=1";
    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo base_url("$controller_name/save_for_later/$type");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : post_data
		, error : function (xhr, status, error) {
			if (xhr.status == 401) {
				window.location.href = '/login'; return;
			} else {
				alert("An error occured: " + xhr.status + " " + xhr.statusText);
			}}
        , success : function(response, status, request) {
			if(response == true) alert("Order saved.");
			else if(response == 100) alert('Nothing in cart to save.');
			else alert("Save fail.");

			// location.replace("<//?php echo base_url("/pastorders");?>");
			location.reload();
        }
    });
}

function handleCheckout() {
	window.location.href = "/opayo/checkout";
}

function send_order()
{
	$('#spinner2').show();

	var post_data = "order_action=2";
    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo base_url("$controller_name/send_order/$type");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : post_data
		, error : function (xhr, status, error) {
			if (xhr.status == 401) {
				window.location.href = '/login'; return;
			} else {
				$('#spinner2').hide();
				alert("An error occured: " + xhr.status + " " + xhr.statusText);
			}}
        , success : function(response, status, request) {
				if(response == 100)        { alert("You must added products to cart.");	return;	}
				else if(response == -100)  { alert("Error 100.Unable to make trolley into order"); return; }
				else if(response == -101)  { alert("Error 101.Unable to create order header"); return; }
				else if(response == -102)  { alert("Error 102.Unable to create order file"); return; }
				else if(response == -2)    { alert("Error 2.Prepare new order"); return; }
				else if(response == -3)    { alert("Error 3.Prepare an existing order"); return; }
				else if(response == -4)    { alert("Error 4.Clear DB for trolley contents"); return; }
				else if(response == -5)    { alert("Error 5.Copying trolley to order");	return;	}
				else if(response == -6)    { alert("Error 6.Clearing trolley");	return;	}
				else if(response == -103)  { alert("Error 103.During write order file"); return; }
				else if(response == -104)  { alert("Error 6.Updating order with filename"); return;	}
				else if(response == -105)  { alert("Error 6.Close and complete order"); return;	}
				else if(response == -1)    { alert("Send fail."); return; }

				$('#spinner2').hide();
				alert(response);
				// window.location.replace("<//?php echo base_url("/pastorders");?>");
				location.reload();
        }
    });
}

function set_qty(link , prod_id, prod_code)
{
	$('#prod_id').val(prod_id);
	$('#prod_code').val(prod_code);
	var x = $(link).position();
    if($('#how_many_qty_info').css('visibility') == 'visible')
        $('#how_many_qty_info').css('visibility' , 'hidden');
    else
        DisplayPad(x.left , x.top);

}
function DisplayPad(gx , gy)
{
	var tp = 585;
	if($('.small-screen').css('display') == "none"){ tp = 515;}
    $('#how_many_qty_info').css('left' , gx - 430);
    $('#how_many_qty_info').css('top' , gy + tp);
    $('#how_many_qty_info').css('visibility' , 'visible');
    $('#how_many_qty').attr('value' , '');
    $('#how_many_qty').focus();
}

function go_quantity(e){
	var result;

	if(window.event){ result = window.event.keyCode; }
	else if(e){ result = e.which;}

	if(result == 13){ set_qty_trolley(); }
}

function set_qty_trolley()
{
	var qty = $('#how_many_qty').val();
	var prod_id = $('#prod_id').val();
	var prod_code = $('#prod_code').val();
	var type = $("#type").val();
	var num;
	var input_id , span_id;
	
	if(isNaN(Number(qty)))          { $('#how_many_qty').val(''); return; }
	if(Math.round(Number(qty)) < 1)	{ $('#how_many_qty').val(''); return; }

	num = Math.round(Number(qty));
	input_id = "#input_" + prod_id;
	span_id = "#span_" + prod_id;
	$(span_id).text(Math.round(Number(qty)));
	$(span_id).css('display' , '');
	$(input_id).css('display' , 'none');
//	if(Number(qty) != 0) $(span_id).parent().attr('class' , 'price_per_pack');
//	else $(span_id).parent().attr('class' , 'price_per_pack_empty');

	const post_data = `type=<?=$type?>&prod_code=${prod_code}&mode=4&quantity=${Math.round(Number(qty))}`;

    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo base_url("$controller_name/to_cart_quantity");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : post_data
		, error : function (xhr, status, error) {
			if (xhr.status == 401) {
				window.location.href = '/login'; return;
			} else {
				alert("An error occured: " + xhr.status + " " + xhr.statusText);
			}}
        , success : function(response, status, request) {
			var strArray = response.split('********************');
			$('#table_holder').html(strArray[0]);
			$('#total_quantity').text(strArray[1]);
			$('#total_amount').text(strArray[2]);
			$('#total_quantity1').text(strArray[1]);
			$('#total_amount1').text('£'+strArray[2]);
			$('#total_epoints1').text(strArray[3]);
        	$('#how_many_qty').val('');
        	$('#how_many_qty_info').css('visibility' , 'hidden');
        	$('#prod_id').val('');
			update_cart();
        }
    });
}
</script>

<?php
if(count($unknown_products) > 0) {
	echo "<div class='unknown-products'>";
	echo view("orders/partials/unknown_products", ['unknown_products' => $unknown_products]);
	echo "</div>";
} ?>


<div id="product_search_div" style="margin-bottom:-20px; width:100%; display:none;">
    <img src="<?php echo base_url();?>images/spinner_small.gif" alt="spinner" id="spinner1">
    <form action="<?php echo base_url();?>index.php/products/index/search" method="post" accept-charset="utf-8" id="search_form" style="font-family:Arial;" onsubmit="event.preventDefault();">			<div class="large_view">
        <input type="text" name="search0" value="" id="search0" class="product_search_cell" style="width:200px; background:#fff !important;" onclick=" this.select();" onkeyup="go_search(event);">
        </div>        
        <?php echo form_label('&nbsp;', 'product_code');?>
        <input type="hidden" name="sort_key" id="sort_key" value="3">
        <input type="hidden" name="search_mode" id="search_mode" value="default">
        <input type="hidden" name="per_page" id="per_page1" value="30"> 
        <input type="hidden" name="uri_segment" id="uri_segment" value="6">
        <input type="hidden" name="category" id="category" value="0">
        <input type="hidden" name="current_id" id="current_id" value="0">
        <input type="hidden" id="refresh" value="no">
		<input type="hidden" id="type" name="type" value="<?=$type?>">
        <input type="button" value="Search" style="background:#000; padding:7px; width:80px; height:35px; border:#000 2px solid; color:#fff !important; text-transform:uppercase;" onclick="search_query();">
    </form>
</div>
<div id="order_total_div" style="height:110px;">
	<div class="shopping__cart-page-header text-center">
        <div style="position:absolute; right:0;">
			<a href="<?php echo base_url("favorites");?>" onclick="" class="d-flex align-items-center">
				<i class="material-icons" style="color:lightpink">favorite</i> 
				<span class="ms-1" style="font-size:12px; vertical-align:top;">View Favorites List</span>
			</a>
		</div>
        <h2>&nbsp;</h2>
        <div class="tlinks">
			<a href="<?php echo base_url();?>orders/general" class="general">General<br /><span><?php echo $general_lines; ?> Lines <?php echo $general_items; ?> Items</span></a>
			<a href="<?php echo base_url();?>orders/tobacco" class="tobacco">Tobacco<br /><span><?php echo $tobacco_lines; ?> Lines <?php echo $tobacco_items; ?> Items</span></a>
			<a href="<?php echo base_url();?>orders/chilled" class="chilled">Chilled<br /><span><?php echo $chilled_lines; ?> Lines <?php echo $chilled_items; ?> Items</span></a>
			<?php if($spresell_lines) { ?>
			<a href="<?php echo base_url();?>orders/spresell" class="spresell">Seasonal Presell<br /><span><?= $spresell_lines; ?> Lines <?= $spresell_items; ?> Items</span></a>
			<?php } ?>
		</div>
		<p class="desc lead">Review of <span class="value" id="total_quantity"><?php echo $total_quantity;?></span> item(s) <span class="value"><span class="woocommerce-Price-amount amount">£</span><span id="total_amount"><?php echo $total_amount;?></span></p>
	</div>
</div>
<div id="title_bar">
	<div id="new_button" onclick="popup_dialog();" style="bottom:0px !important;">
		<div>
			<span><?php echo lang('Main.'.$controller_name.'_import'); ?></span>
		</div>
	</div>
</div>
<input type="hidden" name="current_id" id="current_id" value="0">
<div id="table_holder" class="table_holder" style="margin-top: 30px;">
	<?php echo $manage_table; ?>
</div>
<div id="order_action_div">
	<img src='<?php echo base_url()?>images/spinner_small.gif' alt='spinner' id='spinner2' />
    <div class="cart_total">
        <h3>Trolley Totals</h3>
        <h5>TOTAL ITEMS	 <span id="total_quantity1"><?php echo $total_quantity;?></span></h5>
        <h5>TOTAL EX-VAT <span id="total_amount1">£<?php echo $total_amount;?></span></h5>
        <!--<h5>E-POINTS SCORE <span id="total_epoints1"><?php echo $total_epoints;?></span></h5>-->
    </div>   
    <div class="action_btns">
		<div class="_btn _green check-out" onclick="handleCheckout();">
			<span>Check Out</span>
		</div>
        <div class="_btn _green send-order disabled" onclick="send_order();">
            <span><?php echo lang('Main.'.$controller_name.'_send_order');?></span>
        </div>
        <div class="_btn _red" onclick="save_for_later();">
            <span><?php echo lang('Main.'.$controller_name.'_save_for_later');?></span>
        </div>
        <div class="_btn" onclick="location.replace('<?php echo base_url("products");?>')">
            <span>Continue Shopping</span>
        </div>
    </div>
    <br style="clear:both;">
</div>

<div id="feedback_bar"></div>
	<fieldset id="how_many_qty_info" style='background-color:#FFFFFF; position: absolute; width:360px; height:40px;padding-top:10px; padding-right:10px; text-align:right; visibility:hidden; z-index:1000;'>
		<?php echo form_label(lang('Main.pastorders_how_many').':', 'how_many',array('class'=>'required')); ?>
		<?php echo form_input(array('name'=>'how_many_qty' , 'id'=>'how_many_qty' , 'class'=>'product_search_cell' , 'style'=>'height:18px;' , 'onkeyup'=>'go_quantity(event);'));?>
		&nbsp;&nbsp;<div class='tiny_button' style='float: right;' onmouseover="this.className='tiny_button_over'" onmouseout="this.className='tiny_button'" onclick="$('#how_many_qty_info').css('visibility' , 'hidden');"><span>Cancel</span></div>
	&nbsp;&nbsp;<div class='tiny_button' style='float: right; margin-right:20px;' onmouseover="this.className='tiny_button_over'" onmouseout="this.className='tiny_button'" onclick="set_qty_trolley();"><span>OK</span></div>
		<input type='hidden' name='prod_id' id='prod_id' value='0'>
	</fieldset>

<div id="dialog_form" title="Reload Products" style="font-family:Arial; font-size:12px;">
	<?php echo form_open_multipart(base_url('orders/do_excel_import/'),array('id'=>'product_form')); ?>

		<ul id="error_message_box"></ul>
		<fieldset id="product_basic_info">
			<legend>Import</legend>
			<div class="field_row clearfix">

				<div class='form_field'>
				<?php echo form_upload(array(
					'name'=>'file_path',
					'id'=>'file_path',
					'value'=>'')
				);?>
				</div>
			</div>
			<div class="field_row clearfix">
				<div class='form_field'>
						<?php echo form_checkbox(array('name'=>'empty_trolley' , 'id'=>'empty_trolley') , '1' , FALSE); ?>
						<span class="medium"><?php echo lang('Main.orders_empty_trolley');?></span>
				</div>
			</div>
		</fieldset>
	<?php echo form_close(); ?>
</div>

<?php echo view("partial/footer"); ?>