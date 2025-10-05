<?php
    $breakpoints = [
        "450" => ["slidesPerView" => 2, "spaceBetween" => 8],
        "650" => ["slidesPerView" => 3, "spaceBetween" => 4],
        "1220" => ["slidesPerView" => 4, "spaceBetween" => 8],
        "1530" => ["slidesPerView" => 5, "spaceBetween" => 8],
        "1850" => ["slidesPerView" => 6, "spaceBetween" => 8],
        "2250" => ["slidesPerView" => 7, "spaceBetween" => 8],
    ];
?>

<div 
    class="products-carousel order-cms" 
    id="<?= $cms_id ?>"
    style="background-color:<?= $data['template']['background']['bg_color'] ?? 'transparent' ?>"
>
    <?php if(!empty($data['ribbon']['content'])) { ?>
    <h5 class="p-2 px-4" 
        style="color: <?= $data['ribbon']['txt_color'] ?>; background-color: <?= $data['ribbon']['bg_color'] ?>; text-align:<?= $data['ribbon']['align'] ?? 'left' ?>"
    >
        <?= $data['ribbon']['content'] ?>
    </h5>
    <?php } ?>

    <div class="p-3 pt-0">
        <div class="swiper swiper-theme" 
            data-autoplay-delay="<?= $data['dwell_time'] * 1000 ?>"
            data-breakpoints='<?= json_encode($breakpoints) ?>'
        > 
            <div class="swiper-wrapper"> 
                <?php foreach($data['products'] as $product) { ?>
                    <div class="swiper-slide p-2">
                        <?= view('v2/components/Product', ['product' => $product]) ?>
                    </div>
                <?php } ?>
            </div>
            
            <div class="swiper-button-prev">
                <i class="bi bi-arrow-left"></i>
            </div> 
            <div class="swiper-button-next">
                <i class="bi bi-arrow-right"></i>
            </div> 
        </div> 
    </div>
</div>