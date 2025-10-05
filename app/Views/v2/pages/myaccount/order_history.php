<?php 
$table_columns = [
    [
        'id' => 'branch',
        'label' => 'Branch',
    ], 
    [
        'id' => 'date',
        'label' => 'Date',
        'sort' => true
    ],
    [
        'id' => 'order_number',
        'label' => 'Ord.No'
    ],
    [
        'id' => 'amount',
        'label' => '£'
    ],
    [
        'id' => 'document_origin',
        'label' => 'Type'
    ]
];
?>

<?= $this->extend('v2/layout/main_layout') ?>

<?= $this->section('css') ?>
<style>
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex p-4">
    <?= view('v2/pages/myaccount/sidebar') ?>

    <div class="order-history flex-fill ms-0 ms-md-4">
        <table class="table order-primary-table">
            <thead>
                <tr>
                    <?php 
                    foreach($table_columns as $col) {
                    ?>
                    <th>
                        <div class="d-flex align-items-center">
                            <div class="me-2"><?= $col['label'] ?></div>
                            <?php if( isset($col['sort'])) { ?>
                            <div class="d-flex flex-column">
                                <img 
                                    src="/images/icons/caret_up.svg" 
                                    style="width: 10px; height: 10px" 
                                    class="sort-icon cursor-pointer"
                                    data-sort-field="<?= $col['id'] ?>"
                                    data-sort-direction="up"
                                />
                                <img 
                                    src="/images/icons/caret_down.svg" 
                                    style="width: 10px; height: 10px" 
                                    class="sort-icon sort-down cursor-pointer"
                                    data-sort-field="<?= $col['id'] ?>"
                                    data-sort-direction="down"
                                />
                            </div>
                            <?php } ?>
                        </div>
                    </th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5">
                        <div class="text-center d-flex justify-content-center align-items-center">
                            <img src="/images/gifs/loading.gif" style="width:30px; height: 30px;" />
                            <span class="ms-2">Fetching Order Histories...</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    var total_orders = null;
    var showOptions = {
        sort: {
            field: 'date',
            direction: 'down'
        }
    }

    function fetchOrders() {
        $.ajax({
            url: '/myaccount/order_history',
            method: 'get',
            error: function (request, status, error) {
                showToast("error", error);
            },
            success:function(d) {
                if(d.data.status === 'success') {
                    total_orders = d.data.order_headers.filter(function(oh) {
                        return oh.branch > 0 ? true : false;
                    });
                    showOrders(total_orders);
                }
            }
        })
    }

    function showOrders() {
        const tbody = $(".order-history table tbody");
        tbody.html('');

        if(total_orders?.length > 0) {
            let total_amount = 0;
            let orders = total_orders;

            if(showOptions.sort.field) {
                // To remove active class from img.sort-icon
                $("img.sort-icon").removeClass("active");

                // To add active class
                $(`img.sort-icon[data-sort-field='${showOptions.sort.field}'][data-sort-direction='${showOptions.sort.direction}']`).addClass("active");

                orders = orders.sort(function(order1, order2) {
                    if(showOptions.sort.field == 'date') {
                        const date1 = new Date(order1.date);
                        const date2 = new Date(order2.date);
                        return (date1 > date2) ? (showOptions.sort.direction === 'up' ? 1 : -1) : (showOptions.sort.direction === 'up' ? -1 : 1);
                    }
                });
            }

            orders.forEach(function(order) {
                total_amount += parseInt(order.total_value);
                let tr = document.createElement("tr");
                
                let html = `<td>${order.branch_name}</td>`;
                html += `<td>${formatDate(new Date(order.date), "DD/MM")}</td>`;
                html += `<td>${order.order_number}</td>`;
                html += `<td><div class='amount ${order.total_value > 0 ? 'add' : (order.total_value < 0 ? 'subtract' : '')}'>${order.total_value}</div></td>`;
                html += `<td>${order.document_origin}</td>`;
                $(tr).html(html);
                $(tr).addClass("cursor-pointer");
                $(tr).data('order', order);

                tbody.append(tr);
            });

            // total row
            let tr = document.createElement("tr");
            let html = "<td colspan='3'>Total</td>";
            html += `<td>${total_amount}</td>`;
            html += "<td />";
            $(tr).html(html);
            $(tr).addClass("total");

            tbody.append(tr);
        } else {
            html = "<tr><td colspan='5' class='text-center'>No Orders</td></tr>";
            tbody.html(html);
        }
    }

    $(document).ready(function() {
        // sort-icon.click event handler
        $(document).on('click', 'table thead img.sort-icon', function(e) {
            showOptions.sort.field = $(e.target).data('sort-field');
            showOptions.sort.direction = $(e.target).data('sort-direction');
            showOrders();
        })

        // history table tr.click event handler
        $(document).on('click', 'table tbody tr', function(e) {
            const tr = $(e.target).closest('tr');
            const order = tr.data('order');
            if(order) {
                const url = `/myaccount/order_detail?on=${order.order_number}&branch=${order.branch}&dt=${order.date_time}`
                window.location.href = url;
            }
        })

        fetchOrders();
    });
</script>
<?= $this->endSection() ?>
