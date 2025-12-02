<?php
    $perPageOptions = [30, 40, 50, 75, 100, 150, 200];
    $sort_options = [
        ['label' => 'Best Selling (desc)', 'value' => 9],
        ['label' => 'Brand (asc)', 'value' => 10],
        ['label' => 'Description (asc)', 'value' => 3],
        ['label' => 'Description (desc)', 'value' => 4],
        ['label' => 'POR% (asc)', 'value' => 5],
        ['label' => 'POR% (desc)', 'value' => 6],
        ['label' => 'Price (asc)', 'value' => 7],
        ['label' => 'Price (desc)', 'value' => 8],
        ['label' => 'SKU (asc)', 'value' => 1],
        ['label' => 'SKU (desc)', 'value' => 2],
    ];
?>

<?= $this->extend('v2/layout/main_layout') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" type="text/css" href="/assets/css/page/product.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="p-2 px-4 promos-page-content">
    <input type="hidden" name="offset" id="offset" value="<?= $offset ?>" />
    <input type="hidden" name="type" id="type" value="<?= $type ?>">

    <h5 class="mt-2">Promos</h5>
    <div class="d-flex products-section mt-2">
        <div class="filter-section <?= $is_mobile ? 'sidebar collapsed' : '' ?>" id="product-side-filter">
            <div class="sidebar-content pt-4 p-1">
                <ul class="promo-type-filter">
                    <?php 
                    // Map pricelist IDs to promo types
                    $promo_type_map = [
                        '10' => ['type' => 'du', 'user_field' => 'price_list010'],
                        '12' => ['type' => 'us', 'user_field' => 'price_list012'],
                        '999' => ['type' => 'cc', 'user_field' => 'price_list999'],
                    ];
                    
                    // Loop through pricelists sorted by priority
                    foreach($pricelists as $id => $pricelist) {
                        $pricelist_id = (string)$id;
                        
                        // Check if this pricelist has a promo type mapping
                        if (isset($promo_type_map[$pricelist_id])) {
                            $promo_type = $promo_type_map[$pricelist_id]['type'];
                            $user_field = $promo_type_map[$pricelist_id]['user_field'];
                            
                            // Check if user has access to this pricelist and if promo_page is enabled
                            if (isset($user_info->$user_field) && $user_info->$user_field == "1" && 
                                isset($pricelist['promo_page']) && $pricelist['promo_page'] == 1) {
                                $label = $pricelist['ribbon_label'] ?? strtoupper($promo_type);
                                ?>
                                <li class="<?= $type == $promo_type ? 'active' : '' ?>">
                                    <a href="#" class="promo-type hover-no-underline" data-promo-type="<?= $promo_type ?>">
                                        <?= htmlspecialchars($label) ?>
                                    </a>
                                </li>
                                <?php
                            }
                        }
                    }
                    ?>
                </ul>

                <div class="card">
                    <div class="card-header d-flex">
                        <div class="flex-fill">Filter by:</div>
                        <div><a href="#" class="clear-filter" id="clear-filter">Clear</a></div>
                    </div>
                    <div class="card-body">
                        <div class="accordion mb-3" id="filter-priceEnd-accordion">
                            <div class="accordion-item">
                                <h5 class="accordion-header">
                                    <button class="accordion-button p-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#filter-priceEnd-accordion-item" aria-expanded="true" aria-controls="collapseOne">
                                        Promo End Date:
                                    </button>                            
                                </h5>
                                <div id="filter-priceEnd-accordion-item" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#filter-priceEnd-accordion">
                                    <div class="accordion-body p-2">
                                        <?php foreach($priceEnds as $pe) { ?>
                                            <div class="form-check">
                                                <input 
                                                    class="form-check-input" 
                                                    type="checkbox" 
                                                    data-price-end="<?= $pe ?>"
                                                    name="filter_priceEnd"
                                                    <?= (in_array($pe, $filter_priceEnds) ? 'checked' : '') ?> 
                                                />
                                                <label class="form-check-label" for=""><?= date('d/m/Y', $pe) ?></label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion mb-4" id="filter-category-accordion">
                            <div class="accordion-item">
                                <h5 class="accordion-header">
                                    <button class="accordion-button p-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#filter-category-accordion-item" aria-expanded="true" aria-controls="collapseOne">
                                        Category:
                                    </button>
                                </h5>
                                <div id="filter-category-accordion-item" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#filter-category-accordion">
                                    <div class="accordion-body p-2">
                                        <ul>
                                            <li><a href="#" data-category-id="" class="filter-category <?= empty($category_id) ? 'active' :''  ?>">All</a></li>
                                            <?php foreach($category as $c) { ?>
                                                <li><a href='#' data-category-id="<?= $c->category_id ?>" class="<?= $c->category_id == $category_id ? 'active' : '' ?>"><?= $c->category_name ?></a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="chk_im_new" <?= $im_new ? 'checked' : '' ?>>
                                <label class="form-check-label <?= $im_new ? 'text-black' : '' ?>" for="chk_im_new">New Product</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="chk_plainprofit" <?= $plan_profit ? 'checked' : '' ?>>
                                <label class="form-check-label <?= $plan_profit ? 'text-black' : '' ?>" for="chk_plainprofit">Plan for Profit</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="chk_own_label" <?= $own_label ? 'checked' : '' ?>>
                                <label class="form-check-label <?= $own_label ? 'text-black' : '' ?>" for="chk_own_label">Own Label</label>
                            </div>
                            <?php if(!empty($user_info)) { ?>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="chk_favorite" <?= $favorite ? 'checked' : '' ?>>
                                    <label class="form-check-label <?= $favorite ? 'text-black' : '' ?>" for="chk_favorite">Favourite</label>
                                </div>
                            <?php } ?>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="chk_rrp" <?= $rrp ? 'checked' : '' ?>>
                                <label class="form-check-label <?= $rrp ? 'text-black' : '' ?>" for="chk_rrp">£1.00 rrp</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="chk_pmp" <?= $pmp ? 'checked' : '' ?>>
                                <label class="form-check-label <?= $pmp ? 'text-black' : '' ?>" for="chk_pmp">PMP</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="chk_non_pmp" <?= $non_pmp ? 'checked' : '' ?>>
                                <label class="form-check-label <?= $non_pmp ? 'text-black' : '' ?>" for="chk_non_pmp">Non PMP</label>
                            </div>                    
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-fill">
            <div class="products-table px-md-2 px-lg-4">
                <div class="products-table-header d-none d-md-flex justify-content-between align-items-center px-2">
                    <div class="flex-fill d-flex align-items-center">
                        <div class="d-flex align-items-center toggle-sidebar me-4" data-toggle-target="#product-side-filter">
                            <i class="bi bi-funnel-fill me-1" style="font-size: 20px;"></i>
                            Filter
                        </div>

                        <div class="d-flex align-items-center">
                            <?php if($total_rows > 0) { ?>
                                <span class="mx-2 text-black" style="white-space: nowrap;"><?= $from ?> - <?= $to ?></span>
                                <label>of</label>
                                <span class="mx-2 text-black"><?= $total_rows ?></span>
                                <label>products</label>
                            <?php } else { ?>
                                No Products
                            <?php } ?>
                        </div>

                        <div class="d-flex align-items-center ms-2 ms-xxl-4">
                            <label class="me-2">Display</label>
                            <select class="form-select" name='per_page' id="per_page" aria-label="Display Per Page"  style="min-width: 80px;">
                                <?php foreach($perPageOptions as $perPage) { ?>
                                    <option value='<?= $perPage ?>' <?= ($per_page == $perPage) ? "selected='true'" : "" ?>><?=$perPage?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="mx-2">
                        <?= view('v2/components/SearchInput2', ['name'=>'search1', 'id'=>'search1', 'value'=>$search1 ?? '', 'placeholder' => 'Search here']) ?>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center ms-2 ms-xxl-4">
                            <label for="sort_key" class="me-2">Sort By</label>
                            <select class="form-select" name='sort_key' id='sort_key' aria-label="Sort" >
                            <?php foreach ($sort_options as $sort) { ?>
                                <option value='<?= $sort['value'] ?>' class="circle" <?= $sort['value'] == $sort_key ? 'selected' : '' ?>>
                                    <?= $sort['label'] ?>
                                </option>
                            <?php } ?>
                            </select>
                        </div>

                        <div class="d-flex align-items-center view-mode ms-2 ms-xxl-4">
                            <button type="button" class="btn <?= $view_mode == 'grid' ? 'active' : '' ?>" data-view-mode="grid" ><i class="bi bi-grid-3x3"></i></button>
                            <button type="button" class="btn ms-2 <?= $view_mode == 'list' ? 'active' : '' ?>" data-view-mode="list"><i class="bi bi-list-ul"></i></button>
                        </div>
                    </div>
                </div>

                <div class="products-table-header d-md-none px-2">
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center flex-fill">
                            <?php if($total_rows > 0) { ?>
                                <span class="text-black"><?= $from ?> - <?= $to ?></span>
                                <label class="mx-2">of</label>
                                <span class="me-2 text-black"><?= $total_rows ?></span>
                                <label>products</label>
                            <?php } else { ?>
                                No found Products
                            <?php } ?>
                        </div>
                        <div class="d-flex align-items-center view-mode">
                            <button type="button" class="btn <?= $view_mode == 'grid' ? 'active' : '' ?>" data-view-mode="grid" ><i class="bi bi-grid-3x3"></i></button>
                            <button type="button" class="btn ms-2 <?= $view_mode == 'list' ? 'active' : '' ?>" data-view-mode="list"><i class="bi bi-list-ul"></i></button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="flex-fill d-flex align-items-center">
                            <div class="d-flex align-items-center toggle-sidebar" data-toggle-target="#product-side-filter">
                                <i class="bi bi-funnel-fill me-1" style="font-size: 20px;"></i>
                                Filter
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <label for="sort_key" class="me-2"><i class="bi bi-sort-alpha-up" style="font-size: 20px;"></i></label>
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

                <?php if($total_rows > 0) { ?>
                    <div class="products-table-body mt-3 <?= $view_mode ?>-view">
                        <?php foreach($products as $product) {
                            echo view("v2/components/Product", ['product' => $product, 'view_mode' => $view_mode]);
                        } ?>
                    </div>
                <?php } else { ?>
                    <div class="mt-4 text-center">
                        No found Products
                    </div>
                <?php } ?>

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
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    function load_promos(filter) {
        const type = $("#type").val() ?? 'du';
        const data = {
            sort_key: filter && filter.sort_key ? filter.sort_key : $('#sort_key').val(),
            category_id: $('#category_id').val() ?? 0,
            offset: $("#offset").val() ?? 0,
            per_page: $('#per_page').val() ?? 50,
            view_mode: $("#view_mode").val() ?? 'grid',
            im_new: $("#chk_im_new").is(':checked') ? 1 : 0,
            plan_profit: $("#chk_plainprofit").is(':checked') ? 1 : 0,
            own_label: $("#chk_own_label").is(':checked') ? 1 : 0,
            favorite: $("#chk_favorite").is(':checked') ? 1 : 0,
            rrp: $("#chk_rrp").is(':checked') ? 1 : 0,
            pmp: $("#chk_pmp").is(':checked') ? 1 : 0,
            non_pmp: $("#chk_non_pmp").is(':checked') ? 1 : 0,
            spresell: 0,
            search0: $('#search0').val().replace(/[\/()|'*]/g, ' '),
            search1: $('#search1').val().replace(/[\/()|'*]/g, ' '),
        }
        const filter_priceEnds = getFilterPriceEnds();
        if(filter_priceEnds?.length > 0) {
            data.filter_priceEnds = JSON.stringify(filter_priceEnds);
        }

        const queryParams = new URLSearchParams(data);

        let url = `/promos/index/${type}?${queryParams}`;
        window.location.href = url;
    }

    function getFilterPriceEnds() {
        const filter_priceEnds = [];
        const peChkBoxes = $("input[name='filter_priceEnd']");
        for(let i=0; i<peChkBoxes.length; i++) {
            if(peChkBoxes[i].checked) {
                filter_priceEnds.push($(peChkBoxes[i]).data('price-end'));
            }		
        }
        return filter_priceEnds;
    }

    function onWindowResized() {
        if (window.visualViewport.width > 1201) {
            $(".filter-section").removeClass("sidebar collapsed");
        } else {
            $(".filter-section").addClass("sidebar collapsed");
        }
    };
    window.visualViewport.addEventListener('resize', function () {
        onWindowResized();
    });
    onWindowResized();


    $(document).ready(function(e) {
        $(document).on('change', '#per_page, #chk_im_new, #chk_plainprofit, #chk_own_label, #chk_favorite, #chk_rrp, #chk_pmp, #chk_non_pmp, input[name="filter_priceEnd"], #sort_key, #search1', function(e) {
            $("#offset").val(0);

            let filter = {};

            if (e.target.id == 'sort_key') {
                filter = {...filter, sort_key: $(e.target).val()};
            }
            load_promos(filter);
        })

        $(document).on('click', '#clear-filter', function(e) {
            $('#chk_im_new, #chk_plainprofit, #chk_own_label, #chk_rrp, #chk_pmp, #chk_non_pmp, input[name="filter_priceEnd"]').prop('checked', false);
            $("#offset").val(0);

            setTimeout(function(){
                load_promos();
            }, 500)
        })

        $(document).on('click', '.products-table .view-mode button', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const $button = $(e.target).closest('button');
            const view_mode = $button.data('view-mode');
            $('#view_mode').val(view_mode);
            
            load_promos();
        })

        $(document).on('click', '#filter-category-accordion-item ul li a', function(e) {
            const category_id = $(e.target).data('category-id');
            $("#category_id").val(category_id);
            load_promos();
        })

        $(document).on('click', '.filter-section a.promo-type', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const type = $(e.target).data('promo-type');
            $("#type").val(type);

            load_promos();
        })
    })
</script>
<?= $this->endSection() ?>