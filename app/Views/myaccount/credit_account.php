<?php echo view("partial/header"); ?>
<div class="d-flex flex-column align-items-center mt-4 my-account-main p-2">
    <!-- Credit Account -->
    <?php 
    if(isset($credit_account)) {
    ?>
    <div class="credit-account p-4">
        <h4 class="m-0 mb-4">Credit Account</h4>
        <div class="d-flex">
            <div class="flex-fluid text-left">
                <label>Spent</label> 
                <span class="value ms-2">£<?= $credit_account['balance'] ?></span>
            </div>
            <div class="flex-fluid text-right">
                <label>Available</label> 
                <span class="value ms-2">£<?= $credit_account['credit_limit'] - $credit_account['balance'] ?></span>
            </div>
        </div>

        <div class='credit-progress d-flex mt-1'>
            <div class='spent' style='width:<?= $credit_account['spent_percent'] ?>%'></div>
            <div class='available' style='width:<?= $credit_account['available_percent'] ?>%'></div>
        </div>

        <div class="balance-detail d-flex justify-content-between mt-4">
            <div class='balance text-left'>
                <label>Balance</label>
                <div class="value">£<?= $credit_account['balance'] ?></div>
            </div>
            <div class="not-yet-due">
                <label>Not Due Yet</label>
                <div class="value">£<?= $credit_account['not_due'] ?></div>
            </div>
            <div class="due">
                <label>Due</label>
                <div class="value">£<?= $credit_account['due'] ?></div>
            </div>
            <div class="over-due text-red">
                <div>Over Due</div>
                <div class="fw-bold">£<?= $credit_account['overdue'] ?></div>
            </div>
        </div>

        <div class="d-flex mt-4">
            <div>
                <label>Credit Limit:</label>
                <span class="ms-2 value">£<?= $credit_account['credit_limit'] ?></span>
            </div>
            <div class="flex-fluid text-right ms-4">
                <label>Terms:</label>
                <span><?= $credit_account['terms'] ?></span>
            </div>
        </div>

        <div class="d-flex mt-4">
            <div>
                <label>Payable:</label>
                <span class="ms-2 value">£9999.90</span>
            </div>
            <div class="flex-fluid text-right ms-4">
                * There may be recent charges or payment pending.
            </div>
        </div>
    </div>
    <?php } ?>


    <!-- send payment -->
    <div class="send-payment p-4 mt-8">
        <form id='payment_form'>
            <h4 class="m-0 mb-4">Send a Payment</h4>
            <div class="d-flex text-left">
                <div class="branch d-flex flex-column">
                    <label class="ms-2">Branch:</label>
                    <div class="d-flex flex-fluid align-items-end">
                        <select name='payment_branch' id='payment_branch'>
                            <?php 
                            foreach($branches as $branch) {
                            ?>
                            <option value="<?= $branch['id'] ?>"><?= $branch['site_name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="ms-4 pay-amount flex-fluid d-flex flex-column">
                    <label>Amount to Pay:</label>
                    <div class="flex-fluid d-flex align-items-end">
                        <input type="number" id="pay_amount" name="pay_amount" placeholder="Enter Amount" required/>
                    </div>
                </div>
                <div class="ms-4 d-flex flex-column">
                    <label>&nbsp;</label>
                    <div class="flex-fluid">
                        <button type="submit" id="send_payment" class="btn btn-success">Pay</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="last-payment mt-8">
            <p class="fs-120 text-left text-color-888 ps-2">Your last payment details:</p>
            <table class="gh-table">
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
</div>
<?php echo view("partial/footer"); ?>

<script type="text/javascript">
function registerEventHandlers() {
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
                    alert("An error occured: " + xhr.status + " " + xhr.statusText);
                }},
            success:function(d) {
                if(d.success == true) {
                    window.open(d.data.url, "Payment");
                } else {
                    toast("error", d.error);
                }

                $('#send_payment').prop('disabled', false);
            }
        })
        return false;
    });
}

$(document).ready(function() {
    registerEventHandlers();

    <?php if($mode == 'balance') { ?>
        scrollToElement($(".credit-account")[0]);
    <?php } else if($mode == 'payment') { ?>
        scrollToElement($(".send-payment")[0]);
    <?php } ?>
})
</script>