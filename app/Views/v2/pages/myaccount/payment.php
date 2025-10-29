<?= $this->extend('v2/layout/main_layout') ?>

<?= $this->section('css') ?>
<style>
    .delivery-payment {
        /* width: 600px; */
        /* max-width: 95vw; */
        padding: 20px;
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
                align-items: center;
                li {
                    padding: 0;
                    margin-right: 10px;
                    img {
                        height: 50px;
                        /* width: 50px; */
                    }
                }
            }
        }
    }
    .billing.card {
        padding: 20px;
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
    .mt-0  { margin-top: 0px !important;     }
    .mt-10 { margin-top: 10px !important;    }
    .mt-20 { margin-top: 20px !important;    }
    .mt-30 { margin-top: 30px !important;    }
    .mb-0  { margin-bottom: 0px !important;  }
    .mb-10 { margin-bottom: 10px !important; }
    .mb-20 { margin-bottom: 20px !important; }
    .mb-30 { margin-bottom: 30px !important; }
    .delivery-container {
        border-radius: 10px;
        border: 1px solid #eee;
        padding: 20px 20px 0px 20px;
    }
    .one-collection-container {
        border: 1px solid #eee;
        border-radius: 10px;
    }
   
    .chk-out-pad-on-mobile {
        width: fit-content !important;
    }
    @media (max-width: 992px) {
        img.img-sz-on-mobile {
            width: 50px !important;
            height: auto !important;
        }
        .chk-out-pad-on-mobile {
            width: 100%;
        }
        .charge-value-on-mobile {
            margin-right: 0px !important;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<input type="hidden" name="cart_typenames" id="cart_typenames" value="<?php echo $cart_typenames;?>">
<input type="hidden" name="cc_charge" id="cc_charge" value="<?php echo $cc_charge;?>">
<input type="hidden" name="dv_charge" id="dv_charge" value="<?php echo $dv_charge;?>">

<div class="d-flex flex-column flex-lg-row mx-auto chk-out-pad-on-mobile">
    <div class="delivery-payment">
        <h5>Check out</h5>
        <!-- Tab Selector -->
        <div class="delivery-section mt-4">
            <h6>Order Type</h6>
            <div class="delivery-container mb-30">
                <?php if ($this->data['payment_charges'] &&  
                         ($this->data['payment_charges']->collection  == 1 || $this->data['payment_charges']->collection == '1' || 
                          $this->data['payment_charges']->delivery    == 1 || $this->data['payment_charges']->delivery   == '1' )) { ?>
                <ul class="d-inline-flex delivery-methods mt-10 mb-10" role="tablist" aria-label="Delivery Methods">
                    <?php if ($this->data['payment_charges'] && 
                             ($this->data['payment_charges']->collection  == 1 || $this->data['payment_charges']->collection == '1')) { ?>
                        <li class="one-delivery-method pickup-depot 
                            <?= $this->data['payment_charges']->collection  == 1 || $this->data['payment_charges']->collection == '1' ? 'active' : '' ?>"
                            data-bs-toggle="pill"
                            data-bs-target="#pane-pickup-depot" 
                            role="tab" 
                            aria-controls="pane-pickup-depot" 
                        >
                            Collection
                        </li>
                    <?php } ?>
                    <?php if ($this->data['payment_charges'] && 
                             ($this->data['payment_charges']->delivery  == 1 || $this->data['payment_charges']->delivery == '1' )) { ?>
                        <li class="nav-link one-delivery-method via-delivery 
                            <?= $this->data['payment_charges']->collection != 1 && $this->data['payment_charges']->collection != '1' && 
                               ($this->data['payment_charges']->delivery  == 1 || $this->data['payment_charges']->delivery  == '1') ? 'active' : '' ?>"
                            data-bs-toggle="pill"
                            data-bs-target="#pane-via-delivery" 
                            role="tab" 
                            aria-controls="pane-via-delivery" 
                        >
                            Delivery
                        </li>
                    <?php } ?>
                </ul>
                <?php } ?>

                <div class="tab-content">
                    <?php if ($this->data['payment_charges'] && 
                             ($this->data['payment_charges']->collection  == 1 || $this->data['payment_charges']->collection == '1')) { ?>
                        <div class="tab-pane fade mb-30 mt-0 
                            <?= $this->data['payment_charges']->collection  == 1 || $this->data['payment_charges']->collection == '1' ? 'active show' : '' ?>"
                            id="pane-pickup-depot"
                            role="tabpanel" 
                            aria-labelledby="tab-pickup-depot"
                        >
                            <div class="payment-section mt-10 mb-20">
                                <div style="color: black; font-size: 17px; font-weight: bold;">Choose a date</div>
                                <div class="comment mt-2 text-black ">
                                    We will do our best to have the order ready on time.
                                </div>
                                <div class="comment">
                                    <span class="pickup-date">
                                        (We will notify you when your order is ready for collection.)
                                    </span>
                                </div>

                                <div class="branch-select mx-auto">
                                    <div class="mt-2">
                                        <select id='delivery_date1' name='delivery_date1' class="form-select">
                                            <?php 
                                                foreach($collection_delivery_dates as $index => $c_d_date) {
                                                    $date_dt_value = $c_d_date->format('d/m/Y');
                                                    $date_df_value = $c_d_date->format('l');
                                                    $date_tz_value = $c_d_date->getTimezone(); // DateTimeZone object
                                                    echo '<option value="' . $date_dt_value . '" '.( $index == 0 ? 'selected' : '') . '>' . $date_df_value . ' ' . $date_dt_value . '</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-20" style="color: black; font-size: 17px; font-weight: bold;">
                                    Please select a collection container
                                </div>

                                <div class="one-collection-container d-flex align-items-center p-2 px-4 mb-2">
                                    <div class="flex-fill">Pallet</div>
                                    <div>
                                        <input class="form-check-input" type="radio" name="collection_container" 
                                            id="pallet" value="pallet" checked>
                                    </div>
                                </div>
                                <div class="one-collection-container d-flex align-items-center p-2 px-4 mb-2">
                                    <div class="flex-fill">Cage</div>
                                    <div>
                                        <input class="form-check-input" type="radio" name="collection_container" 
                                            id="cage" value="cage" >
                                    </div>
                                </div>
                                <div class="one-collection-container d-flex align-items-center p-2 px-4 mb-2">
                                    <div class="flex-fill">Trolley</div>
                                    <div>
                                        <input class="form-check-input" type="radio" name="collection_container" 
                                            id="trolley" value="trolley" >
                                    </div>
                                </div>
                                <div class="one-collection-container d-flex align-items-center p-2 px-4 mb-2">
                                    <div class="flex-fill">Box</div>
                                    <div>
                                        <input class="form-check-input" type="radio" name="collection_container" 
                                            id="box" value="box" >
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($this->data['payment_charges'] && 
                             ($this->data['payment_charges']->delivery  == 1 || $this->data['payment_charges']->delivery == '1' )) { ?>
                        <div class="tab-pane fade  
                            <?= $this->data['payment_charges']->collection != 1 && $this->data['payment_charges']->collection != '1' && 
                               ($this->data['payment_charges']->delivery  == 1 || $this->data['payment_charges']->delivery  == '1') ? 'active show' : '' ?>"
                            id="pane-via-delivery"
                            role="tabpanel" 
                            aria-labelledby="tab-via-delivery"
                        >
                            <div class="payment-section mt-10 mb-20">
                                <div style="color: black; font-size: 17px; font-weight: bold;">Choose a date</div>
                                <div class="comment mt-2 text-black ">
                                    We will do our best to have the order ready on time.
                                </div>
                                <div class="comment">
                                    <span class="pickup-date">
                                        (We will notify you when your order is ready for delivery.)
                                    </span>
                                </div>

                                <div class="branch-select mx-auto">
                                    <div class="mt-2">
                                        <select id='delivery_date2' name='delivery_date2' class="form-select">
                                            <?php 
                                                foreach($collection_delivery_dates as $index => $c_d_date) {
                                                    $date_dt_value = $c_d_date->format('d/m/Y');
                                                    $date_df_value = $c_d_date->format('l');
                                                    $date_tz_value = $c_d_date->getTimezone(); // DateTimeZone object
                                                    echo '<option value="' . $date_dt_value . '" '.( $index == 0 ? 'selected' : '') . '>' . $date_df_value . ' ' . $date_dt_value . '</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- <div class="mt-20" style="color: black; font-size: 17px; font-weight: bold;">
                                    Delivery Charge
                                </div> -->
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php if (!$this->data['payment_charges'] ||  
                         ( $this->data['payment_charges']->collection  != 1 && $this->data['payment_charges']->collection != '1' && 
                           $this->data['payment_charges']->delivery    != 1 && $this->data['payment_charges']->delivery   != '1' )) { ?>
                    <div style="color: red; padding-bottom: 20px">There is not any order type.</div>
                 <?php } ?>
            </div>
        </div>

        <div class="payment-section mt-0">
            <h6>Payment Method</h6>
            <?php if ($this->data['payment_methods']->e_order) { ?>
                <div class="one-pay-method pay-with-order d-flex align-items-center p-2 px-4 mb-2">
                    <div class="flex-fill">with Order</div>
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
                    <div class="flex-fill">at Depot</div>
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
                    <div class="flex-fill">EchoPay</div>
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
                    <div class="flex-fill">Bank Transfer</div>
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
                    <div class="flex-fill">Credit Account</div>
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
                            <li><img class="img-sz-on-mobile" src="/images/icons/png/visa.png" /></li>
                            <li><img class="img-sz-on-mobile" src="/images/icons/png/master-card.png" /></li>
                            <li><img class="img-sz-on-mobile" src="/images/icons/png/american-express.png" /></li>
                        </ul>
                    </div>
                    <div style="padding-top: 10px">
                        <input class="form-check-input" type="radio" name="payment_method" 
                            id="pay_in_card" value="pay_in_card" 
                             <?php if ($this->data["payment_default_method"] == "debit_credit_card") { ?>
                                checked
                            <?php } ?>
                        >
                    </div>
                </div>
               
            </div>
            <?php } ?>
        </div>
    </div>

    <div style="padding-left: 20px; padding-right: 20px">
        <div class="billing card ms-0 mt-4 ms-lg-4">
            <div class="card-header">
                <h5 class="card-title">Billing Details</h5>
            </div>

            <div class="card-body">
                <div class="billing-item d-flex">
                    <div class="flex-fill me-8 charge-value-on-mobile"><label>Item Total</label></div>
                    <div><span class="value" id="pay_total_amount">£<?= $total_amount ?></span></div>
                </div>
                <div class="billing-item d-flex delivery-charge-v-in-right-sidebar">
                    <div class="flex-fill me-8 charge-value-on-mobile" id="order_type_label">Delivery Charge</div>
                    <div>
                        <span id="charge" style="font-weight: bold; color: black;"></span>
                    </div>
                    
                </div>
                <div class="billing-item d-flex">
                    <div class="flex-fill me-8 charge-value-on-mobile"><label>VAT</label></div>
                    <div><span class="value" id="pay_total_vats">£<?= $total_vats ?></span></div>
                </div>
            </div>

            <div class="card-footer">
                <div class="subtotal">
                    <div><label>Subtotal</label></div>
                    <div class="value" id="cart_subtotal2"></div>
                </div>

                <div class="mt-4">
                    <a href="#" id="send_orders" class="btn btn-danger w-100">Next to Complete</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= view("v2/partials/confirm_order_trolley_modal"); ?>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    function make_order(type, payload, cb_success, cb_error) {
        return $.ajax({
            url: `/orders/send_order/${type}`,
            method:"POST",
            data: JSON.stringify(payload),
            cache:false,
            processData:false,
        })
        .then(function(d) {
            if (cb_success) {
                cb_success(d)
            } else {
                return {success: true}
            }
        })
        .catch(function(error) {
            if (cb_error) {
                cb_error(error)
            } else {
                showToast({type: "error", message: d.error});
            }
        })
    }

    $(document).on('click', '#send_orders', function(e) {
        debugger
        let cart_typenames = $('#cart_typenames').val();
        var arr = cart_typenames.split(',');
        // no trolley ----------
        if (arr.length == 0) {
            showToast({type: "error", message: "There is not any product in any trolley"});
            return;
        }
        
        let delivery_date           = $('#delivery_date1').val()
        if ($('.one-delivery-method.via-delivery').hasClass('active')) {
            delivery_date           = $('#delivery_date2').val()
        }
        let delivery_charge         = $('#delivery_charge_v').text()
        let collection_container    = $('input[name="collection_container"]:checked').val()
        let payment_method          = $('input[name="payment_method"]:checked').val()
        let delivery_method         = $('.delivery-methods li.active').data('bs-target')
        // no delivery method ----------
        if(!delivery_method) {
            showToast({type: "error", message: "None of the delivery method is selected"});
            return;
        }

        if (arr.length == 1) {
            let payload = {
                delivery_date,
                delivery_method,
                delivery_charge,
                collection_container,
                payment_method
            };
            let res = make_order(arr[0], payload, function(res) {
                let url = `<?php echo base_url("");?>pastorders`;
	            window.location.href = url;
            });
        } else {
            const confirm_order_trolley_modal = $("#confirm_order_trolley_dialog");
            const modal = new bootstrap.Modal(confirm_order_trolley_modal[0]);
            modal.show();
        }
    })

    $(document).on('click', '.one-delivery-method.pickup-depot', function(e) {
        debugger
        $('#order_type_label').text('Click & Collect');
        $('.delivery-charge-v-in-right-sidebar').removeClass('must-hide');
        //----------
        let pay_total_amount    = $('#pay_total_amount').text();
        let pay_total_vats      = $('#pay_total_vats').text();
        let pay_charge          = $('#cc_charge').val();

        pay_total_amount = pay_total_amount.slice(1);
        pay_total_vats = pay_total_vats.slice(1);

        let cart_subtotal2 = parseFloat(pay_total_amount) + parseFloat(pay_charge) + parseFloat(pay_total_vats);
        $('#cart_subtotal2').text('£'+cart_subtotal2.toFixed(2));
        //----------
        $('#delivery_date1').val($('#delivery_date2').val());
        //----------
        $('#charge').text('£'+parseFloat(pay_charge).toFixed(2));
        //----------
        $('#pay_total_vats').text('£'+parseFloat(pay_total_vats).toFixed(2));

    })

    $(document).on('click', '.one-delivery-method.via-delivery', function(e) {
        debugger
        $('#order_type_label').text('Delivery Charge');
        $('.delivery-charge-v-in-right-sidebar').removeClass('must-hide');
        //----------
        let pay_total_amount    = $('#pay_total_amount').text();
        let pay_total_vats      = $('#pay_total_vats').text();
        let pay_charge          = $('#dv_charge').val();

        pay_total_amount = pay_total_amount.slice(1);
        pay_total_vats = pay_total_vats.slice(1);

        let cart_subtotal2 = parseFloat(pay_total_amount) + parseFloat(pay_charge) + parseFloat(pay_total_vats);
        $('#cart_subtotal2').text('£'+cart_subtotal2.toFixed(2));
         //----------
        $('#delivery_date2').val($('#delivery_date1').val());
        //----------
        $('#charge').text('£'+parseFloat(pay_charge).toFixed(2));
        //----------
        $('#pay_total_vats').text('£'+parseFloat(pay_total_vats).toFixed(2));
    })

    $(document).ready(function() {
        let el = $('.delivery-methods li')
        if (el.length == 0) {
            $('.delivery-charge-v-in-right-sidebar').addClass('must-hide');
            //----------
            let pay_total_amount    = $('#pay_total_amount').text();
            let pay_total_vats      = $('#pay_total_vats').text();

            pay_total_amount = pay_total_amount.slice(1);
            pay_total_vats = pay_total_vats.slice(1);

            let cart_subtotal2 = parseFloat(pay_total_amount) + parseFloat(pay_total_vats);
            $('#cart_subtotal2').text('£'+cart_subtotal2.toFixed(2));
            //----------
            $('#pay_total_vats').text('£'+parseFloat(pay_total_vats).toFixed(2));
        } else {
            el[0].click();
        }
    })

    $(document).on('click', '#confirm_order_trolley_dialog .order-complete', function(e) {
        debugger
        let delivery_date           = $('#delivery_date1').val()
        if ($('.one-delivery-method.via-delivery').hasClass('active')) {
            delivery_date           = $('#delivery_date2').val()
        }
        let delivery_charge         = $('#delivery_charge_v').text()
        let collection_container    = $('input[name="collection_container"]:checked').val()
        let payment_method          = $('input[name="payment_method"]:checked').val()
        let delivery_method         = $('.delivery-methods li.active').data('bs-target')
        // no delivery method----------
        if(!delivery_method) {
            showToast({type: "error", message: "None of the delivery method is selected"});
            return;
        }

        let payload = {
            delivery_date,
            delivery_method,
            delivery_charge,
            collection_container,
            payment_method
        };

        let typename  = $('input[name="trolley_container"]:checked').val()
        if (typename != 'all') {
            let res = make_order(typename, payload, function(res) {
                let url = `<?php echo base_url("");?>pastorders`;
	            window.location.href = url;
            });
        } else {
            let cart_typenames = $('#cart_typenames').val();
            var arr = cart_typenames.split(',');

            let res = Promise.all(arr.map(function(type) {
                return make_order(type, payload)
            }))
            .then(function(responses) {
                debugger
                let failed_res = responses.filter(itm => !itm.success)
                if (failed_res.length > 0) {
                    showToast({type: "error", message: "Sorry, some issues occured during send orders."});
                } else {
                    let url = `<?php echo base_url("");?>pastorders`;
                    window.location.href = url;
                }
            })
        }
    })
</script>
<?= $this->endSection() ?>

