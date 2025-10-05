<div class="p-4 my-cart-body my-cart-body-limited">
    <ul class="d-inline-flex cart-type-select" role="tablist" aria-label="Cart sections">
        <?php foreach($types as $index => $type) { ?>
            <li class="nav-link one-cart-type <?= $type['id'] ?> <?= $index == 0 ? 'active' : '' ?> px-2 px-md-3 px-lg-4 py-2" 
                id="tab-general" 
                data-bs-toggle="pill" 
                data-bs-target="#pane-<?= $type['id'] ?>" 
                role="tab" 
                aria-controls="pane-<?= $type['id'] ?>" 
                aria-selected="true"
            ><?= $type['label'] ?></li>
        <?php } ?>
    </ul>

    <div class="tab-content mt-3">
        <?php foreach($types as $index => $type) { ?>
            <div class="tab-pane fade <?= $index == 0 ? 'show active' : '' ?> <?= $type['id'] ?>" 
                id="pane-<?= $type['id'] ?>" 
                role="tabpanel" 
                aria-labelledby="tab-<?= $type['id'] ?>"
                data-lines="<?= $type['lines'] ?>"
                data-items="<?= $type['items'] ?>"
            >
                <div class="d-flex align-items-center cart-lines-items">
                    <span><?= $type['lines'] ?> Lines <?= $type['items'] ?> Items</span>
                </div>

                <div class="cart-items mt-2">
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
        <label>This trolley sub total is</label>
        <div class="total-amount" id="cart_subtotal">£<?= $total_amount/* + $delivery_charge + $total_vats*/ ?></div>
    </div>
    <div>
        <a href="/orders/checkout" class="btn btn-danger">Go to Checkout</a>
    </div>
</div>