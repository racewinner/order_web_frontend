<div class="newsfeed d-md-block" id="news">
    <h4>United Promotions</h4>    
    <?php foreach($brochures as $brochure) { 
        $media_url = '';
        if(!empty($brochure['media_web']['url'])) {
            $media_url = $brochure['media_web']['url'];
        } else if(!empty($brochure['media_mobile']['url'])) {
            $media_url = $brochure['media_mobile']['url'];
        }
    ?>
        <div class="news cms-content"
            data-id="<?= $brochure['id'] ?>"
            data-link="<?= $brochure['link_url'] ?>"
            data-prodcodes="<?= $brochure['prod_codes'] ?>"
        >
            <?php if(!empty($brochure['ribbon']['content'])) { ?>
                <div class="p-1" style="background:<?= $brochure['ribbon']['bg_color'] ?>;color:<?=$brochure['ribbon']['txt_color']?>;">
                    <?= $brochure['ribbon']['content'] ?>
                </div>
            <?php } ?>
            <img src="<?= $media_url ?>">
        </div>            
    <?php } ?>
</div>
<div class="newsfeed d-md-none" id="news">
    <h4>United Promotions</h4>    
    <?php foreach($brochures as $brochure) { 
        $media_url = '';
        if(!empty($brochure['media_mobile']['url'])) {
            $media_url = $brochure['media_mobile']['url'];
        } else if(!empty($brochure['media_web']['url'])) {
            $media_url = $brochure['media_web']['url'];
        }
    ?>
        <div class="news cms-content"
            data-id="<?= $brochure['id'] ?>"
            data-link="<?= $brochure['link_url'] ?>"
            data-prodcodes="<?= $brochure['prod_codes'] ?>"
        >
            <?php if(!empty($brochure['ribbon']['content'])) { ?>
                <div class="p-1" style="background:<?= $brochure['ribbon']['bg_color'] ?>;color:<?=$brochure['ribbon']['txt_color']?>;">
                    <?= $brochure['ribbon']['content'] ?>
                </div>
            <?php } ?>
            <img src="<?= $media_url ?>">
        </div>            
    <?php } ?>
</div>
