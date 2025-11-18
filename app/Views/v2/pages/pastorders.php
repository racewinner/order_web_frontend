<?php
    $perPageOptions = [30, 40, 50, 75, 100, 150, 200];
    $sort_options = [
        ['label' => 'A/C (asc)', 'value' => 1],
        ['label' => 'A/C (desc)', 'value' => 2],
        ['label' => 'Date (asc)', 'value' => 3],
        ['label' => 'Date (desc)', 'value' => 4],
        ['label' => 'Status (asc)', 'value' => 5],
        ['label' => 'Status (desc)', 'value' => 6],
    ];
?>

<?= $this->extend('v2/layout/main_layout') ?>

<?= $this->section('css') ?>
<style>
    .orders-table-body table {
        thead {
            font-size: 80%;
        }
        tbody {
            font-size: 90%;
            i {
                font-size: 20px;
            }
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="p-4">
    <input type="hidden" name="offset" id="offset" value="<?= $offset ?>" />

    <h5 class="mt-2">Past Orders</h5>
    <p class="desc">Orders sent / saved by <span><?= $user_info->username; ?></span></p>

    <div class="order-table-header d-flex">
        <div class="flex-fill d-flex align-items-center">
            <div class="d-flex align-items-center whitespace-nowrap">
                <?php if($total_rows > 0) { ?>
                    <span class="mx-2 text-black"><?= $from ?> - <?= $to ?></span>
                    <label>of</label>
                    <span class="mx-2 text-black"><?= $total_rows ?></span>
                    <label>orders</label>
                <?php } else { ?>
                    No found Orders
                <?php } ?>
            </div>

            <div class="d-flex align-items-center ms-2 ms-sm-4">
                <label class="me-2 d-none d-sm-block">Display</label>
                <select class="form-select" name='per_page' id="per_page" aria-label="Display Per Page">
                    <?php foreach($perPageOptions as $perPage) { ?>
                        <option value='<?= $perPage ?>' <?= ($per_page == $perPage) ? "selected='true'" : "" ?>><?=$perPage?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="d-flex align-items-center">
            <div class="d-flex align-items-center ms-2 ms-sm-4">
                <label for="sort_key" class="me-2 d-none d-sm-block">Sort By</label>
                <select class="form-select" name='sort_key' id='sort_key' aria-label="Sort" >
                <?php foreach ($sort_options as $sort) { ?>
                    <option value='<?= $sort['value'] ?>' class="circle" <?= $sort['value'] == $sort_key ? 'selected' : '' ?>>
                        <?= $sort['label'] ?>
                    </option>
                <?php } ?>
                </select>
            </div>
        </div>
    </div>

    <div class="orders-table-body mt-3" style="overflow: auto;">
        <table class="table order-primary-table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">A/C</th>
                    <th scope="col">DATE</th>
                    <th scope="col">TIME</th>
                    <th scope="col">TOTAL AMOUNT</th>
                    <th scope="col">STATUS</th>
                    <th scope="col">FILE REF(FOR UNITED USE ONLY)</th>
                    <th scope="col">INFO</th>
                    <th scope="col">TYPE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($orders as $index => $order) { ?>
                    <tr>
                        <td>wo2-<?= $order->order_id ?></td>
                        <td><?= $order->username ?></td>
                        <td><?= substr($order->order_date , 0 , 4)."/".substr($order->order_date , 4 , 2)."/".substr($order->order_date , 6 , 2) ?></td>
                        <td><?= substr($order->order_time , 0 , 2).":".substr($order->order_time , 2 , 2).":".substr($order->order_time , 4 , 2) ?></td>
                        <td>0</td>

                        <?php if($order->completed == 1) {?>
                            <td><i class="bi bi-check-circle-fill" style="color: #00b279;"></i></td>
                            <td><?= $order->filename ?></td>
                        <?php } else { ?>
                            <td><i class="bi bi-cloud-check" style="color: #00b279;"></i></td>
                            <td>&nbsp;</td>
                        <?php } ?>

                        <td>
                            <a class="btn-modal" data-href="" data-container=".view-modal">
                                <?php if($order->presell == 1) { ?>
                                    <i class="bi bi-p-circle" style="color: purple;"></i>
                                <?php } else { ?>
                                    <i class="bi bi-eye-fill" style="color: #069ad8;"></i>
                                <?php } ?>
                            </a>
                        </td>

                        <td>
                            <?php if($order->type == 'general') { ?>
                                <i class="bi bi-boxes" style="color: #00b279;"></i>
                            <?php } else if($order->type == 'tobacco') { ?>
                                <i class="bi bi-fire" style="color: #00b279;"></i>
                            <?php } else { ?>
                                <i class="bi bi-snow" style="color: #00b279;"></i>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php if($total_rows > 0) { ?>
        <div class="d-flex justify-content-end mt-4">
            <?= view('v2/components/Pagination', [
                'curd_page'=>$curd_page, 
                'total_page'=>$total_page,
                'base_url' => $base_url,
                'per_page' => $per_page,
            ]) ?>
        </div>
    <?php } ?>
</section>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    function load_orders() {
        const data = {
            sort_key: $('#sort_key').val(),
            offset: $("#offset").val() ?? 0,
            per_page: $('#per_page').val() ?? 50,
        }
        const queryParams = new URLSearchParams(data);

        const url = `/pastorders?${queryParams}`;
        window.location.href = url;
    }

    $(document).ready(function(e) {
        $(document).on('change', '#per_page, #sort_key', function(e) {
            load_orders();
        })
    })
</script>
<?= $this->endSection() ?>