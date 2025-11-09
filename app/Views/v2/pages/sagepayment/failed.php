<h1>Payment Failed</h1>
<p>Order ID: <?php echo $order_id; ?></p>
<p>Error: <?php echo $error; ?></p>
<a href="<?php echo base_url('payment/initiate'); ?>">Try Again</a>