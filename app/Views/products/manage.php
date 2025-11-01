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

<?php if (request()->uri->getSegment(1) == 'products' && !empty($category_banners)) {
	echo view("partial/banners_carousel", ['banners' => $category_banners, 'carousel_id' => 'category_banners_carousel']);
} ?>


<div id="content_area_wrapper">
	<div id="content_area">
		<style type="text/css">
			.ui-dialog {
				font-family: Arial;
				font-size: 12px;
			}

			.ui-autocomplete {
				max-height: 300px;
				overflow-y: auto;
				overflow-x: hidden;
				font-size: 12px;
			}

			html .ui-autocomplete {
				height: 300px;
				font-size: 12px;
			}

			html {
				overflow: auto;
			}
		</style>

		<script type="text/javascript">
			$(document).ready(function () {
				$("#sort").val('<?= request()->getGet('sort_key') ?>').formSelect();
				$("#search0").on('keyup', function (e) {
					if (e.keyCode == 13) {
						$("#curd_page").val(1);
						load_products();
					}
				});

				// Extract Category
				var url = $(location).attr('href').split('/');
				var a_cat = '<?= request()->getGet('category_id') ?>';

				console.log("A_Cat: " + a_cat);
				// if( url.includes("search") ){ a_cat = ''; }

				if ($.isNumeric(a_cat)) {
					//select_category(this, a_cat);
					$('#loading').hide();
					init_pagination();

					$("#cat_main, #subcategories").css('display', 'none');
					$("#content_main").css('display', 'block');
					$("#dialog-data").css('display', 'block');
					$(".nav_category").css('visibility', 'visible')
					$("#categories i.sc").css('display', 'inline-block');
				}
				else {
					$("#a_subcat, #go_subcat, #categories i.sc, #content_main").css('display', 'none');
					$("#cat_main").css('display', 'flex');
					init_pagination();
					$('#loading').hide();
				}

				function onWindowResized() {
					if (window.visualViewport.width > 1201) {
						$("#product_side_filter").removeClass("sidenav");
					} else {
						$("#product_side_filter").addClass("sidenav");
					}
				};
				window.visualViewport.addEventListener('resize', function () {
					onWindowResized();
				});
				onWindowResized();

				$("#go_cat").on("click", function () {
					$("#cat_main").css('display', 'flex');
					$("#subcategories, #content_main, #go_subcat, #categories i.sc, #a_subcat").css('display', 'none');
				});
				$("#go_subcat").on("click", function () {
					var cn = $(this).attr('class').split(" ")[1];
					var opened = $(this).attr('class');
					if ($(this).hasClass("active") == false) {
						$("#" + opened).css('display', 'flex');
						$("#subcategories").css('display', 'block');
						$("#cat_main, #content_main, #a_subcat").css('display', 'none');
						$(this).removeClass('active').addClass('active');
					}
				});
				$("#cat_main .c_item").each(function (index) {
					var mc = $(this).attr('class').substring(7);
					if (mc == a_cat) {
						$("#go_subcat").html($(this).find(".category-name").html()).addClass('active');
					}
					$(this).on("click", function () {
						var cn = $(this).attr('id').substring(4);
						if (cn == 1) { $("#content_main").css('display', 'block'); }
						$("#subcategories").css('display', 'block');
						$("#subcategories .c_container").each(function (index) {
							$(this).css('display', 'none');
						});
						if ($('#subcat_' + cn).find('div.solo').length != 0 || cn == 1) {
							//$('#subcat_'+cn).css('display','none');
							//$("#content_main").css('display','block');
							sessionStorage.setItem('sub_cat_t_class', 'active subcat_' + cn);
							$(".nav_category").css('visibility', 'hidden');
						} else {
							$("#subcat_" + cn).css('display', 'flex');
							sessionStorage.setItem('sub_cat_t_class', 'subcat_' + cn);
							$("#cat_main").css('display', 'none');
						}

						var newclass = "active subcat_" + cn + " ";
						const category_name = $(this).find(".category-name").html();
						$("#go_subcat").html(category_name).removeClass().addClass(newclass);
						$("#subcat_title").val(category_name);
						sessionStorage.setItem('sub_cat_title', category_name);
						$("#go_subcat, #categories i.sc").css('display', 'inline-block');
						$("#a_subcat").css('display', 'none');
					});
				});
				$("#subcategories .c_container .c_item").each(function (index) {

					var sc = $(this).attr('class').split(' ')[1]
					var mc = $(this).attr('class').split(' ')[2]
					if (sc == a_cat) {
						$("#a_subcat").html($(this).find(".category-name").html()).addClass('active');
						var $main_cat = $("#cat_main ." + mc);
						var id = $main_cat.attr('id').substring(4);
						$("#go_subcat").html($main_cat.find(".category-name").html()).addClass('subcat_' + id);
					}

					$(this).on("click", function () {
						var category_name = $(this).find(".category-name").html();
						if (category_name) {
							$("#a_subcat, #categories i.sc").css('display', 'inline-block');
							$("#a_subcat").html(category_name).addClass('active');
							$("#subcat_active").val(category_name);
							$(".nav_category").css('visibility', 'hidden');
							init_pagination();
						}
					});
				});

				//set pagination
				function init_pagination() {
					var t = Number($('#total_page').val());
					var p = $('#curd_page').val();
					if (p == t) { $("#next").removeClass().addClass("disabled"); }
					if (p == 1) { $("#prev").removeClass().addClass("disabled"); }

					$("ul.pagination li.num").each(function (index) {
						var cn = $(this).attr('id').substring(1);
						if (cn == p) { $(this).removeClass().addClass("active"); }
					});
				}

				$("#go_btn").click(function () {
					$(this).data('clicked', true);
					$("#go_page").trigger("keyup");
				});
				$('#chk_im_new').click(function () {
					var im_new = ($('#im_new').val() == 1) ? 0 : 1;
					$('#im_new').val(im_new);
					$("#curd_page").val(1);
					load_products();
				});
				$('#chk_plainprofit').click(function () {
					var plan_profit = ($('#plan_profit').val() == 1) ? 0 : 1;
					$('#plan_profit').val(plan_profit);
					$("#curd_page").val(1);
					load_products();
				});
				$('#chk_own_label').click(function () {
					var own_label = ($('#own_label').val() == 'Y') ? 'N' : 'Y';
					$('#own_label').val(own_label);
					$("#curd_page").val(1);
					load_products();
				});
				$('#chk_favorite').click(function () {
					var favorite = ($('#favorite').val() == 1) ? 0 : 1;
					$("#curd_page").val(1);
					$('#favorite').val(favorite);
					load_products();
				});
				$("#chk_rrp").click(function () {
					var rrp = ($('#rrp').val() == 1) ? 0 : 1;
					$("#curd_page").val(1);
					$('#rrp').val(rrp);
					load_products();
				})
				$("#chk_pmp").click(function () {
					var pmp = ($('#pmp').val() == 1) ? 0 : 1;
					$("#curd_page").val(1);
					$('#pmp').val(pmp);
					load_products();
				})
				$("#chk_non_pmp").click(function () {
					var non_pmp = ($('#non_pmp').val() == 1) ? 0 : 1;
					$("#curd_page").val(1);
					$('#non_pmp').val(non_pmp);
					load_products();
				})
				$("input[name='filter_brand']").change(function (e) {
					$("#curd_page").val(1);
					load_products();
				})
				$("button#gridview").click(function () {
					$("#view_mode").val('grid');
					load_products();
				})
				$("button#listview").click(function () {
					$("#view_mode").val('list');
					load_products();
				})

				$("#dialog_form").dialog
					(
						{
							autoOpen: false,
							height: 250,
							width: 480,
							modal: true,
							buttons:
							{
								"Start": function () {
									$('#img_wait').css('visibility', 'visible');
									$(this).dialog({ buttons: {} });

									$.ajax({
										type: "POST"
										, async: true
										, url: "<?php echo base_url("$controller_name/reload_product/"); ?>"
										, dataType: "text"
										, timeout: 30000
										, cache: false
										, data: "person_id=0"
										, error: function (xhr, status, error) {
											if (xhr.status == 401) {
												window.location.href = '/login'; return;
											} else {
												console.log("An error occured: " + xhr.status + " " + xhr.statusText);
											}},
										, success: function (response, status, request) {
											alert(response);
										}
									});
									$(this).dialog('close');
									post_product_form_submit();

								}
							}

						}
					);

				//enable_search0('<?php echo base_url("$controller_name/suggest0") ?>','<?php echo base_url("$controller_name/suggest1") ?>','<?php echo base_url("$controller_name/suggest2") ?>','<?php echo lang("Main.common_confirm_search") ?>');

			});

			function popup_dialog(user_id) {
				$('#img_wait').css('visibility', 'hidden');
				$('#dialog_form').dialog('open');
			}

			function post_product_form_submit() {
				location.reload();
			}

			function select_per_page(url) {
				$('#curd_page').val(1);
				load_products();
			}

			function select_category(link, category_id) {
				var nCurrentSortKey = $('#sort_key').val();
				var search0 = $('#search0').val();
				var search1 = $('#search1').val();
				var search2 = $('#search2').val();
				var per_page = $('#per_page').val();

				$('#category').val(category_id);
				$.ajax({
					type: "POST"
					, async: true
					, url: "<?php echo base_url("$controller_name/select_category"); ?>"
					, dataType: "html"
					, timeout: 30000
					, cache: false
					, data: "sort_key=" + nCurrentSortKey + "&search0=" + search0 + "&search1=" + search1 + "&search2=" + search2 + "&search_mode=" + search_mode + "&category_id=" + category_id + "&per_page=" + per_page
					, error: function (xhr, status, error) {
                        if (xhr.status == 401) {
                            window.location.href = '/login'; return;
                        } else {
                            console.log("An error occured: " + xhr.status + " " + xhr.statusText);
                        }}
					, success: function (response, status, request) {
						$('#loading').hide();

						var strArray = response.split('********************');
						//$('#search_mode').val($.trim(strArray[1]));
						//$('#product_pagination_div').html(strArray[0]);
						//$('#product_pagination_div1').html(strArray[0]);
						$('#sortable_table tbody').html(strArray[1]);
					}
				});
			}

			function refresh_page(cn, category_id) {
				var cn = cn;
				if ($('#subcat_' + cn).find('div.solo').length != 0 || cn == 1) {
					$("#category").val(category_id);
					load_products();
				} else if (cn == 0) {
					$("#category").val(category_id);
					load_products();
				}
			}

			function getFilterBrands() {
				const filter_brands = [];
				const brChkBoxes = $("input[name='filter_brand']");
				for (let i = 0; i < brChkBoxes.length; i++) {
					if (brChkBoxes[i].checked) {
						filter_brands.push($(brChkBoxes[i]).data('brand'));
					}
				}
				return filter_brands;
			}

			function set_direct_page(e, url) {
				var result;

				if (window.event) { result = window.event.keyCode; }
				else if (e) { result = e.which; }

				if (result == 13 || $('#go_btn').data('clicked')) {
					var page_num = $('input.curd_page').val();

					var total_page = $('#last_page_number').text();
					if (isNaN(Number(page_num))) {
						alert("You must input the numeric.");
						$('#curd_page').val("");
						return;
					}

					if (Number(page_num) > Number(total_page)) {
						alert("Page number is too big.");
						$('#curd_page').val("");
						return;
					}

					if (Number(page_num) < 1) {
						alert("Page Number must be a integer.");
						$('#curd_page').val("");
						return;
					}
					if (Math.round(Number(page_num)) < 1) {
						$('#curd_page').val("");
						return;
					}

					$("#curd_page").val(page_num);
					load_products();
				}
			}

			function inc_quantity(mode, prod_id, prod_code, prod_desc) {
				let spresell = Number($("#spresell").val() ?? 0);
				cart_inc_quantity(mode, prod_id, prod_code, spresell, prod_desc);
			}

			function go_search(e) {
				var result;
				if (window.event) {
					result = window.event.keyCode;
				} else if (e) {
					result = e.which;
				}
				if (result == 13) {
					do_search0(true);
				}
			}

			function go_quantity(e) {
				var result;
				if (window.event) { result = window.event.keyCode; }
				else if (e) { result = e.which; }
				if (result == 13) { set_qty_trolley(); }
			}

			function edit_quantity(prod_id, prod_code) {
				cart_edit_quantity(prod_id, prod_code);
			}

			function change_quantity(prod_id, prod_code, e) {
				cart_change_quantity(prod_id, prod_code, e);
			}

			function set_qty(link, prod_id, prod_code) {
				$('#prod_id').val(prod_id);
				$('#prod_code').val(prod_code);
				var x = $(link).position();
				if ($('#how_many_qty_info').css('visibility') == 'visible')
					$('#how_many_qty_info').css('visibility', 'hidden');
				else
					DisplayPad(x.left, x.top);
			}

			function DisplayPad(gx, gy) {
				//$('#how_many_qty_info').css('left' , gx - 430);
				//$('#how_many_qty_info').css('top' , gy + 110);
				var tp = 345;
				if ($('.small-screen').css('display') == "none") { tp = 255; }
				$('#how_many_qty_info').css('left', gx - 430);
				$('#how_many_qty_info').css('top', gy + tp);
				$('#how_many_qty_info').css('visibility', 'visible');
				$('#how_many_qty').attr('value', '');
				$('#how_many_qty').focus();
			}

			function set_qty_trolley() {
				var qty = $('#how_many_qty').val();
				var prod_id = $('#prod_id').val();
				var prod_code = $('#prod_code').val();
				var num;
				var input_id, span_id;
				if (isNaN(Number(qty))) {
					$('#how_many_qty').val('');
					return;
				}

				if (Math.round(Number(qty)) < 1) {
					$('#how_many_qty').val('');
					return;
				}

				num = Math.round(Number(qty));
				input_id = "#input_" + prod_id;
				span_id = "#span_" + prod_id + ", #span__" + prod_id;
				$(span_id).text(Math.round(Number(qty)));
				$(span_id).css('display', '');
				$(input_id).css('display', 'none');
				if (Number(qty) != 0) $(span_id).parent().attr('class', 'price_per_pack');
				else $(span_id).parent().attr('class', 'price_per_pack_empty');

				$.ajax({
					type: "POST"
					, async: true
					, url: "<?php echo base_url("$controller_name/to_cart"); ?>"
					, dataType: "html"
					, timeout: 30000
					, cache: false
					, data: "prod_code=" + prod_code + "&mode=3" + "&quantity=" + Math.round(Number(qty))
					, error: function (xhr, status, error) {
                        if (xhr.status == 401) {
                            window.location.href = '/login'; return;
                        } else {
                            console.log("An error occured: " + xhr.status + " " + xhr.statusText);
                        }}
					, success: function (response, status, request) {
						$('#how_many_qty').val('');
						$('#how_many_qty_info').css('visibility', 'hidden');
						$('#prod_id').val('');
						update_cart();
					}
				});
			}

			function goto_page(page) {
				$('#curd_page').val(page);
				load_products();
			}

			function load_products() {
				var total_page = $('#last_page_number').text();
				var sort_key = $('#sort_key').val();
				var search0 = encodeURIComponent($('#search0').val().replace(/[\/()|'*]/g, ' '));
				var search1 = '';
				var search2 = '';
				var category_id = $('#category').val() ?? 0;
				var page_num = $('#curd_page').val() ?? 1;
				var per_page = $('#per_page').val() ?? 30;
				var im_new = Number($('#im_new').val() ?? 0);
				var plan_profit = Number($("#plan_profit").val() ?? 0);
				var own_label = $("#own_label").val() ?? 'N';
				var view_mode = $("#view_mode").val();
				var favorite = Number($("#favorite").val() ?? 0);
				var rrp = Number($("#rrp").val() ?? 0);
				var pmp = Number($("#pmp").val() ?? 0);
				var non_pmp = Number($("#non_pmp").val() ?? 0);
				var offset = (Math.round(Number(page_num)) - 1) * Number(per_page);
				let spresell = Number($("#spresell").val() ?? 0);

				category_id = Number(category_id);

				let location_site = "<?= $controller_name ?>/index?";
				location_site += "&sort_key=" + sort_key;
				location_site += "&category_id=" + category_id;
				location_site += "&offset=" + offset;
				location_site += "&per_page=" + per_page;
				location_site += "&view_mode=" + view_mode;
				location_site += "&mobile=" + (isMobile() ? 1 : 0);

				if (im_new) location_site += "&im_new=" + im_new;
				if (plan_profit) location_site += "&plan_profit=" + plan_profit;
				if (own_label == 'Y') location_site += "&own_label=" + own_label;
				if (favorite) location_site += "&favorite=" + favorite;
				if (search0) location_site += "&search0=" + search0;
				if (search1) location_site += "&search0=" + search1;
				if (search2) location_site += "&search0=" + search2;
				if (rrp) location_site += "&rrp=" + rrp;
				if (pmp) location_site += "&pmp=" + pmp;
				if (non_pmp) location_site += "&non_pmp=" + non_pmp;
				if (spresell) location_site += "&spresell=" + spresell;

				const filter_brands = getFilterBrands();
				if (filter_brands?.length > 0) {
					location_site += "&filter_brands=" + encodeURIComponent(JSON.stringify(filter_brands));
				}

				window.location.href = location_site;
			}

			function clearFilter() {
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

				const brChkBoxes = $("input[name='filter_brand']");
				for (let i = 0; i < brChkBoxes.length; i++) {
					brChkBoxes[i].checked = false;
				}
				$("#curd_page").val(1);

				load_products();
			}

			function onSideSort(sort_key) {
				$('#sort_key').val(sort_key);
				$("#curd_page").val(1);
				load_products();
			}
			function onSortDropbox(e) {
				$('#sort_key').val(e.target.value);
				$("#curd_page").val(1);
				load_products();
			}
		</script>


		<div id="product_search_div" style="margin:-10px 0px -20px 0px; display:none">
			<img src='<?php echo base_url() ?>images/spinner_small.gif' alt='spinner' id='spinner1' />
			<?php echo form_open("$controller_name/search", array('id' => 'search_form', 'style' => 'font-family:Arial;')); ?>
			<?php //echo form_label(lang('products_product_code').' '.' Search :', 'product_code');
			if (empty($search0))
				$search0 = '';
			?>
			<div class="large_view">
				<?php
				echo form_input(array('name' => 'search0', 'id' => 'search0', 'class' => 'search product_search_cell', 'style' => 'width:200px; background:#fff !important;', 'value' => urldecode($search0), 'onclick' => ' this.select();', 'onkeyup' => 'go_search(event);')); ?>
				<!--</div>
		<div class="small_view">
		<?php
		echo form_input(array('name' => 'search0', 'id' => 'search0', 'class' => 'product_search_cell', 'style' => 'width:200px; background:#fff !important;', 'value' => urldecode($search0), 'onkeyup' => 'go_search(event);')); ?>-->
			</div>
			<?php echo form_label('&nbsp;', 'product_code'); ?>
			<div style="display:none;">
				<?php echo form_label(lang('products_barcode') . ' ' . ':', 'barcode'); ?>
				<?php
				if (empty($search1))
					$search1 = '';
				echo form_input(array('name' => 'search1', 'id' => 'search1', 'size' => '5', 'class' => 'product_search_cell', 'style' => 'width:130px;', 'value' => $search1, 'onkeyup' => 'go_search(event);')); ?>
				<?php echo form_label('&nbsp;&nbsp;&nbsp;', 'product_code'); ?>
				<?php echo form_label(lang('products_description') . ' ' . ':', 'product_description'); ?>
				<?php
				if (empty($search2))
					$search2 = '';
				echo form_input(array('name' => 'search2', 'id' => 'search2', 'class' => 'product_search_cell', 'style' => 'width:230px;', 'value' => $search2, 'onkeyup' => 'go_search(event);')); ?>
			</div>
			<input type="hidden" name="im_new" id="im_new" value="<?= $im_new ?? 0 ?>">
			<input type="hidden" name="plan_profit" id="plan_profit" value="<?= $plan_profit ?? 0 ?>">
			<input type="hidden" name="own_label" id="own_label" value="<?= $own_label ?? 0 ?>">
			<input type="hidden" name="favorite" id="favorite" value="<?= $favorite ?? 0 ?>">
			<input type="hidden" name="rrp" id="rrp" value="<?= $rrp ?? 0 ?>">
			<input type="hidden" name="pmp" id="pmp" value="<?= $pmp ?? 0 ?>">
			<input type="hidden" name="non_pmp" id="non_pmp" value="<?= $non_pmp ?? 0 ?>">
			<input type="hidden" name="view_mode" id="view_mode" value="<?= $view_mode ?? 'grid' ?>">
			<input type="hidden" name="sort_key" id="sort_key" value="<?= $sort_key ?? 3 ?>">
			<input type="hidden" name="per_page" id="per_page1" value="<?= $per_page ?? 30 ?>">
			<input type="hidden" name="offset" id="offset" value="<?= $offset ?? 0 ?>">
			<input type="hidden" name="category" id="category" value="<?= $category_id ?? 0 ?>">
			<input type="hidden" name="current_id" id="current_id" value="0">
			<input type="hidden" name="spresell" id="spresell" value="<?= $spresell ?? 0 ?>">
			<input type="hidden" id="refresh" value="no">
			</form>
		</div>

		<div id="order_total_div" class="text-left ps-2 mt-4">
			<div class="shopping__cart-page-header"><?php echo lang('Main.products'); ?></div>
		</div>

		<div id="categories" class="ps-2">
			<div class="nav_category">
				<span id="go_cat">Products</span> <i class="material-icons">navigate_next</i> <span
					id="go_subcat">&nbsp;</span> <i class="material-icons sc"
					style="display:none;">navigate_next</i><span id="a_subcat">&nbsp;</span>
			</div>
			<?php echo $categories; ?>
		</div>

		<div id="content_categories_area1" style="display:none;">
			<div style="margin-top:25px;">&nbsp;</div>
			<div id="categories_border_round">
				<div class="infoBoxHeading_td">Categories</div>
			</div>
		</div>
		<div class="progress" id="loading"
			style="display:block; width:50%; left:25%; margin-top:120px; position:absolute;">
			<div class="indeterminate"></div>
		</div>

		<?php if (request()->uri->getSegment(2) == 'index') { ?>
			<div class="d-flex products-section" style="margin-top: 20px;">
				<div class="filter-section p-4 side-filter sidenav" id="product_side_filter">
					<div class="d-flex align-items-center" style="font-size:14px; margin-bottom: 10px;">
						<div class="flex-fluid">Filter by :</div>
						<div><a class="waves-effect waves-teal btn-flat text-underline" onclick="clearFilter()">Clear</a>
						</div>
					</div>
					<div class="mb-4">
						<label>
							<input type="checkbox" class="" id="chk_im_new" <?= $im_new ? 'checked' : '' ?> />
							<span for='chk_im_new'>New Product</span>
						</label>
					</div>
					<div class="mb-4">
						<label>
							<input type="checkbox" class="" id="chk_plainprofit" <?= $plan_profit ? 'checked' : '' ?> />
							<span for='chk_plainprofit'>Plan Profit</span>
						</label>
					</div>
					<div class="mb-4">
						<label>
							<input type="checkbox" class="" id="chk_own_label" <?= $own_label == 'Y' ? 'checked' : '' ?> />
							<span for='chk_own_label'>Own Label</span>
						</label>
					</div>
					<div class="mb-4">
						<label>
							<input type="checkbox" class="" id="chk_favorite" <?= $favorite ? 'checked' : '' ?> />
							<span for='chk_favorite'>Favorite</span>
						</label>
					</div>
					<div class="mb-4">
						<label>
							<input type="checkbox" class="" id="chk_rrp" <?= $rrp ? 'checked' : '' ?> />
							<span for='chk_rrp'>£1.00 rrp</span>
						</label>
					</div>
					<div class="mb-4">
						<label>
							<input type="checkbox" class="" id="chk_pmp" <?= $pmp ? 'checked' : '' ?> />
							<span for='chk_pmp'>PMP</span>
						</label>
					</div>
					<div class="mb-4">
						<label>
							<input type="checkbox" class="" id="chk_non_pmp" <?= $non_pmp ? 'checked' : '' ?> />
							<span for='chk_non_pmp'>No PMP</span>
						</label>
					</div>
					<div class="mb-4">
						<div class="mb-2">Brand: </div>
						<?php
						foreach ($brands as $brand) {
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
								<input type="checkbox" name="filter_brand" data-brand="" <?= (in_array('', $filter_brands) ? 'checked' : '') ?> />
								<span>Others</span>
							</label></div>
					</div>
				</div>

				<div class="flex-fluid d-flex flex-column ms-md-4">
					<?php if (!empty($sponsor)) { ?>
						<div class="sponsor-products px-4" style="background:<?= $sponsor['ribbon']['bg_color'] ?>;">
							<div class="sponsor-ribbon text-center" style="color:<?= $sponsor['ribbon']['txt_color'] ?>;">
								<?= $sponsor['ribbon']['content'] ?>
							</div>
							<?= $sponsor['manage_table'] ?>
						</div>
					<?php } ?>

					<div class="products-table px-4">
						<div class="products-table-header d-none flex-on-extra-large justify-content-between align-items-center px-4">
							<div class="d-flex align-items-center" style="margin-right: 50px;">
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
										<option value='30' <?php if ($per_page == 30)
											echo "selected='true'"; ?>>30</option>
										<option value='40' <?php if ($per_page == 40)
											echo "selected='true'"; ?>>40</option>
										<option value='50' <?php if ($per_page == 50)
											echo "selected='true'"; ?>>50</option>
										<option value='75' <?php if ($per_page == 75)
											echo "selected='true'"; ?>>75</option>
										<option value='100' <?php if ($per_page == 100)
											echo "selected='true'"; ?>>100
										</option>
										<option value='150' <?php if ($per_page == 150)
											echo "selected='true'"; ?>>150
										</option>
										<option value='200' <?php if ($per_page == 200)
											echo "selected='true'"; ?>>200
										</option>
									</select>
								</div>
							</div>
							<div class="d-flex flex-fluid justify-content-end align-items-center">
								<label class="me-2">Sort By</label>
								<div style="width: 130px;">
									<select name='sort' id='sort' onchange="onSortDropbox(event);"
										style="background:#000; color:#fff;">
										<option value="" disabled selected>Choose</option>
										<?php
										foreach ($sort_options as $sort) {
											?>
											<option value='<?= $sort['value'] ?>' data-icon="<?= $sort['icon'] ?>"
												class="circle">
												<?= $sort['label'] ?>
											</option>
											<?php
										}
										?>
									</select>
								</div>
							</div>
							<div class="products-view-style d-flex align-items-center" style="margin-left: 50px;">
								<label class="me-4">View</label>
								<button id="gridview" type='button' class="me-2 cursor-pointer" style="border: 0px;">
									<img src='images/icons/grid-view.svg' style="width: 20px; height: 20px;" />
								</button>
								<button id="listview" type='button' class="cursor-pointer" style='border: 0px;'>
									<img src='images/icons/list-view.svg' style="width: 20px; height: 20px;" />
								</button>
							</div>
						</div>

						<div class="products-table-header d-block hide-on-extra-large-only mb-4 px-4">
							<div class="d-flex align-items-center mb-2" style="margin-right: 50px;">
								<label>Showing</label>
								<span class="mx-2"><?= $from ?> - <?= $to ?></span>
								<label>of</label>
								<span class="mx-2"><?= $total_rows ?></span>
								<label>products</label>
							</div>
							<div class="d-flex justify-content-between">
								<div class="d-flex align-items-center sidenav-trigger" data-target="product_side_filter">
									<img src="/images/icons/filter.svg" style="width: 25px; height: 25px;" />
									<label class="ms-2">Filter</label>
								</div>

								<div class="d-flex align-items-center sidenav-trigger" data-target="rside-sort">
									<label class="me-2">Sort By</label>
									<img src="/images/icons/sort.svg" style="width: 20px; height: 20px;" />
								</div>
								<div class="sidenav right-aligned p-4" id="rside-sort">
									<div class="py-4 mb-4" style="font-size: 110%; border-bottom: 1px solid #ddd;">Sort by:
									</div>
									<?php
									foreach ($sort_options as $sort) {
										?>
										<div class="d-flex align-items-center mt-4 sort-option <?= $sort_key == $sort['value'] ? 'active' : '' ?>"
											onclick="onSideSort(<?= $sort['value'] ?>)">
											<div class="flex-fluid d-flex">
												<label class="me-4"><?= $sort['label'] ?></label>
												<img src="<?= $sort['icon'] ?>" style="width: 20px; height: 20px" />
											</div>
											<div>
												<?php
												if ($sort_key == $sort['value']) {
													?>
													<svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
														xmlns="http://www.w3.org/2000/svg">
														<path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="red" stroke-width="2"
															stroke-linecap="round" stroke-linejoin="round" />
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
										<img src='images/icons/grid-view.svg' style="width: 20px; height: 20px;" />
									</button>
									<button id="listview" type='button' class="cursor-pointer" style='border: 0px;'>
										<img src='images/icons/list-view.svg' style="width: 20px; height: 20px;" />
									</button>
								</div>
							</div>
						</div>

						<div id="content_main" style="display:none;">
							<div id="table_holder" class="table_holder">
								<?php echo $manage_table; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>

		<script src="<?php echo base_url(); ?>js/manage_tables.js?v=<?= env('app.asset.version') ?>"
			type="text/javascript" language="javascript" charset="UTF-8"></script>

		<?php if (request()->uri->getSegment(2) == 'index') { ?>
			<div id="actions">
				<ul class="pagination">
					<li class="go_page">
						<input type="text" id="go_page" name="page" class="curd_page" value="<?php echo $curd_page; ?>"
							size="4" onkeyup="set_direct_page(event)" onclick="this.select();"
							style="height:2rem; background:#fbfbfb !important;"><i class="material-icons go"
							id="go_btn">slideshow</i>
					</li>
					<li class="waves-effect" id="prev"><a href="javascript:void();" onclick="pPrev('/products/index');"><i
								class="material-icons">chevron_left</i></a></li>
					<?php
					$t = $total_page;
					for ($i = 1; $i <= $t; $i++) {
						$h = intval($t / 2);
						if ($curd_page > 3) {
							$h = $curd_page;
						}
						if ($i < 4 || ($i <= $h + 1 && $i >= $h - 1) || ($i == 4 && $t == 6)) { ?>
							<li class="waves-effect num" id="p<?php echo $i; ?>">
								<a href="javascript:void()" onclick="goto_page(<?php echo $i; ?>)">
									<?php echo $i; ?>
								</a>
							</li>
						<?php } else if ($i == $t) { ?>
								<li class="waves-effect num" id="p<?php echo $i; ?>">
									<a href="javascript:void()" onclick="goto_page(<?php echo $i; ?>)"><?php echo $i; ?></a>
								</li>
						<?php } else if ($i == 5 || $i == $t - 1) { ?>
									<li class="disabled"><a><i class="material-icons">more_horiz</i></a></li>
						<?php }
					} ?>
					<li class="waves-effect" id="next"><a href="javascript:void();" onclick="pNext('/products/index');"><i
								class="material-icons">chevron_right</i></a></li>
				</ul>
				<br style="clear:both;">
			</div>
		<?php } ?>
	</div>
</div>
</div>

<div id="dialog-data" style="display:none">
	<div id="feedback_bar" style="display:none;"></div>
	<fieldset id="how_many_qty_info"
		style='background-color:#FFFFFF; position: absolute; width:400px; height:40px;padding-top:10px; padding-right:10px; text-align:right; visibility:hidden; z-index:1000;'>
		<?php echo form_label(lang('Main.pastorders_how_many') . ':', 'how_many', array('class' => 'required')); ?>
		<?php echo form_input(array('name' => 'how_many_qty', 'id' => 'how_many_qty', 'class' => 'product_search_cell', 'style' => 'width:120px; height:18px;', 'onkeyup' => 'go_quantity(event);')); ?>
		&nbsp;&nbsp;&nbsp;<div class='tiny_button' style='float: right;' onmouseover="this.className='tiny_button_over'"
			onmouseout="this.className='tiny_button'" onclick="$('#how_many_qty_info').css('visibility' , 'hidden');">
			<span>Cancel</span>
		</div>
		&nbsp;&nbsp;&nbsp;<div class='tiny_button' style='float: right; margin-right:20px;'
			onmouseover="this.className='tiny_button_over'" onmouseout="this.className='tiny_button'"
			onclick="set_qty_trolley();"><span>OK</span></div>
		<input type='hidden' name='prod_id' id='prod_id' value='0'>
	</fieldset>
	<div id="dialog_form" title="Reload Products" style="font-family:Arial; font-size:12px;">
		<?php echo form_open('#', array('id' => 'reload_form')); ?>
		<fieldset id="ftp_location_info">
			<div class="field_row clearfix">
				<div id="please_wait">
					<img src="<?php echo base_url("images/spinner_load.gif"); ?>"
						style="width:100px; height:100px; visibility:hidden;" id="img_wait">
				</div>
			</div>
		</fieldset>
		<?php echo form_close(); ?>
	</div>
	<input type='hidden' name='prod_id' id='prod_id' value='0'>
	<input type="hidden" name="curd_page" id="curd_page" value="<?php echo $curd_page; ?>" size="4">
	<input type="hidden" name="page" id="total_page" value="<?php echo $total_page; ?>" size="4">
	<input type="hidden" name="subcat_title" id="subcat_title" value="">
	<input type="hidden" name="subcat_active" id="subcat_active" value="">
	<span id="last_page_number" style="display:none;"><?php echo $total_page; ?></span>
</div>
<?php echo view("partial/footer"); ?>