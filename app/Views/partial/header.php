<?php $router = service('router'); ?>

<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="<?= !empty($keywords) ? esc($keywords) : '' ?>">
	<meta name="description" content="<?= !empty($description) ? esc($description) : '' ?>">
	<title><?= !empty($title) ? esc($title) : esc(lang('Main.main_title')); ?></title>

	<base href="<?php echo base_url(); ?>" />
	<link rel="stylesheet" rev="stylesheet"
		href="<?php echo base_url(); ?>css/epos.css?v=<?= env('app.asset.version') ?>" />
	<link rel="stylesheet" rev="stylesheet"
		href="<?php echo base_url(); ?>css/app.css?v=<?= env('app.asset.version') ?>" />
	<link rel="stylesheet" rev="stylesheet"
		href="<?php echo base_url(); ?>css/style.css?v=<?= env('app.asset.version') ?>" />
	<link rel="stylesheet" rev="stylesheet"
		href="<?php echo base_url(); ?>css/epos_print.css?v=<?= env('app.asset.version') ?>" media="print" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins" type="text/css">
	<link rel="preload" as="style" onload="this.rel = 'stylesheet'"
		href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<!--Let browser know website is optimized for mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery-ui.css" />
	<script src="<?php echo base_url(); ?>js/jquery-1.10.2.min.js"></script>
	<script src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
	<script src="<?php echo base_url(); ?>js/app.js?v=<?= env('app.asset.version') ?>"></script>
	<script src="<?php echo base_url(); ?>js/order_carousel.js?v=<?= env('app.asset.version') ?>"></script>

	<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>css/ghpages-materialize.css"
		media="screen,projection" />

	<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/weather-icons/2.0.9/css/weather-icons.min.css"
		rel="stylesheet">
	<!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">-->

	<script src="<?php echo base_url(); ?>js/manage_tables.js?v=<?= env('app.asset.version') ?>" type="text/javascript"
		language="javascript" charset="UTF-8"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/yahoo-weather-jquery-plugin.js"></script>

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-131104203-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag() { dataLayer.push(arguments); }
		gtag('js', new Date());
		gtag('config', 'UA-131104203-1');    
	</script>

	<script type="text/javascript">
		<?php if (request()->uri->getSegment(1) == 'cpanel') {
			$root = 'cpanel';
			$logout = 'home/logout';
			$login = '/login';
			?>
			var controller = 'cpanel';
		<?php } else {
			$root = 'home';
			$logout = 'home/logout';
			$login = '/login'; ?>
			var controller = 'home';
		<?php } ?>

		var base_url = '<?php echo base_url(); ?>';

		<?php if (!empty($user_info)) { ?>
			update_cart();
		<?php } ?>

		<?php if (request()->uri->getSegment(1) == 'products') { ?>
			$(document).ready(function () {
				window.history.pushState(null, "", window.location.href);
				window.onpopstate = function () {
					window.history.pushState(null, "", window.location.href);
					window.location.href = base_url + '/products';
				};
			});
		<?php } ?>

		function combined_total() {
      debugger
			var total = parseFloat($("#general").val()) + parseFloat($("#tobacco").val()) + parseFloat($("#chilled").val());
			$("#combined .cart-amount").text(parseFloat(total).toFixed(2));
			console.log("Total: " + total);
		}

		function search_query_header() {
			if ($('#product_search_div .large_view').css('display') == 'none') {
				$('#product_search_div .large_view #search0').attr("id", "search00");
				$('#product_search_div .small_view #search0').attr("id", "search0");
			} else {
				$('#product_search_div .large_view #search0').attr("id", "search0");
				$('#product_search_div .small_view #search0').attr("id", "search00");
			}
			var location_site = "<?php echo base_url("products/index"); ?>";
			var nCurrentSortKey = 6;
			var category_id = $('#category0').val();
			var per_page = 30;
			const search0 = $('#search0').val();

			location_site += "?";
			location_site += "&sort_key=" + nCurrentSortKey;
			location_site += "&category_id=" + category_id;
			location_site += "&offset=0";
			location_site += "&per_page=" + per_page;
			/* location_site += "&im_new=" + ($('#im_new').val() ?? 0); */
			/* location_site += "&plan_profit=" + ($('#plan_profit').val() ?? 0); */
			/* location_site += "&own_label=" + ($("#own_label").val() ?? 0); */
			location_site += "&view_mode=" + ($("#view_mode").val() ?? 'grid');
			location_site += '&search0=' + encodeURIComponent(search0.replace(/[\/()|'*]/g, ' '));

			location.replace(location_site);
		}

		function gotoProductDetail(prod_id) {
			let location_site = `<?= base_url("/products") ?>/${prod_id}/show`;
			window.location.href = location_site;
		}

		function favorite(pid, prod_id, prod_code) {
			var prod_id_ = "#f_" + prod_id;
			var state = '';
			if ($(prod_id_).hasClass("active")) { state = "active"; }
			var post_data = "pid=" + pid + "&prod_code=" + prod_code + "&state=" + state;
			$.ajax({
				type: 'POST'
				, async: true
				, url: base_url + 'products/favorite'
				, dataType: "html"
				, timeout: 30000
				, cache: false
				, data: post_data
				, error: function (xhr, status, error) {
					if (xhr.status == 401) {
						window.location.href = '/login'; return;
					} else {
						alert("An error occured: " + xhr.status + " " + xhr.statusText);
					}}
				, success: function (data) {
					if (state == "active") { $(prod_id_).removeClass("active"); }
					else { $(prod_id_).addClass("active"); }
					<?php if (request()->getUri()->getSegment(1) == 'favorites') { ?>
						resync_favorites();
					<?php } ?>
					console.log(data + " favorite function " + prod_id);
				}
			});
		}


		<?php if (request()->uri->getSegment(1) == 'home' || request()->uri->getSegment(1) == 'promos' || request()->uri->getSegment(1) == 'pastorders' || request()->uri->getSegment(1) == 'contactus' || request()->uri->getSegment(1) == 'presells' || request()->uri->getSegment(1) == 'orders') { ?>
			function go_search(e) {
				e.preventDefault();
				var result = e.keyCode || e.which;
				if (result == 13) { search_query_header(); }
			}

			function enable_search(suggest_url, confirm_search_message) {
				//Keep track of enable_email has been called
				if (!enable_search.enabled)
					enable_search.enabled = true;

				$('#search').click(function () {
					$(this).attr('value', '');
				});

				$("#search").autocomplete(suggest_url, { max: 100, delay: 10, selectFirst: false });
				$("#search").result(function (event, data, formatted) {
					do_search(true);
				});

				$('#search_form').submit(function (event) {
					event.preventDefault();

					if (get_selected_values().length > 0) {
						if (!confirm(confirm_search_message)) return;
					}
					do_search(true);
				});
			}

			$(document).ready(function () {	
								<?php if (request()->getUri()->getSegment(2) == 'general') { ?> $("#general").addClass("active"); <?php } ?> 
								<?php if (request()->getUri()->getSegment(2) == 'tobacco') { ?> $("#tobacco").addClass("active"); <?php } ?> 
								<?php if (request()->getUri()->getSegment(2) == 'chilled') { ?> $("#chilled").addClass("active"); <?php } ?>
			});
		<?php } ?>

		<?php if (request()->uri->getSegment(1) == 'home' || request()->uri->getSegment(1) == 'cpanel' || request()->uri->getSegment(1) == 'promos') { ?>
			<?php if (request()->uri->getSegment(1) == 'promos') { ?>

			<?php } else { ?>

				function load_products(type) {
					$.ajax({
						'url': base_url + '/' + controller + '/refresh_products',
						'type': 'POST', //the way you want to send data to your URL
						'data': { 'type': type },
						'error' : function (xhr, status, error) {
							if (xhr.status == 401) {
								window.location.href = '/login'; return;
							} else {
								alert("An error occured: " + xhr.status + " " + xhr.statusText);
							}},
						'success': function (data) {
							var container = $('#featured');
							//jquery selector (get element by id)
							if (data.indexOf("<!DOCTYPE") == -1) { container.html(data); }
							else { location.reload(); }
						}
					});
				}
			<?php } ?>

			<?php if (request()->uri->getSegment(1) == 'cpanel') { ?>
				load_all_products();
				function load_all_products() {
					$.ajax({
						'url': base_url + controller + '/refresh_all_products',
						'type': 'POST', //the way you want to send data to your URL
						'data': { 'type': 0 },
						'error' : function (xhr, status, error) {
							if (xhr.status == 401) {
								window.location.href = '/login'; return;
							} else {
								alert("An error occured: " + xhr.status + " " + xhr.statusText);
							}},
						'success': function (data) { //probably this request will return anything, it'll be put in var "data"
							var container = $('#featured'); //jquery selector (get element by id)
							if (data) { container.html(data); }
						}
					});
				}
				$("#l_2").trigger('click');
				$("#l_2").click(function () {
					alert("I'm clicked!");
				});

				$('#featuredform').submit(function (e) { e.preventDefault() });

				function update_featured() {
					var c = 0;
					$('input[type=text]').each(function () {
						if ($(this).val() && $(this).val().length > 6) { c = c + 1; }
					});

					if (c >= 16) {
						$.ajax({
							'url': base_url + 'cpanel/update_featured',
							'type': 'POST', //the way you want to send data to your URL
							'data': $('#featuredform').serialize(),
							'error': function (xhr, status, error) {
								if (xhr.status == 401) {
									window.location.href = '/login'; return;
								} else {
									$('#msg').html(response);
									alert("An error occured: " + xhr.status + " " + xhr.statusText);
								}},
							'success': function (data) {
								if (data) { location.reload(); }
							}
						});
					}
					else { alert(" Please enter correct product code for all products "); }
				}

				function do_image_uploader(f, t) {


					var form_data = new FormData();
					if (t == 1) {
						var file_data = $('#slider' + f).prop('files')[0];
						form_data.append('slider' + f, file_data);
					}
					else if (t == 2) {
						var file_data = $('#sponsor' + f).prop('files')[0];
						form_data.append('sponsor' + f, file_data);
					} else if (t == 3) {
						var file_data = $('#promotion' + f).prop('files')[0];
						form_data.append('promotion' + f, file_data);
					} else if (t == 4) {
						var file_data = $('#cat' + f).prop('files')[0];
						form_data.append('cat' + f, file_data);
					}
					form_data.append('ft', t);

					if (t == 3) {
						var file_data = $('#promotion' + f + '_1').prop('files')[0];
						form_data.append('promotion' + f + '_1', file_data);
						$.ajax({
							url: base_url + controller + 'do_uploader/' + f + '_1',
							dataType: 'text',
							cache: false,
							contentType: false,
							processData: false,
							data: form_data,
							type: 'post',
							error: function (xhr, status, error) {
								if (xhr.status == 401) {
									window.location.href = '/login'; return;
								} else {
									$('#msg').html(response);
									alert("An error occured: " + xhr.status + " " + xhr.statusText);
								}},
							success: function (response) { $('#msg').html(response); },
						});
					}
					$.ajax({
						url: base_url + 'cpanel/do_uploader/' + f,
						dataType: 'text',
						cache: false,
						contentType: false,
						processData: false,
						data: form_data,
						type: 'post',
						error: function (xhr, status, error) {
							if (xhr.status == 401) {
								window.location.href = '/login'; return;
							} else {
								$('#msg').html(response);
								alert("An error occured: " + xhr.status + " " + xhr.statusText);
							}},
						success: function (data) { $('#msg').html(data); alert("response" + $('#msg').html(data)); location.reload(); },
					});

				}
				$(document).ready(function () {
					$("#slides_count, #sponsors_count").on('change', function () {
						var s1 = $("#slides_count").val();
						var s2 = $("#sponsors_count").val();
						$.ajax({
							'url': base_url + 'cpanel/push_scount',
							'type': 'POST',
							'data': { 's1': s1, 's2': s2 },
							'error': function (xhr, status, error) {
								if (xhr.status == 401) {
									window.location.href = '/login'; return;
								} else {
									alert("An error occured: " + xhr.status + " " + xhr.statusText);
								}},
							'success': function (data) {
								if (data == true) {
									location.reload();
								}

							}

						});
					});
					$("#switch, #switch2, #link1, #link1_1, #period1, #period1_1, #p1_date, #link2, #link2_1, #period2, #period2_1, #p2_date, #link3, #link3_1, #period3, #period3_1, #p3_date, #link3a, #link3a_1, #period3a, #period3a_1, #p3a_date, #link4, #link4_1, #period4, #period4_1, #p4_date, #link4a, #link4a_1, #period4a, #period4a_1, #p4a_date, #link5, #link5_1, #period5, #period5_1, #p5_date, #s1_period, #s2_period, #s3_period, #s4_period, #s5_period, #s6_period, #s7_period, #s8_period, #s9_period, #s10_period, #s1_name, #s2_name, #s3_name, #s4_name, #s5_name, #s6_name, #s7_name, #s8_name, #s9_name, #s10_name, #s1_ids, #s2_ids, #s3_ids, #s4_ids, #s5_ids, #s6_ids, #s7_ids, #s8_ids, #s9_ids, #s10_ids, #sp1_period, #sp2_period, #sp3_period, #sp4_period, #sp5_period, #sp6_period, #sp1_name, #sp2_name, #sp3_name, #sp4_name, #sp5_name, #sp6_name, #sp1_ids, #sp2_ids, #sp3_ids, #sp4_ids, #sp5_ids, #sp6_ids, #s1_date, #s2_date, #s3_date, #s4_date, #s5_date, #s6_date, #s7_date, #s8_date, #s9_date, #s10_date, #c34_ids, #c13_ids, #c30_ids, #c1_ids, #c20_ids, #c31_ids, #c5_ids, #c3_ids, #c2_ids, #c24_ids, #c33_ids, #c15_ids, #c7_ids, #c8_ids, #c22_ids, #c23_ids, #c27_ids, #c26_ids, #c21_ids, #c6_ids, #c10_ids, #c28_ids, #c4_ids, #c35_ids, #c14_ids, #c32_ids").on('change', function () {
						var v = 0;
						var l = $(this).attr('id');
						if (l == 'switch') {
							l = 'state special event';
							if ($('#switch').prop('checked') == true) { v = 1; }
						}
						else if (l == 'switch2') {
							l = 'state newsletter';
							if ($('#switch2').prop('checked') == true) { v = 1; }
						}
						else {
							v = $('#' + l).val();
							if (l == 'link1') { l = 'link newsletter'; }
							if (l == 'link1_1') { l = 'link newsletter2'; }
							if (l == 'period1') { l = 'link newsletter period'; }
							if (l == 'period1_1') { l = 'link newsletter2 period'; }
							if (l == 'p1_date') { l = 'link newsletter date'; }
							if (l == 'link2') { l = 'link cash & carry'; }
							if (l == 'link2_1') { l = 'link cash & carry2'; }
							if (l == 'period2') { l = 'link cash & carry period'; }
							if (l == 'period2_1') { l = 'link cash & carry2 period'; }
							if (l == 'p2_date') { l = 'link cash & carry date'; }
							if (l == 'link3') { l = 'link day-today'; }
							if (l == 'link3_1') { l = 'link day-today2'; }
							if (l == 'period3') { l = 'link day-today period'; }
							if (l == 'period3_1') { l = 'link day-today2 period'; }
							if (l == 'p3_date') { l = 'link day-today date'; }
							if (l == 'link3a') { l = 'link day-today upcoming'; }
							if (l == 'link3a_1') { l = 'link day-today upcoming2'; }
							if (l == 'period3a') { l = 'link day-today upcoming period'; }
							if (l == 'period3a_1') { l = 'link day-today upcoming2 period'; }
							if (l == 'p3a_date') { l = 'link day-today upcoming date'; }
							if (l == 'link4') { l = 'link usave'; }
							if (l == 'link4_1') { l = 'link usave2'; }
							if (l == 'period4') { l = 'link usave period'; }
							if (l == 'period4_1') { l = 'link usave2 period'; }
							if (l == 'p4_date') { l = 'link usave date'; }
							if (l == 'link4a') { l = 'link usave upcoming'; }
							if (l == 'link4a_1') { l = 'link usave upcoming2'; }
							if (l == 'period4a') { l = 'link usave upcoming period'; }
							if (l == 'period4a_1') { l = 'link usave upcoming2 period'; }
							if (l == 'p4a_date') { l = 'link usave upcoming date'; }
							if (l == 'link5') { l = 'link special event'; }
							if (l == 'link5_1') { l = 'link special event2'; }
							if (l == 'period5') { l = 'link special event period'; }
							if (l == 'period5_1') { l = 'link special event2 period'; }
							if (l == 'p5_date') { l = 'link special event date'; }
						}

						$.ajax({
							'url': base_url + '/' + controller + '/push_plink/',
							'type': 'POST',
							'data': { 'l': l, 'v': v },
							'error': function (xhr, status, error) {
								if (xhr.status == 401) {
									window.location.href = '/login'; return;
								} else {
									alert("An error occured: " + xhr.status + " " + xhr.statusText);
								}},
							'success': function (data) {
								if (data == true) { console.log("[-] " + l + " - " + v); /*location.reload();*/ }
							}
						});
					});
				});
			<?php } ?>

			$("document").ready(function () {
				<?php if (request()->uri->getSegment(1) == 'promos') { ?>
				<?php } else { ?>
					setTimeout(function () {
						<?php if (request()->uri->getSegment(1) == 'home') { ?>
							$("#f_links ul li:nth-child(1) a").trigger('click');
						<?php } else if (request()->uri->getSegment(1) == 'cpanel') { ?>
								$("#f_links ul li:nth-child(1) a").trigger('click');
						<?php } ?>
					}, 10);
				<?php } ?>
				$("#f_links ul li a").click(function () {
					$("#f_links ul li a").removeClass("active");
					$(this).addClass("active");
				});
			});
		<?php } ?>
		if (controller != 'cpanel') { controller = 'home'; }
		check_daytoday('<?php echo session()->get('person_id'); ?>');
		function check_daytoday(pid) {
			$.ajax({
				'url': base_url + controller + '/check_daytoday',
				'type': 'POST', //the way you want to send data to your URL
				'data': { 'person_id': pid },
				'error': function (xhr, status, error) {
					if (xhr.status == 401) {
						window.location.href = '/login'; return;
					} else {
						alert("An error occured: " + xhr.status + " " + xhr.statusText);
					}},
				'success': function (data) { //probably this request will return anything, it'll be put in var "data"
					if (data == 'No') { $('#f_dt, #f_dt1, #f_dt1a').css('display', 'none'); $('#dta, #dt, #and').css('display', 'none'); }
				}
			});
		}
		check_usave('<?php echo session()->get('person_id'); ?>');
		function check_usave(pid) {
			$.ajax({
				'url': base_url + controller + '/check_usave',
				'type': 'POST',
				'data': { 'person_id': pid },
				'error': function (xhr, status, error) {
					if (xhr.status == 401) {
						window.location.href = '/login'; return;
					} else {
						alert("An error occured: " + xhr.status + " " + xhr.statusText);
					}},
				'success': function (data) {
					if (data == 'No') { $('#usa, #us, #and, #f_us, #f_usa').css('display', 'none'); }
				}
			});
		}
		check_both_promos('<?php echo session()->get('person_id'); ?>');
		function check_both_promos(pid) {
			$.ajax({
				'url': base_url + controller + '/check_both_promos',
				'type': 'POST',
				'data': { 'person_id': pid },
				'error': function (xhr, status, error) {
					if (xhr.status == 401) {
						window.location.href = '/login'; return;
					} else {
						alert("An error occured: " + xhr.status + " " + xhr.statusText);
					}},
				'success': function (data) {
					var nlink = $("#promos").attr("href");
					var nlink1 = $("#presells").attr("href");
					if (nlink && nlink1) {
						nlink = nlink.slice(0, -2);
						nlink1 = nlink1.slice(0, -2);
						if (data == 'cc') { $('#promos, #promos1').attr('href', nlink + 'cc'); $('#presells, #presells1').attr('href', nlink1 + 'cc'); }
						else { $('#promos, #promos1').attr('href', nlink + 'du'); $('#presells, #presells1').attr('href', nlink1 + 'du'); }
					}
				}
			});
		}

	</script>

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<!--<script async src="https://www.googletagmanager.com/gtag/js?id=UA-111358414-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	
	  gtag('config', 'UA-111358414-1');
	</script>-->
</head>

<body class="<?= !empty($top_ribbon) ? "with-top-ribbon" : "" ?>">
	<?php
	function getRemoteFilesize($url, $formatSize = true, $useHead = true)
	{
		if (false !== $useHead) {
			stream_context_set_default(array('http' => array('method' => 'HEAD')));
		}
		$head = array_change_key_case(get_headers($url, 1));
		$clen = isset($head['content-length']) ? $head['content-length'] : 0;
		if (!$clen) {
			return -1;
		}
		if (!$formatSize) {
			return $clen;
		}
		return $clen;
	}

	$category0[] = ['value' => 0, 'label' => 'All'];
	$category0[] = ['value' => 13, 'label' => 'BABY'];
	$category0[] = ['value' => 30, 'label' => 'BEER/LAGER'];
	$category0[] = ['value' => 1, 'label' => 'BISCUITS'];
	$category0[] = ['value' => 20, 'label' => 'BAGS'];
	$category0[] = ['value' => 31, 'label' => 'CIDER'];
	$category0[] = ['value' => 5, 'label' => 'TOBACCO'];
	$category0[] = ['value' => 3, 'label' => 'CONFECTIONERY'];
	$category0[] = ['value' => 2, 'label' => 'CRISPS'];
	$category0[] = ['value' => 24, 'label' => 'ECIGGS'];
	$category0[] = ['value' => 33, 'label' => 'FORTIFIED WINES'];
	$category0[] = ['value' => 15, 'label' => 'GROCERY'];
	$category0[] = ['value' => 7, 'label' => 'HEALTH'];
	$category0[] = ['value' => 8, 'label' => 'HOUSEHOLD'];
	$category0[] = ['value' => 22, 'label' => 'TISSUES'];
	$category0[] = ['value' => 23, 'label' => 'LAUNDRY'];
	$category0[] = ['value' => 26, 'label' => 'MEDICINES'];
	$category0[] = ['value' => 21, 'label' => 'NON-FOOD'];
	$category0[] = ['value' => 6, 'label' => 'PETFOODS'];
	$category0[] = ['value' => 28, 'label' => 'SUNDRIES'];
	$category0[] = ['value' => 4, 'label' => 'SOFT DRINKS'];
	$category0[] = ['value' => 35, 'label' => 'SPIRITS'];
	$category0[] = ['value' => 14, 'label' => 'STATIONERY'];
	$category0[] = ['value' => 32, 'label' => 'WINES'];

	$curr_category0 = 0;
	$uriSegments = request()->uri->getSegments();
	if ($uriSegments[0] == 'products') {
		$curr_category0 = request()->getGet('category_id');
	}
	?>

	<?php if (!empty($top_ribbon)) { ?>
		<div class="top_ribbon p-1"
			style="background:<?= $top_ribbon['ribbon']['bg_color'] ?>; color: <?= $top_ribbon['ribbon']['txt_color'] ?>;">
			<?= $top_ribbon['ribbon']['content'] ?>
		</div>
	<?php } ?>

	<nav class="topbar">
		<div class="nav-wrapper">
			<a href="<?php echo base_url($root); ?>" class="ajax-link linked left" target="_parent"><img
					alt="UWS ordering" src="<?php echo base_url(); ?>images/menubar/uws-logo.jpg" class="logoimage"
					style="margin:10px 20px 0px 0px; width:110px;"></a>
			<div class="searchbox left">
				<img src="<?php echo base_url(); ?>images/spinner_small.gif" alt="spinner" id="spinner1">
				<form action="<?php echo base_url(); ?>products/index/search" method="post" accept-charset="utf-8"
					id="search_form" style="font-family:Arial; background:none !important; padding:0px !important;"
					onsubmit="event.preventDefault();">
					<div class="d-flex align-items-center">
						<input type="text" class="m-0 me-2" name="search0" value="<?php if (isset($search0)) {
							echo urldecode($search0);
						} else {
							echo "";
						} ?>" id="search0" placeholder="Find Products" class="product_search_cell" onclick=" this.select();"
							onkeyup="go_search(event);">
						<div class="category0-select hide-on-med-and-down">
							<select id='category0' name='category0'>
								<?php
								foreach ($category0 as $cat) {
									if ($cat['value'] == $curr_category0)
										echo '<option value="' . $cat['value'] . '" selected>' . $cat['label'] . '</option>';
									else
										echo '<option value="' . $cat['value'] . '">' . $cat['label'] . '</option>';
								}
								?>
							</select>
						</div>
						<?php echo form_label('&nbsp;', 'product_code'); ?>
						<input type="hidden" name="sort_key" id="sort_key" value="<?php if (isset($sort_key)) {
							echo $sort_key;
						} else {
							echo "3";
						} ?>">
						<input type="hidden" name="search_mode" id="search_mode" value="<?php if (isset($search_mode)) {
							echo $search_mode;
						} else {
							echo "default";
						} ?>">
						<input type="hidden" name="per_page" id="per_page1" value="<?php if (isset($per_page)) {
							echo $per_page;
						} else {
							echo "30";
						} ?>">
						<input type="hidden" name="uri_segment" id="uri_segment" value="<?php if (isset($uri_segment)) {
							echo $uri_segment;
						} else {
							echo "6";
						} ?>">
						<input type="hidden" name="category" id="category" value="<?php if (isset($category_id)) {
							echo $category_id;
						} else {
							echo "0";
						} ?>">
						<input type="hidden" name="current_id" id="current_id" value="0">
						<input type="hidden" id="refresh" value="no">
						<input type="button" value="GO"
							style="font-size:14px; background:red; padding:7px; border:#000 0px solid; color:#fff !important; text-transform:uppercase; border-radius:2px; max-width:38px; height:36px; cursor: pointer;"
							onclick="search_query_header();">
					</div>
				</form>
			</div>

			<?php if (!empty($user_info)) { ?>
				<ul class="right hide-on-med-and-down" id="mainnav">
					<?php $i = 0;
					$icon = null;
					$cartlink = null;
					$cartname = null;
					foreach ($allowed_modules as $module) {
						switch ($module['module_id']) {
							case 'home':
								$icon = 'home';
								break;
							case 'products':
								$icon = 'view_list';
								break;
							case 'promos':
								$icon = 'local_offer';
								break;
							case 'pastorders':
								$icon = 'history';
								break;
							case 'favorites':
								$icon = 'favorite';
								break;
							case 'contactus':
								$icon = 'email';
								break;
							case 'orders':
								$icon = 'history';
								break;
							case 'employees':
								$icon = 'person';
								break;
							case 'seasonal_presell':
								$icon = 'local_offer';
								break;
						}

						if ($icon != 'shopping_cart' && $module['module_id'] != "orders") {
							if ($module['module_id'] == 'promos') {
								if ($user_info->username == 'guest') {
									$href = base_url($module['module_id']) . '/index/cc';
								} else {
									$href = base_url($module['module_id']) . '/index/du';
								}
							} else if ($module['module_id'] == 'seasonal_presell') {
								$href = base_url($module['module_id']) . "/index?category_id=0&spresell=1";
							} else {
								$href = base_url($module['module_id']);
							}
							?>
							<li>
								<a id="<?= lang('Main.module_' . $module['module_id']) ?>" href="<?= $href ?>"
									class="first-link <?php if (request()->uri->getSegment(1) == $module['module_id']) { ?> active <?php } else ?>
							   <?php if (request()->uri->getSegment(1) == "employees" && lang("Main.module_" . $module['module_id'] . "_slug") == "users") { ?> active <?php } ?>" target="_parent">
									<i class="material-icons"><?php echo $icon; ?></i>
									<?php echo lang("Main.module_" . $module['module_id']) ?>
								</a>
							</li>
							<?php
						} else {
							$cartlink = base_url($module['module_id']);
							$cartname = lang("Main.module_" . $module['module_id']);
						}
					}
					?>

					<li class="dropdown-menu-li">
						<a class="dropdown-trigger my_account_dropdown" data-target="my_account_dropdown">
							<i class="material-icons">account_box</i>
							<i class="material-icons m-0 expand_more">expand_more</i>
							My Account
						</a>
						<ul id="my_account_dropdown" class="dropdown-content">
							<li class="<?= empty($credit_account['credit_limit']) ? 'disabled' : '' ?>"><a
									href="/myaccount/credit_account/balance">My Balance</a></li>
							<li><a href="#" id='my_branches'>My Branches</a></li>
							<li><a href="/myaccount/order_history">My Order History</a></li>
							<li><a href="/myaccount/invoice_history">My Invoice History</a></li>
							<li><a href="/myaccount/credit_account/payment">Make a Payment</a></li>
							<li class="<?= empty($credit_account['credit_limit']) ? 'disabled' : '' ?>"><a
									href="/myaccount/ledger">Credit Ledger Details</a></li>
							<?php if (isset($loyalty_url)) { ?>
								<li><a href="<?= $loyalty_url ?>" target="_blank">United Loyalty Program</a></li>
							<?php } ?>
							<li><a href="<?php echo base_url($logout); ?>"><?php echo lang("Main.common_logout"); ?></a>
							</li>
						</ul>
					</li>

					<?php if (request()->uri->getSegment(1) != 'cpanel') { ?>
						<li><a id="combined" href="<?php echo $cartlink; ?>/orders/general" target="_parent" class=""><i
									class="material-icons">shopping_cart</i>£<span
									class="cart-amount"><?php echo isset($carttotal); ?></span></a></li>
					<?php } ?>
				</ul>

				<a href="#" data-target="slide-out" class="sidenav-trigger right mainnav"
					style="margin:0px 10px !important;" target="_parent"><i class="material-icons">menu</i></a>
				<a class="waves-effect hide-on-large-only right mainnav" style="margin:0 3px"
					href="<?php echo $cartlink; ?>/general" target="_parent"><i class="material-icons">shopping_cart</i></a>
				<ul id="slide-out" class="sidenav">
					<li>
						<div class="user-view">
							<div class="background red accent-2">&nbsp; </div>
							<ul>
								<li><a href="<?php echo base_url($root); ?>" style="color:#fff; padding-left:15px;"><i
											class="material-icons" style="color:#eee;">local_shipping</i>UWS Web
										Ordering1</a></li>
							</ul>
						</div>
					</li>
					<?php $i = 0;
					$icon = null;
					$cartlink = null;
					$cartname = null;
					foreach ($allowed_modules as $module) {
						$i = $i + 1;
						switch ($module['module_id']) {
							case 'home':
								$icon = 'home';
								break;
							case 'products':
								$icon = 'view_list';
								break;
							case 'promos':
								$icon = 'local_offer';
								break;
							case 'orders':
								$icon = 'history';
								break;
							case 'pastorders':
								$icon = 'history';
								break;
							case 'favorites':
								$icon = 'favorite';
								break;
							case 'contactus':
								$icon = 'email';
								break;
							case 'employees':
								$icon = 'person';
								break;
							case 'seasonal_presell':
								$icon = 'view_list';
								break;
						}

						if ($icon != 'shopping_cart' && $module['module_id'] != "orders") {
							if ($module['module_id'] == 'promos') {
								if ($user_info->username == 'guest') {
									$href = base_url($module['module_id']) . '/index/cc';
								} else {
									$href = base_url($module['module_id']) . '/index/du';
								}
							} else if ($module['module_id'] == 'seasonal_presell') {
								$href = base_url($module['module_id']) . "/index?category_id=0&spresell=1";
							} else {
								$href = base_url($module['module_id']);
							}
							?>
							<li>
								<a id="<?= lang('Main.module_' . $module['module_id']) ?>" href="<?= $href ?>" class="first-link"
									target="_parent">
									<i class="material-icons"><?php echo $icon; ?></i>
									<?php echo lang("Main.module_" . $module['module_id']) ?>
								</a>
							</li>
						<?php } else {
							$cartlink = base_url($module['module_id']);
							$cartname = lang("Main.module_" . $module['module_id']);
						}
					} ?>
					<li>
						<div class="divider"></div>
					</li>

					<li class="collapsible-menu">
						<a>
							<i class="material-icons">account_box</i>My Account
							<i class="material-icons m-0 float-right expand_less">expand_less</i>
							<i class="material-icons m-0 float-right expand_more">expand_more</i>
						</a>
						<ul>
							<li class="<?= empty($credit_account['credit_limit']) ? 'disabled' : '' ?>"><a
									href="/myaccount/credit_account/balance">My Balance</a></li>
							<li><a href="/myaccount/order_history">My Order History</a></li>
							<li><a href="/myaccount/invoice_history">My Invoice History</a></li>
							<li><a href="/myaccount/credit_account/payment">Make a Payment</a></li>
							<li class="<?= empty($credit_account['credit_limit']) ? 'disabled' : '' ?>"><a
									href="/myaccount/ledger">Credit Ledger Details</a></li>
							<?php if (isset($loyalty_url)) { ?>
								<li><a href="<?= $loyalty_url ?>" target="_blank">United Loyalty Program</a></li>
							<?php } ?>
							<li><a href="<?php echo base_url($logout); ?>"><?php echo lang("Main.common_logout"); ?></a>
							</li>
						</ul>
					</li>


					<?php if (request()->uri->getSegment(1) != 'cpanel') { ?>
						<li><a class="waves-effect" href="<?php echo $cartlink; ?>/general" target="_parent"><i
									class="material-icons">shopping_cart</i>Trolley</a>
						</li>
					<?php } ?>
					<li>
						<div class="divider"></div>
					</li>
					<li><a href="javascript:void(0)" class="sidenav-close" target="_parent"><i
								class="material-icons">close</i>Close</a></li>
				</ul>
			<?php } else { ?>

				<ul class="right hide-on-med-and-down" id="mainnav">
					<li>
						<a href="<?php echo base_url('home'); ?>"
							class="first-link <?= request()->uri->getSegment(1) == 'home' ? 'active' : '' ?>"
							target="_parent">
							<i class="material-icons">home</i>
							Home
						</a>
					</li>
					<li><a href="<?php echo base_url($login); ?>" target="_parent"><i
								class="material-icons">account_box</i><?php echo lang("Main.login_login"); ?></a></li>
				</ul>
				<a href="#" data-target="slide-out" class="sidenav-trigger right mainnav"
					style="margin:0px 10px !important;" target="_parent"><i class="material-icons">menu</i></a>
				<ul id="slide-out" class="sidenav">
					<li>
						<div class="user-view">
							<div class="background red accent-2">&nbsp; </div>
							<ul>
								<li><a href="<?php echo base_url($root); ?>" style="color:#fff; padding-left:15px;"><i
											class="material-icons" style="color:#eee;">local_shipping</i>UWS Web
										Ordering1</a></li>
							</ul>
						</div>
					</li>
					<li>
						<a href="<?php echo base_url('home'); ?>"
							class="first-link <?= request()->uri->getSegment(1) == 'home' ? 'active' : '' ?>"
							target="_parent">
							<i class="material-icons">home</i>
							Home
						</a>
					</li>
					<li><a href="<?php echo base_url($login); ?>" target="_parent"><i
								class="material-icons">account_box</i><?php echo lang("Main.login_login"); ?></a></li>
					<li>
						<div class="divider"></div>
					</li>
					<li><a href="javascript:void(0)" class="sidenav-close" target="_parent"><i
								class="material-icons">close</i>Close</a></li>
				</ul>
			<?php } ?>
		</div>

		<nav class="catnav">
			<div class="nav-wrapper hide-on-med-and-down">
				<ul class="hide-on-med-and-down">
					<?php echo isset($catnames) ? $catnames : ''; ?>
				</ul>
			</div>
		</nav>
	</nav>

	<ul id="dropdown1" class="dropdown-content">
		<li><a href="#!">one</a></li>
		<li><a href="#!">two</a></li>
		<li class="divider"></li>
		<li><a href="#!">three</a></li>
	</ul>

	<header id="header-container">
		<div class="row" style="display:none">
			<div class="order-logo">
				<a href="<?php echo base_url($root); ?>" class="ajax-link linked" target="_parent"><img
						alt="UWS ordering" src="<?php echo base_url(); ?>images/menubar/uws-logo.jpg"></a>
			</div>
			<div class="order-nav">
				<ul id="menu-main-menu">
					<?php $i = 0;
					$icon = null;
					$cartlink = null;
					$cartname = null;
					foreach ($allowed_modules as $module) {
						$i = $i + 1;
						switch ($module['module_id']) {
							case 'home':
								$icon = 'home';
								break;
							case 'products':
								$icon = 'view_list';
								break;
							case 'promos':
								$icon = 'local_offer';
								break;
							case 'orders':
								$icon = 'history';
								break;
							case 'pastorders':
								$icon = 'history';
								break;
							case 'favorites':
								$icon = 'favorite';
								break;
							case 'contactus':
								$icon = 'email';
								break;
							case 'employees':
								$icon = 'person';
								break;
							case 'seasonal_presell':
								$icon = 'view_list';
								break;
						}

						if ($icon != 'shopping_cart' && $module['module_id'] != "orders") {
							if ($module['module_id'] == 'promos') {
								if ($user_info->username == 'guest') {
									$href = base_url($module['module_id']) . '/index/cc';
								} else {
									$href = base_url($module['module_id']) . '/index/du';
								}
							} else if ($module['module_id'] == 'seasonal_presell') {
								$href = base_url($module['module_id']) . "/index?category_id=0&spresell=1";
							} else {
								$href = base_url($module['module_id']);
							}

							?>
							<li class="menu-item">
								<a id="<?= lang('Main.module_' . $module['module_id']) ?>" href="<?= $href ?>"
									class="first-link <?php if (request()->uri->getSegment(1) == lang("Main.module_" . $module['module_id'] . "_slug")) { ?> active <?php } else ?>
						<?php if (request()->uri->getSegment(1) == "employees" && lang("Main.module_" . $module['module_id'] . "_slug") == "users") { ?> active <?php } ?>" target="_parent">
									<?php echo lang("Main.module_" . $module['module_id']); ?>
								</a>
							</li>
							<?php
						} else {
							$cartlink = base_url($module['module_id']);
							$cartname = lang("Main.module_" . $module['module_id']);
						}
					} // For Each Loop end 
					if (request()->uri->getSegment(1) == 'cpanel') { ?>
						<li class="menu-item">
							<b>Manager Control Panel</b> - Welcome <?php echo session()->get('manager_username'); ?>!
						</li>
					<?php } ?>
				</ul>
				<a href="#" data-layoutaction-link="search-box" title="Search" class="search-link"><i
						class="caviar-icon-search-menu"></i></a>
			</div>
			<div class="order-misc">
				<ul class="menu-action text-right">
					<li class="device-behavior"><a href="<?php echo base_url($logout); ?>" title="Logout"
							target="_parent"><?php echo lang("Main.common_logout"); ?></a></li>
					<!--<li class="device-behavior"><a href="<?php echo base_url("main"); ?>" title="Hub" target="_parent">Hub</a></li>-->
					<?php if (request()->uri->getSegment(1) != 'cpanel' && $cartname != "") { ?>
						<li style="margin-right:23px !important; line-height:initial;"><a class="mini-cart__link"
								href="<?php echo $cartlink; ?>" data-layoutaction-link="shop-cart"
								title="View your shopping cart" target="_parent"><span><?php echo $cartname; ?></span><span
									class="cart-counter">0</span></a></li>
					<?php } ?>
				</ul>
			</div>
			<br style="clear:both;">
		</div>
		<!--end of row-->

		<input type="hidden" id="general" value="0" />
		<input type="hidden" id="tobacco" value="0" />
		<input type="hidden" id="chilled" value="0" />

		<?php if (!empty($user_info)) { ?>
			<div id="my_branches_dialog" class="modal">
				<div class="modal-header">
					My Branches
				</div>
				<div class="modal-content branches">
					<ul>
						<?php foreach ($all_branches as $branch) { ?>
							<li>
								<label>
									<input type="checkbox" class="filled-in" data-branch="<?= $branch['id'] ?>"
										<?= in_array($branch['id'], $user_info->branches) ? 'checked' : '' ?> />
									<span class="ms-2"><?= $branch['site_name'] ?></span>
								</label>
							</li>
						<?php } ?>
					</ul>
				</div>
				<div class="modal-footer pe-4">
					<button type="button" class="modal-close waves-effect waves-green btn-flat">Cancel</button>
					<button type="button" class="btn-primary btn ms-4" id="btn_select">Select</button>
				</div>
			</div>
		<?php } ?>
	</header>

	<script>
		$(document).ready(function () {
			// To initialize modal.
			$('.modal').modal();

			// To show error if any.
			<?php
			if (isset($error)) {
				?>
				toast('error', "<?= $error ?>");
			<?php } ?>

			// To check whether mobile screen
			const data = {
				is_mobile: (window.visualViewport.width < 992) ? 1 : 0
			}
			$.ajax({
				'url': base_url + 'home/mobile',
				'type': 'POST', //the way you want to send data to your URL
				'data': data,
				'error': function (xhr, status, error) {
					if (xhr.status == 401) {
						window.location.href = '/login'; return;
					} else {
						alert("An error occured: " + xhr.status + " " + xhr.statusText);
					}},
				'success': function (data) {
				}
			});

			// event handler for select branches
			$(document).on('click', '#my_branches_dialog button#btn_select', function (e) {
				let arrBranches = [];
				const chks = $("#my_branches_dialog ul li input[type='checkbox']:checked");
				for (let i = 0; i < chks.length; i++) {
					arrBranches.push($(chks[i]).data('branch'));
				}
				const branches = arrBranches.join(',');

				const theThis = this;
				$(theThis).addClass('disabled');
				$.ajax({
					type: 'POST',
					url: '<?= base_url("myaccount/my_branches") ?>',
					data: { branches },
					error: function (xhr, status, error) {
						if (xhr.status == 401) {
							window.location.href = '/login'; return;
						} else {
							alert("An error occured: " + xhr.status + " " + xhr.statusText);
						}},
					success: function (data) {
						$(theThis).removeClass('disabled');
						toast("success", data.message);
						setTimeout(() => {
							$("#my_branches_dialog").modal("close");
						}, 500);
					}
				});
			})

			// To show my_branches dialog
			$(document).on('click', 'a#my_branches', function (e) {
				e.preventDefault();
				$("#my_branches_dialog").modal("open");
			})
		})
	</script>

	<main>
		<div id="main-container">