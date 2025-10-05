<?= $this->extend('v2/layout/main_layout') ?>

<?= $this->section('css') ?>
<style>
table {
    tfoot {
        .order-total {
            .total-lines {
                color: #111;
            }
            .total-amount {
                font-size: 130%;
                font-weight: bold;
                color: var(--bs-success)
            }
        }
    }
}    
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-xl p-4">
    <h5 class="d-flex align-items-center">
        <a class="cursor-pointer back me-2"><i class="bi bi-arrow-left-short" style="font-size: 40px;"></i></a>
        <span>Order Detail: </span>
        <span class="ms-2">(<?= $order_header['dt'] ?>)</span>
    </h5>

    <table class="table order-primary-table mt-4">
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($order_details)) { 
                $total_qty = 0;
                $total_amount = 0;
                foreach($order_details as $t) {
                    $qty = round(floatval($t['qty_ordered']), 2);
                    $total_qty += $qty;
                    $total_amount += $t['total_value'];
            ?>
                <tr>
                    <td>
                        <?php if(!empty($t['product'])) { ?>
                            <?= view('v2/components/Product', ['view_mode' => 'list_mini', 'product' => $t['product']]) ?>
                        <?php } else { ?>
                            <div class="px-4">
                                <h6 class="fw-bold"><?= $t['description'] ?></h6>
                                <div><label class="me-1">Code:</label> <span><?=$t['item']?></span></div>
                                <div><label class="me-1">Pack:</label> <span><?=$t['pack_description']?></span></div>
                                <div><label class="me-1">RRP:</label> <span>£<?=$t['rrp']?></span></div>
                            </div>
                        <?php } ?>
                    </td>
                    <td>£<?= $t['price'] ?></td>
                    <td><?= $qty ?></td>
                    <td>£<?= $t['total_value'] ?></td>
                </tr>
            <?php } } else { ?>
                <tr><td colspan="4" class="text-center">No Items</td></tr>
            <?php } ?>
        </tbody>

        <?php if($total_qty > 0) { ?>
        <tfoot>
            <tr><td colspan="4">
                <div class="d-flex p-2 px-4">
                    <div class="order-total flex-fill">
                        <div class="total-lines">
                            <?= count($order_details) ?> Lines & <?= $total_qty ?> Qty - (<?= $order_header['on'] ?>)
                        </div>
                        <div class="total-amount">
                            Order Value: £<?= $total_amount ?>
                        </div>
                    </div>
                    <div class="">
                        <a href="#" class="btn btn-danger">Repeat Order</a>
                    </div>
                </div>
            </td></tr>
        </tfoot>
        <?php } ?>
    </table>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<?= $this->endSection() ?>
