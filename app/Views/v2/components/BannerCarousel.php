<div id="<?= $cms_id ?>" class="banners-carousel carousel slide order-cms" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <?php foreach($data['items'] as $index => $item) { ?>
      <button type="button" data-bs-target="#<?= $cms_id ?>" data-bs-slide-to="<?=$index?>" class="<?= $index == 0 ? 'active' : '' ?>" aria-current="true"></button>
    <?php }?>
  </div>
  
  <div class="carousel-inner d-md-block">
    <?php 
    foreach($data['items'] as $index => $item) { 
      $media_url = $is_mobile ? $item['media_mobile_url'] : $item['media_web_url'];
    ?>
      <div class="carousel-item cms-content <?= $index == 0 ? 'active' : '' ?>" 
        data-bs-interval="<?= ( ( !empty($item['dwell_time']) && $item['dwell_time'] > 0) ? $item['dwell_time'] : $data['dwell_time']) * 1000 ?>"
        data-id="<?= $item['id'] ?>"
        data-link="<?= $item['link_url'] ?>"
        data-cms-itm-id="<?= $item['id'] ?>"
        data-cms-itm-tp="<?= $item['type'] ?>"
        data-prodcodes="<?= $item['prod_codes'] ?>"
      >
        <?php if(!empty($item['ribbon']['content'])) { ?>
          <div class="ribbon px-4" 
            style="background-color:<?= $item['ribbon']['bg_color'] ?>; color: <?= $item['ribbon']['txt_color'] ?>; text-align:<?= $item['ribbon']['align'] ?? 'left' ?>"
          >
            <?= $item['ribbon']['content'] ?>
          </div>
        <?php } ?>

        <img src="<?= $media_url ?>" class="d-block w-100">
      </div>
    <?php } ?>
  </div>  

  <button class="carousel-control-prev" type="button" data-bs-target="#<?= $cms_id ?>" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#<?= $cms_id ?>" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>