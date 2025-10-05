<div class="fullwidth-container">
     <div class="inner-wrapper">
        <div class="featured d-md-block" id="sponsors">
            <?php foreach($bottom_banners as $bottom_banner) { 
                $media_url = '';
                if(!empty($bottom_banner['media_web']['url'])) {
                    $media_url = $bottom_banner['media_web']['url'];
                } else if(!empty($bottom_banner['media_mobile']['url'])) {
                    $media_url = $bottom_banner['media_mobile']['url'];
                }
            ?>
                <div 
                    class="block b sh cms-content" 
                    style="background-image:url('<?= $media_url ?>')"
                    data-id="<?= $bottom_banner['id'] ?>"
                    data-link="<?= $bottom_banner['link_url'] ?>"
                    data-prodcodes="<?= $bottom_banner['prod_codes'] ?>"
                >
                </div>
            <?php } ?>
            <br style="clear:both;">
        </div>
        
        <div class="featured d-md-none" id="sponsors">
            <?php foreach($bottom_banners as $bottom_banner) { 
                $media_url = '';
                if(!empty($bottom_banner['media_mobile']['url'])) {
                    $media_url = $bottom_banner['media_mobile']['url'];
                } else if(!empty($bottom_banner['media_web']['url'])) {
                    $media_url = $bottom_banner['media_web']['url'];
                }
            ?>
                <div 
                    class="block b sh cms-content" 
                    style="background-image:url('<?= $media_url ?>')"
                    data-id="<?= $bottom_banner['id'] ?>"
                    data-link="<?= $bottom_banner['link_url'] ?>"
                    data-prodcodes="<?= $bottom_banner['prod_codes'] ?>"
                >
                </div>
            <?php } ?>
            <br style="clear:both;">
        </div>
     </div>
</div>