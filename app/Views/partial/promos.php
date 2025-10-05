<?php echo view("partial/header"); ?>
<div id="product_search_div" style="margin-bottom:10px; width:100%">
    <img src="http://staging.uniteduk.co.uk/images/spinner_small.gif" alt="spinner" id="spinner1">
    <form action="http://staging.uniteduk.co.uk/index.php/products/search" method="post" accept-charset="utf-8" id="search_form" style="font-family:Arial;">			<div class="large_view">
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
	<div class="shopping__cart-page-header text-center">
        <h2><?php echo $this->lang->line('module_'.$controller_name); ?></h2>
	</div>	
</div>
<div class="featured-products">
     <div id="f_links">
     <ul>
         <li><a href="javascript:void();" onclick="load_products('new')">DAY-TODAY & USAVE</a></li>
         <li><a href="javascript:void();" onclick="load_products('sale')" class="active" >CASH & CARRY</a></li>
     </ul>
     </div>
     <div id="featured" class="f_container"></div>

</div>

</div></div>
<?php $this->load->view("partial/footer"); ?>

