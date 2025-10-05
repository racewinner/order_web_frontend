<html>
<head>
    <title>Import Presell Order Data into Database</title>
    <style>
	table {
 		border-collapse: collapse;
 		border-spacing: 0;
	}
	td,	th {
	  padding: 0;
	}
	* {
	  -webkit-box-sizing: border-box;
	  -moz-box-sizing: border-box;
	  box-sizing: border-box;
	}
	*:before,	*:after {
	  -webkit-box-sizing: border-box;
	  -moz-box-sizing: border-box;
	  box-sizing: border-box;
	}
    .table-bordered{font-size:xx-small}
	table {
	  background-color: transparent;
	}
	table col[class*="col-"] {
	  position: static;
	  display: table-column;
	  float: none;
	}
	table td[class*="col-"],	table th[class*="col-"] {
	  position: static;
	  display: table-cell;
	  float: none;
	}
	caption {
	  padding-top: 8px;
	  padding-bottom: 8px;
	  color: #777777;
	  text-align: left;
	}
	th {
	  text-align: center;
	}
	.table {
	  width: 100%;
	  max-width: 100%;
	  margin-bottom: 20px;
	}
	.table > thead > tr > th,	.table > tbody > tr > th,	.table > tfoot > tr > th,	.table > thead > tr > td,	.table > tbody > tr > td,	.table > tfoot > tr > td {
	  padding: 8px;
	  line-height: 1.42857143;
	  vertical-align: top;
	  border-top: 1px solid #dddddd;
	}
	.table > thead > tr > th {
	  vertical-align: bottom;
	  border-bottom: 2px solid #dddddd;
	}
	.table > caption + thead > tr:first-child > th,	.table > colgroup + thead > tr:first-child > th,	.table > thead:first-child > tr:first-child > th,	.table > caption + thead > tr:first-child > td,	.table > colgroup + thead > tr:first-child > td,	.table > thead:first-child > tr:first-child > td {
	  border-top: 0;
	}
	.table > tbody + tbody {
	  border-top: 2px solid #dddddd;
	}
	.table .table {
	  background-color: #ffffff;
	}
	.table-condensed > thead > tr > th,	.table-condensed > tbody > tr > th,	.table-condensed > tfoot > tr > th,	.table-condensed > thead > tr > td,	.table-condensed > tbody > tr > td,	.table-condensed > tfoot > tr > td {
	  padding: 5px;
	}
	.table-bordered {
	  border: 1px solid #dddddd;
	}
	.table-bordered > thead > tr > th,	.table-bordered > tbody > tr > th,	.table-bordered > tfoot > tr > th,	.table-bordered > thead > tr > td,	.table-bordered > tbody > tr > td,	.table-bordered > tfoot > tr > td {
	  border: 1px solid #dddddd;
	}
	.table-bordered > thead > tr > th,
	.table-bordered > thead > tr > td {
	  border-bottom-width: 2px;
	}
	.table-striped > tbody > tr:nth-of-type(odd) {
	  background-color: #f9f9f9;
	}
	.table-hover > tbody > tr:hover {
	  background-color: #f5f5f5;
	}
	.table > thead > tr > td.active, 	.table > tbody > tr > td.active, 	.table > tfoot > tr > td.active, 	.table > thead > tr > th.active, 	.table > tbody > tr > th.active, 	.table > tfoot > tr > th.active, 	.table > thead > tr.active > td, 	.table > tbody > tr.active > td, 	.table > tfoot > tr.active > td, 	.table > thead > tr.active > th, 	.table > tbody > tr.active > th,	.table > tfoot > tr.active > th { 
	  background-color: #f5f5f5;
	}
	.table-hover > tbody > tr > td.active:hover, 	.table-hover > tbody > tr > th.active:hover, 	.table-hover > tbody > tr.active:hover > td, 	.table-hover > tbody > tr:hover > .active, 	.table-hover > tbody > tr.active:hover > th {
	  background-color: #e8e8e8;
	}
	.table > thead > tr > td.success, 	.table > tbody > tr > td.success, 	.table > tfoot > tr > td.success, 	.table > thead > tr > th.success, 	.table > tbody > tr > th.success, 	.table > tfoot > tr > th.success, 	.table > thead > tr.success > td, 	.table > tbody > tr.success > td, 	.table > tfoot > tr.success > td, 	.table > thead > tr.success > th, 	.table > tbody > tr.success > th, 	.table > tfoot > tr.success > th {
	  background-color: #dff0d8;
	}
	.table-hover > tbody > tr > td.success:hover, 	.table-hover > tbody > tr > th.success:hover, 	.table-hover > tbody > tr.success:hover > td, 	.table-hover > tbody > tr:hover > .success, 	.table-hover > tbody > tr.success:hover > th {
	  background-color: #d0e9c6;
	}
	.table > thead > tr > td.info, 	.table > tbody > tr > td.info, 	.table > tfoot > tr > td.info, 	.table > thead > tr > th.info, 	.table > tbody > tr > th.info, 	.table > tfoot > tr > th.info, 	.table > thead > tr.info > td, 	.table > tbody > tr.info > td, 	.table > tfoot > tr.info > td, 	.table > thead > tr.info > th, 	.table > tbody > tr.info > th, 	.table > tfoot > tr.info > th {
	  background-color: #d9edf7;
	}
	.table-hover > tbody > tr > td.info:hover, 	.table-hover > tbody > tr > th.info:hover, 	.table-hover > tbody > tr.info:hover > td, 	.table-hover > tbody > tr:hover > .info, .table-hover > tbody > tr.info:hover > th {
	  background-color: #c4e3f3;
	}
	.table > thead > tr > td.warning, 	.table > tbody > tr > td.warning,  	.table > tfoot > tr > td.warning, 	.table > thead > tr > th.warning, 	.table > tbody > tr > th.warning, 	.table > tfoot > tr > th.warning, 	.table > thead > tr.warning > td, 	.table > tbody > tr.warning > td, 	.table > tfoot > tr.warning > td,	.table > thead > tr.warning > th, 	.table > tbody > tr.warning > th, 	.table > tfoot > tr.warning > th {
	  background-color: #fcf8e3;
	}
	.table-hover > tbody > tr > td.warning:hover, .table-hover > tbody > tr > th.warning:hover, .table-hover > tbody > tr.warning:hover > td, .table-hover > tbody > tr:hover > .warning, .table-hover > tbody > tr.warning:hover > th {
	  background-color: #faf2cc;
	}
	.table > thead > tr > td.danger, .table > tbody > tr > td.danger,.table > tfoot > tr > td.danger, .table > thead > tr > th.danger, .table > tbody > tr > th.danger,
	.table > tfoot > tr > th.danger, .table > thead > tr.danger > td, .table > tbody > tr.danger > td, .table > tfoot > tr.danger > td, .table > thead > tr.danger > th,
	.table > tbody > tr.danger > th, .table > tfoot > tr.danger > th {
	  background-color: #f2dede;
	}
	.table-hover > tbody > tr > td.danger:hover, .table-hover > tbody > tr > th.danger:hover, .table-hover > tbody > tr.danger:hover > td, .table-hover > tbody > tr:hover > .danger, .table-hover > tbody > tr.danger:hover > th {
	  background-color: #ebcccc;
	}
	.table-responsive {
	  min-height: .01%;
	  overflow-x: auto;
	}
	.table-bordered th,.table-bordered td{padding:2px 1px 1px 2px !important;}
	.table-bordered td a{ color:#039be5 !important; }
	#presells h3{font-size:1.5em !important;}
	#presells .select-wrapper{background:none;}
	#presells .select-wrapper .caret{fill:#000;}
	
    @media only screen and (min-width: 992px) and (max-width: 1199px){ 
	#presells h3{font-size:1.2em !important;}
	}
    </style>
</head>
<body>
	<div class="box" id="presells">
		<h3 align="center">Import Presell Order Data into Database</h3>
		<br />
		<form method="post" id="import_csv" enctype="multipart/form-data">
			<div class="form-group">
				<label>Select CSV File</label>
				<input type="file" name="csv_file" id="csv_file" required accept=".csv" />
			</div>
			<br />
			<button type="submit" name="import_csv" class="btn btn-info" id="import_csv_btn">Import Presell Data</button>
		</form>
		<br />
		<div id="imported_csv_data"></div>
		<br />
		<br />
        <div class="block" style="text-align:center;">
        	<h3 align="center"><i class="material-icons" style="display:flex; font-size:32px; line-height:15px; margin:0px 0px -15px 15px">autorenew</i> Process Presell Orders</h3>
		    <form method="post" id="process_presell" enctype="multipart/form-data">
                 <div class="input-field inline" style="background:none; width:150px;">
                    <select id="period_reference" name="period_reference">
                          <option value="1">Reference 1</option>
                    </select>
                    <label>Period Reference</label>
                    
                </div>
                <br />
                <button type="submit" name="process_presell" class="btn btn-info" id="process_presell_btn">Bulk Convert to Orders</button>
            </form>    
		<div id="period_ref"></div>          
        </div>	
        <div class="block" style="text-align:center;">
        	<h3 align="center"><i class="material-icons" style="display:flex; font-size:32px; line-height:15px; margin:0px 0px -15px 15px">add_circle</i> Add New Presell Item</h3>
		    <form method="post" id="add_presell" enctype="multipart/form-data">
                 <div class="input-field inline" style="background:none; width:150px;">
                    <select id="period_reference_add" name="period_reference_add">
                          <option value="1">Reference 1</option>
                    </select>
                    <label>Period Reference</label>
                    
                </div>
                <br />
                <button type="submit" name="add_presell" class="btn btn-info" id="add_presell_btn" style="background-color:#039be5;">Add New Presell Item</button>
            </form>    
		<div id="period_ref"></div>          
        </div>		
        <div class="block" style="text-align:center;">
        	<h3 align="center"><i class="material-icons" style="display:flex; font-size:32px; line-height:15px; margin:0px 0px -15px 15px">delete_forever</i> Delete Presell Entries</h3>
		    <form method="post" id="delete_presell" enctype="multipart/form-data">
                 <div class="input-field inline" style="background:none; width:150px;">
                    <select id="period_reference_delete" name="period_reference_delete">
                          <option value="1">Reference 1</option>
                    </select>
                    <label>Period Reference</label>
                    
                </div>
                <br />
                <button type="submit" name="delete_presell" class="btn btn-info" id="delete_presell_btn" style="background-color:#ff3535;">Delete Presell Entries</button>
            </form>    
		<div id="period_ref"></div>          
        </div>		
	</div>
</body>
</html>

<script>
$(document).ready(function(){

	load_data();
	load_ref();

	function load_data()
	{
		$.ajax({
			url:"<?php echo base_url(); ?>presells_import/load_data",
			method:"POST",
			success:function(data)
			{
				$('#imported_csv_data').html(data);
			}
		})
	}
	
	function load_ref()
	{
		$.ajax({
			url:"<?php echo base_url(); ?>presells_import/load_ref",
			method:"POST",
			success:function(data){
              $('#period_reference, #period_reference_add, #period_reference_delete').html(data);
			  $('#period_reference, #period_reference_add, #period_reference_delete').formSelect();
			}
		})
	}

	$('#import_csv').on('submit', function(event){
		event.preventDefault();
		$.ajax({
			url:"<?php echo base_url(); ?>index.php/presells_import/import",
			method:"POST",
			data:new FormData(this),
			contentType:false,
			cache:false,
			processData:false,
			beforeSend:function(){
				$('#import_csv_btn').html('Importing...');
			},
			success:function(data)
			{
				//alert("Import response: " +data);
				$('#import_csv')[0].reset();
				$('#import_csv_btn').attr('disabled', false);
				$('#import_csv_btn').html('Import Done');
				load_data();
	            load_ref();
			}
		})
	});
	
	$('#process_presell').on('submit', function(event){
		event.preventDefault();
		if($("#period_reference").val() != null){
			$.ajax({
				url:"<?php echo base_url(); ?>presells_import/process",
				method:"POST",
				data:new FormData(this),
				contentType:false,
				cache:false,
				processData:false,
				beforeSend:function(){
					$('#process_presell_btn').html('Converting...');
				},
				success:function(data)
				{
					alert("Process response: " + data);
					$('#process_presell')[0].reset();
					$('#process_presell_btn').attr('disabled', false);
					$('#process_presell_btn').html('Conversion Done');
					load_data();
					load_ref();
				}
			})
		}
	});
	
	$('#add_presell').on('submit', function(event){
		event.preventDefault();		
		if($("#period_reference_add").val() != null){
			$(".ui-dialog-buttonpane > div > span:first-child").text("");
			$("#presell_form input[name='period_reference']").val("");
			$("#presell_form input[name='unique_id']").val("");
			$("#presell_form input[name='prod_code']").val("");
			$("#presell_form input[name='prod_code1']").val("");
			$("#price_list").val("999").formSelect();
			$("#presell_form input[name='wholesale']").val("");
			$("#presell_form input[name='retail']").val("");
			$("#presell_form input[name='prod_sell']").val("");
			$("#presell_form input[name='prod_rrp']").val("");
			$("#group_desc").val("Accessories").formSelect();
			$("#presell_form input[name='prod_desc']").val("");
			$("#presell_form input[name='prod_pack_desc']").val("");
			$("#presell_form input[name='shelf_life']").val("");
			$("#presell_form input[name='prod_uos']").val("");
			$("#vat_code").val("A").formSelect();
			$("#presell_form input[name='prod_level3']").val("");
			$("#presell_form input[name='p_size']").val("");
			$("#is_disabled").val("N").formSelect();
			$("#promo").val("N").formSelect();
			$("#van").val("None").formSelect();
			$("#presell_form input[name='g_qty']").val("");
			$("#presell_form input[name='g_min']").val("");
			$("#presell_form input[name='g_max']").val("");
			$("#presell_form input[name='s_qty']").val("");
			$("#presell_form input[name='s_min']").val("");
			$("#presell_form input[name='s_max']").val("");
			$("#presell_form input[name='m_qty']").val("");
			$("#presell_form input[name='m_min']").val("");
			$("#presell_form input[name='m_max']").val("");
			$("#presell_form input[name='l_qty']").val("");
			$("#presell_form input[name='l_min']").val("");
			$("#presell_form input[name='l_max']").val("");
			$("#presell_form input[name='e_qty']").val("");
			$("#presell_form input[name='e_min']").val("");
			$("#presell_form input[name='e_max']").val("");
			popup_dialog('',1);
		}
	});
	$('#delete_presell').on('submit', function(event){
		event.preventDefault();
		if($("#period_reference_delete").val() != null){
			$.ajax({
				url:"<?php echo base_url(); ?>index.php/presells_import/delete_entries",
				method:"POST",
				data:new FormData(this),
				contentType:false,
				cache:false,
				processData:false,
				beforeSend:function(){
					$('#delete_presell_btn').html('Deleting...');
				},
				success:function(data)
				{
					//alert("Delete response: " + data);
					$('#delete_presell')[0].reset();
					$('#delete_presell_btn').attr('disabled', false);
					$('#delete_presell_btn').html('Presell Entries Deleted');
					load_data();
					load_ref();
				}
			})
		}
	});
	
});
</script>