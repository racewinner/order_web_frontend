<?php if(!empty($products_carousel)) { 
    $products_count = count($products_carousel['products']);
    $carousel_body_height = empty($user_info) ? 420:540;
?>
<div class="my-4" style="background:white; border-radius: 5px;">
    <?php if(!empty($products_carousel['media_web']['url'])) { ?>
        <div class="w-100">
            <img src="<?= $products_carousel['media_web']['url'] ?>" class="w-100" />
        </div>
    <?php } ?>

    <?php if(!empty($products_carousel['ribbon']['content'])) { ?>
        <div class="p-1" 
            style="background: <?= $products_carousel['ribbon']['bg_color'] ?>; color: <?= $products_carousel['ribbon']['txt_color'] ?>"
        >
            <?= $products_carousel['ribbon']['content'] ?>
        </div>
    <?php } ?>

    <div id="<?= $carousel_id ?>" class="d-flex align-items-center ">
        <div class="px-1">
            <a class="carousel-left"><img src="/images/icons/line-angle-left.svg" style="width:10px;" /></a>
        </div>
        <div class="flex-fluid carousel carousel-slide center grid-wrapper f_container table_holder" style="height: <?= $carousel_body_height ?>px !important;">
            <?php foreach($products_carousel['products'] as $product) { ?>
                <div class="carousel-item width-fit-content height-fit-content">
                    <?= $product->html ?>
                </div>
            <?php } ?>
        </div>
        <div class="px-1">
            <a class="carousel-right"><img src="/images/icons/line-angle-right.svg" style="width:10px;" /></a>
        </div>
    </div>
</div>
<?php } ?>

<script>
    $(document).ready(function() {
        const autoPlay = <?= (!empty($products_carousel['products']) && count($products_carousel['products']) > 1) ? 'true' : 'false' ?>;
        OrderCarouselManager.init(
            document.getElementById("<?= $carousel_id ?>"), 
            {
                autoPlay,
                dwell_time: <?= $products_carousel['dwell_time'] ?> * 1000
            }
        );
    })
</script>