<div class="brands d-md-block">
    <h4>Our Brands</h4>
    <div class="p_container">
        <?php foreach($brands as $brand) { 
            $media_url = '';
            if(!empty($brand['logo_web']['url'])) {
                $media_url = $brand['logo_web']['url'];
            } else if(!empty($brand['logo_mobile']['url'])) {
                $media_url = $brand['logo_mobile']['url'];
            }
        ?>
            <div class="p_item">
                <a class="partner-brand" data-brand="<?= $brand['brand'] ?>">
                    <img src="<?= $media_url ?>">
                </a>
            </div>    
        <?php } ?>
    </div>
</div>
<div class="brands d-md-none">
    <h4>Our Brands</h4>
    <div class="p_container">
        <?php foreach($brands as $brand) { 
            $media_url = '';
            if(!empty($brand['media_mobile']['url'])) {
                $media_url = $brand['media_mobile']['url'];
            } else if(!empty($brand['media_web']['url'])) {
                $media_url = $brand['media_web']['url'];
            }
        ?>
            <div class="p_item">
                <a class="partner-brand" data-brand="<?= $brand['brand'] ?>">
                    <img src="<?= $media_url ?>">
                </a>
            </div>    
        <?php } ?>
    </div>
</div>
