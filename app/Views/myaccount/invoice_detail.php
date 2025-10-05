<?php echo view("partial/header"); ?>
<div class="invoice-detail">
    <div class="my-2 d-flex justify-content-center align-items-center title">
        <img 
            src="/images/icons/arrow_back.svg" 
            class="me-4 cursor-pointer" 
            style="width: 25px; height: 25px;" 
            onclick="window.history.back();" 
        />
        <img src="/images/icons/invoice_pound.svg" style="width: 30px; height: 30px;" />
        <div class="ms-2">
            <span>Invoice Detail </span>
            <span class="ms-2" style="color: #eee;">
                <span>(<?= $invoice_header['tn'] ?></span> 
                <span class="ms-2"><?= $invoice_header['dt'] ?>)</span>
            </span>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <table class="gh-table">
            <thead>
                <tr>
                    <th>Line</th>
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
                        <td><?= $t['line'] ?></td>
                        <td>
                            <div class="fw-bold"><?= $t['description'] ?></div>
                            <div><label class="me-1">Pack:</label> <span><?=$t['pack_description']?></span></div>
                            <div><label class="me-1">Code:</label> <span><?=$t['item']?></span></div>
                            <div><label class="me-1">RRP:</label> <span>£<?=$t['rrp']?></span></div>
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
                <?php } ?>
                    <tr class="total">
                        <td colspan="3">Total</td>
                        <td><?=$total_qty?></td>
                        <td>£<?=$total_amount?></td>
                    </tr>
                <?php } else { ?>
                    <tr><td colspan="5">No Items</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Structure -->
<div id="add-product-cart-modal" class="modal table_holder">
  <div class="close-btn">
    <img 
        class="modal-close"
        src="/images/icons/close-round-line.svg" 
        style="width:25px; height:25px;" 
    />
  </div>
  <div class="modal-content grid-wrapper">

  </div>
</div>

<?php echo view("partial/footer"); ?>

<script type="text/javascript">
function inc_quantity(mode, prod_id, prod_code, prod_desc)
{
    cart_inc_quantity(mode, prod_id, prod_code, 0, prod_desc, function(response, status, request) {
        if(response < 0) return;
        $(".quantity > span").text(response);
        update_cart();
    });
}

function edit_quantity(prod_id, prod_code)
{
    
}

function show_detail(prod_code) {
    $.ajax({
        url: `/products/${prod_code}/show_by_code`,
        type: 'GET',
        error: function (request, status, error) {
            toast("error", error);
        },
        success:function(data) {
            $("#add-product-cart-modal .modal-content").html(data);
            const inst = M.Modal.getInstance($("#add-product-cart-modal")[0]);
            inst.open();
        }
    })
}
</script>