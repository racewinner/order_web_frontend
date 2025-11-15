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
            <div class="d-flex full-fill" style="justify-content: space-between; align-items: center;">
                <h5 class="fw-bold pg-subject-title-on-mobile">My Trolley</h5>
                <div class="show-in-mobile" style="padding: 0px 10px;">
                    <a href="/orders/payment?xxx" id="nxt2complete" class="btn btn-danger w-100 checkout-button" 
                       style="font-size: 80%;padding: 5px 12px;">Next</a>
                </div>
            </div>

        </div>
        <div class="my-cart-body">
            <ul class="d-inline-flex cart-type-select" role="tablist" aria-label="Cart sections">
                <?php foreach($types as $index => $type) { ?>
                    <li class="nav-link one-cart-type <?= $type['id'] ?> <?= $index == 0 ? 'active' : '' ?> px-2 px-md-3 px-lg-4 py-2" 
                        id="tab-<?= $type['id'] ?>" 
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
                        <input type="hidden" name="bknd_item_total" id="bknd_item_total" value="<?= $type['item_total'] ?>">
                        <input type="hidden" name="bknd_vat" id="bknd_vat" value="<?= $type['vat'] ?>">


                        <div class="d-flex align-items-center cart-lines-items mb-4">
                            <span><?= $type['lines'] ?> Lines <?= $type['items'] ?> Items</span>
                        </div>

                        <div class="cart-items mt-2 cart-items-on-mobile">
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
                <div class="flex-fill"><label>Item Total</label></div>
                <div><span class="value" id="cur_trolley_total_amount"></span></div>
            </div>
            <div class="billing-item d-flex">
                <div class="flex-fill"><label>VAT</label></div>
                <div><span class="value" id="cur_trolley_total_vats"></span></div>
            </div>
        </div>

        <div class="card-footer">
            <div class="subtotal subtotal-desc">
                <div><label>Trolley total is </label></div>
                <div class="value" id="cur_trolley_total"></div>
            </div>

            <div class="mt-4">
                <a href="/orders/payment?xxx" id="nxt2complete" class="btn btn-danger w-100 checkout-button">Next</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
     $(document).ready(function() {
        let el = $('.one-cart-type')
        if (el.length == 0) {
            return;
        } else {
            el[0].click();
        }
    })
    $(document).on('click', '.one-cart-type', function(e) {
        let el_tab_id = e.currentTarget.id
        let cart_typename = el_tab_id.slice(4)
        
        let item_total = $(`#pane-${cart_typename} input#bknd_item_total`).val();
        let vat = $(`#pane-${cart_typename} input#bknd_vat`).val();

        $('#cur_trolley_total_amount').text(`£${parseFloat(item_total).toFixed(2)}`);
        $('#cur_trolley_total_vats').text(`£${parseFloat(vat).toFixed(2)}`);
        $('#cur_trolley_total').text(`£${(parseFloat(item_total) + parseFloat(vat)).toFixed(2)}`);

        // set URL to NxtBtn
        let data = {
            cart_typename
        }
        const queryParams = new URLSearchParams(data);

        let url = `/orders/payment?${queryParams}`;
        $('#nxt2complete').attr('href', url);
    })

    $(document).on('click', '.checkout-button', function(e) {
        add_loadingSpinner_to_button(this);
    })
</script>

<?= $this->endSection() ?>