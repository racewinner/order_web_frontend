<?php $this->load->view("partial/header"); ?>
<!--<div id="content_area_wrapper" >
<div id="content_area" >-->
<style type="text/css">
	.ui-autocomplete{ max-height:300px; overflow-y:auto; overflow-x:hidden; font-size:12px; }
    html .ui-autocomplete{ height:300px; font-size:12px; }
    html {overflow: auto;}
</style>
<script type="text/javascript">
$(document).ready(function(){

    //set pagination
    var t = $('#total_page').val();
	var p = $('#curd_page').val();
	if(p==t){ $("#next").removeClass().addClass("disabled"); }
	if(p==1){ $("#prev").removeClass().addClass("disabled"); }
	
	$( "ul.pagination li" ).each(function( index, element ) {
	  if(index > t){ return false; }
      if($( this ).is( "#p"+index )){ 
	      if(index==p){ $( this ).removeClass().addClass("active"); }
         }
     });
	
	
	$( "#dialog_form" ).dialog
	(
		{
			autoOpen:false ,
			height: 475 ,
			width: Math.min(520, $(window).width() * .95),
			modal: true ,
			buttons:
			{
				"Go": function()
				{
					var bValid = true;
					var person_id = $('#person_id');
					var username = $('#username') , email = $( "#email" ) , password = $( "#password" ) , repeat_password = $('#repeat_password');
					var price_list005, price_list007 , price_list008 , price_list009 , price_list010 , price_list011 , price_list012 , price_list999;
					var msg = $('#error_message_box');
					bValid = bValid && check_empty_field(username, "<? echo $this->lang->line('employees_username'); ?>");
			        bValid = bValid && check_empty_field(email, "<? echo $this->lang->line('common_email'); ?>");
					if(person_id.val() == '0')
					{
			        	bValid = bValid && check_empty_field(password, "<? echo $this->lang->line('employees_password'); ?>");
			        	bValid = bValid && check_empty_field(repeat_password, "<? echo $this->lang->line('employees_repeat_password'); ?>");
					}

					if(password.val() != repeat_password.val())
					{
						msg.html("<li><? echo $this->lang->line('employees_password_must_match'); ?></li>");
						bValid = false;
					}


					if(bValid)
					{
						if($('#price_list005').prop('checked')) price_list005 = '1';
						else price_list005 = '';
						if($('#price_list007').prop('checked')) price_list007 = '1';
						else price_list007 = '';
						if($('#price_list008').prop('checked')) price_list008 = '1';
						else price_list008 = '';
						if($('#price_list009').prop('checked')) price_list009 = '1';
						else price_list009 = '';
						if($('#price_list010').prop('checked')) price_list010 = '1';
						else price_list010 = '';
						if($('#price_list011').prop('checked')) price_list011 = '1';
						else price_list011 = '';
						if($('#price_list012').prop('checked')) price_list012 = '1';
						else price_list012 = '';
						if($('#price_list999').prop('checked')) price_list999 = '1';
						else price_list999 = '';
					    $.ajax({
					        type : "POST"
					        , async : true
					        , url : "<?php echo site_url("$controller_name/save");?>"
					        , dataType : "json"
					        , timeout : 30000
					        , cache : false
					        , data : "person_id=" + person_id.val() +
					        			"&username=" + username.val() +
					        			"&email=" + email.val() +
					        			"&password=" + password.val() +
					        			"&price_list005=" + price_list005 +
					        			"&price_list007=" + price_list007 +
					        			"&price_list008=" + price_list008 +
					        			"&price_list009=" + price_list009 +
					        			"&price_list010=" + price_list010 +
					        			"&price_list011=" + price_list011 +
					        			"&price_list012=" + price_list012 +
					        			"&price_list999=" + price_list999
					        , error : function(request, status, error) {
						         //alert("failed : " + request.status + "\r\nmessage : " + request.reponseText);
						    }
					        , success : function(response, status, request) {
						         //alert("success : " + request.status + "\r\nmessage : " + request.reponseText);
					        }
					    });
			        	$(this).dialog('close');
			        	post_person_form_submit();

					}


				}
			}

		}
	);
    /*
	enable_search1('<?php echo site_url("$controller_name/suggest")?>','<?php echo $this->lang->line("common_confirm_search")?>');
	*/
});

function popup_dialog(person_id)
{
	if(person_id == 0)
	{
		$('#username').val('');
		$('#email').val('');
		$('#password').val('');
		$('#repeat_password').val('');
		$('#price_list005').prop('checked' , false);
		$('#price_list007').prop('checked' , false);
		$('#price_list008').prop('checked' , false);
		$('#price_list009').prop('checked' , false);
		$('#price_list010').prop('checked' , false);
		$('#price_list011').prop('checked' , false);
		$('#price_list012').prop('checked' , false);
		$('#price_list999').prop('checked' , true);
		$('#error_message_box').html('');
		$('#person_id').val('0');
	}
	else
	{
	    $.ajax({
	        type : "POST"
	        , async : true
	        , url : "<?php echo site_url("$controller_name/get_user_info");?>"
	        , dataType : "json"
	        , timeout : 30000
	        , cache : false
	        , data : "person_id=" + person_id
	        , error : function(request, status, error) {
		         alert("get user info : " + request.status + "\r\nmessage : " + request.reponseText);
	        }
	        , success : function(response, status, request) {

	    		$('#username').val(response[0]);
	    		$('#email').val(response[1]);
	    		$('#label_password').attr('class' , 'wide');
	    		$('#label_repeat_password').attr('class' , 'wide');
	    		$('#repeat_password').val('');
	    		$('#password').val('');
	    		$('#price_list005').prop('checked' , Number(response[3]));
	    		$('#price_list007').prop('checked' , Number(response[4]));
	    		$('#price_list008').prop('checked' , Number(response[5]));
	    		$('#price_list009').prop('checked' , Number(response[6]));
	    		$('#price_list010').prop('checked' , Number(response[7]));
	    		$('#price_list011').prop('checked' , Number(response[8]));
	    		$('#price_list012').prop('checked' , Number(response[9]));
	    		$('#price_list999').prop('checked' , Number(response[10]));
	    		$('#error_message_box').html('');
	    		$('#person_id').val(person_id);
	        }
	    });
	}
	$('#dialog_form').dialog('open');
}

function check_empty_field(link , label)
{
	var msg = $('#error_message_box');
	if(link.val().length < 1)
	{
		msg.html("<li>The " + label + " is a required field.</li>");
		return false;
	}
	else return true;
}

function post_person_form_submit()
{
	var nCurrentSortKey = $('#sort_key').val();
	var search_mode = $('#search_mode').val();
	var search = $('#search').val();
	var per_page = $('#per_page').val();
	var uri_segment;
	var location_site = "<?php echo site_url("$controller_name/index");?>";
	var page_num = $('#curd_page').val();
	location_site = location_site + "/" + search_mode + "/";

	uri_segment = (Number(page_num) - 1) * Number(per_page);
	if(search_mode == 'default')
		location_site = location_site + nCurrentSortKey + "/" + per_page + "/" + uri_segment;
	else if(search_mode == 'search')
	{
		if(search == '') search = "12345678901234567890";
		location_site = location_site + search + "/" + nCurrentSortKey + "/" + per_page + "/" + uri_segment;
	}
	location.replace(location_site);

}

function select_per_page(url)
{

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
	else if(search_mode == 'search')
	{
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

	if(search_mode == 'default')
		location_site = location_site + sort_key + "/" + per_page + "/" + uri_segment;
	else if(search_mode == 'search')
	{
		if(search == '') search = "12345678901234567890";
		location_site = location_site + search + "/" + sort_key + "/" + per_page + "/" + uri_segment;
	}
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
	var total_page = $('#last_page_number').text();
	var location_site = url;

	if(curd_page == total_page) return;
	location_site = location_site + "/" + search_mode + "/";
	uri_segment = Number(curd_page) * Number(per_page);
	if(search_mode == 'default')
		location_site = location_site + sort_key + "/" + per_page + "/" + uri_segment;
	else if(search_mode == 'search')
	{
		if(search == '') search = "12345678901234567890";
		location_site = location_site + search + "/" + sort_key + "/" + per_page + "/" + uri_segment;
	}
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

	if(search_mode == 'default')
		location_site = location_site + sort_key + "/" + per_page + "/" + uri_segment;
	else if(search_mode == 'search')
	{
		if(search == '') search = "12345678901234567890";
		location_site = location_site + search + "/" + sort_key + "/" + per_page + "/" + uri_segment;
	}

	location.replace(location_site);
}

function set_direct_page(e , url)
{
	var result;
	if(window.event) result = window.event.keyCode;
	else if(e) result = e.which;
	if(result == 13)
	{
		var page_num = $('#curd_page').val();
		var total_page = $('#last_page_number').text();
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
		var search = $('#search').val();
		var per_page = $('#per_page').val();
		var uri_segment;
		category_id = Number(category_id);
		var location_site = url;
		location_site = location_site + "/" + search_mode + "/";
		uri_segment = ( Math.round(Number(page_num)) - 1) * Number(per_page);
		if(search_mode == 'default') location_site = location_site + sort_key + "/" + per_page + "/" + uri_segment;
		else if(search_mode == 'search')
		{
			if(search == '') search = "12345678901234567890";
			location_site = location_site + search + "/" + sort_key + "/" + per_page + "/" + uri_segment;
		}
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

			if(nIndex > 1) return;
			if($(this).attr('class') != "")
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
				if($(this).attr('class') != "")
					$(this).attr('class' , 'header');
			}
		});
	$('#sort_key').val(nCurrentSortKey);



    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo site_url("$controller_name/sort_user");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "sort_key=" + nCurrentSortKey + "&search=" + search + "&search_mode=" + search_mode + "&per_page=" + per_page
        , error : function(request, status, error) {

         alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
            var strArray = response.split('********************');
            $('#customer_pagination_div_left_div').html(strArray[0]);
            $('#sortable_table tbody').html(strArray[1]);
    		tb_init('#sortable_table a.thickbox');
//    		update_sortable_table();

        }
    });

	return;
}

</script>

<div id="order_total_div" style="padding-bottom:30px;">
	<div class="shopping__cart-page-header text-center">
        <h2><?php echo $this->lang->line('common_list_of').' '.$this->lang->line('module_'.$controller_name); ?></h2>
        <p class="desc lead">Manage Managers</p>
	</div>	
</div>
<div id="product_search_div" style="display:none;"><?php echo form_open("$controller_name/search",array('id'=>'search_form')); ?>
	<input type="text" name ='search' id='search' class='cell1' style="width:200px; background:#fff !important;" onclick="this.select();"/>
    <label for="product_code" class="">&nbsp;&nbsp;&nbsp;</label>
	<input type="hidden" name="sort_key" id="sort_key" value="<?php echo $sort_key;?>">
	<input type="hidden" name="search_mode" id="search_mode" value="<?php echo $search_mode;?>">
	<input type="hidden" name="uri_segment" id="uri_segment" value="<?php echo $uri_segment;?>">
    <input type="button" value="<?php echo $this->lang->line('common_search'); ?>" style="background:#000; padding:7px; width:80px; height:35px; border:#000 2px solid; color:#fff !important; text-transform:uppercase;" >
</form>
</div>
<div id="title_bar">
	<div id="new_button" onclick="popup_dialog();">
		<div>
			<span><?php echo $this->lang->line($controller_name.'_new'); ?></span>
		</div>
	</div>
</div>
<div id="table_holder" class="table_holder">
<?php echo $manage_table; ?>
</div>
<div id="actions">
  <div class="input-field col s12">
    <select name='per_page' id='per_page' onchange="select_per_page('<? echo site_url("$controller_name/index/");?>');">
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
     <li class="waves-effect" id="prev"><a href="javascript:void();" onclick="prev_page('<? echo site_url("$controller_name/index/");?>');"><i class="material-icons">chevron_left</i></a></li>
     <!--<li class="active" id="p1"><a href="javascript:void()" onclick="first_page('<? echo site_url("$controller_name/index/");?>');">1</a></li>-->
     <?php $t = $total_page; for($i=1; $i<=$t; $i++){ ?>
	 <li class="waves-effect" id="p<?php echo $i; ?>">
         <a href="javascript:void()" onclick="goto_page(<?php echo $i; ?>,'<? echo site_url("$controller_name/index/");?>');"><?php echo $i; ?></a>
     </li> 
	 <?php }?>
     <li class="waves-effect" id="next"><a href="javascript:void();" onclick="next_page('<? echo site_url("$controller_name/index/");?>');"><i class="material-icons">chevron_right</i></a></li>
  </ul>
  <br style="clear:both;">
</div>

<?php $this->load->view("partial/footer"); ?>
