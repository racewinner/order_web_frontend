<?= $this->extend('v2/layout/main_layout') ?>

<?= $this->section('css') ?>
<style>
    .delivery-payment {
        .my-cart-header {
            border: none !important;
            padding-top: 2rem;
            padding-bottom: 1rem;
        }
        /* width: 600px; */
        /* max-width: 95vw; */
        padding: 20px;
        input[type='radio'] {
            width: 25px;
            height: 25px;
        }
        .delivery-section {
            ul.order-methods {
                padding: 0px;
                list-style: none;
                width: 100%;
                gap: 10px;
                li.one-order-method {
                    cursor: pointer;
                    /* width: 200px; */
                    text-align: center;
                    padding: 8px 20px;
                    border: 1px solid #aaa;
                    border-radius: 10px;
                    margin-right: 0px;
                    flex-grow: 1;
                    max-width: 200px;
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
            margin-top: 20px;
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
        padding: 20px 20px 20px 20px;
    }
    .one-collection-container, 
    .one-delivery-container {
        border: 1px solid #eee;
        border-radius: 10px;
    }
    @media (max-width: 992px) {
        .delivery-payment {
            /* width: 600px; */
            /* max-width: 95vw; */
            padding: 0px;
            input[type='radio'] {
                width: 25px;
                height: 25px;
            }
            .delivery-section {
                ul.delivery-methods {
                    padding: 0px;
                    list-style: none;
                    width: 100%;
                    gap: 10px;
                    li.one-order-method {
                        cursor: pointer;
                        /* width: 200px; */
                        text-align: center;
                        padding: 8px 20px;
                        border: 1px solid #aaa;
                        border-radius: 10px;
                        margin-right: 0px;
                        flex-grow: 1;
                        max-width: 200px;

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
                margin-top: 20px;
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
        img.img-sz-on-mobile {
            width: 50px !important;
            height: auto !important;
        }
        .charge-value-on-mobile {
            margin-right: 0px !important;
        }
        .delivery-payment {
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
<input type="hidden" name="cart_typename" id="cart_typename" value="<?php echo $cart_typename;?>">
<input type="hidden" name="cc_charge" id="cc_charge" value="<?php echo $cc_charge;?>">
<input type="hidden" name="dv_charge" id="dv_charge" value="<?php echo $dv_charge;?>">

<div class="d-flex flex-column flex-lg-row mx-auto main-content-pad-on-mobile">
    <div class="delivery-payment">
        <div class="my-cart-header d-flex">
            <div class="flex-fill">
                <h5 class="fw-bold">Check out</h5>
            </div>
        </div>
        <!-- Tab Selector -->
        <div class="delivery-section">
            <h6>Order Type</h6>
            <div class="delivery-container mb-30">
                <div class="">
                    <?php if ($this->data['payment_charges'] &&  
                             ($this->data['payment_charges']->collection  == 1 || $this->data['payment_charges']->collection == '1' || 
                              $this->data['payment_charges']->delivery    == 1 || $this->data['payment_charges']->delivery   == '1' )) { ?>
                    <ul class="d-inline-flex order-methods mt-10 mb-10" role="tablist" aria-label="Delivery Methods">
                        <?php if ($this->data['payment_charges'] && 
                                 ($this->data['payment_charges']->collection  == 1 || $this->data['payment_charges']->collection == '1')) { ?>
                            <li class="one-order-method pickup-depot 
                              <?= $this->data['payment_charges']->collection  == 1 || $this->data['payment_charges']->collection == '1' ? /*'active' */'' : '' ?>"
                                data-bs-toggle="pill"
                                data-bs-target="#pane-pickup-depot" 
                                role="tab" 
                                aria-controls="pane-pickup-depot" 
                            >
                                Collection
                            </li>
                        <?php } ?>
                        <?php if ($this->data['payment_charges'] && 
                                 ($this->data['payment_charges']->delivery  == 1 || $this->data['payment_charges']->delivery  == '1' )) { ?>
                            <li class="nav-link one-order-method via-delivery 
                              <?= $this->data['payment_charges']->collection!= 1 && $this->data['payment_charges']->collection!= '1' && 
                                 ($this->data['payment_charges']->delivery  == 1 || $this->data['payment_charges']->delivery  == '1') ? /*'active' */'' : '' ?>"
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
                            <div id="pane-pickup-depot" class="tab-pane fade d-none
                              <?= $this->data['payment_charges']->collection  == 1 || $this->data['payment_charges']->collection == '1' ? 'active show' : '' ?>"
                                role="tabpanel" 
                                aria-labelledby="tab-pickup-depot"
                            >
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
                                        <select id='collection_date' name='collection_date' class="form-select">
                                            <?php 
                                                foreach($collection_delivery_dates as $index => $c_d_date) {
                                                    if (!empty($c_d_date)) {
                                                        $date_dt_value = $c_d_date->format('d/m/Y');
                                                        $date_df_value = $c_d_date->format('l');
                                                        $date_tz_value = $c_d_date->getTimezone(); // DateTimeZone object
                                                        echo '<option value="' . $date_dt_value . '" '.( $index == 0 ? 'selected' : '') . '>' . $date_df_value . ' ' . $date_dt_value . '</option>';
                                                    } else {
                                                        echo '<option value=""'.( $index == 0 ? 'selected' : '') . '></option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-20" style="color: black; font-size: 17px; font-weight: bold;">
                                    Please select a collection container
                                </div>
                                <?php if (!empty($this->data['payment_charges']) &&
                                          !empty($this->data['payment_charges']->cc_container_types) && 
                                          in_array('pallet', $this->data['payment_charges']->cc_container_types)) { ?>
                                    <div class="one-collection-container d-flex align-items-center p-2 px-4 mt-2">
                                        <div class="flex-fill">Pallet</div>
                                        <div>
                                            <input class="form-check-input" type="radio" name="collection_container" 
                                                id="pallet" value="pallet" 
                                            >
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($this->data['payment_charges']) &&
                                          !empty($this->data['payment_charges']->cc_container_types) && 
                                          in_array('cage', $this->data['payment_charges']->cc_container_types)) { ?>
                                    <div class="one-collection-container d-flex align-items-center p-2 px-4 mt-2">
                                        <div class="flex-fill">Cage</div>
                                        <div>
                                            <input class="form-check-input" type="radio" name="collection_container" 
                                                id="cage" value="cage" 
                                            >
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($this->data['payment_charges']) &&
                                          !empty($this->data['payment_charges']->cc_container_types) && 
                                          in_array('trolley', $this->data['payment_charges']->cc_container_types)) { ?>
                                    <div class="one-collection-container d-flex align-items-center p-2 px-4 mt-2">
                                        <div class="flex-fill">Trolley</div>
                                        <div>
                                            <input class="form-check-input" type="radio" name="collection_container" 
                                                id="trolley" value="trolley" 
                                            >
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($this->data['payment_charges']) &&
                                          !empty($this->data['payment_charges']->cc_container_types) && 
                                          in_array('box', $this->data['payment_charges']->cc_container_types)) { ?>
                                    <div class="one-collection-container d-flex align-items-center p-2 px-4 mt-2">
                                        <div class="flex-fill">Box</div>
                                        <div>
                                            <input class="form-check-input" type="radio" name="collection_container" 
                                                id="box" value="box" 
                                            >
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (  empty($this->data['payment_charges']) ||
                                            empty($this->data['payment_charges']->cc_container_types) || (
                                            !in_array('pallet',  $this->data['payment_charges']->cc_container_types) &&
                                            !in_array('cage',    $this->data['payment_charges']->cc_container_types) &&
                                            !in_array('trolley', $this->data['payment_charges']->cc_container_types) &&
                                            !in_array('box',     $this->data['payment_charges']->cc_container_types) )) { ?>
                                    <div style="padding: 1rem;
                                                font-size: 80%;
                                                border: 1px solid red;
                                                border-radius: 10px;
                                                color: red;
                                                margin-top: 10px;">
                                        <div><i class="bi bi-exclamation-triangle-fill" style="margin-right: 5px"></i>Error</div>
                                        <div>Please ask to have the correct container types set for your account.</div>    
                                        <div>You cannot check out until this has been done.</div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php if ($this->data['payment_charges'] && 
                                 ($this->data['payment_charges']->delivery  == 1 || $this->data['payment_charges']->delivery == '1' )) { ?>
                            <div id="pane-via-delivery" class="tab-pane fade d-none
                              <?= $this->data['payment_charges']->collection!= 1 && $this->data['payment_charges']->collection!= '1' && 
                                 ($this->data['payment_charges']->delivery  == 1 || $this->data['payment_charges']->delivery  == '1') ? 'active show' : '' ?>"
                                role="tabpanel" 
                                aria-labelledby="tab-via-delivery"
                            >
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
                                        <select id='delivery_date' name='delivery_date' class="form-select">
                                            <?php 
                                                foreach($collection_delivery_dates as $index => $c_d_date) {
                                                    if (!empty($c_d_date)) {
                                                        $date_dt_value = $c_d_date->format('d/m/Y');
                                                        $date_df_value = $c_d_date->format('l');
                                                        $date_tz_value = $c_d_date->getTimezone(); // DateTimeZone object
                                                        echo '<option value="' . $date_dt_value . '" '.( $index == 0 ? 'selected' : '') . '>' . $date_df_value . ' ' . $date_dt_value . '</option>';
                                                    } else {
                                                        echo '<option value=""'.( $index == 0 ? 'selected' : '') . '></option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-20" style="color: black; font-size: 17px; font-weight: bold;">
                                    Please select a delivery container
                                </div>
                                <?php if (!empty($this->data['payment_charges']) &&
                                          !empty($this->data['payment_charges']->dv_container_types) && 
                                          in_array('pallet', $this->data['payment_charges']->dv_container_types)) { ?>
                                    <div class="one-delivery-container d-flex align-items-center p-2 px-4 mt-2">
                                        <div class="flex-fill">Pallet</div>
                                        <div>
                                            <input class="form-check-input" type="radio" name="delivery_container" 
                                                id="pallet" value="pallet" 
                                            >
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($this->data['payment_charges']) &&
                                          !empty($this->data['payment_charges']->dv_container_types) && 
                                          in_array('cage', $this->data['payment_charges']->dv_container_types)) { ?>
                                    <div class="one-delivery-container d-flex align-items-center p-2 px-4 mt-2">
                                        <div class="flex-fill">Cage</div>
                                        <div>
                                            <input class="form-check-input" type="radio" name="delivery_container" 
                                                id="cage" value="cage" 
                                            >
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($this->data['payment_charges']) &&
                                          !empty($this->data['payment_charges']->dv_container_types) && 
                                          in_array('trolley', $this->data['payment_charges']->dv_container_types)) { ?>
                                    <div class="one-delivery-container d-flex align-items-center p-2 px-4 mt-2">
                                        <div class="flex-fill">Trolley</div>
                                        <div>
                                            <input class="form-check-input" type="radio" name="delivery_container" 
                                                id="trolley" value="trolley" 
                                            >
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($this->data['payment_charges']) &&
                                          !empty($this->data['payment_charges']->dv_container_types) && 
                                          in_array('box', $this->data['payment_charges']->dv_container_types)) { ?>
                                    <div class="one-delivery-container d-flex align-items-center p-2 px-4 mt-2">
                                        <div class="flex-fill">Box</div>
                                        <div>
                                            <input class="form-check-input" type="radio" name="delivery_container" 
                                                id="box" value="box" 
                                            >
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if (  empty($this->data['payment_charges']) ||
                                            empty($this->data['payment_charges']->dv_container_types) || (
                                            !in_array('pallet',  $this->data['payment_charges']->dv_container_types) &&
                                            !in_array('cage',    $this->data['payment_charges']->dv_container_types) &&
                                            !in_array('trolley', $this->data['payment_charges']->dv_container_types) &&
                                            !in_array('box',     $this->data['payment_charges']->dv_container_types) )) { ?>
                                    <div style="padding: 1rem;
                                                font-size: 80%;
                                                border: 1px solid red;
                                                border-radius: 10px;
                                                color: red;
                                                margin-top: 10px;">
                                        <div><i class="bi bi-exclamation-triangle-fill" style="margin-right: 5px"></i>Error</div>
                                        <div>Please ask to have the correct container types set for your account.</div>    
                                        <div>You cannot check out until this has been done.</div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if (!$this->data['payment_charges'] ||  
                             ( $this->data['payment_charges']->collection  != 1 && $this->data['payment_charges']->collection != '1' && 
                               $this->data['payment_charges']->delivery    != 1 && $this->data['payment_charges']->delivery   != '1' )) { ?>
                        <div style="padding: 1rem;
                                    font-size: 80%;
                                    border: 1px solid red;
                                    border-radius: 10px;
                                    color: red;
                                    margin-top: 10px;">
                            <div><i class="bi bi-exclamation-triangle-fill" style="margin-right: 5px"></i>Error</div>
                            <div>There is not any order type.</div>
                            <div>Please ask to have the correct container types set for your account.</div>    
                            <div>You cannot check out until this has been done.</div>
                        </div>
                    <?php } ?>
                </div>
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

    <div>
        <div class="billing card ms-0 mt-4 ms-lg-4">
            <div class="card-header">
                <h5 class="card-title">Billing Details</h5>
            </div>

            <div class="card-body">
                <div class="billing-item d-flex">
                    <div class="flex-fill me-8 charge-value-on-mobile"><label>Item Total</label></div>
                    <div><span class="value" id="pay_total_amount">£<?= $total_amount ?></span></div>
                </div>
                <div class="billing-item d-flex delivery-charge-v-in-right-sidebar d-none">
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
                    <a href="#" id="confirm_order" class="btn btn-danger w-100 d-none">Confirm</a>
                </div>
                <div class="">
                    <a href="#" id="send_orders" class="btn btn-danger w-100">Next</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= view("v2/partials/confirm_order_modal"); ?>
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
        .then(function(res) {
            if (cb_success) {
                cb_success(res)
            } else {
                return {success: true}
            }
        })
        .catch(function(error) {
            console.log(`make order error: ${error}`)
            if (cb_error) {
                cb_error(error)
            } else {
                showToast({type: "error", message: d.error});
            }
        })
    }

    $(document).on('click', '#confirm_order', function(e) {
        e.preventDefault();
        $.ajax({
            type : "GET"
            , async : true
            , url:"<?php echo base_url(); ?>orders/confirm_order"
            , dataType : "json"
            , timeout : 30000
            , cache : false
            , error : function (xhr, status, error) {
                if (xhr.status == 401) {
                    window.location.href = '/login'; return;
                } else {
                    console.log("An error occured: " + xhr.status + " " + xhr.statusText);
                }}
            , success : function(response, status, request) {
                const confirm_order_modal = $("#confirm_order_dialog");
                const modal = new bootstrap.Modal(confirm_order_modal[0]);
                modal.show();
            }
        });
    })

    $(document).on('submit', '#confirm_order_form', function(e) {
        event.preventDefault();
		$.ajax({
			url:"<?php echo base_url(); ?>orders/check_order_number",
			method:"POST",
			data:new FormData(this),
			contentType:false,
			cache:false,
			processData:false,
			beforeSend:function(){
				// $('#import_csv_btn').html('Importing...');
			},
			error: function (xhr, status, error) {
				if (xhr.status == 401) {
					window.location.href = '/login'; return;
				} else {
					console.log("An error occured: " + xhr.status + " " + xhr.statusText);
				}},
			success:function(res) {
                if (res.success) {
                    $('#send_orders').click();
                } else {
                    showToast({type: 'error', message: res.msg})
                }
			},
            complete: function() {
                remove_loadingSpinner_from_button(e.target);

                $('#pin_verify_number').val('')

                modalId = $(e.target).attr('id').replace('form', 'dialog')
                $(`#${modalId}`).find(".btn-close").click();
            }
		})
    })

    $(document).on('click', '#send_orders', function(e) {
        let pay_total_amount    = $('#pay_total_amount').text();
        pay_total_amount = pay_total_amount.slice(1);
        if (parseFloat(pay_total_amount) == 0) {
            alert_message('There is not any product to order.')
            return;
        }

        let cart_typename = $('#cart_typename').val();
        var arr = cart_typename.split(',');
        if (arr.length == 0) {
            alert_message('Please select trolley to order.');
            return;
        }
        
        let order_method = $('.order-methods li.active').data('bs-target')
        if(!order_method) {
            alert_message('Please select collection or delivery.')
            return;
        }

        let order_date = (order_method == '#pane-pickup-depot') ?
                          $('#collection_date').val() :
                          $('#delivery_date'  ).val() ;
        if(!order_date) {
            alert_message('Please select a required for date.')
            return;
        }
        
        let order_container       = '';
        let collection_container  = $('input[name="collection_container"]:checked').val();
        let delivery_container    = $('input[name="delivery_container"  ]:checked').val();
        if ((order_method == '#pane-pickup-depot' && !collection_container) || 
            (order_method != '#pane-pickup-depot' && !delivery_container  ))
        {
            alert_message('Please select a container type.')
            return;
        } else {
            order_container = order_method == '#pane-pickup-depot' ? collection_container : delivery_container;
        }

        let payment_method = $('input[name="payment_method"]:checked').val();
        if(!payment_method) {
            alert_message('Please select a payment method.')
            return;
        }

        let delivery_charge = $('#delivery_charge_v').text();

        if (arr.length == 1) {
            let payload = {
                delivery_method:        order_method,
                delivery_date:          order_date,
                delivery_charge:        delivery_charge,
                collection_container:   order_container,
                payment_method:         payment_method
            };
           
            let res = make_order(arr[0], payload, function(res) {
                debugger
                const {success, msg} = res;
                if (success) {
                    showToast({
                        type: 'success',
                        message: "Product order has done successfully.",
                    });
                    let url = `<?php echo base_url("");?>myaccount/order_history`;
	                window.location.href = url;
                } else {
                    alert_message(res.msg)
                }
            });
        } else {
            const confirm_order_trolley_modal = $("#confirm_order_trolley_dialog");
            const modal = new bootstrap.Modal(confirm_order_trolley_modal[0], {backdrop: 'static'});
            modal.show();
        }
    })

    $(document).on('click', '.one-order-method.pickup-depot', function(e) {
        $('#pane-pickup-depot').removeClass('d-none');
        $('.delivery-charge-v-in-right-sidebar').removeClass('d-none');
        $('#order_type_label').text('Click & Collect');
        $('.delivery-charge-v-in-right-sidebar').removeClass('must-hide');

        let pay_total_amount    = $('#pay_total_amount').text();
        let pay_total_vats      = $('#pay_total_vats').text();
        let pay_charge          = $('#cc_charge').val();

        pay_total_amount = pay_total_amount.slice(1);
        pay_total_vats = pay_total_vats.slice(1);

        $('#pay_total_amount').text('£'+parseFloat(pay_total_amount).toFixed(2));
        $('#pay_total_vats').text('£'+parseFloat(pay_total_vats).toFixed(2));
        $('#charge').text('£'+parseFloat(pay_charge).toFixed(2));

        let cart_subtotal2 = parseFloat(pay_total_amount) + parseFloat(pay_charge) + parseFloat(pay_total_vats);
        $('#cart_subtotal2').text('£'+cart_subtotal2.toFixed(2));
        
        const data = {
            cart_typename:          $('#cart_typename').val(),
            order_type:             'collection',
            payment_method:         $('[name="payment_method"]:checked').val(),
            collection_container:   $('[name="collection_container"]:checked').val(),
            delivery_container:     $('[name="delivery_container"]:checked').val(),
            order_date:             $('#collection_date').val(),
        }
       
        const queryParams = new URLSearchParams(data);

        let url = `/orders/payment?${queryParams}`;
        history.replaceState(null, '', url)
    })

    $(document).on('click', '.one-order-method.via-delivery', function(e) {
        $('#pane-via-delivery').removeClass('d-none');
        $('.delivery-charge-v-in-right-sidebar').removeClass('d-none');
        $('#order_type_label').text('Delivery Charge');
        $('.delivery-charge-v-in-right-sidebar').removeClass('must-hide');

        let pay_total_amount    = $('#pay_total_amount').text();
        let pay_total_vats      = $('#pay_total_vats').text();
        let pay_charge          = $('#dv_charge').val();

        pay_total_amount = pay_total_amount.slice(1);
        pay_total_vats = pay_total_vats.slice(1);

        $('#pay_total_amount').text('£'+parseFloat(pay_total_amount).toFixed(2));
        $('#pay_total_vats').text('£'+parseFloat(pay_total_vats).toFixed(2));
        $('#charge').text('£'+parseFloat(pay_charge).toFixed(2));

        let cart_subtotal2 = parseFloat(pay_total_amount) + parseFloat(pay_charge) + parseFloat(pay_total_vats);
        $('#cart_subtotal2').text('£'+cart_subtotal2.toFixed(2));

        const data = {
            cart_typename:          $('#cart_typename').val(),
            order_type:             'delivery',
            payment_method:         $('[name="payment_method"]:checked').val(),
            collection_container:   $('[name="collection_container"]:checked').val(),
            delivery_container:     $('[name="delivery_container"]:checked').val(),
            order_date:             $('#delivery_date').val(),
        }
        
        const queryParams = new URLSearchParams(data);

        let url = `/orders/payment?${queryParams}`;
        history.replaceState(null, '', url)
    })

    $(document).on('click', '[name="payment_method"]', function(e) {
        const params = new URLSearchParams(window.location.search);

        const cart_typename         = params.get('cart_typename');
        const order_type            = params.get('order_type');
        const payment_method        = $(e.target).val();
        const collection_container  = params.get('collection_container');
        const delivery_container    = params.get('delivery_container');
        const order_date            = params.get('order_date');

        if (payment_method == 'pay_in_card') {
            $('#send_orders').text('Make Payment and Submit Order')
        } else {
            $('#send_orders').text('Submit Order')
        }

        const data = {
            cart_typename:          cart_typename,
            order_type:             order_type,
            payment_method:         payment_method,
            collection_container:   collection_container,
            delivery_container:     delivery_container,
            order_date:             order_date,
        }
        
        const queryParams = new URLSearchParams(data);

        let url = `/orders/payment?${queryParams}`;
        history.replaceState(null, '', url)

    })

    $(document).on('click', '[name="collection_container"]', function(e) {
        const params = new URLSearchParams(window.location.search);

        const cart_typename         = params.get('cart_typename');
        const order_type            = params.get('order_type');
        const payment_method        = params.get('payment_method');
        const collection_container  = $(e.target).val();
        const delivery_container    = params.get('delivery_container');
        const order_date            = params.get('order_date');

        const data = {
            cart_typename:          cart_typename,
            order_type:             order_type,
            payment_method:         payment_method,
            collection_container:   collection_container,
            delivery_container:     delivery_container,
            order_date:             order_date,
        }
        
        const queryParams = new URLSearchParams(data);

        let url = `/orders/payment?${queryParams}`;
        history.replaceState(null, '', url)
    })

    $(document).on('click', '[name="delivery_container"]', function(e) {
        const params = new URLSearchParams(window.location.search);

        const cart_typename         = params.get('cart_typename');
        const order_type            = params.get('order_type');
        const payment_method        = params.get('payment_method');
        const collection_container  = params.get('collection_container');
        const delivery_container    = $(e.target).val();
        const order_date            = params.get('order_date');

        const data = {
            cart_typename:          cart_typename,
            order_type:             order_type,
            payment_method:         payment_method,
            collection_container:   collection_container,
            delivery_container:     delivery_container,
            order_date:             order_date,
        }
        
        const queryParams = new URLSearchParams(data);

        let url = `/orders/payment?${queryParams}`;
        history.replaceState(null, '', url)
    })

    $(document).on('click', '#collection_date', function(e) {
        const params = new URLSearchParams(window.location.search);

        const cart_typename         = params.get('cart_typename');
        const order_type            = params.get('order_type');
        const payment_method        = params.get('payment_method');
        const collection_container  = params.get('collection_container');
        const delivery_container    = params.get('delivery_container');
        const order_date            = $(e.target).val();

        $('#delivery_date').val(order_date);

        const data = {
            cart_typename:          cart_typename,
            order_type:             order_type,
            payment_method:         payment_method,
            collection_container:   collection_container,
            delivery_container:     delivery_container,
            order_date:             order_date,
        }
        
        const queryParams = new URLSearchParams(data);

        let url = `/orders/payment?${queryParams}`;
        history.replaceState(null, '', url)
    })

    $(document).on('click', '#delivery_date', function(e) {
        const params = new URLSearchParams(window.location.search);

        const cart_typename         = params.get('cart_typename');
        const order_type            = params.get('order_type');
        const payment_method        = params.get('payment_method');
        const collection_container  = params.get('collection_container');
        const delivery_container    = params.get('delivery_container');
        const order_date            = $(e.target).val();

        $('#collection_date').val(order_date);

        const data = {
            cart_typename:          cart_typename,
            order_type:             order_type,
            payment_method:         payment_method,
            collection_container:   collection_container,
            delivery_container:     delivery_container,
            order_date:             order_date,
        }
        
        const queryParams = new URLSearchParams(data);

        let url = `/orders/payment?${queryParams}`;
        history.replaceState(null, '', url)
    })

    $(document).ready(function() {
        const params                = new URLSearchParams(window.location.search);

        const order_type            = params.get('order_type');
        const payment_method        = params.get('payment_method');
        const collection_container  = params.get('collection_container');
        const delivery_container    = params.get('delivery_container')
        const order_date            = params.get('order_date');

        if (payment_method) {
            $(`#${payment_method}`).click();
        } 
        if (collection_container) {
            $(`#${collection_container}`).click();
        }
        if (delivery_container) {
            $(`#${delivery_container}`).click();
        }
        if (order_date) {
            $(`#collection_date`).val(order_date);
            $(`#delivery_date`  ).val(order_date);
        }

        const charge = $('#charge').text();

        if (order_type) {
            let bsTgtStr = order_type == 'collection' ? '#pane-pickup-depot' : '#pane-via-delivery';
            let el = $(`[data-bs-target="${bsTgtStr}"]`);
            if (!el.hasClass('active') || charge == undefined || charge == '' ) {
                el.click();
            }
        } else {
            let el = $('.delivery-methods li')
            if (el.length == 0) {
                $('.delivery-charge-v-in-right-sidebar').addClass('must-hide');
                let pay_total_amount    = $('#pay_total_amount').text();
                let pay_total_vats      = $('#pay_total_vats').text();

                pay_total_amount = pay_total_amount.slice(1);
                pay_total_vats = pay_total_vats.slice(1);

                let cart_subtotal2 = parseFloat(pay_total_amount) + parseFloat(pay_total_vats);
                $('#cart_subtotal2').text('£'+cart_subtotal2.toFixed(2));
                $('#pay_total_amount').text('£'+parseFloat(pay_total_amount).toFixed(2));
                $('#pay_total_vats').text('£'+parseFloat(pay_total_vats).toFixed(2));
            } else {
                let selected_el_bsTarget = $(el[0]).data('bs-target');
                if ((order_type == 'collection' && selected_el_bsTarget != '#pane-pickup-depot') ||
                    (order_type == 'delivery' && selected_el_bsTarget != '#pane-via-delivery')) {
                    el[0].click();
                }
            }
        }

        let pay_total_amount    = $('#pay_total_amount').text();
        let pay_total_vats      = $('#pay_total_vats').text();

        pay_total_amount = pay_total_amount.slice(1);
        pay_total_vats = pay_total_vats.slice(1);

        $('#pay_total_amount').text('£'+parseFloat(pay_total_amount).toFixed(2));
        $('#pay_total_vats').text('£'+parseFloat(pay_total_vats).toFixed(2));

        let cart_subtotal2 = parseFloat(pay_total_amount) + parseFloat(pay_total_vats);
        $('#cart_subtotal2').text('£'+cart_subtotal2.toFixed(2));
    })

    $(document).on('click', '#confirm_order_trolley_dialog .order-complete', function(e) {
        let delivery_date           = $('#delivery_date').val()
        let delivery_charge         = $('#delivery_charge_v').text()
        let collection_container    = $('input[name="collection_container"]:checked').val()
        let payment_method          = $('input[name="payment_method"]:checked').val()
        let delivery_method         = $('.delivery-methods li.active').data('bs-target')

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
                const {success, msg} = res;
                if (success) {
                    showToast({
                        type: 'success',
                        message: "Product order has done successfully.",
                    });
                    let url = `<?php echo base_url("");?>pastorders`;
	                window.location.href = url;
                } else {
                    showToast({
                        type: 'error',
                        message: res.msg,
                    });
                    location.reload();
                }
            });
        } else {
            let cart_typename = $('#cart_typename').val();
            var arr = cart_typename.split(',');

            let res = Promise.all(arr.map(function(type) {
                return make_order(type, payload)
            }))
            .then(function(responses) {
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