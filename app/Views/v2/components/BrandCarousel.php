<?php
    $breakpoints = [
        "320" => ["slidesPerView" => 2, "spaceBetween" => 8],
        "470" => ["slidesPerView" => 3, "spaceBetween" => 10],
        "620" => ["slidesPerView" => 4, "spaceBetween" => 10],
        "770" => ["slidesPerView" => 5, "spaceBetween" => 10],
        "930" => ["slidesPerView" => 6, "spaceBetween" => 10],
        "1100" => ["slidesPerView" => 7, "spaceBetween" => 10],
        "1280" => ["slidesPerView" => 8, "spaceBetween" => 10],
        "1450" => ["slidesPerView" => 9, "spaceBetween" => 10],
        "1650" => ["slidesPerView" => 10, "spaceBetween" => 10],
        "1850" => ["slidesPerView" => 11, "spaceBetween" => 10],
    ];
?>

<div class="brands-carousel order-cms" id="<?= $cms_id ?>"
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
            data-slidesperview = "2"
            data-breakpoints='<?= json_encode($breakpoints) ?>'
        > 
            <div class="swiper-wrapper brands"> 
                <?php foreach($data['items'] as $index => $item) {
                    $media_url = $is_mobile ? $item['media_mobile_url'] : $item['media_web_url'];
                ?>
                    <div class="swiper-slide p-2 one-brand cms-content"
                        data-link="<?= $item['link_url'] ?>"
                        data-cms-itm-id="<?= $item['id'] ?>"
                        data-cms-itm-tp="<?= $item['type'] ?>"
                        data-cms-itm-nm="<?= $item['data']['brand'] ?>"
                        data-prodcodes="<?= $item['prod_codes'] ?>"
                    >
                        <img src="<?= $media_url ?>" />
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