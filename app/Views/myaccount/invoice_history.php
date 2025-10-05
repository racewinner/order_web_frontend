<?php echo view("partial/header"); ?>

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
        'id' => 'transaction_number',
        'label' => 'Inv.No'
    ],
    [
        'id' => 'amount',
        'label' => '£'
    ],
    [
        'id' => 'type',
        'label' => 'Type'
    ]
];
?>

<div class="invoice-history">
    <div class="my-2 d-flex justify-content-center align-items-center title">
        <img src="/images/icons/invoice_pound.svg" style="width: 30px; height: 30px;" />
        <span class="ms-2">Invoice History</span>
    </div>
    <div class="d-flex justify-content-center">
        <table class="gh-table">
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
                            <span class="ms-2">Fetching Invoice Histories...</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php echo view("partial/footer"); ?>

<script type="text/javascript">
var total_invoices = null;
var showOptions = {
    sort: {
        field: 'date',
        direction: 'down'
    }
}

function showInvoices() {
    const tbody = $(".invoice-history table tbody");
    tbody.html('');

    if(total_invoices?.length > 0) {
        let total_amount = 0;
        let invoices = total_invoices;

        if(showOptions.sort.field) {
            // To remove active class from img.sort-icon
            $("img.sort-icon").removeClass("active");

            // To add active class
            $(`img.sort-icon[data-sort-field='${showOptions.sort.field}'][data-sort-direction='${showOptions.sort.direction}']`).addClass("active");

            invoices = invoices.sort(function(invoice1, invoice2) {
                if(showOptions.sort.field == 'date') {
                    const date1 = new Date(invoice1.date);
                    const date2 = new Date(invoice2.date);
                    return (date1 > date2) ? (showOptions.sort.direction === 'up' ? 1 : -1) : (showOptions.sort.direction === 'up' ? -1 : 1);
                }
            });
        }

        invoices.forEach(function(invoice) {
            total_amount += parseInt(invoice.total_value);

            let tr = document.createElement("tr");
            
            let html = `<td>${invoice.branch_name}</td>`;
            html += `<td>${formatDate(new Date(invoice.date), "DD/MM")}</td>`;
            html += `<td>${invoice.transaction_number}</td>`;
            html += `<td><div class='amount ${invoice.total_value > 0 ? 'add' : (invoice.total_value < 0 ? 'subtract' : '')}'>${invoice.total_value}</div></td>`;
            html += `<td>${invoice.document_origin}</td>`;
            $(tr).html(html);
            $(tr).addClass("cursor-pointer");
            $(tr).data('invoice', invoice);

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
        html = "<tr><td colspan='5' class='text-center'>No Invoices</td></tr>";
        tbody.html(html);
    }
}

function registerEventHandler() {

    // sort-icon.click event handler
    $(document).on('click', 'img.sort-icon', function(e) {
        showOptions.sort.field = $(e.target).data('sort-field');
        showOptions.sort.direction = $(e.target).data('sort-direction');
        showInvoices();
    })

    // history table tr.click event handler
    $(document).on('click', 'table tbody tr', function(e) {
        const tr = $(e.target).closest('tr');
        const invoice = tr.data('invoice');
        if(invoice) {
            const url = `/myaccount/invoice_detail?tn=${invoice.transaction_number}&branch=${invoice.branch}&dt=${invoice.date_time}`
            window.location.href = url;
        }
    })
}

$(document).ready(function() {
    registerEventHandler();

    $.ajax({
        url: '/myaccount/invoice_history',
        method: 'get',
        error: function (request, status, error) {
            toast("error", error);
        },
        success:function(d) {
            if(d.data?.status === 'success') {
                total_invoices = d.data.transaction_headers;
            }
            showInvoices(total_invoices);
        }
    })
})
</script>
    