<?php echo view("partial/header"); ?>
<!--<div id="content_area_wrapper" >
<div id="content_area" >-->
<style type="text/css">
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

		//set pagination
		var t = $('#total_page').val();
		var p = $('#curd_page').val();
		if (p == t) { $("#next").removeClass().addClass("disabled"); }
		if (p == 1) { $("#prev").removeClass().addClass("disabled"); }

		$("ul.pagination li").each(function (index, element) {
			if (index > t) { return false; }
			if ($(this).is("#p" + index)) {
				if (index == p) { $(this).removeClass().addClass("active"); }
			}
		});

		$("#api_key").click(function () {
			$("#api_key").select();
			setTimeout(
				function () {
					document.execCommand("copy");
					alert("API Key copied to clipboard");
				}, 200);
		});

		$("#dialog_form").dialog
			(
				{
					autoOpen: false,
					// height: 500 ,
					width: Math.min(800, $(window).width() * .95),
					modal: true,
					buttons:
					{
						"Generate Key": function () {
							$.ajax({
								async: true
								, url: "<?php echo base_url("$controller_name/generate_key"); ?>" + "/" + $('#person_id').val()
								, timeout: 30000
								, cache: false
								, error: function (request, status, error) {
									if (request.status != 200) alert("generate key : " + request.status + "\r\nmessage : " + request.reponseText);
								}
								, success: function (response, status, request) {
									$('#api_key').val(response);
									$('#api_key').trigger('click');
									$('#error_message_box').html('');
								}
							});
						},
						"Copy Key": function () {
							$('#api_key').trigger('click');
						},
						"Save": function () {
							const theThis = this;
							var bValid = true;
							var person_id = $('#person_id');
							var username = $('#username'), email = $('#email'), password = $('#password'), repeat_password = $('#repeat_password');
							var presell_band = $('#presell_band'); api_key = $('#api_key');
							var price_list005, price_list007, price_list008, price_list009, price_list010, price_list011, price_list012, price_list999;
							var msg = $('#error_message_box');
							bValid = bValid && check_empty_field(username, "<?php echo lang('Main.employees_username'); ?>");
							bValid = bValid && check_empty_field(email, "<?php echo lang('Main.common_email'); ?>");
							if (person_id.val() == '0') {
								bValid = bValid && check_empty_field(password, "<?php echo lang('Main.employees_password'); ?>");
								bValid = bValid && check_empty_field(repeat_password, "<?php echo lang('Main.employees_repeat_password'); ?>");
							}

							if (password.val() != repeat_password.val()) {
								msg.html("<li><?php echo lang('Main.employees_password_must_match'); ?></li>");
								bValid = false;
							}

							// branches
							let branches = [];
							$sel_branch_chkboxes = $("input.branch:checked");
							if ($sel_branch_chkboxes.length > 0) {
								for (let i = 0; i < $sel_branch_chkboxes.length; i++) {
									branches.push($sel_branch_chkboxes[i].value)
								}
							}

							// delivery
							const delivery = $("input#delivery")[0].checked ? 1 : 0;
							const delivery_charge = $("input#delivery_charge").val();
							const collect = $("input#collect")[0].checked ? 1 : 0;
							const pay = $("input#pay")[0].checked ? 1 : 0;

							if (bValid) {
								if ($('#price_list005').prop('checked')) price_list005 = '1';
								else price_list005 = '';
								if ($('#price_list007').prop('checked')) price_list007 = '1';
								else price_list007 = '';
								if ($('#price_list008').prop('checked')) price_list008 = '1';
								else price_list008 = '';
								if ($('#price_list009').prop('checked')) price_list009 = '1';
								else price_list009 = '';
								if ($('#price_list010').prop('checked')) price_list010 = '1';
								else price_list010 = '';
								if ($('#price_list011').prop('checked')) price_list011 = '1';
								else price_list011 = '';
								if ($('#price_list012').prop('checked')) price_list012 = '1';
								else price_list012 = '';
								if ($('#price_list999').prop('checked')) price_list999 = '1';
								else price_list999 = '';

								const data = "person_id=" + person_id.val() +
									"&username=" + username.val() +
									"&email=" + email.val() +
									"&password=" + password.val() +
									"&branches=" + branches.join(',') +
									"&presell_band=" + presell_band.val() +
									"&price_list005=" + price_list005 +
									"&price_list007=" + price_list007 +
									"&price_list008=" + price_list008 +
									"&price_list009=" + price_list009 +
									"&price_list010=" + price_list010 +
									"&price_list011=" + price_list011 +
									"&price_list012=" + price_list012 +
									"&price_list999=" + price_list999 +
									"&api_key=" + api_key +
									`&delivery=${delivery}` +
									`&delivery_charge=${delivery_charge}` +
									`&collect=${collect}` +
									`&pay=${pay}`;

								$.ajax({
									type: "POST"
									, async: true
									, url: "<?php echo base_url("$controller_name/save"); ?>"
									, dataType: "json"
									, timeout: 30000
									, cache: false
									, data
									, error: function (request, status, error) {
										//alert("failed : " + request.status + "\r\nmessage : " + request.reponseText);
									}
									, success: function (response, status, request) {
										$(theThis).dialog('close');
										load_users();
									}
								});

							}
						}
					}

				}
			);

		$("#search").on('keyup', function (e) {
			if (e.keyCode == 13) {
				$("#curd_page").val(1);
				load_users();
			}
		})

		enable_search1('<?php echo base_url("$controller_name/suggest") ?>', '<?php echo lang("common_confirm_search") ?>');

		$(document).on('change', '#delivery_charge', function(e) {
			$(e.target).val(floatV(e.target.value));
		})
	});

	function popup_dialog(person_id = 0) {
		$('#username').val('');
		$('#email').val('');
		$('#password').val('');
		$('#repeat_password').val('');
		$('#price_list005').prop('checked', false);
		$('#price_list007').prop('checked', false);
		$('#price_list008').prop('checked', false);
		$('#price_list009').prop('checked', false);
		$('#price_list010').prop('checked', false);
		$('#price_list011').prop('checked', false);
		$('#price_list012').prop('checked', false);
		$('#price_list999').prop('checked', true);
		$('#error_message_box').html('');
		$('#person_id').val('0');

		if (person_id) {
			$.ajax({
				type: "POST"
				, async: true
				, url: "<?php echo base_url("$controller_name/get_user_info"); ?>"
				, dataType: "json"
				, timeout: 30000
				, cache: false
				, data: "person_id=" + person_id
				, error: function (request, status, error) {
					if (request.status != 200) alert("get user info : " + request.status + "\r\nmessage : " + request.reponseText);
				}
				, success: function (response, status, request) {
					$('#username').val(response[0]);
					$('#email').val(response[1]);
					$('#label_password').attr('class', 'wide');
					$('#label_repeat_password').attr('class', 'wide');
					$('#repeat_password').val('');
					$('#password').val('');
					$('#price_list005').prop('checked', Number(response[3]));
					$('#price_list007').prop('checked', Number(response[4]));
					$('#price_list008').prop('checked', Number(response[5]));
					$('#price_list009').prop('checked', Number(response[6]));
					$('#price_list010').prop('checked', Number(response[7]));
					$('#price_list011').prop('checked', Number(response[8]));
					$('#price_list012').prop('checked', Number(response[9]));
					$('#price_list999').prop('checked', Number(response[10]));
					$('#presell_band').val(response[11]).formSelect();
					$('#api_key').val(response[12]);
					$("#delivery").prop('checked', Number(response[14]));
					$("#delivery_charge").val(floatV(response[15]));
					$("#collect").prop('checked', Number(response[16]));
					$("#pay").prop('checked', Number(response[17]));

					$branch_chkboxes = $("input.branch");
					customer_branches = response[13].split(',');
					for (let i = 0; i < $branch_chkboxes.length; i++) {
						const branch_chkbox = $branch_chkboxes[i];
						const branch_id = branch_chkbox.value;
						if (customer_branches.includes(branch_id)) {
							$(branch_chkbox).prop('checked', true);
						}
					}

					$('#error_message_box').html('');
					$('#person_id').val(person_id);
				}
			});
		}
		$('#dialog_form').dialog('open');
	}

	function check_empty_field(link, label) {
		var msg = $('#error_message_box');
		if (link.val().length < 1) {
			msg.html("<li>The " + label + " is a required field.</li>");
			return false;
		}
		else return true;
	}

	function load_users() {
		var nCurrentSortKey = $('#sort_key').val();
		var search = $('#search').val();
		var per_page = $('#per_page').val();
		var location_site = "<?php echo base_url("$controller_name/index"); ?>?";
		var page_num = $('#curd_page').val();
		const offset = (Number(page_num) - 1) * Number(per_page);

		if (nCurrentSortKey) location_site += "&sort_key=" + nCurrentSortKey;
		if (per_page) location_site += "&per_page=" + per_page;
		if (offset) location_site += "&offset=" + offset;
		if (search) location_site += "&search=" + encodeURIComponent(search);

		location.replace(location_site);
	}

	function select_per_page() {
		load_users();
	}

	function first_page() {
		var curd_page = Number($('#curd_page').val());
		if (curd_page <= 1) return;

		$('#curd_page').val(1);
		load_users();
	}

	function prev_page() {
		var curd_page = Number($('#curd_page').val());
		if (curd_page <= 1) return;

		$('#curd_page').val(curd_page - 1);
		load_users();
	}

	function next_page() {
		var curd_page = Number($('#curd_page').val());
		var total_page = Number($("#total_page").val());
		if (curd_page >= total_page) return;

		$('#curd_page').val(curd_page + 1);
		load_users();
	}

	function last_page(url) {
		var curd_page = Number($('#curd_page').val());
		var total_page = Number($("#total_page").val());
		if (curd_page >= total_page) return;

		$('#curd_page').val(total_page);
		load_users();
	}

	function set_direct_page(e, url) {
		var result;
		if (window.event) result = window.event.keyCode;
		else if (e) result = e.which;
		if (result == 13) {
			var page_num = Number($('#curd_page').val());
			goto_page(page_num);
		}
	}

	function goto_page(page) {
		var total_page = Number($("#total_page").val());

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
			$('#curd_page').val("1");
			return;
		}

		load_users();
	}
</script>

<div id="order_total_div" style="padding-bottom:30px;">
	<div class="shopping__cart-page-header text-center">
		<h2><?php echo lang('Main.common_list_of') . ' ' . lang('Main.module_' . $controller_name); ?></h2>
	</div>
</div>
<div>
	<input type="text" name="search" id="search" autocomplete="off" class='cell1'
		style="width:200px; background:#fff !important; padding: 0px 10px;" value="<?= $search ?>" />
	<input type="hidden" name="sort_key" id="sort_key" value="<?= $sort_key ?>">
	<input type="hidden" name="offset" id="offset" value="<?= $offset ?>">
</div>
<div id="title_bar">
	<div id="new_button" onclick="popup_dialog();">
		<div>
			<span><?php echo lang('Main.' . $controller_name . '_new'); ?></span>
		</div>
	</div>
</div>
<div id="table_holder" class="table_holder">
	<?php echo $manage_table; ?>
</div>
<div id="actions">
	<div class="input-field col s12 d-flex align-items-center">
		<div class="me-2"><label>Display</label></div>
		<div style="width:80px;">
			<select name='per_page' id='per_page'
				onchange="select_per_page('<?php echo base_url("$controller_name/index/"); ?>');">
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
					echo "selected='true'"; ?>>100</option>
				<option value='150' <?php if ($per_page == 150)
					echo "selected='true'"; ?>>150</option>
				<option value='200' <?php if ($per_page == 200)
					echo "selected='true'"; ?>>200</option>
			</select>
		</div>
	</div>
	<ul class="pagination">
		<li class="waves-effect" id="prev"><a href="javascript:void();" onclick="prev_page();"><i
					class="material-icons">chevron_left</i></a></li>
		<?php $t = $total_page;
		for ($i = 1; $i <= $t; $i++) { ?>
			<li class="waves-effect" id="p<?php echo $i; ?>">
				<a href="javascript:void()" onclick="goto_page(<?php echo $i; ?>);"><?php echo $i; ?></a>
			</li>
		<?php } ?>
		<li class="waves-effect" id="next"><a href="javascript:void();" onclick="next_page();"><i
					class="material-icons">chevron_right</i></a></li>
	</ul>
	<br style="clear:both;">
</div>

<?php echo view("partial/footer"); ?>

<div id="dialog_form" title="Create/Edit User" style="font-family:Arial; font-size:12px; overflow:unset;">
	<?php echo form_open('#', array('id' => 'customer_form')); ?>
	<div id="required_fields_message"><?php echo lang('Main.common_fields_required_message'); ?></div>
	<ul id="error_message_box"></ul>

	<div class="d-flex">
		<fieldset id="customer_login_info" class="flex-fluid">
			<legend><?php echo lang("Main.customers_login_info"); ?></legend>
			<div class="field_row clearfix">
				<?php echo form_label(lang('Main.employees_band') . ':', 'band'); ?>
				<div class='form_field'><?php
				$options = array('' => 'None', 'small' => 'Small', 'medium' => 'Medium', 'large' => 'Large', 'elite' => 'Elite');
				echo form_dropdown('presell_band', $options, 'None', 'id="presell_band"'); ?></div>
			</div>
			<div class="field_row clearfix">
				<?php echo form_label(lang('Main.common_email') . ':', 'email', array('class' => 'required')); ?>
				<div class='form_field'>
					<?php echo form_input(array('name' => 'email', 'id' => 'email', 'value' => '')); ?>
				</div>
			</div>
			<div class="field_row clearfix">
				<?php echo form_label(lang('Main.employees_username') . ':', 'username', array('class' => 'required')); ?>
				<div class='form_field'>
					<?php echo form_input(array('name' => 'username', 'id' => 'username', 'value' => '')); ?>
				</div>
			</div>
			<?php $password_label_attributes = array('class' => 'required', 'id' => 'label_password'); ?>

			<div class="field_row clearfix">
				<?php echo form_label(lang('Main.employees_password') . ':', 'password', $password_label_attributes); ?>
				<div class='form_field'><?php echo form_password(array('name' => 'password', 'id' => 'password')); ?>
				</div>
			</div>
			<?php $repeat_password_label_attributes = array('class' => 'required', 'id' => 'label_repeat_password'); ?>
			<div class="field_row clearfix">
				<?php echo form_label(lang('Main.employees_repeat_password') . ':', 'repeat_password', $repeat_password_label_attributes); ?>
				<div class='form_field'>
					<?php echo form_password(array('name' => 'repeat_password', 'id' => 'repeat_password')); ?>
				</div>
			</div>
		</fieldset>

		<fieldset id="customer_permission_info" class="flex-fluid" style="margin-left: 5px;">
			<legend><?php echo lang('Main.customers_price_list'); ?></legend>
			<p><?php echo lang('Main.customers_choice_desc'); ?></p>
			<ul id="permission_list">
				<li>
					<label>
						<?php
						$price_list005 = false;
						echo form_checkbox(array('name' => 'price_list005', 'id' => 'price_list005'), '005', $price_list005);
						?>
						<span class="medium">Q5</span>
					</label>
				</li>
				<li>
					<label>
						<?php
						$price_list007 = false;
						if (isset($person_info->price_list007))
							$price_list007 = (boolean) $person_info->price_list007;
						echo form_checkbox(array('name' => 'price_list007', 'id' => 'price_list007'), '007', $price_list007);
						?>
						<span class="medium">One Day Specials</span>
					</label>
				</li>
				<li>
					<label>
						<?php
						$price_list008 = false;
						if (isset($person_info->price_list008))
							$price_list008 = (boolean) $person_info->price_list008;
						echo form_checkbox(array('name' => 'price_list008', 'id' => 'price_list008'), '008', $price_list008);
						?>
						<span class="medium">Day ToDay Elite</span>
					</label>
				</li>
				<li>
					<label>
						<?php
						$price_list009 = false;
						if (isset($person_info->price_list009))
							$price_list009 = (boolean) $person_info->price_list009;
						echo form_checkbox(array('name' => 'price_list009', 'id' => 'price_list009'), '009', $price_list009);
						?>
						<span class="medium">Chill Delivered Promo</span>
					</label>
				</li>
				<li>
					<label>
						<?php
						$price_list010 = false;
						if (isset($person_info->price_list010))
							$price_list010 = (boolean) $person_info->price_list010;
						echo form_checkbox(array('name' => 'price_list010', 'id' => 'price_list010'), '010', $price_list010);
						?>
						<span class="medium">Day ToDay</span>
					</label>
				</li>
				<li>
					<label>
						<?php
						$price_list011 = false;
						if (isset($person_info->price_list011))
							$price_list011 = (boolean) $person_info->price_list011;
						echo form_checkbox(array('name' => 'price_list011', 'id' => 'price_list011'), '011', $price_list011);
						?>
						<span class="medium">Day ToDay Express</span>
					</label>
				</li>
				<li>
					<label>
						<?php
						$price_list012 = false;
						if (isset($person_info->price_list012))
							$price_list012 = (boolean) $person_info->price_list012;
						echo form_checkbox(array('name' => 'price_list012', 'id' => 'price_list012'), '012', $price_list012);
						?>
						<span class="medium">USave</span>
					</label>
				</li>
				<li>
					<label>
						<?php
						$price_list999 = false;
						if (isset($person_info->price_list999))
							$price_list999 = (boolean) $person_info->price_list999;
						echo form_checkbox(array('name' => 'price_list999', 'id' => 'price_list999'), '999', $price_list999);
						?>
						<span class="medium">Std Pricing</span>
					</label>
				</li>
			</ul>
		</fieldset>

		<?php if (!empty($all_branches)) { ?>
			<fieldset id="customer_branches_info" class="flex-fluid" style="margin-left: 5px; padding: 10px;">
				<legend>Branches:</legend>
				<ul>
					<?php foreach ($all_branches as $br) { ?>
						<li style="text-align:left; padding: 5px;">
							<label>
								<?= form_checkbox(array('class' => 'branch'), $br['id'], false); ?>
								<span class="medium"><?= $br['site_name'] ?></span>
							</label>
						</li>
					<?php } ?>
				</ul>
			</fieldset>
		<?php } ?>
	</div>

	<div class="mt-1">
		<fieldset id="customer_delivery">
			<legend>Order Types:</legend>
			<ul class="d-flex align-items-center justify-content-between">
				<li class="d-flex align-items-center">
					<label>
						<?= form_checkbox(array('id' => 'delivery'), 'del' ); ?>
						<span class="medium">Delivery,</span>
					</label>
					<div class="d-flex align-items-center ms-4">
						<label class="medium" style="font-size: 1rem !important;">Delivery Charge:</label>
						<span class="ms-4" style="font-size: 120%;">£</span>
						<div class="form_field ms-1">
							<?= form_input(array('id' => 'delivery_charge', 'class'=>'text-center')); ?>
						</div>
					</div>
				</li>
				<li>
					<label>
						<?= form_checkbox(array('id' => 'collect'), 'col'); ?>
						<span class="medium">Click and Collect</span>
					</label>
				</li>
				<li>
					<label>
						<?= form_checkbox(array('id' => 'pay'), 'pay'); ?>
						<span class="medium">Pay</span>
					</label>
				</li>
			</ul>
		</fieldset>
	</div>

	<fieldset id="customer_api_key" style="margin-top:10px;">
		<div class="field_row clearfix" style="margin:20px 0px 5px 0px;">
			<?php echo form_label('API Key :', 'api_key'); ?>
			<div class='form_field' style='margin-bottom:0px;'>
				<?php echo form_input(array('name' => 'api_key', 'id' => 'api_key', 'value' => '', 'readonly' => 'readonly')); ?>
			</div>
		</div>
	</fieldset>

	<input type="hidden" id="person_id" value="">
	<input type="hidden" name="page" id="curd_page" value="<?php echo $curd_page; ?>" size="4">
	<input type="hidden" name="page" id="total_page" value="<?php echo $total_page; ?>" size="4">
	<?php echo form_close(); ?>
</div>
</div>
</div>