<?= $this->extend('v2/layout/main_layout') ?>

<?= $this->section('css') ?>
<style>
    .delivery-payment {
        width: 600px;
        max-width: 95vw;
        input[type='radio'] {
            width: 25px;
            height: 25px;
        }
        .delivery-section {
            ul.delivery-methods {
                padding: 0px;
                list-style: none;
                li.one-delivery-method {
                    cursor: pointer;
                    width: 200px;
                    text-align: center;
                    padding: 8px 20px;
                    border: 1px solid #aaa;
                    border-radius: 10px;
                    margin-right: 20px;
                    &.active {
                        border: 1px solid red;
                        color: red;
                    }
                    @media (max-width: 450px) {
                        font-size: 90%;
                        width: 170px;
                    }
                    @media (max-width: 380px) {
                        font-size: 90%;
                        width: fit-content;
                    }
                }
            }
            .delivery-method-pane {
                border-radius: 10px;
                border: 1px solid #eee;
                padding: 20px;
                i {
                    font-size: 30px;
                    color: red;
                }
                a.edit-address {
                    color: var(--bs-bright-blue);
                    text-decoration: underline;
                }
            }
        }
        .payment-section {
            margin-top: 30px;
            .one-pay-method {
                border: 1px solid #eee;
                border-radius: 10px;
                padding: 20px;
                &.pay-in-card {
                    input.expire-date, input.cvv {
                        width: 150px;
                        max-width: 30vw;
                    }
                }
            }
            ul.card-types {
                list-style: none;
                margin-top: 5px;
                padding: 0px;
                li {
                    padding: 0;
                    margin-right: 10px;
                    img {
                        height: 30px;
                        width: 50px;
                    }
                }
            }
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
    .mt-0 {
        margin-top: 0px !important;
    }
    .mt-10 {
        margin-top: 10px !important;
    }
    .mt-20 {
        margin-top: 20px !important;
    }
    .mt-30 {
        margin-top: 30px !important;
    }
    .mb-0 {
        margin-bottom: 0px !important;
    }
    .mb-10 {
        margin-bottom: 10px !important;
    }
    .mb-20 {
        margin-bottom: 20px !important;
    }
    .mb-30 {
        margin-bottom: 30px !important;
    }
    .delivery-container {
        border-radius: 10px;
        border: 1px solid #eee;
        padding: 20px 20px 0px 20px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column flex-lg-row mx-auto w-fit-content p-4">
    <div class="delivery-payment">
        <h5>Delivery & Payment</h5>
        <!-- <div>Tab Selector</div> -->
        <div class="delivery-section mt-4">
            <h6>Delivery Method</h6>
            <div class="delivery-container mb-30">
                <div style="color: black; font-size: 17px; font-weight: bold;">Pickup Date & Time</div>
                <div class="comment mt-2 text-black ">
                    Please select the required (collection/deliervy) date from the dropdown.
                </div>
                <div class="comment">
                    <span class="pickup-date">
                        (We will notify you when your order is ready for collection/delivery.)
                    </span>
                </div>

                <div class="branch-select mx-auto">
                    <div class="mt-2">
                        <select id='branch' name='branch' class="form-select">
                            <?php 
                                foreach($collection_delivery_dates as $index => $c_d_date) {
                                    $date_dt_value = $c_d_date->format('d/m/Y');
                                    $date_df_value = $c_d_date->format('l'); //'l': Monday, 'D': Mon
                                    // $date_tm_value = $c_d_date->format('Y-m-d');
                                    $date_tz_value = $c_d_date->getTimezone(); // DateTimeZone object
                                    echo '<option value="' . $date_dt_value . '" '.( $index == 0 ? 'selected' : '') . '>' . $date_df_value . ' ' . $date_dt_value . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <ul class="d-inline-flex delivery-methods mt-20 mb-10" role="tablist" aria-label="Delivery Methods">
                    <li class="nav-link one-delivery-method via-delivery active"
                        data-bs-toggle="pill"
                        data-bs-target="#pane-via-delivery" 
                        role="tab" 
                        aria-controls="pane-via-delivery" 
                    >
                        Via Delivery
                    </li>
                    <li class="one-delivery-method pickup-depot"
                        data-bs-toggle="pill"
                        data-bs-target="#pane-pickup-depot" 
                        role="tab" 
                        aria-controls="pane-pickup-depot" 
                    >
                        Pickup from Depot
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="pane-via-delivery"
                        role="tabpanel" 
                        aria-labelledby="tab-via-delivery"
                    >
                        <!-- <h6 class="d-flex">
                            <div class="fw-bold flex-fill">Delivery Address</div>
                            <div><i class="bi bi-truck"></i></div>
                        </h6>

                        <div class="username">Your Name</div>
                        <div class="address">110 Easter Queenslie Rd, Glasgow G33 4UL</div>

                        <div class="mt-2">
                            <a class="edit-address">Edit Address</a>
                        </div> -->
                        <div class="payment-section mt-10 mb-20">
                            <span class="comment mt-2 text-black mb-2">
                                Delivery Charge:
                            </span>
                            <span style="color: red; font-weight: bold;"><?= $this->data['delivery_charge'] ?></span>
                        </div>
                    </div>
                    <div class="tab-pane fade mb-30 mt-0" id="pane-pickup-depot"
                        role="tabpanel" 
                        aria-labelledby="tab-pickup-depot"
                    >
                        <div class="payment-section mt-0">
                            <div class="comment mt-2 text-black mb-2">
                                Please select a collection container
                            </div>

                            <div class="one-pay-method pay_by_echopay d-flex align-items-center p-2 px-4 mb-2">
                                <div class="flex-fill">Pallet</div>
                                <div>
                                    <input class="form-check-input" type="radio" name="collection_container" 
                                        id="pallet" value="pallet" >
                                </div>
                            </div>
                            <div class="one-pay-method pay_by_echopay d-flex align-items-center p-2 px-4 mb-2">
                                <div class="flex-fill">Cage</div>
                                <div>
                                    <input class="form-check-input" type="radio" name="collection_container" 
                                        id="cage" value="cage" >
                                </div>
                            </div>
                            <div class="one-pay-method pay_by_echopay d-flex align-items-center p-2 px-4 mb-2">
                                <div class="flex-fill">Trolley</div>
                                <div>
                                    <input class="form-check-input" type="radio" name="collection_container" 
                                        id="trolley" value="trolley" >
                                </div>
                            </div>
                            <div class="one-pay-method pay_by_echopay d-flex align-items-center p-2 px-4 mb-2">
                                <div class="flex-fill">Box</div>
                                <div>
                                    <input class="form-check-input" type="radio" name="collection_container" 
                                        id="box" value="box" >
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="payment-section mt-0">
            <h6>Payment Method</h6>
            <?php if ($this->data['payment_methods']->e_order) { ?>
            <div class="one-pay-method pay-with-order d-flex align-items-center p-2 px-4 mb-2">
                <div class="flex-fill">Pay with Order</div>
                <div>
                    <input class="form-check-input" type="radio" name="payment_method" 
                        id="pay_with_order" value="pay_with_order" 
                        <?php if ($this->data["payment_default_method"] == "e_order") { ?>
                            checked
                        <?php } ?>
                    >
                </div>
            </div>
            <?php } ?>
            <?php if ($this->data['payment_methods']->depot) { ?>
            <div class="one-pay-method pay-in-depot d-flex align-items-center p-2 px-4 mb-2">
                <div class="flex-fill">Pay at Depot</div>
                <div>
                    <input class="form-check-input" type="radio" name="payment_method" 
                        id="pay_in_depot" value="pay_in_depot" 
                        <?php if ($this->data["payment_default_method"] == "depot") { ?>
                            checked
                        <?php } ?>
                    >
                </div>
            </div>
            <?php } ?>
            <?php if ($this->data['payment_methods']->echo_pay) { ?>
            <div class="one-pay-method pay_by_echopay d-flex align-items-center p-2 px-4 mb-2">
                <div class="flex-fill">Pay by EchoPay</div>
                <div>
                    <input class="form-check-input" type="radio" name="payment_method" 
                        id="pay_by_echopay" value="pay_by_echopay" 
                        <?php if ($this->data["payment_default_method"] == "echo_pay") { ?>
                            checked
                        <?php } ?>
                    >
                </div>
            </div>
            <?php } ?>
            <?php if ($this->data['payment_methods']->bank_transfer) { ?>
            <div class="one-pay-method pay_by_bank_transfer d-flex align-items-center p-2 px-4 mb-2">
                <div class="flex-fill">Pay by Bank Transfer</div>
                <div>
                    <input class="form-check-input" type="radio" name="payment_method" 
                        id="pay_by_bank_transfer" value="pay_by_bank_transfer" 
                        <?php if ($this->data["payment_default_method"] == "bank_transfer") { ?>
                            checked
                        <?php } ?>
                    >
                </div>
            </div>
            <?php } ?>
            <?php if ($this->data['payment_methods']->credit_account) { ?>
            <div class="one-pay-method pay_by_credit_account d-flex align-items-center p-2 px-4 mb-2">
                <div class="flex-fill">Pay by Credit Account</div>
                <div>
                    <input class="form-check-input" type="radio" name="payment_method" 
                        id="pay_by_credit_account" value="pay_by_credit_account" 
                        <?php if ($this->data["payment_default_method"] == "credit_account") { ?>
                            checked
                        <?php } ?>
                    >
                </div>
            </div>
            <?php } ?>
            <?php if ($this->data['payment_methods']->debit_credit_card) { ?>
            <div class="one-pay-method pay-in-card p-2 px-4">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <div>Debit / Credit Card</div>
                        <ul class="card-types d-flex">
                            <li><img src="/images/icons/png/master-card.png" /></li>
                            <li><img src="/images/icons/png/visa.png" /></li>
                            <li><img src="/images/icons/png/discover.png" /></li>
                        </ul>
                    </div>
                    <div>
                        <input class="form-check-input" type="radio" name="payment_method" 
                            id="pay_in_card" value="pay_in_card" 
                             <?php if ($this->data["payment_default_method"] == "debit_credit_card") { ?>
                                checked
                            <?php } ?>
                        >
                    </div>
                </div>
                <!-- <div class="card-detail-info mt-2">
                    <div class="d-flex align-items-center">
                        <div class="flex-fill">
                            <input class="form-control w-100" placeholder="Card Number" name="card_holder_name" />
                        </div>
                        <div class="ms-3">
                            <input class="form-control" placeholder="Card Holder Name" name="card_holder_name" />
                        </div>
                    </div>
                    <div class="d-flex mt-2">
                        <input class="form-control expire-date" name="expire_date" placeholder="00/00" />
                        <input class="form-control ms-2 cvv" name="cvv" placeholder="CVV" />
                    </div>
                </div> -->
            </div>
            <?php } ?>

            <!-- <div class="one-pay-method pay-in-paypal p-3 px-4 mt-3">
                <div class="d-flex align-items-center">
                    <div class="flex-fill">
                        <img src="/images/icons/png/paypal.png" style="height: 25px; width: auto;" />
                    </div>
                    <div>
                        <input class="form-check-input" type="radio" name="payment_method" id="pay_in_paypal" value="pay_in_paypal">
                    </div>
                </div>
            </div> -->
        </div>
        
    </div>

    <div class="billing card ms-0 mt-4 ms-lg-4">
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
            <div class="subtotal">
                <div><label>Subtotal</label></div>
                <!-- <div class="value">£5535.36</div> -->
                <div class="value" id="cart_subtotal">£<?= $total_amount + $delivery_charge + $total_vats ?></div>
            </div>

            <div class="mt-4">
                <!-- <a href="/orders/payment" class="btn btn-danger w-100">Next to Complete</a> -->
                <a href="#" id="send_orders" class="btn btn-danger w-100">Next to Complete</a>
            </div>
        </div>
        
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    $(document).on('click', '#send_orders', function(e) {
        debugger
        let data = {};
        $.ajax({
            url: '/orders/send_order/general',
            method:"POST",
            data:data,
            cache:false,
            processData:false,
            error: function (request, status, error) {
                // $('#send_payment').prop('disabled', false);
                // showToast({type: "error", message: d.error});
                debugger
                alert('error')
            },
            success:function(d) {
                // if(d.success == true) {
                //     window.open(d.data.url, "Payment");
                // } else {
                //     showToast({type: "error", message: d.error});
                // }

                // $('#send_payment').prop('disabled', false);
                // showToast({type: "success", "ok"});
                debugger
                alert('success')
            }
        })
        return false;
    })
</script>
<?= $this->endSection() ?>

