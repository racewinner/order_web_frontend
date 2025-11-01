<?php echo view("partial/header"); ?>
<script type="text/javascript">
$(document).ready(function()
{
	$('#phone_number').focus();
});

function send_message()
{
	var phone_number = $('#phone_number').val();
	var msg = $('#msg').val();

	if(phone_number == '')
	{
		$('#phone_number').focus();
   	    alert("Phone number is required.");
		return;
	}

	if((msg == ''))
	{
		$('#msg').focus();
    	alert("You must write a message.");
		return;
	}

    $.ajax({
        type : "POST"
        , async : true
        , url : "<?php echo base_url("$controller_name/send_message");?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : "phone_number=" + phone_number + "&msg=" + msg
        , error : function (xhr, status, error) {
            if (xhr.status == 401) {
                window.location.href = '/login'; return;
            } else {
				console.log("An error occured: " + xhr.status + " " + xhr.statusText);
	    	}},
        , success : function(response, status, request) {

			if(response == -1)
				alert("Message sending FAILED.");
			else
				alert("Your message has been sent.\n A copy has been sent to your email address.");
                window.location.reload();

        }
    });
}

</script>
<div id="product_search_div" style="margin-bottom:-20px; width:100%; display:none">
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
        <input type="hidden" name="current_id" id="current_id" value="0">
        <input type="hidden" id="refresh" value="no">
        <input type="button" value="Search" style="background:#000; padding:7px; width:80px; height:35px; border:#000 2px solid; color:#fff !important; text-transform:uppercase;" onclick="search_query();">
    </form>
</div>
<div id="order_total_div">
	<div>
        <h2>Contact Us</h2>
        <p class="desc">Please enter details below</span></p>
	</div>	
</div>
<div class="contact-form">
	<div class="vc_column-inner ">
		<div class="wpb_wrapper">
			<div class="row">
                <div class="col1">
                    <label>Account Number:</label><br>
                    <input type="text" name="user-name" value="<?php echo $user_info->username;?>" style="width:100%; height:40px; padding-left:13px;" readonly="readonly">
                </div>
                <div class="col1a"></div>
                <div class="col1">
                    <label>Email Address:</label><br>
                    <input type="text" name="e-mail" value="<?php echo $user_info->email;?>" style="width:100%; height:40px; padding-left:13px;" readonly="readonly">
                </div>
                <div class="col1">	
                    <label>Phone:</label><br>
                    <span class="wpcf7-form-control-wrap your-email"><input type="text" name="phone_number" id="phone_number" class="product_search_cell" style="width:100%; height:40px; padding-left:13px;"></span>
                </div>
                <br style="clear:both;">
                <div class="col-sm-12">
                <div class="col2">
                    <label>Message:</label><br>
                    <span class="wpcf7-form-control-wrap message">
                    <?php echo form_textarea(array('name' => 'msg' , 'id' => 'msg' , 'value'=> '' , 'rows' => '10', 'cols' => '40' , 'style' => 'border:1px solid #CCCCCC; padding-left:13px;')
);?></span>
                </div>
                <div class="col-sm-12">
                   <div class="button" onclick="send_message();">Send</div>
                </div>
			</div>
		</div>
	</div>
</div>
</div>

<div class="map">
     <iframe width="100%" height="200" frameborder="0" src="https://www.google.com/maps/embed/v1/place?q=United+Wholesale+%28Scotland%29+LTD%2C110+Easter+Queenslie+Road%2CGlasgow%2CG33+4UL&amp;key=AIzaSyCq4vWNv6eCGe2uvhPRGWQlv80IQp8dwTE" class="contact-map"></iframe>
</div>

<?php echo view("partial/footer"); ?>

