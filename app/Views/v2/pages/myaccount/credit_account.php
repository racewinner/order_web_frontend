<?= $this->extend('v2/layout/main_layout') ?>

<?= $this->section('css') ?>
<style>
    .credit-account, .send-payment {
        .card-body {
            font-size: 95%;
            .credit-progress {
                height: 10px;

                .spent {
                    background-color: rgb(235, 100, 21);
                    border-top-left-radius: 10px;
                    border-bottom-left-radius: 10px;
                }

                .available {
                    background-color: #eee;
                    border-top-right-radius: 10px;
                    border-bottom-right-radius: 10px;
                }
            }
            .value {
                color: #111;
                font-weight: bold;
                font-size: 110%;
            }
            .credit-limit .value {
                color: var(--bs-success);
            }
        }
    }
    .balance-detail {
        .balance-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            .tag {
                width: 100%;
                border-radius: 10px;
                padding-top: 20px;
                padding-bottom: 20px;
                img {
                    width: 25px;
                    height: 25px;
                }
            }
            .value {
                font-weight: bold;
                color: #111;
            }
            &.balance .tag {
                background: linear-gradient(to bottom, rgb(238,247,249), rgb(243,251,253));
            }
            &.not-yet-due .tag {
                background: linear-gradient(to bottom, rgb(240,254,251), rgb(242,251,239));
            }
            &.due .tag {
                background: linear-gradient(to bottom, rgb(255,251,241), rgb(254,246,241));
            }
            &.over-due .tag {
                background: linear-gradient(to bottom, rgb(255,242,242), rgb(254,247,243));
            }
        }
    }
    .last-payment {
        margin-top: 30px;
    }
    .make-payment {
        box-shadow: 0px 3px 6px rgba(0,0,0,0.1);
        border-radius: 10px;
        height: fit-content;
        min-width: 400px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="p-4 d-flex">
    <?= view('v2/pages/myaccount/sidebar') ?>

    <div class="flex-fill d-flex flex-column flex-xl-row px-4">
        <div class="flex-fill">
            <!-- Credit Account -->
            <?php if(isset($credit_account)) { ?>
            <div class="credit-account card">
                <div class="card-body">
                    <h5 class="card-title">Credit Account</h5>

                    <div class="d-flex">
                        <div class="flex-fill text-left">
                            <label>Spent</label> 
                            <span class="value ms-2">£<?= $credit_account['balance'] ?></span>
                        </div>
                        <div class="flex-fill text-end">
                            <label>Available</label> 
                            <span class="value ms-2">£<?= $credit_account['credit_limit'] - $credit_account['balance'] ?></span>
                        </div>
                    </div>

                    <div class='credit-progress mt-1 d-flex'>
                        <div class='spent' style='width:<?= $credit_account['spent_percent'] ?>%'></div>
                        <div class='available' style='width:<?= $credit_account['available_percent'] ?>%'></div>
                    </div>

                    <div class="d-flex mt-4">
                        <div class="credit-limit">
                            <label>Credit Limit:</label>
                            <span class="ms-2 value">£<?= $credit_account['credit_limit'] ?></span>
                        </div>
                        <div class="flex-fill text-end ms-4">
                            <label>Terms:</label>
                            <span><?= $credit_account['terms'] ?></span>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex">
                        <div class="flex-fill">Payable:</div>
                        <div class="value">£0.0</div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <p class="comment mt-4" style="font-style: italic;">
                * There may be recent charges or payment pending
            </p>

            <div class="balance-detail d-flex justify-content-between mt-4">
                <div class='balance-item mx-1 mx-md-2 balance'>
                    <div class="tag d-flex flex-column justify-content-center align-items-center">
                        <img src="/images/icons/png/euro-coin-outline.png" />
                        <label>Balance</label>
                    </div>
                    <div class="value">£<?= $credit_account['balance'] ?></div>
                </div>
                <div class="balance-item mx-1 mx-md-2 not-yet-due">
                    <div class="tag d-flex flex-column justify-content-center align-items-center">
                        <img src="/images/icons/png/euro-coin-outline.png" />
                        <label>Not Due Yet</label>
                    </div>
                    <div class="value">£<?= $credit_account['not_due'] ?></div>
                </div>
                <div class="balance-item mx-1 mx-md-2 due">
                    <div class="tag d-flex flex-column justify-content-center align-items-center">
                        <img src="/images/icons/png/euro-coin-outline.png" />
                        <label>Due</label>
                    </div>
                    <div class="value">£<?= $credit_account['due'] ?></div>
                </div>
                <div class="balance-item mx-1 mx-md-2 over-due">
                    <div class="tag d-flex flex-column justify-content-center align-items-center">
                        <img src="/images/icons/png/euro-coin-outline.png" />
                        <label>Over Due</label>
                    </div>
                    <div class="fw-bold">£<?= $credit_account['overdue'] ?></div>
                </div>
            </div>

            <div class="fw-bold last-payment">
                <p class="text-left text-black ps-2">Your last payment details:</p>
                <table class="table order-primary-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Branch</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php if(isset($last_payment)) { ?>
                                <td><?= $last_payment['payment']['timeStamp']->format('d/m') ?></td>
                                <td><?= $last_payment['payment']['timeStamp']->format('H:i') ?></td>
                                <td><?= $last_payment['payment']['branch_name'] ?></td>
                                <td>£<?= $last_payment['payment']['amount'] ?></td>
                                <td>
                                    <div class="payment-status <?= strtolower($last_payment['payment']['status']) ?>">
                                        <span><?= $last_payment['payment']['status'] ?></span>
                                    </div>
                                </td>
                            <?php } else { ?>
                                <td colspan="5" class="text-center">No Payment</td>
                            <?php } ?>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="make-payment p-4 mt-4 ms-0 ms-xl-4 mt-xl-0">
            <form id='payment_form'>
                <h5 class="card-title text-black mt-0">Make a Payment</h5>
                <div class="branch mt-4">
                    <div><label>Branch:</label></div>
                    <select name='payment_branch' id='payment_branch' class="form-select w-100 mt-1">
                        <?php foreach($branches as $branch) { ?>
                        <option value="<?= $branch['id'] ?>"><?= $branch['site_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mt-4 pay-amount">
                    <div><label>Amount to Pay:</label></div>
                    <input type="number" id="pay_amount" name="pay_amount" class="form-control w-100" placeholder="Enter Amount" required/>
                </div>
                <div class="mt-4 pay-action">
                    <button type="submit" id="send_payment" class="btn btn-outline-danger w-100">Pay</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function(e) {
        $(document).on('submit', '#payment_form', function() {
            $('#send_payment').prop('disabled', true);
            $.ajax({
                url: '/myaccount/sendpayment',
                method:"POST",
                data:$("#payment_form").serialize(),
                cache:false,
                processData:false,
                error: function (xhr, status, error) {
                    if (xhr.status == 401) {
                        window.location.href = '/login'; return;
                    } else {
                        $('#send_payment').prop('disabled', false);
                        // alert("An error occured: " + xhr.status + " " + xhr.statusText);
                    }},
                success:function(d) {
                    if(d.success == true) {
                        window.open(d.data.url, "Payment");
                    } else {
                        showToast({type: "error", message: d.error});
                    }

                    $('#send_payment').prop('disabled', false);
                }
            })
            return false;
        });

        <?php if($mode == 'balance') { ?>
            scrollToElement($(".credit-account")[0]);
        <?php } else if($mode == 'payment') { ?>
            scrollToElement($(".send-payment")[0]);
        <?php } ?>
    })
</script>
<?= $this->endSection() ?>