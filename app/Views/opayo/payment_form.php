<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Payment Gateway...</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .redirect-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="redirect-card p-5 text-center">
                    <div class="spinner-border text-primary mb-4" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h3 class="mb-3">Redirecting to Payment Gateway</h3>
                    <p class="text-muted">Please wait while we redirect you to the secure payment page...</p>
                    
                    <div class="mt-4">
                        <strong>Order Details:</strong><br>
                        <small class="text-muted">
                            Order ID: <?= $paymentData['order_id'] ?><br>
                            Amount: $<?= $paymentData['amount'] ?><br>
                            Email: <?= $paymentData['customer_email'] ?>
                        </small>
                    </div>
                    
                    <div class="mt-4">
                        <small class="text-muted">
                            If you are not redirected automatically, 
                            <a href="#" onclick="submitForm()">click here</a>.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Hidden form for payment data -->
    <form id="payment-form" method="POST" action="<?= esc($opayo_url) ?>" style="display: none;">
        <?php foreach ($formData as $key => $value): ?>
            <input type="hidden" name="<?= esc(esc($key)) ?>" value="<?= esc(esc($value)) ?>">
        <?php endforeach; ?>
    </form>
    
    <script>
        function submitForm() {
            document.getElementById('payment-form').submit();
        }
        
        // Auto-submit after 3 seconds
        // setTimeout(function() {
        //     submitForm();
        // }, 3000);
    </script>
</body>
</html>