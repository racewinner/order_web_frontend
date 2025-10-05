<?= $this->extend('v2/layout/main_layout') ?>

<?= $this->section('css') ?>
<style>
table {
    tfoot {
        .invoice-total {
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
        <span>Invoice Detail: </span>
        <span class="ms-2">(<?= $invoice_header['dt'] ?>)</span>
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
            <?php if(isset($transaction_details)) { 
                $total_qty = 0;
                $total_amount = 0;
                foreach($transaction_details as $t) {
                    $total_qty += $t['qty'];
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
                    <td><?= $t['qty'] ?></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="me-2">£<?= $t['total_value'] ?></span>
                            <?php if(isset($t['product'])) { ?>
                                <img 
                                    class="cursor-pointer"
                                    src="/images/icons/add-item-in-cart.svg" 
                                    style="width: 30px; height: 30px;" 
                                    onclick="show_detail('<?= $t['item'] ?>')" 
                                />
                            <?php } else { ?>
                                <img 
                                    class="cursor-pointer"
                                    src="/images/icons/remove-from-cart.svg" 
                                    style="width: 30px; height: 30px;" 
                                />
                            <?php } ?>
                        </div>
                    </td>
                </tr>
            <?php } } else { ?>
                <tr><td colspan="4" class="text-center">No Items</td></tr>
            <?php } ?>
        </tbody>

        <?php if($total_qty > 0) { ?>
        <tfoot>
            <tr><td colspan="4">
                <div class="invoice-total flex-fill p-2 px-4">
                    <div class="total-lines">
                        <?= count($transaction_details) ?> Lines & <?= $total_qty ?> Qty - (<?= $invoice_header['tn'] ?>)
                    </div>
                    <div class="total-amount">
                        Invoice Value: £<?= $total_amount ?>
                    </div>
                </div>
            </td></tr>
        </tfoot>
        <?php } ?>
    </table>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
function show_detail(prod_code) {
    const $view_modal = $(".view-modal");

    $.ajax({
        url: `/products/${prod_code}/show_by_code`,
        type: 'GET',
        error: function (request, status, error) {
            showToas("error", error);
        },
        success:function(data) {
            $view_modal.find('.modal-title').html("Product Detail");
            $view_modal.find(".modal-content .modal-body").html(data);
            const modal = new bootstrap.Modal($view_modal[0]);
            modal.show();
        }
    })
}
</script>
<?= $this->endSection() ?>
