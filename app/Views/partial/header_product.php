<!doctype html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<base href="<?php echo base_url();?>" />
	<title><?php echo $this->config->item('company').' -- '.$this->lang->line('common_powered_by').' ePOS' ?></title>
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/epos.css?v=<?=env('app.asset.version')?>" />
	<link rel="stylesheet" rev="stylesheet" href="<?php echo base_url();?>css/epos_print.css?v=<?=env('app.asset.version')?>"  media="print"/>
	<script>BASE_URL = '<?php echo site_url(); ?>';</script>

	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.css" />
	<script src="<?php echo base_url();?>js/jquery-1.10.2.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui.js"></script>

	<script src="<?php echo base_url();?>js/common.js?v=<?=env('app.asset.version')?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/manage_tables.js?v=<?=env('app.asset.version')?>" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="<?php echo base_url();?>js/swfobject.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<style type="text/css">

    .ui-autocomplete
    {
        max-height: 300px;
        overflow-y: auto;
        overflow-x: hidden;
        font-size:12px;
    }

    html .ui-autocomplete
    {
        height: 300px;
        font-size:12px;
    }

		html {overflow: auto;}
	</style>

</head>
<body>
<div id="menubar">
	<div id="menubar_container">
		<div id="menubar_company_info">
			<canvas id="CompanyTitleCanvas" style="width:100%; height:120px;"></canvas>
		</div>
		<div id="menubar_navigation">
			<canvas id="drawCanvas" style="width:100%; height:100%;"></canvas>
		</div>
		<div id="menubar_footer">
			<div class="menu_item">
				<a href="<?php echo site_url("home");?>" class="first-link"><?php echo $this->lang->line("module_home"); ?></a>
			</div>
			<?php
				foreach($allowed_modules->result() as $module)
				{
			?>
					<div class="menu_item">
						<a href="<?php echo site_url("$module->module_id");?>" class="first-link"><?php echo $this->lang->line("module_".$module->module_id) ?></a>
					</div>
			<?php
				}
			?>
			<div class="menu_item_logout"><?php echo anchor("home/logout",$this->lang->line("common_logout")); ?></div>

		</div>
	</div>

</div>
<div id="content_area_wrapper" style="position:absolute; height:200%;">

<div id="content_area">
