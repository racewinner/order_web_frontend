<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <svg class="text-success" width="64" height="64" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                        
                        <h2 class="text-success mb-3">Payment Successful!</h2>
                        <p class="lead mb-4">Thank you for your payment. Your transaction has been processed successfully.</p>
                        
                        <?php if ($payment): ?>
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5>Transaction Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <strong>Order Id:</strong><br>
                                            <?= esc($payment['order_id']) ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <strong>Amount:</strong><br>
                                            £ <?= number_format($payment['amount'], 2) ?>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-sm-6">
                                            <strong>Description:</strong><br>
                                            <?= esc($payment['description']) ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <strong>Status:</strong><br>
                                            <span class="badge bg-success"><?= esc($payment['status']) ?></span>
                                        </div>
                                    </div>
                                    <?php if ($payment['tx_auth_no']): ?>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <strong>Authorization Number:</strong><br>
                                                <?= esc($payment['tx_auth_no']) ?>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        <?php endif ?>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="<?= base_url() ?>" class="btn btn-primary">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>