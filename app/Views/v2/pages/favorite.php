<?= $this->extend('v2/layout/main_layout') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" type="text/css" href="/assets/css/page/product.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="p-2">
    <h5 class="mt-2">Favorites</h5>

    <div class="products-section">
        <div class="products-table px-md-2 px-lg-4">
            <div class="products-table-header d-flex justify-content-between align-items-center px-2">
                <div class="flex-fill d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <?php if($total_rows > 0) { ?>
                            <label>Found</label>
                            <span class="mx-2 text-black"><?= $total_rows ?></span>
                            <label>products</label>
                        <?php } else { ?>
                            No found favourite products
                        <?php } ?>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center view-mode ms-4">
                        <button type="button" class="btn <?= $view_mode == 'grid' ? 'active' : '' ?>" data-view-mode="grid" ><i class="bi bi-grid-3x3"></i></button>
                        <button type="button" class="btn ms-2 <?= $view_mode == 'list' ? 'active' : '' ?>" data-view-mode="list"><i class="bi bi-list-ul"></i></button>
                    </div>
                </div>
            </div>

            <div class="products-table-body mt-3 <?= $view_mode ?>-view">
                <?php 
                if(!empty($products)) {
                    foreach($products as $product) {
                        echo view("v2/components/Product", ['product' => $product, 'view_mode' => $view_mode]);
                    } 
                }
                ?>
            </div>
        </div>
    </div>
</section>    
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    function load_favorites() {
        const data = {
            view_mode: $("#view_mode").val() ?? 'grid',
        }

        const queryParams = new URLSearchParams(data);

        let url = `/favorites?${queryParams}`;
        window.location.href = url;
    }

    $(document).ready(function(e) {
        $(document).on('click', '.products-table .view-mode button', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const $button = $(e.target).closest('button');
            const view_mode = $button.data('view-mode');
            $('#view_mode').val(view_mode);
            
            load_favorites();
        })
    })
</script>
<?= $this->endSection() ?>