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
                <th>Action</th>
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
                                <div>
                                    <label class="me-1">Code:</label> 
                                    <span class="prod_code_2do" data-trolley-type="<?= $product->type ?>" data-can-reorder="no"><?=$t['item']?></span>
                                </div>
                                <div><label class="me-1">Pack:</label> <span><?=$t['pack_description']?></span></div>
                                <div><label class="me-1">RRP:</label> <span>£<?=$t['rrp']?></span></div>
                            </div>
                        <?php } ?>
                    </td>
                    <td>£<?= $t['price'] ?></td>
                    <td><div class="prod_qty_2do"><?= $qty ?></div></td>
                    <td>£<?= $t['total_value'] ?></td>
                    <?php if(!empty($t['product'])) { ?>
                        <td><i class="bi bi-cart3 reorder-product cursor-pointer" style="font-size:20px; color: #ff6c00;"></i></td>
                    <?php } else { ?>
                        <td><i class="bi bi-cart3 reorder-product cursor-pointer disabled" style="font-size:20px; color: #ff6c00;"></i></td>
                    <?php } ?>

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
                        <button id="add-to-cart-by-bulk" class="btn btn-danger">Repeat Order</button>
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
$(document).ready(function(e) {
    $(document).on('click', '.bi-cart3.reorder-product', function(e) {
        const params        = new URLSearchParams(window.location.search);
        const on            = params.get('on');
        const branch        = params.get('branch');
        const dt            = params.get('dt');

        tbl_row = e.target.closest('tr');

        prod_code = $(tbl_row).find('.prod_code_2do').text();
        prod_type = $(tbl_row).find('.prod_code_2do').data('trolley-type');
        prod_qty  = $(tbl_row).find('.prod_qty_2do' ).text();
        products  = [{prod_code, prod_type, prod_qty}];

        payload = {
            on,
            branch,
            dt,
            products: JSON.stringify(products)
        }
        console.log(payload);

        $.ajax({
            type: "POST"
            , async: true
            , url: "/orders/add_to_cart/by_bulk"
            , dataType: "json"
            , timeout: 30000
            , cache: false
            , data: payload
            , error: function (xhr, status, error) {
                debugger
                if (xhr.status == 401) {
                    window.location.href = '/login'; return;
                } else {
                    console.log("An error occured: " + xhr.status + " " + xhr.statusText);
                }}
            , success: function (response, status, request) {
                debugger
                if (response.success) {
                    update_cart();
                    alert_message('Your request to register has been sent.', 'Info');
                    return;
                } else {
                    alert_message('Sorry, there was something wrong in reordering.', 'Error');
                    return;
                }
            }
            , complete: function() {
                debugger
                remove_loadingSpinner_from_button(e.target);
            }
        });
    })
    $(document).on('click', 'button#add-to-cart-by-bulk', function(e) {

        debugger
        const params        = new URLSearchParams(window.location.search);
        const on            = params.get('on');
        const branch        = params.get('branch');
        const dt            = params.get('dt');

        // get products in order list
        let products        = [];
        let table           = $('.order-primary-table')
        let tbody           = table.find('tbody')
        let tbl_rows        = tbody.find('tr')

        for(let i=0; i<tbl_rows.length; i++) {
            tbl_row   = tbl_rows.eq(i);
            let can_reorder = tbl_row.find('.prod_code_2do').data('can-reorder');
            if (can_reorder == 'no') 
                continue;
            prod_code = tbl_row.find('.prod_code_2do').text();
            prod_type = tbl_row.find('.prod_code_2do').data('trolley-type');
            prod_qty  = tbl_row.find('.prod_qty_2do' ).text();
            products  = [...products, {prod_code, prod_type, prod_qty}];
        }
        products.reverse();

        payload = {
            on,
            branch,
            dt,
            products: JSON.stringify(products)
        }
        console.log(payload);

        $.ajax({
            type: "POST"
            , async: true
            , url: "/orders/add_to_cart/by_bulk"
            , dataType: "json"
            , timeout: 30000
            , cache: false
            , data: payload
            , error: function (xhr, status, error) {
                debugger
                if (xhr.status == 401) {
                    window.location.href = '/login'; return;
                } else {
                    console.log("An error occured: " + xhr.status + " " + xhr.statusText);
                }}
            , success: function (response, status, request) {
                debugger
                if (response.success) {
                    update_cart();
                    alert_message('Your request to register has been sent.', 'Info');
                    return;
                } else {
                    alert_message('Sorry, there was something wrong in reordering.', 'Error');
                    return;
                }
            }
            , complete: function() {
                debugger
                remove_loadingSpinner_from_button(e.target);
            }
        });
    })
})
</script>
<?= $this->endSection() ?>
