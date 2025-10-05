<div class="category-carousel  order-cms py-4" id="<?= $cms_id ?>"
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
            data-breakpoints='{"576": { "slidesPerView": 3, "spaceBetween": 16 }, "768": { "slidesPerView": 4, "spaceBetween": 24 }, "992": { "slidesPerView": 5, "spaceBetween": 24 }, "1320": { "slidesPerView": 7, "spaceBetween": 20 }}'
        > 
            <div class="swiper-wrapper brands"> 
                <?php foreach($data['items'] as $index => $item) {
                    $media_url = $is_mobile ? $item['media_mobile_url'] : $item['media_web_url'];
                ?>
                    <div class="swiper-slide p-2 one-category cms-content"
                        data-link="<?= $item['link_url'] ?>"
                        data-prodcodes="<?= $item['prod_codes'] ?>"
                    >
                        <div class="category-image">
                            <img src="<?= $media_url ?>" />
                        </div>

                        <?php if(!empty($item['ribbon']['content'])) { ?>
                            <div class="mt-2 text-center description"
                                style="color: <?= $data['ribbon']['txt_color'] ?>; background-color: <?= !empty($data['ribbon']['bg_color']) ? $data['ribbon']['bg_color'] : 'transparent' ?>; text-align:<?= $data['ribbon']['align'] ?? 'left' ?>"                        
                            >
                                <?= $item['ribbon']['content'] ?>
                            </div>
                        <?php } ?>
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