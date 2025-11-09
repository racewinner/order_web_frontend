<div class="container" style="margin-top:50px;">
    <div class="mx-auto w-100" style="max-width: 600px;">
        <div class="card">
            <div class="card-header">
                <h4>Checkout</h4>
            </div>
            <div class="card-content">
                <form method="GET" action="<?= base_url('sagepayment/initiate') ?>">
                    <?= csrf_field() ?>
                    
                    <div class="mb-4 text-left">
                        <label for="order_id" class="form-label">Order ID</label>
                        <input type="text" class="form-control" id="order_id" name="order_id" value="52454" readonly>
                    </div>
                    
                    <div class="mb-4 text-left">
                        <label for="amount" class="form-label">Amount (£)</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="76.16" readonly>
                    </div>
                    
                    <div class="mb-4 text-left">
                        <label for="customer_email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="customer_email" name="customer_email" value="test_ac_1234@ecso.co.uk" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Pay <span class="">£<?= number_format(76.16, 2) ?></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>