<?php if(!empty($categories)) { 
    $dwell_time = 1000;
    foreach($categories as $category) {
        if($dwell_time < $category['dwell_time'] * 1000) {
            $dwell_time = $category['dwell_time'] * 1000;
        }
    }
?>
<div id="<?= $carousel_id ?>_pc" class="position-relative d-md-block banners-carousel categories-carousel">
    <?php if(count($categories) > 1) { ?>
        <div class="px-1 d-flex align-items-center" style="position:absolute; left:0px; top: 0px; bottom: 0px; z-index:10">
            <div 
                class="d-flex justify-content-center align-items-center" 
                style="border-radius:50%; padding: 5px; background:white; width:30px; height: 30px;"
            >
                <a class="carousel-left"><img src="/images/icons/line-angle-left.svg" style="width:10px;" /></a>
            </div>
        </div>
    <?php } ?>

    <div class="carousel carousel-slider center">
        <?php foreach($categories as $category) { 
            $media_url = '';
            if(!empty($category['media_web']['url'])) {
                $media_url = $category['media_web']['url'];
            } else if(!empty($category['media_mobile']['url'])) {
                $media_url = $category['media_mobile']['url'];
            }
        ?>
            <div 
                class="carousel-item w-100 d-flex flex-column cms-content" 
                data-id="<?= $category['id'] ?>"
                data-category-id="<?= !empty($category['sub_cat_id']) ? $category['sub_cat_id'] : $category['top_cat_id'] ?>"
            >
                <?php if(!empty($category['ribbon']['content'])) { ?>
                    <div class="p-1" 
                        style="background: <?= $category['ribbon']['bg_color'] ?>; color: <?= $category['ribbon']['txt_color'] ?>"
                    >
                        <?= $category['ribbon']['content'] ?>
                    </div>
                <?php } ?>
                <div class="flex-fluid d-flex align-items-center w-100" style="background:black;">
                    <div class="d-flex justify-content-center align-items-center w-100">
                        <img src="<?= $media_url ?>" style="width:100%;" />
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php if(count($categories) > 1) { ?>
        <div class="px-1 d-flex align-items-center" style="position:absolute; right:0px; top: 0px; bottom: 0px; z-index:10">
            <div 
                class="d-flex justify-content-center align-items-center" 
                style="border-radius:50%; padding: 5px; background:white; width:30px; height: 30px;"
            >
                <a class="carousel-right"><img src="/images/icons/line-angle-right.svg" style="width:10px;" /></a>
            </div>
        </div>
    <?php } ?>
</div>

<div id="<?= $carousel_id ?>_mobile" class="position-relative banners-carousel d-md-none">
    <?php if(count($categories) > 1) { ?>
        <div class="px-1 d-flex align-items-center" style="position:absolute; left:0px; top: 0px; bottom: 0px; z-index:10">
            <div 
                class="d-flex justify-content-center align-items-center" 
                style="border-radius:50%; padding: 5px; background:white; width:30px; height: 30px;"
            >
                <a class="carousel-left"><img src="/images/icons/line-angle-left.svg" style="width:10px;" /></a>
            </div>
        </div>
    <?php } ?>

    <div class="carousel carousel-slider center">
        <?php foreach($categories as $category) { 
            $media_url = '';
            if(!empty($category['media_mobile']['url'])) {
                $media_url = $category['media_mobile']['url'];
            } else if(!empty($category['media_web']['url'])) {
                $media_url = $category['media_web']['url'];
            }
        ?>
            <div 
                class="carousel-item w-100 h-100 d-flex flex-column cms-content" 
                data-id="<?= $category['id'] ?>"
                data-link="<?= $category['link_url'] ?>"
                data-prodcodes="<?= $category['prod_codes'] ?>"
            >
                <?php if(!empty($category['ribbon']['content'])) { ?>
                    <div class="p-1" 
                        style="background: <?= $category['ribbon']['bg_color'] ?>; color: <?= $category['ribbon']['txt_color'] ?>"
                    >
                        <?= $category['ribbon']['content'] ?>
                    </div>
                <?php } ?>
                <div class="flex-fluid d-flex align-items-center w-100 h-100" style="background:black;">
                    <div class="d-flex justify-content-center align-items-center w-100 h-100">
                        <img src="<?= $media_url ?>" style="max-width:100%; max-height:100%;" />
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php if(count($categories) > 1) { ?>
        <div class="px-1 d-flex align-items-center" style="position:absolute; right:0px; top: 0px; bottom: 0px; z-index:10">
            <div 
                class="d-flex justify-content-center align-items-center" 
                style="border-radius:50%; padding: 5px; background:white; width:30px; height: 30px;"
            >
                <a class="carousel-right"><img src="/images/icons/line-angle-right.svg" style="width:10px;" /></a>
            </div>
        </div>
    <?php } ?>
</div>
<?php } ?>

<script>
    $(document).ready(function() {
        const autoPlay = <?= (count($categories) > 1) ? 'true' : 'false' ?>;
        BannerCarouselManager.init(
            document.getElementById("<?= $carousel_id ?>_pc"),
            {
                autoPlay,
                indicators: true,
                dwell_time: <?= $dwell_time ?>,
            }
        );
        BannerCarouselManager.init(
            document.getElementById("<?= $carousel_id ?>_mobile"),
            {
                autoPlay,
                indicators: true,
                dwell_time: <?= $dwell_time ?>,
            }
        );
    })
</script>