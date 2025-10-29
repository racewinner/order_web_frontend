<div class="employee-edit">
    <form id="employee_form" method="post" action="/employees/save" class="<?= empty($employee) ? 'need-password' : '' ?>">
        <input type="hidden" id="person_id" name="person_id" value="<?= $employee?->person_id ?? '' ?>" />
        <input type="hidden" id="branches" name="branches" value="" />
        <input type="hidden" id="payment_methods" name="payment_methods" value="" />
        <input type="hidden" id="payment_charges" name="payment_charges" value="" />
        <input type="hidden" id="username_email_available" value="0" />

        <div class="d-flex2 justify-content-between" style="gap: 10px">
            <div class="flex-fill user-login-info card full-width-on-mobile">
                <div class="card-header p-2">
                    <div class='m-0'>User Login Info</div>
                </div>
                <div class="card-body p-3">
                    <div class="mb-2">
                        <label>User Band:</label>
                        <select class="form-select" name="presell_band" id="presell_band">
                            <?php foreach($band_options as $key=>$band) { ?>
                                <option value="<?=$key?>" <?= $employee?->presell_band == $key ? 'selected' : '' ?> ><?= $band ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="required">E-Mail:</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="email" name="email" 
                            placeholder="User Email" 
                            value="<?= $employee?->email ?? '' ?>" 
                            required 
                            <?= !empty($employee) ? 'readonly' : '' ?> 
                        />
                    </div>
                    <div class="mb-3">
                        <label class="required">Username:</label>
                        <input type="text" class="form-control" 
                            id="username" name="username" 
                            placeholder="username" 
                            value="<?= $employee?->username ?? '' ?>" 
                            required 
                            <?= !empty($employee) ? 'readonly' : '' ?>
                        />
                    </div>

                    <?php if(!empty($employee)) { ?>
                        <div class="form-check">
                            <input class="form-check-input" id="change_password" type="checkbox" />
                            <label class="form-check-label">Change Password</label>
                        </div>
                    <?php } ?>

                    <section id="password-section">
                        <div class="mb-2">
                            <label class="required">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" <?= empty($employee) ? 'required' : '' ?> />
                        </div>
                        <div class="">
                            <label class="required">Password Again:</label>
                            <input type="password" class="form-control" id="repeat_password" name="repeat_password" placeholder="Password Again" <?= empty($employee) ? 'required' : '' ?> />
                        </div>
                    </section>
                </div>
            </div>
            <div class="flex-fill user-pricelist card full-width-on-mobile">
                <div class="card-header p-2">
                    <div class='m-0'>User PriceList</div>
                </div>
                <div class="card-body p-3">
                    <p class="comment mb-1">Tick the boxes to assign price schemes</p>
                    <ul>
                        <?php foreach($price_options as $key => $lbl) { 
                            $price_field = "price_list" . $key;
                        ?>
                            <li class="form-check mb-3">
                                <input class="form-check-input" name="price_list<?=$key?>" type="checkbox" value="<?= $key ?>" <?= (!empty($employee) && $employee->$price_field == "1") ? 'checked' : '' ?> />
                                <label class="form-check-label"><?= $lbl ?></label>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="flex-fill user-branches card full-width-on-mobile">
                <div class="card-header p-2">
                    <div class='m-0'>Branches</div>
                </div>
                <div class="card-body p-3">
                    <ul>
                        <?php foreach($all_branches as $branch) { ?>
                            <li class="form-check mb-3">
                                <input class="form-check-input branch" type="checkbox" value="<?= $branch['id'] ?>" <?= (!empty($employee) && in_array($branch['id'], $employee->branches)) ? 'checked' : '' ?> />
                                <label class="form-check-label"><?= $branch['site_name'] ?></label>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="flex-fill user-payment-methods card full-width-on-mobile">
                <div class="card-header p-2">
                    <div class='m-0'>Payment Methods</div>
                </div>
                <div class="card-body p-3">
                    <ul>
                        <li class="form-check mb-3">
                            <input class="form-check-input payment-methods" type="checkbox" value="e_order" <?= (!empty($employee) && !empty($payment_methods) && $payment_methods?->e_order) ? 'checked' : '' ?> />
                            <label class="form-check-label">Order</label>
                        </li>
                        <li class="form-check mb-3">
                            <input class="form-check-input payment-methods" type="checkbox" value="depot" <?= (!empty($employee) && !empty($payment_methods) && $payment_methods?->depot) ? 'checked' : '' ?> />
                            <label class="form-check-label">Depot</label>
                        </li>
                        <li class="form-check mb-3">
                            <input class="form-check-input payment-methods" type="checkbox" value="echo_pay" <?= (!empty($employee) && !empty($payment_methods) && $payment_methods?->echo_pay) ? 'checked' : '' ?> />
                            <label class="form-check-label">EchoPay</label>
                        </li>
                        <li class="form-check mb-3">
                            <input class="form-check-input payment-methods" type="checkbox" value="bank_transfer" <?= (!empty($employee) && !empty($payment_methods) && $payment_methods?->bank_transfer) ? 'checked' : '' ?> />
                            <label class="form-check-label">Bank Transfer</label>
                        </li>
                        <li class="form-check mb-3">
                            <input class="form-check-input payment-methods" type="checkbox" value="credit_account" <?= (!empty($employee) && !empty($payment_methods) && $payment_methods?->credit_account) ? 'checked' : '' ?> />
                            <label class="form-check-label">Credit Account</label>
                        </li>
                        <li class="form-check mb-3">
                            <input class="form-check-input payment-methods" type="checkbox" value="debit_credit_card" <?= (!empty($employee) && !empty($payment_methods) && $payment_methods?->debit_credit_card) ? 'checked' : '' ?> />
                            <label class="form-check-label">Debit / Credit Card</label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="user-order-types card mt-4 justify-content-center full-width-on-mobile">
            <div class="card-header p-2">
                <div class='m-0'>Order Types</div>      
            </div>
            <div class="card-body p-3 d-flex2 align-items-center justify-content-around">
                <div class="delivery align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="delivery" 
                            <?= (!empty($employee) && !empty($payment_charges) && $payment_charges?->delivery) ? 'checked' : '' ?> />
                        <label class="form-check-label">Delivery, Delivery Charge:</label>
                    </div>
                    <div>
                        <div class="ms-2 d-flex align-items-center">
                            <span class="me-1" style="padding-right: 4px; margin-left: 15px;">Min Charge</span>
                            <input type="" id="dv-min-charge" name="dv-min-charge" 
                                class="form-control charge-input" value="<?= $payment_charges->dv_min_charge ?? 0 ?>" />
                        </div>
                        <div class="ms-2 d-flex align-items-center my-2">
                            <span class="me-1" style="padding-right: 4px; margin-left: 40px;">per item</span>
                            <input type="" id="dv-per-item" name="dv-per-item" 
                                class="form-control charge-input" value="<?= $payment_charges->dv_per_item ?? 0 ?>" />
                        </div>
                        <div class="ms-2 d-flex align-items-center my-2">
                            <span class="me-1" style="padding-right: 4px; margin-left: 10px;">Max Charge</span>
                            <input type="" id="dv-max-charge" name="dv-max-charge" 
                                class="form-control charge-input" value="<?= $payment_charges->dv_max_charge ?? 0 ?>" />
                        </div>
                        <div class="ms-2 d-flex align-items-center my-2">
                            <label class="form-check-label" style="padding-right: 10px">Min plus Item</label>
                            <input class="form-check-input ms-0" type="checkbox" name="dv_mpi" 
                                <?= (!empty($employee) && !empty($payment_charges) && $payment_charges->dv_mpi == '1') ? 'checked' : '' ?> />
                        </div>
                    </div>
                </div>
                <div class="align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="collect" 
                            <?= (!empty($employee) && !empty($payment_charges) && $payment_charges?->collection) ? 'checked' : '' ?> />
                        <label class="form-check-label">Click and Collect:</label>
                    </div>
                    <div>
                        <div class="ms-2 d-flex align-items-center">
                            <span class="me-1" style="padding-right: 4px; margin-left: 15px;">Min Charge</span>
                            <input type="" id="cc-min-charge" name="cc-min-charge" 
                                class="form-control charge-input" value="<?= $payment_charges->cc_min_charge ?? 0 ?>" />
                        </div>
                        <div class="ms-2 d-flex align-items-center my-2">
                            <span class="me-1" style="padding-right: 4px; margin-left: 40px;">per item</span>
                            <input type="" id="cc-per-item" name="cc-per-item" 
                                class="form-control charge-input" value="<?= $payment_charges->cc_per_item ?? 0 ?>" />
                        </div>
                        <div class="ms-2 d-flex align-items-center my-2">
                            <span class="me-1" style="padding-right: 4px; margin-left: 10px;">Max Charge</span>
                            <input type="" id="cc-max-charge" name="cc-max-charge" 
                                class="form-control charge-input" value="<?= $payment_charges->cc_max_charge ?? 0 ?>" />
                        </div>
                        <div class="ms-2 d-flex align-items-center my-2">
                            <label class="form-check-label" style="padding-right: 10px">Min plus Item</label>
                            <input class="form-check-input ms-0" type="checkbox" name="cc_mpi" 
                                <?= (!empty($employee) && !empty($payment_charges) && $payment_charges->cc_mpi == '1') ? 'checked' : '' ?> />
                        </div>
                    </div>
                </div>
                <!-- <div class="d-flex align-items-center ms-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="pay" value="1" 
                        < ?= ($employee?->pay == '1') ? 'checked' : '' ?> />
                        <label class="form-check-label">Pay</label>
                    </div>
                </div> -->
            </div>
        </div>

        <div class="user-api-key card mt-4 full-width-on-mobile">
            <div class="card-header p-2">
                <div class='m-0'>API Key</div>
            </div>
            <div class="card-body p-3">
                <label>Key:</label>
                <input class="form-control fs-80" name="api_key" id="api_key" value="<?= $employee?->api_key ?? '' ?>" />
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4 gap-10px">
            <button type="button" class="btn btn-warning" id="btn_generate_key">Generate Key</button>
            <button type="button" class="btn btn-success" id="btn_copy_key">Copy Key</button>
            <button type="submit" class="btn btn-info" id="btn_save">Save</button>
        </div>
    </form>
</div>