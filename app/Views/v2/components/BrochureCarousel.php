
<?php
    $breakpoints = [
        "420" => ["slidesPerView" => 2, "spaceBetween" => 8],
        "650" => ["slidesPerView" => 3, "spaceBetween" => 10],
        "1010" => ["slidesPerView" => 4, "spaceBetween" => 10],
        "1270" => ["slidesPerView" => 5, "spaceBetween" => 10],
        "1830" => ["slidesPerView" => 6, "spaceBetween" => 10],
        "2150" => ["slidesPerView" => 7, "spaceBetween" => 10],
    ];
?>

<div class="brochure-carousel order-cms" id="<?= $cms_id ?>"
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
        <div class="swiper swiper-theme w-100" 
            data-autoplay-delay="<?= $data['dwell_time'] * 1000 ?>"
            data-breakpoints='<?= json_encode($breakpoints) ?>'
        >
            <div class="swiper-wrapper"> 
                <?php foreach($data['items'] as $index => $item) {
                    $media_url = $is_mobile ? $item['media_mobile_url'] : $item['media_web_url'];
                    $ribbon_lines = explode(',', $item['ribbon']['content']);
                ?>
                    <div class="swiper-slide cms-content"
                        data-link="<?= $item['link_url'] ?>"
                        data-prodcodes="<?= $item['prod_codes'] ?>"
                    >
                        <div class="one-brochure p-2">
                            <div class="image">
                                <img src="<?= $media_url ?>" />
                            </div>
                            <div class="description p-2 mt-2">
                                <div class="line1"><?= $ribbon_lines[0] ?? '&nbsp;' ?></div>
                                <div class="line2 mt-2"><?= $ribbon_lines[1] ?? '&nbsp;' ?></div>
                            </div>
                            <div class="px-2 mb-3 read-more">Read More <i class="bi bi-arrow-right"></i></div>
                        </div>
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