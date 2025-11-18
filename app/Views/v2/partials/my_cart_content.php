<?php
  $extra_cls = "my-cart-body " . $cls;
?>
<div class="<?= $extra_cls ?>" style="padding-left: 1.5rem; padding-right: 1rem; padding-top: 1.5rem; padding-bottom: 1.5rem;">
    <!-- <ul class="d-inline-flex cart-type-select must-hide" role="tablist" aria-label="Cart sections">
        <php foreach($types as $index => $type) { ?>
            <li class="nav-link one-cart-type <= $type['id'] ?> <= $index == 0 ? 'active' : '' ?> px-2 px-md-3 px-lg-4 py-2" 
                id="mini-tab-general" 
                data-bs-toggle="pill" 
                data-bs-target="#mini-pane-<= $type['id'] ?>" 
                role="tab" 
                aria-controls="mini-pane-<= $type['id'] ?>" 
                aria-selected="true"
            ><= $type['label'] ?></li>
        <php } ?>
    </ul> -->

    <div class="tab-content mt-3">
        <?php foreach($types as $index => $type) { ?>
            <div class="tab-pane fade <?= $index == 0 ? 'show active' : '' ?> <?= $type['id'] ?>" 
                id="mini-pane-<?= $type['id'] ?>" 
                role="tabpanel" 
                aria-labelledby="tab-<?= $type['id'] ?>"
                data-lines="<?= $type['lines'] ?>"
                data-items="<?= $type['items'] ?>"
            >
                <div class="cart-items mt-2 cart-items-on-mobile">
                    <?php foreach($type['orders'] as $order) { 
                        echo view("v2/components/CartItem", ['order' => $order]);
                    } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<div class="my-cart-footer p-4">
    <div class="flex-fill cart-total cart-total-desc">
        <span><?= $type['lines'] ?> Lines <?= $type['items'] ?> Items</span>
        <!-- <label>This trolley sub total is</label> -->
        <label>total is</label>
        <div class="total-amount" id="cart_subtotal">£<?= $total_amount/* + $delivery_charge + $total_vats*/ ?></div>
    </div>
    <div>
        <a href="/orders/checkout" class="btn btn-danger checkout-button" style="width: 220px;">Go to Checkout</a>
    </div>
</div>