<?php
    $product = $order->product;
?>

<div class="one-cart-item one-product card border bg-transparent"
    data-prod-id="<?= $product->prod_id ?>"
    data-prod-code="<?= $product->prod_code ?>"
    data-prod-desc="<?= $product->prod_desc ?>"
    data-order-type="<?= $order->group_type ?? '' ?>"
>
    <?php if(!empty($product->p_label) && $product->p_label != 'CC' && $product->p_label != '') { 
        echo "<div class='prod-label'><span style='background-color:" . $product->ribbon_background."' >" . $product->p_label . "</span></div>";
    } ?>

    <div class="card-body p-2 d-flex2">
        <div class="d-flex justify-content-center align-items-center">
            <?php if(!empty($product->image_url)) { ?>
                <img class="prod-image" src="<?= $img_host . '/product_images/' . $product->image_url . '?v=' . $product->image_version  ?>" alt="" loading="lazy">
            <?php } else { ?>
                <img class="prod-image" src="/images/icons/ribbon/no-product.png" alt="" loading="lazy">
            <?php } ?>
        </div>

        <div class="ms-2 flex-fill d-flex flex-column position-relative">
            <!-- Title -->
            <h6 class="card-title prod-desc mb-1"><?= $product->prod_desc ?></h6>

            <div class="flex-fill d-flex flex-column prod-other-props justify-content-end">
                <?php if(!empty($product->brand)) { ?>
                    <div class="prod-brand">
                        <label>Brand: </label>
                        <span class="ms-2 prop-value"><?= $product->brand ?? '' ?></span>
                    </div>
                <?php } else { ?>
                    <div class="prod-brand">&nbsp;</div>
                <?php } ?>

                <div>
                    <span class="prod-pack inline">
                        <label>Pack: </label>
                        <span class="ms-1 ms-sm-2" style="color:black;">
                            <span class="prop-value"><?= $product->prod_pack_desc ?></span>
                            <label class="mx-1 text-gray">x</label>
                            <span class="prop-value"><?= $product->prod_uos?></span>
                            <span class="ms-1 fw-bold prop-value"><?= $product->case ?></span>
                        </span>

                        <span class="prod-spec">
                            <label class="mx-1">|</label>
                            <label>Code: </label>
                            <span class="ms-1 ms-sm-2 prop-value prod_code_2do" 
                                  data-trolley-type="<?= $product->type ?>"
                                  data-can-reorder="yes"><?= $product->prod_code ?></span>
                        </span>
                    </span>
                </div>

                <div class="prod-pack">
                    <label>Pack: </label>
                    <span class="ms-1 ms-sm-2" style="color:black;">
                        <span class="prop-value"><?= $product->prod_pack_desc ?></span>
                        <label class="mx-1 text-gray">x</label>
                        <span class="prop-value"><?= $product->prod_uos?></span>
                        <span class="ms-1 fw-bold prop-value"><?= $product->case ?></span>
                    </span>

                    <span class="prod-spec">
                        <label>Code: </label>
                        <span class="ms-1 ms-sm-2 prop-value prod_code_2do" 
                              data-trolley-type="<?= $product->type ?>"
                              data-can-reorder="yes"><?= $product->prod_code ?></span>
                    </span>
                </div>

                <div>
                    <span class="prod-rrp">
                        <label>RRP: </label>
                        <span class="ms-1 ms-sm-2 prop-value">£<?= number_format($product->prod_rrp,2,'.','') ?></span>
                    </span>
                    
                    <span class="prod-por inline">
                        <label class="mx-1">|</label>
                        <label>POR: </label>
                        <span class="ms-1 ms-sm-2 prop-value"><?= $product->por ?>%</span>
                    </span>
                </div>

                <div class="prod-por">
                    <label>POR: </label>
                    <span class="ms-1 ms-sm-2 prop-value"><?= $product->por ?>%</span>
                </div>

                <?php if(!empty($product->shelf_life)) { ?>    
                <div class="prod-shelf-life">
                    <label class="mx-1">|</label>
                    <span>
                        <label>Shelf Life:</label>    
                        <span class="ms-1 ms-sm-2 prop-value"><?= $product->shelf_life ?></span>
                    </span>
                </div>
                <?php } ?>

            </div>

            <div class="profit-avail d-flex align-items-end">
                <?php if(isset($product->pfp) && $product->pfp == "1") { ?>
                    <div class="profit d-none">
                        <img src="<?=$img_host?>/images/icons/top-selling-line.png" title="top-selling-line" />
                    </div>
                    <div class="profit">
                        <img src="/images/icons/ribbon/top-selling-line.png" title="top-selling-line" />
                    </div>
                <?php } ?>
                <?php if($product->available['icon_name']) { ?>
                    <?php if ($product->available['icon_name'] == 'out-of-stock' || 
                            $product->available['icon_name'] == 'new-item' || 
                            $product->available['icon_name'] == 'low-stock' || 
                            $product->available['icon_name'] == 'coming-soon') { ?>
                        <div class="stock-avail ms-1">
                            <img src="/images/icons/ribbon/<?=$product->available['icon_name']?>.png" title="<?=$product->available['icon_title']?>" />
                        </div>
                    <?php } else { ?>
                        <div class="stock-avail ms-1">
                            <img src="<?=$img_host?>/images/icons/<?=$product->available['icon_name']?>.png" title="<?=$product->available['icon_title']?>" />
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>

            <?php if($product->price >= 0) { ?>
                <div class="prod-price">
                    <?php if($product->price == 0) { ?>
                        <div class="current-price call-for-price">Call for Price</div>
                    <?php } else { ?>
                        <div class="d-flex align-items-center">
                            <span class="current-price">£<?= $product->price ?></span>
                            <?php if($product->is_show_non_promo_price) { ?>
                              <span class="deprecated ms-2">£<?= $product->non_promo_price ?></span>
                            <?php } ?>
                        </div>
                    <?php } ?>
                        
                    <?php if(!empty($product->promo_end_text) && $product->promo_end_text) { ?>
                        <div class="promo-end-text"><?= $product->promo_end_text ?></div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

        <div class="d-flex align-items-end ms-0 ms-sm-2">
        <?php if($product->available['avail']) { ?>
            <!-- <div class="purchase-action d-flex align-items-center px-1 must-hide">
                <i class="bi bi-dash minus-cart"></i>
                <input class="form-control cart-quantity" value="<?= ''/*$product->cart_quantity ?? 0*/ ?>" />
                <i class="bi bi-plus-lg add-cart"></i>
            </div> -->
            <?php } ?>
            <?php if($product->available['avail']) { ?>
              <div class="purchase-action d-flex align-items-center px-1">
                  <i class="bi bi-dash minus-cart"></i>
                  <input class="form-control cart-quantity" value="<?= $product->cart_quantity ?? 0 ?>" />
                  <i class="bi bi-plus-lg add-cart"></i>
              </div>
            <?php } else { ?>
              <div class="purchase-action d-flex align-items-center px-1 must-hide">
                  <i class="bi bi-dash minus-cart"></i>
                  <input class="form-control cart-quantity" value="<?= $product->cart_quantity ?? 0 ?>" />
                  <i class="bi bi-plus-lg add-cart"></i>
              </div>
            <?php } ?>
        </div>

        <button type="button" class="remove-item">
            <i class="bi bi-trash"></i>
        </button>
    </div>
</div>
