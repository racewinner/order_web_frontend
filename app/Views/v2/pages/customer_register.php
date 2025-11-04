<?= $this->extend('v2/layout/auth_layout') ?>

<?= $this->section('css') ?>
<style>
.employee-edit form {
    .card-header {
        font-weight: bold;
    }
    .card-body {
        ul {
            padding: 0;
        }
    }
    .user-login-info {
        .card-body {
            font-size: 80%;
            input, select {
                font-size: 90%;
            }
        }
    }
    &:not(.need-password) {
        #password-section {
            display: none;
        }
    }
}
.user-order-types input.invalid {
    border: 1px solid #ff0000;
    box-shadow: 0px 0px 0px 4px #ffa3a3;
}
.user-table-on-mobile {
    display: none;
}
@media (max-width: 992px) {
    .add_user_btn_on_mobile {
        margin-left: 5px !important;
        padding: 0px !important;
        font-size: 10px;
        text-overflow: ellipsis;
        white-space: nowrap;
        width: 74px;
        overflow: hidden;
    }
    .user-table-on-desktop {
        display: none;
    }
    .user-table-on-mobile {
        display: block;
    }
    .vertical-middle-on-mobile {
        vertical-align: middle;
    }
}
.customer-register-panel {
    /* width: 90%; */
    max-width: 1200px;
    padding-left: 40px;
    padding-right: 40px;
    button#btn-login {
        margin-top: 20px !important;
        width: 100%;
    }
}

.card-row {
    gap: 30px;
}
@media (max-width: 992px) {
    .card-row {
        gap: 0px !important;
    }
}
.flex-fill2 {
    flex: 1 1 !important
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="customer-register-panel mx-auto">
<h4 class="text-center mb-4 pt-4-5">Customer Register</h4>
<div class="employee-edit">
    <form id="customer_register_form" class="" style="display: flex; flex-direction: column; gap: 10px;">
        <input type="hidden" id="sell_taxes" name="sell_taxes" value="" />


        <div class="d-flex2 justify-content-between" style="gap: 10px">
            <div class="user-login-info card full-width-on-mobile" style="flex: 2">
                <div class="card-header p-2">
                    <div class='m-0'>Business</div>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex2" style="gap: 20px">
                        <div class="full-fill">
                            <div class="mb-2">
                                <label class="required">Business Legal Name:</label>
                                <input type="text" class="form-control" 
                                    id="busi_legal_nm" name="busi_legal_nm" 
                                    placeholder="Business Legal Name" 
                                    value="" 
                                    required 
                                />
                            </div>
                            <div class="mb-2">
                                <label class="required">Business Start Date:</label>
                                <input type="text" class="form-control" 
                                    id="busi_start_dt" name="busi_start_dt" 
                                    placeholder="Business Start Date" 
                                    value="" 
                                    required 
                                />
                            </div>
                            <div class="mb-2">
                                <label class="required">Business Trading Name:</label>
                                <input type="text" class="form-control" 
                                    id="busi_trad_nm" name="busi_trad_nm" 
                                    placeholder="Business Trading Name" 
                                    value="" 
                                    required 
                                />
                            </div>
                        </div>
                        <div class="full-fill">
                            <div class="mb-2">
                                <label class="required">Address Line 1:</label>
                                <input type="text" class="form-control" 
                                    id="addr_line1" name="addr_line1" 
                                    placeholder="Address Line 1" 
                                    value="" 
                                    required 
                                />
                            </div>
                            <div class="mb-2">
                                <label class="">Address Line 2:</label>
                                <input type="text" class="form-control" 
                                    id="addr_line2" name="addr_line2" 
                                    placeholder="Address Line 2" 
                                    value="" 
                                />
                            </div>
                        </div>
                        <div class="full-fill">
                            <div class="mb-2">
                                <label class="required">Country:</label>
                                <input type="text" class="form-control" 
                                    id="country" name="country" 
                                    placeholder="Country" 
                                    value="" 
                                    required 
                                />
                            </div>
                            <div class="mb-2">
                                <label class="required">City:</label>
                                <input type="text" class="form-control" 
                                    id="city" name="city" 
                                    placeholder="City" 
                                    value="" 
                                    required 
                                />
                            </div>
                            <div class="mb-2">
                                <label class="required">Post Code:</label>
                                <input type="text" class="form-control" 
                                    id="post_code" name="post_code" 
                                    placeholder="Post Code" 
                                    value="" 
                                    required 
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="d-flex2 justify-content-between" style="gap: 10px">
            <div class="user-login-info card full-width-on-mobile" style="flex: 1">
                <div class="card-header p-2">
                    <div class='m-0'>Contact</div>
                </div>
                <div class="card-body p-3">
                    <div class="mb-2">
                        <label class="required">Contact Name:</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="contact_nm" name="contact_nm" 
                            placeholder="Contact Name" 
                            value="" 
                            required 
                        />
                    </div>
                    <div class="mb-2">
                        <label class="">Contact Telephone Landline:</label>
                        <input type="text" class="form-control" 
                            id="contact_phone_ll" name="contact_phone_ll" 
                            placeholder="Contact Telephone Landline" 
                            value="" 
                        />
                    </div>
                    <div class="mb-2">
                        <label class="required">Contact Telephone Mobile:</label>
                        <input type="text" class="form-control" 
                            id="contact_phone_mb" name="contact_phone_mb" 
                            placeholder="Contact Telephone Mobile" 
                            value="" 
                            required 
                        />
                    </div>
                    <div class="mb-2">
                        <label class="required">Contact email adresss:</label>
                        <input type="text" class="form-control" 
                            id="contact_email" name="contact_email" 
                            placeholder="Contact email adresss" 
                            value="" 
                            required 
                        />
                    </div>
                </div>
            </div>
            <div class="user-login-info card full-width-on-mobile" style="flex: 1">
                <div class="card-header p-2">
                    <div class='m-0'>Company</div>
                </div>
                <div class="card-body p-3">
                    <div class="mb-2">
                        <label class="">Company Number:</label>
                        <input type="text" class="form-control" 
                            id="company_no" name="company_no" 
                            placeholder="Company Number" 
                            value="" 
                        />
                    </div>
                    <div class="mb-2">
                        <label class="">VAT Number:</label>
                        <input type="text" class="form-control" 
                            id="vat_number" name="vat_number" 
                            placeholder="VAT Number" 
                            value="" 
                        />
                    </div>
                    <div class="mb-2">
                        <label class="required">For how many years has the business been trading?</label>
                        <input type="text" class="form-control" 
                            id="busi_trad_years" name="busi_trad_years" 
                            placeholder="For how many years has the business been trading?" 
                            value="" 
                            required 
                        />
                    </div>
                </div>
            </div>
            <div class="user-pricelist card full-width-on-mobile" style="flex: 1">
                <div class="card-header p-2">
                    <div class='m-0'>Sell</div>
                </div>
                <div class="card-body p-3 fs-80">
                    <ul>
                        <li class="form-check mb-2">
                            <input class="form-check-input sell-tax" type="checkbox" value="alcohol" />
                            <label class="form-check-label">Do you sell Alcohol?</label>
                        </li>
                        <li class="form-check mb-2">
                            <input class="form-check-input sell-tax" type="checkbox" value="tobacco" />
                            <label class="form-check-label">Do you sell Tobacco?</label>
                        </li>
                        <li class="form-check mb-2">
                            <input class="form-check-input sell-tax" type="checkbox" value="vapes" />
                            <label class="form-check-label">Do you sell Vapes?</label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="d-flex2 justify-content-between" style="gap: 10px">
            <div class="user-login-info card full-width-on-mobile" style="flex: 1">
                <div class="card-header p-2">
                    <div class='m-0'>Store</div>
                </div>
                <div class="card-body p-3">
                    <div class="mb-2">
                        <label class="required">Store size in square feet?</label>
                        <input type="text" class="form-control" 
                            id="store_sz" name="store_sz" 
                            placeholder="Store size in square feet?" 
                            value="" 
                            required 
                        />
                    </div>
                    <div class="mb-2">
                        <label class="required">Store average turnover weekly?</label>
                        <input type="text" class="form-control" 
                            id="store_avg" name="store_avg" 
                            placeholder="Store average turnover weekly?" 
                            value="" 
                            required 
                        />
                    </div>
                </div>
            </div>
            <div class="user-branches card full-width-on-mobile" style="flex: 1">
                <div class="card-header p-2">
                    <div class='m-0'>Payment & Offer</div>
                </div>
                <div class="card-body p-3">
                    <div class="mb-2 fs-80">
                        <input class="form-check-input credit-acc-facility" type="checkbox" value="credit_acc_facility" />
                        <label class="form-check-label">Do you require a credit account facility?</label>
                    </div>
                    <div class="mb-2">
                        <label class="fs-80">What is your prefered payment method? </label>
                        <select class="form-select fs-80" name="pref_payment_method" id="pref_payment_method">
                                <option value=""></option>
                                <option value="dropdown_cash">dropdown Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="echo_pay">EchoPay</option>
                                <option value="card">Card</option>
                                <option value="credit_facility">Credit Facility</option>
                        </select>
                    </div>
                    <div class="mb-2 fs-80">
                        <input class="form-check-input offers-and-info" type="checkbox" value="offers_and_info" />
                        <label class="form-check-label">Do you wish to receive marketing offers and info?</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4 gap-10px">
            <button type="submit" class="btn btn-info" id="btn_customer_save" style="min-width: 120px">Save</button>
            <button class="btn btn-danger" id="btn-go-back" style="min-width: 120px">Go Back</button>
        </div>
    </form>
</div>
    
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function(e) {
        $(document).on('click', '#btn-go-back', function(e) {
            debugger
            window.location.href = '/login';
        })

        $(document).on('submit', 'form#customer_register_form', function(e) {
            e.preventDefault();
            const $form = $(e.target);
            
            //  sell taxes
            let sell_taxes = [];
            $sell_tax_chkboxes = $form.find("input.sell-tax:checked");
            for(let i=0; i<$sell_tax_chkboxes.length; i++) {
                sell_taxes.push($sell_tax_chkboxes[i].value);
            }
            $form.find("input#sell_taxes").val(sell_taxes);
debugger
            //  credit account facility
            let credit_acc_facility = "";
            if ($form.find("input.credit-acc-facility:checked").length > 0) {
                credit_acc_facility = 1
            }

            
            //  offers and info
            let offers_and_info = "";
            if ($form.find("input.offers-and-info:checked").length > 0) {
                offers_and_info = 1
            }

            debugger
            const dt = $("#customer_register_form").serialize();
            const params = new URLSearchParams(dt);
            let payload = Object.fromEntries(params.entries());
            payload = {
                ...payload,
                sell_taxes: $('input#sell_taxes').val(),
                credit_acc_facility: credit_acc_facility,
                offers_and_info: offers_and_info
            }
            console.log(payload);

            $.ajax({
                type: "POST"
                , async: true
                , url: "customer-register"
                , dataType: "html"
                , timeout: 30000
                , cache: false
                , data: payload
                , error: function (xhr, status, error) {
                    if (xhr.status == 401) {
                        window.location.href = '/login'; return;
                    } else {
                        console.log("An error occured: " + xhr.status + " " + xhr.statusText);
                    }}
                , success: function (response, status, request) {
                    showToast({
                        type: 'success',
                        message: "Customer data was registered successfully.",
                    });
                    setTimeout(function() {
                        window.location.href = '/login';
                    }, 2000)
                }
                , complete: function() {
                    remove_loadingSpinner_from_button(e.target);
                }
            });

        })
    })

    

</script>
<?= $this->endSection() ?>
