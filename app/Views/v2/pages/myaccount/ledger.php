<?php 
$table_columns = [
    [
        'id' => 'date',
        'label' => 'Date',
        'sort' => true
    ],
    [
        'id' => 'type',
        'label' => 'Type'
    ],
    [
        'id' => 'amount',
        'label' => '£'
    ],
    [
        'id' => 'runningTotal',
        'label' => 'Balance'
    ],
    [
        'id' => 'ledgerRef',
        'label' => 'Ledger Id'
    ],
    [
        'id' => 'ref',
        'label' => 'Reference'
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

    <div class="account-ledger flex-fill ms-0 ms-md-4" style="overflow: auto;">
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
                    <td colspan="6">
                        <div class="text-center d-flex justify-content-center align-items-center">
                            <img src="/images/gifs/loading.gif" style="width:30px; height: 30px;" />
                            <span class="ms-2">Fetching Ledger Information...</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script type="text/javascript">
    var total_ledger_logs = null;
    var showOptions = {
        sort: {
            field: 'date',
            direction: 'down'
        }
    }

    function showLedgerLogs() {
        const tbody = $(".account-ledger table tbody");
        tbody.html('');

        if(total_ledger_logs?.length > 0) {
            let logs = total_ledger_logs;

            if(showOptions.sort.field) {
                // To remove active class from img.sort-icon
                $("img.sort-icon").removeClass("active");

                // To add active class
                $(`img.sort-icon[data-sort-field='${showOptions.sort.field}'][data-sort-direction='${showOptions.sort.direction}']`).addClass("active");

                logs = logs.sort(function(log1, log2) {
                    if(showOptions.sort.field == 'date') {
                        const date1 = new Date(log1.date);
                        const date2 = new Date(log2.date);
                        return (date1 > date2) ? (showOptions.sort.direction === 'up' ? 1 : -1) : (showOptions.sort.direction === 'up' ? -1 : 1);
                    }
                });
            }

            logs.forEach(function(log) {
                let tr = document.createElement("tr");
                
                let html = `<td>${log.date}</td>`;
                html += `<td>${log.type}</td>`;
                html += `<td><div class='amount ${log.amount > 0 ? 'add' : (log.amount < 0 ? 'subtract' : '')}'>${log.amount}</div></td>`;
                html += `<td><div class='amount ${log.runningTotal > 0 ? 'add' : (log.runningTotal < 0 ? 'subtract' : '')}'>${log.runningTotal}</div></td>`;
                html += `<td>${log.ledgerRef}</td>`;
                html += `<td>${log.ref}</td>`;
                $(tr).html(html);
                $(tr).data('log', log);

                tbody.append(tr);
            });
        } else {
            html = "<tr><td colspan='6' class='text-center'>No Data</td></tr>";
            tbody.html(html);
        }
    }

    function registerEventHandler() {

        // sort-icon.click event handler
        $(document).on('click', 'img.sort-icon', function(e) {
            showOptions.sort.field = $(e.target).data('sort-field');
            showOptions.sort.direction = $(e.target).data('sort-direction');
            showLedgerLogs();
        })
    }

    $(document).ready(function() {
        registerEventHandler();

        $.ajax({
            url: '/myaccount/ledger',
            method: 'get',
            error: function (request, status, error) {
                showToast("error", error);
            },
            success:function(d) {
                total_ledger_logs = d.data.data;
                showLedgerLogs(total_ledger_logs);
            }
        })
    })
</script>
<?= $this->endSection() ?>