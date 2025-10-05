<?php  
echo view("partial/header"); 

foreach($cms as $index => $one) {
    switch($one['type']) {
        case CmsType['home_banner']:
            echo view("partial/banners_carousel", ['banners' => $one['data'], 'carousel_id' => 'home_banners_carousel_' . $index]);
            break;
        case CmsType['category_carousel']:
            echo view("partial/categories_carousel", ['categories' => $one['data'], 'carousel_id' => 'home_categories_carousel_' . $index]);
            break;
        case CmsType['products_carousel']:
            foreach($one['data'] as $carousel) {
                echo view("partial/products_carousel", ['products_carousel' => $carousel, 'carousel_id' => 'products_carousel_' . $carousel['id']]);
            }
            break;
        case CmsType['brochure']:
            echo view("partial/brochures", ['brochures' => $one['data']]);
            break;
        case CmsType['brand']:
            echo view("partial/brands", ['brands' => $one['data']]);
            break;
        case CmsType['bottom_banner']:
            echo view("partial/bottom_banners", ['bottom_banners' => $one['data']]);
            break;        
    }
}
?>

<div class="fullwidth-container">
     <div class="inner-wrapper">
          <h4>Frequently Asked Questions</h4>
          <ul class="collapsible">
              <li>
                  <div class="collapsible-header"><i class="material-icons right">arrow_downward</i>Do I need an account to place my order?</div>
                  <div class="collapsible-body"><span>Yes, if you don't have one - you must get in touch with your local UWS Depot manager to set an account up.</span></div>
              </li>
              <li>
              	<div class="collapsible-header"><i class="material-icons right">arrow_downward</i>How often can I get a delivery to my store?</div>
              	<div class="collapsible-body"><span>Fresh deliveries available two/three times per week depending on store location (Tuesday/Thursday/Saturday and Wednesday/Friday).</span></div>
              </li>
              <li>
              	<div class="collapsible-header"><i class="material-icons right">arrow_downward</i>How is my delivery being made to me?</div>
              	<div class="collapsible-body"><span>Deliveries coming from one of our multi temp vehicles.</span></div>
              </li>
              <li>
                  <div class="collapsible-header"><i class="material-icons right">arrow_downward</i>How much does delivery cost?</div>
                  <div class="collapsible-body"><span>Free delivery (little & often approach).</span></div>
              </li>
              <li>
              	<div class="collapsible-header"><i class="material-icons right">arrow_downward</i>How much lead time do you require?</div>
              	<div class="collapsible-body"><span>48 hour lead time for orders.</span></div>
              </li>
              <li>
              	<div class="collapsible-header"><i class="material-icons right">arrow_downward</i>What is the minimum amount i must order?</div>
              	<div class="collapsible-body"><span>Minimum order of £100 made up via ambient.</span></div>
              </li>
          </ul>                    
     </div>
</div>

</div></div>
<?php echo view("partial/footer"); ?>

<script>
    var carousel_instance = null;

    function inc_quantity(mode, prod_id, prod_code, prod_desc) {
        cart_inc_quantity(mode, prod_id, prod_code, 0, prod_desc);
    }
    function edit_quantity(prod_id, prod_code) {
        cart_edit_quantity(prod_id, prod_code);
    }
    function change_quantity(prod_id, prod_code, e) {
        cart_change_quantity(prod_id, prod_code, e);
    }

    $(document).ready(function() {
        $(document).on('click', "a.partner-brand", function(e) {
            let brand = $(e.target).closest("a.partner-brand").data('brand');
            let filter_brands = encodeURIComponent(JSON.stringify([brand]));
            let location_site = "products/index?";
            location_site += "&category_id=0";
            location_site += "&offset=0";
            location_site += "&per_page=30";
            location_site += "&view_mode=grid";
            location_site += "&mobile=" + (isMobile() ? 1 : 0);
            location_site += "&filter_brands=" + filter_brands;
            window.location.href = location_site;
        })

        $(document).on('click', ".categories-carousel .carousel-item.cms-content", function(e) {
            const category_id = $(e.target).closest(".carousel-item.cms-content").data('category-id');
            let location_site = "products/index?";
            location_site += "search_mode=default";
            location_site += "&sort_key=3";
            location_site += `&category_id=${category_id}`;
            location_site += "&mobile=" + (isMobile() ? 1 : 0);
            location_site += "&offset=0&per_page=30";
            location_site += "&view_mode=grid";
            window.location.href = location_site;
        })
    })
    
</script>

