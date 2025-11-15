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
    .customer-reg-info {
        .card-body {
            font-size: 100%;
            input, select {
                font-size: 100%;
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

.customer-register-panel-for-email {
    .form-control, .form-select {
        font-size: 80%;
        line-height: 1;
    }
    * {
        border-radius: 0 !important;
        color: #545454;
    }
    label {
        font-size: 12px !important;
    }
    .form-check.d-flex label {
        line-height: 25px;
    }

}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="customer-register-panel mx-auto">
    <h4 class="text-center mb-4 pt-4-5">New Account Application Form</h4>
    <div class="employee-edit">
        <form id="customer_register_form" class="needs-validation" novalidate style="display: flex; flex-direction: column; gap: 10px;">

            <div class="d-flex2 justify-content-between" style="gap: 10px">
                <div class="customer-reg-info card full-width-on-mobile" style="flex: 2">
                    <div class="card-header p-2">
                        <div class='m-0'>Business</div>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex2" style="gap: 20px">
                            <div class="full-fill">
                                <div class="mb-3">
                                    <label class="required">Business Legal Name:</label>
                                    <input type="text" maxlength="50" class="form-control" 
                                        id="busi_legal_nm" name="busi_legal_nm" 
                                        placeholder="" 
                                        value="" 
                                        required 
                                    />
                                </div>
                                <div class="mb-3">
                                    <label class="required">Business Trading Name:</label>
                                    <input type="text" maxlength="50" class="form-control" 
                                        id="busi_trad_nm" name="busi_trad_nm" 
                                        placeholder="" 
                                        value="" 
                                        required 
                                    />
                                </div>
                                <div class="mb-3">
                                    <label class="required">Business Start Date:</label>
                                    <input type="text" pattern="\d{2}/\d{2}/\d{4}" class="form-control datepicker" 
                                        id="busi_start_dt" name="busi_start_dt" 
                                        placeholder="" 
                                        value="" 
                                        required 
                                    />
                                </div>
                                <div class="mb-3">
									<label for="prefered_branch" class="">Preferred Branch:</label>
									<select class="form-select" id="prefered_branch" name="prefered_branch" >
										<option value="" selected></option>
										<?php foreach ($all_branches as $branch_name) : ?>
											<option value="<?= esc($branch_name) ?>"><?= esc($branch_name) ?></option>
										<?php endforeach; ?>
									</select>
									<div class="invalid-feedback">Please select a branch.</div>
								</div>
                                <div class="form-check form-switch form-check mb-3">
                                    <input class="form-check-input confirm_legal_owner_director" type="checkbox" id="confirm_legal_owner_director" value="confirm_legal_owner_director" required/>
                                    <label class="form-check-label ps-2" for="confirm_legal_owner_director">I confirm I am the Legal Owner or Director</label>
                                    <div class="invalid-feedback">You must agree to this option setting.</div>
                                </div>
                            </div>
                            <div class="full-fill">
                                <div class="mb-3">
                                    <label class="required">Address Line 1:</label>
                                    <input type="text" maxlength="39" class="form-control" 
                                        id="addr_line1" name="addr_line1" 
                                        placeholder="" 
                                        value="" 
                                        required 
                                    />
                                </div>
                                <div class="mb-3">
                                    <label class="">Address Line 2:</label>
                                    <input type="text" maxlength="30" class="form-control" 
                                        id="addr_line2" name="addr_line2" 
                                        placeholder="" 
                                        value="" 
                                    />
                                </div>
                                <div class="mb-3">
                                    <label class="required">County:</label>
                                    <input type="text" maxlength="25" class="form-control" 
                                        id="county" name="county" 
                                        placeholder="" 
                                        value="" 
                                        required 
                                    />
                                </div>
                                <div class="mb-3">
                                    <label class="required">City:</label>
                                    <input type="text" maxlength="25" class="form-control" 
                                        id="city" name="city" 
                                        placeholder="" 
                                        value="" 
                                        required 
                                    />
                                </div>
                                <div class="mb-3">
                                    <label class="required">Post Code:</label>
                                    <input type="text" maxlength="8" class="form-control" 
                                        id="post_code" name="post_code" 
                                        placeholder="" 
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
                <div class="customer-reg-info card full-width-on-mobile" style="flex: 1">
                    <div class="card-header p-2">
                        <div class='m-0'>Contact</div>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label class="required">Contact Name:</label>
                            <input type="text" maxlength="30" class="form-control" 
                                id="contact_nm" name="contact_nm" 
                                placeholder="" 
                                value="" 
                                required 
                            />
                        </div>
                        <div class="mb-3">
                            <label class="">Contact Telephone Landline:</label>
                            <input type="number" max="100000000000" class="form-control" 
                                id="contact_phone_ll" name="contact_phone_ll" 
                                placeholder="" 
                                value="" 
                            />
                            <div class="invalid-feedback">
                                Please provide a number of less than 11-digits.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="required">Contact Telephone Mobile:</label>
                            <input type="number" max="100000000000" class="form-control" 
                                id="contact_phone_mb" name="contact_phone_mb" 
                                placeholder="" 
                                value="" 
                                required 
                            />
                            <div class="invalid-feedback">
                                Please provide a number of less than 11-digits.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="required">Contact email adresss:</label>
                            <input type="email" maxlength="60" class="form-control" 
                                id="contact_email" name="contact_email" 
                                placeholder="" 
                                value="" 
                                required 
                            />
                            <div class="invalid-feedback">
                                Please provide a email type.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="customer-reg-info card full-width-on-mobile" style="flex: 1">
                    <div class="card-header p-2">
                        <div class='m-0'>Company</div>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label class="">Company Number:</label>
                            <input type="text" maxlength="12" class="form-control" 
                                id="company_no" name="company_no" 
                                placeholder="" 
                                value="" 
                            />
                        </div>
                        <div class="mb-3">
                            <label class="">VAT Number:</label>
                            <input type="number" max="10000000000" class="form-control" 
                                id="vat_number" name="vat_number" 
                                placeholder="" 
                                value="" 
                            />
                            <div class="invalid-feedback">
                                Please provide a number of less than 10-digits.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="required break-over-word">For how many years has the business been trading?</label>
                            <input type="number" max="100" class="form-control" 
                                id="busi_trad_years" name="busi_trad_years" 
                                placeholder="" 
                                value="" 
                                required 
                            />
                            <div class="invalid-feedback">
                                Please provide a number of less than 2-digits.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="customer-reg-info card full-width-on-mobile" style="flex: 1">
                    <div class="card-header p-2">
                        <div class='m-0'>Sell</div>
                    </div>
                    <div class="card-body p-3">
                        <ul>
                            <li class="form-check form-switch form-check mb-3">
                                <input class="form-check-input sell-alcohol" type="checkbox" id="alcohol" value="alcohol" />
                                <label class="form-check-label ps-2" for="alcohol">Do you sell Alcohol?</label>
                            </li>
                            <li class="form-check form-switch form-check mb-3">
                                <input class="form-check-input sell-tobacco" type="checkbox" id="tobacco" value="tobacco" />
                                <label class="form-check-label ps-2" for="tobacco">Do you sell Tobacco?</label>
                            </li>
                            <li class="form-check form-switch form-check mb-3">
                                <input class="form-check-input sell-vapes" type="checkbox" id="vapes" value="vapes" />
                                <label class="form-check-label ps-2" for="vapes">Do you sell Vapes?</label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="d-flex2 justify-content-between" style="gap: 10px">
                <div class="customer-reg-info card full-width-on-mobile" style="flex: 1">
                    <div class="card-header p-2">
                        <div class='m-0'>Store</div>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label class="required">Store size in square feet?</label>
                            <input type="number" max="100000" class="form-control" 
                                id="store_sz" name="store_sz" 
                                placeholder="" 
                                value="" 
                                required 
                            />
                            <div class="invalid-feedback">
                                Please provide a number of less than 5-digits.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="required">Store average turnover weekly?</label>
                            <input type="number" max="100000000" class="form-control" 
                                id="store_avg" name="store_avg" 
                                placeholder="" 
                                value="" 
                                required 
                            />
                            <div class="invalid-feedback">
                                Please provide a number of less than 8-digits.
                            </div>
                        </div>
                        <div class="form-check form-switch mb-3 d-flex">
                            <input class="form-check-input self-service" type="checkbox" id="self_service" />
                            <label class="form-check-label ps-2 break-over-word" for="self_service">I will visit the store to purchase goods</label>
                        </div>
                        <div class="form-check form-switch mb-3 d-flex">
                            <input class="form-check-input click-and-collect" type="checkbox" id="click_and_collect" />
                            <label class="form-check-label ps-2 break-over-word" for="click_and_collect">I will order online then come in to collect goods</label>
                        </div>
                        <div class="form-check form-switch mb-3 d-flex">
                            <input class="form-check-input delivered" type="checkbox" id="delivered" />
                            <label class="form-check-label ps-2 break-over-word" for="delivered">I will order onlne and require a delivery of goods</label>
                        </div>
                        <div class="form-check form-switch mb-3 d-flex">
                            <input class="form-check-input offers-and-info" type="checkbox" id="offers_and_info" />
                            <label class="form-check-label ps-2 break-over-word" for="offers_and_info">Do you wish to receive marketing offers and info?</label>
                        </div>
                    </div>
                </div>
                <div class="customer-reg-info card full-width-on-mobile" style="flex: 1">
                    <div class="card-header p-2">
                        <div class='m-0'>Payment & Offer</div>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label class="">What is your prefered payment method? </label>
                            <select class="form-select" name="pref_payment_method" id="pref_payment_method" required>
                                    <option value=""></option>
                                    <option value="cash">Cash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="echo_pay">EchoPay</option>
                                    <option value="card">Card</option>
                                    <option value="credit_facility">Credit Facility</option>
                            </select>
                            <div class="invalid-feedback">
                                Please provide at least one item.
                            </div>
                        </div>
                        <div class="form-check form-switch mb-3 d-flex">
                            <input class="form-check-input credit-acc-facility" type="checkbox" id="credit_acc_facility" />
                            <label class="form-check-label ps-2 break-over-word" for="credit_acc_facility">Do you require a credit account facility?</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-4 gap-10px">
                <button type="submit" class="btn btn-info" id="btn_customer_save" style="min-width: 120px">Submit request</button>
                <button type="button" class="btn btn-danger" id="btn-go-back" style="min-width: 120px">Go Back</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    const emailRegex = /^[A-Za-z0-9_-]+(\.[A-Za-z0-9_-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,})$/;

    (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    {// email special validator including tld or ccld

                        // If the form is valid according to browser's built-in validation,
                        // then apply custom regex validation for email.
                        const emailInput = document.getElementById('contact_email');

                        if(!emailRegex.test(emailInput.value)) {
                            emailInput.classList.add('is-invalid');
                            emailInput.setCustomValidity('Please provide a email type.'); // marks as invalid
                            // emailInput.reportValidity(); // triggers UI and updates :invalid

                            event.preventDefault();
                            // event.stopPropagation();
                        } else {
                            emailInput.classList.remove('is-invalid');
                            emailInput.setCustomValidity(''); // marks as valid
                        }
                    }
                    /*
                    {// datepicker special validator including the date is valid
                        debugger
                        // If the form is valid according to browser's built-in validation,
                        // then apply valid date for datepicker.
                        const dateInput = document.getElementById('busi_start_dt');

                        const parts = dateInput.value.split('/');
                        const day   = parseInt(parts[0], 10);
                        const month = parseInt(parts[1], 10);
                        const year  = parseInt(parts[2], 10);

                        const date = new Date(year, month - 1, day); // Month is 0-indexed
                        if (date.getFullYear() !== year || date.getMonth() + 1 !== month || date.getDate() !== day) {
                            dateInput.classList.add('is-invalid');
                            dateInput.setCustomValidity('Please provide a email type.'); // marks as invalid
                            // emailInput.reportValidity(); // triggers UI and updates :invalid

                            event.preventDefault();
                        } else {
                            dateInput.classList.remove('is-invalid');
                            dateInput.setCustomValidity(''); // marks as valid
                        }
                    }*/
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    } 

                    form.classList.add('was-validated');

                }, false)
            })
    })()

    $(document).ready(function(e) {
        $('#busi_start_dt').datepicker({
            uiLibrary: 'bootstrap5',
            iconsLibrary: 'fontawesome',
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true
        })
        const $extra = $(`<div class="invalid-feedback">
                            Please enter a valid date in 'dd/mm/yyyy' format.'.
                        </div>`);
        $('.gj-datepicker-bootstrap').append($extra); // jQuery handles all matched elements

        $(document).on('keyup', '#contact_email', function(e) {
            if(!$('form#customer_register_form').hasClass('was-validated'))
                return;
            if (emailRegex.test(e.target.value)) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
                this.setCustomValidity(''); // marks as invalid
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
                this.setCustomValidity('Please provide a email type.'); // marks as invalid
            }
        })

        $(document).on('keyup', '#busi_start_dt', function(e) {
            if(!$('form#customer_register_form').hasClass('was-validated'))
                return;
            const dateInput = document.getElementById('busi_start_dt');

            const parts = dateInput.value.split('/');
            const day   = parseInt(parts[0], 10);
            const month = parseInt(parts[1], 10);
            const year  = parseInt(parts[2], 10);

            const date = new Date(year, month - 1, day); // Month is 0-indexed
            if (date.getFullYear() !== year || date.getMonth() + 1 !== month || date.getDate() !== day) {
                dateInput.classList.add('is-invalid');
                dateInput.setCustomValidity('Please provide a email type.'); // marks as invalid
                // emailInput.reportValidity(); // triggers UI and updates :invalid

                event.preventDefault();
            } else {
                dateInput.classList.remove('is-invalid');
                dateInput.setCustomValidity(''); // marks as valid
            }
        })

        $(document).on('click', '#btn-go-back', function(e) {
            add_loadingSpinner_to_button(e.currentTarget);
            window.location.href = '/login';
        })

        $(document).on('submit', 'form#customer_register_form', function(e) {
            debugger
            e.preventDefault();
            const $form = $(e.target);

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

            //  self service
            let self_service = "";
            if ($form.find("input.self-service:checked").length > 0) {
                self_service = 1
            }

            //  click and collect
            let click_and_collect = "";
            if ($form.find("input.click-and-collect:checked").length > 0) {
                click_and_collect = 1
            }

            //  delivered
            let delivered = "";
            if ($form.find("input.delivered:checked").length > 0) {
                delivered = 1
            }

            //  confirm legal owner or director
            let confirm_legal_owner_director = "";
            if ($form.find("input.confirm_legal_owner_director:checked").length > 0) {
                confirm_legal_owner_director = 1
            }

            //  sell alcohol
            let sell_alcohol = "";
            if ($form.find("input.sell-alcohol:checked").length > 0) {
                sell_alcohol = 1
            }
            //  sell tobacco
            let sell_tobacco = "";
            if ($form.find("input.sell-tobacco:checked").length > 0) {
                sell_tobacco = 1
            }
            //  sell vapes
            let sell_vapes = "";
            if ($form.find("input.sell-vapes:checked").length > 0) {
                sell_vapes = 1
            }

            debugger
            const dt = $("#customer_register_form").serialize();
            const params = new URLSearchParams(dt);
            let payload = Object.fromEntries(params.entries());
            payload = {
                ...payload,
                credit_acc_facility:            credit_acc_facility,
                offers_and_info:                offers_and_info,
                self_service:                   self_service,
                click_and_collect:              click_and_collect,
                delivered:                      delivered,
                confirm_legal_owner_director:   confirm_legal_owner_director,
                sell_alcohol:                   sell_alcohol,
                sell_tobacco:                   sell_tobacco,
                sell_vapes:                     sell_vapes
            }
            console.log(payload);

            $.ajax({
                type: "POST"
                , async: true
                , url: "/customer-register"
                , dataType: "json"
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
                    debugger
                    if (response.success == 1) {
                        alert_message('Your request to register has been sent.', 'Info', 'customer-register-form-modal', function(e){
                            location.reload();
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        });
                        return;
                    } else {
                        alert_message('An error occured: ' + response.msg, 'Error', 'customer-register-form-modal', function(e){
                            return;
                        });
                    }
                }
                , complete: function() {
                    remove_loadingSpinner_from_button(e.target);
                }
            });
        })
    })

    

</script>
<?= $this->endSection() ?>
