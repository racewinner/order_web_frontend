<div class="3banners-carousel order-cms" id="<?= $cms_id ?>"
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
            data-breakpoints='{"576": { "slidesPerView": 1, "spaceBetween": 16 }, "768": { "slidesPerView": 2, "spaceBetween": 24 }, "992": { "slidesPerView": 3, "spaceBetween": 24 }}'
        >
            <div class="swiper-wrapper"> 
                <?php foreach($data['items'] as $index => $item) {
                    $media_url = $is_mobile ? $item['media_mobile_url'] : $item['media_web_url'];
                ?>
                    <div class="swiper-slide cms-content"
                        data-link="<?= $item['link_url'] ?>"
                        data-prodcodes="<?= $item['prod_codes'] ?>"
                    >
                        <?php if(!empty($item['ribbon']['content'])) { ?>
                            <div class="ribbon" 
                                style="background-color:<?= $item['ribbon']['bg_color'] ?>; color: <?= $item['ribbon']['txt_color'] ?>; text-align:<?= $item['ribbon']['align'] ?? 'left' ?>"
                            >
                                <?= $item['ribbon']['content'] ?>
                            </div>
                        <?php } ?>

                        <img src="<?= $media_url ?>" class="w-100" />
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