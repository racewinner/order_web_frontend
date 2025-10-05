<?= $this->extend('v2/layout/main_layout') ?>

<?= $this->section('css') ?>
<style>
    .my-cart {
        .my-cart-header {
            border: none !important;
        }
        .one-cart-item {
            border: none !important;
            border-bottom: 1px solid #ddd !important;
            width: 95vw;
            max-width: 600px;
        }
    }

    .billing.card {
        height: fit-content;
        border: none;
        box-shadow: 0px 4px 8px rgba(0,0,0,0.15);
        .billing-item {
            margin-bottom: 15px;
            .value {
                font-weight: bold;
                color: #111;
            }
        }
        .subtotal {
            label {
                font-weight: bold;
                color: #111;
            }
            .value {
                font-weight: bold;
                color: var(--bs-success);
                font-size: 150%;
            }
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column flex-lg-row mx-auto w-fit-content p-4">
    <div class="my-cart">
        <div class="my-cart-header d-flex py-4">
            <div class="flex-fill">
                <h5 class="fw-bold">My Trolley</h5>
            </div>
        </div>
        <div class="my-cart-body">
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
                        <div class="d-flex align-items-center cart-lines-items mb-4">
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
    </div>

    <div class="billing card ms-0 ms-lg-4 mt-4">
        <div class="card-header">
            <h5 class="card-title">Billing Details</h5>
        </div>

        <div class="card-body">
            <div class="billing-item d-flex">
                <div class="flex-fill me-8"><label>Item Total</label></div>
                <div><span class="value" id="cart_total_amount">£<?= $total_amount ?></span></div>
            </div>
            <div class="billing-item d-flex">
                <div class="flex-fill me-8"><label>Delivery Charge</label></div>
                <div><span class="value" id="cart_delivery_charge">£<?= $delivery_charge ?></span></div>
            </div>
            <div class="billing-item d-flex">
                <div class="flex-fill me-8"><label>VAT</label></div>
                <div><span class="value" id="cart_total_vats">£<?= $total_vats ?></span></div>
            </div>
        </div>

        <div class="card-footer">
            <div class="subtotal subtotal-desc">
                <div><label>Overall order total is </label></div>
                <!-- <div class="value">£5535.36</div> -->
                <div class="value" id="cart_subtotal">£<?= $total_amount + $delivery_charge + $total_vats ?></div>
            </div>

            <div class="mt-4">
                <a href="/orders/payment" class="btn btn-danger w-100">Next to Complete</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<?= $this->endSection() ?>