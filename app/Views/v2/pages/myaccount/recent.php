<?= $this->extend('v2/layout/main_layout') ?>

<?= $this->section('css') ?>
<style>
    .my-cart {
        .my-cart-header {
            border: none !important;
            padding-top: 2rem;
            padding-bottom: 1rem;
        }
        .one-cart-item {
            border: none !important;
            border-bottom: 1px solid #ddd !important;
            /* width: 95vw; */
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

    .cart-recent-notify {
        font-size: 24px;
        font-weight: 500;
        color: darkgray;
        padding: 20px 20px 20px 45px;
    }
    @media (max-width: 992px) {
        .pg-subject-title-on-mobile {
            padding-left: 0px;
        }
        .cart-recent-notify {
            font-size: 22px;
            font-weight: 500;
            color: darkgray;
            padding: 0px 0px 0px 0px;
            text-align: left;
        }
        .my-cart {
            .my-cart-header {
                border: none !important;
                padding-top: 2rem;
                padding-bottom: 1rem;
            }
            .one-cart-item {
                border: none !important;
                border-bottom: 1px solid #ddd !important;
                width: auto;
            }
        }
       
    }
    
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column flex-lg-row mx-auto main-content-pad-on-mobile">
    <div class="my-cart">
        <div class="my-cart-header d-flex">
            <div class="flex-fill">
                <h5 class="fw-bold pg-subject-title-on-mobile">My Trolley</h5>
            </div>
        </div>
        <?php if (isset($type['orders'])) { ?>
            <div class="cart-recent-notify">Latest changes to your trolley</div>
        <?php } else { ?>
            <div class="cart-recent-notify">Your trolley is empty</div>
        <?php } ?>
        <div class="my-cart-body">
            <div class="tab-content mt-3">
                <input type="hidden" name="bknd_item_total" id="bknd_item_total" value="<?= $type['item_total'] ?>">
                <input type="hidden" name="bknd_vat" id="bknd_vat" value="<?= $type['vat'] ?>">

                <?php if (isset($type['orders'])) { ?>
                    <div class="cart-items mt-2 cart-items-on-mobile">
                        <?php foreach($type['orders'] as $order) { 
                            echo view("v2/components/CartItem", ['order' => $order]);
                        } ?>
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
                <div class="flex-fill"><label>Item Total</label></div>
                <div><span class="value" id="cur_trolley_total_amount_for_recent"></span></div>
            </div>
            <div class="billing-item d-flex">
                <div class="flex-fill"><label>VAT</label></div>
                <div><span class="value" id="cur_trolley_total_vats_for_recent"></span></div>
            </div>
        </div>

        <div class="card-footer">
            <div class="subtotal subtotal-desc">
                <div>
                    <label>
                        <span><?= $type['lines'] ?> Lines <?= $type['items'] ?> Items</span>
                        total is </label>
                </div>
                <div class="value" id="cur_trolley_total_for_recent"></div>
            </div>

            <div class="mt-4">
                <a href="/orders/checkout" id="nxt2complete" class="btn btn-danger w-100">Next</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
     $(document).ready(function() {
      
    })
    
</script>

<?= $this->endSection() ?>