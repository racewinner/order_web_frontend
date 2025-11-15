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

    $category_path = [];
    if(!empty($category_id) && $category_id > 0) {
        $found = false;

        foreach($top_categories as $tc) {
            if($tc['category_id'] == $category_id) {
                $category_path[] = $tc;
                $found = true;
                break;
            }

            foreach($tc['sub_categories'] as $sc) {
                if($sc['category_id'] == $category_id) {
                    $category_path[] = $tc;
                    $category_path[] = $sc;
                    $found = true;
                    break;
                }
            }

            if($found) break;
        }
    }
?>

<?= $this->extend('v2/layout/main_layout') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" type="text/css" href="/assets/css/page/product.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if(!empty($category_banners)) {
    echo view("v2/components/BannerCarousel", ['data' => $category_banners, 'cms_id' => 'category_banners_carousel']);
} ?>

<section class="p-2 px-4">
    <input type="hidden" name="offset" id="offset" value="<?= $offset ?>" />

    <h5 class="mt-2"><?= count($category_path) > 0 ? $category_path[count($category_path) - 1]['category_name'] : '' ?></h5>
    <nav style="--bs-breadcrumb-divider: '>';" arial-label="breadcrumb">
        <ol class="breadcrumb"  style="color:#aaa;">
            <li class="breadcrumb-item"><a class="category-link" data-category-id="0">Products</a></li>
            <?php foreach($category_path as $index => $category) { ?>
                <li class="breadcrumb-item <?= $index == count($category_path) - 1 ? 'active' : '' ?>" aria-current="page">
                    <a class="category-link" data-category-id="<?= $category['category_id'] ?>"><?= $category['category_name'] ?></a>
                </li>
            <?php } ?>
        </ol>
    </nav>


    <div class="d-flex products-section mt-2">
        <div class="filter-section <?= $is_mobile ? 'sidebar collapsed' : '' ?>" id="product-side-filter">
            <div class="sidebar-content card">
                <div class="card-header d-flex py-3">
                    <div class="flex-fill text-black fw-bold">Filter by:</div>
                    <div><a href="#" class="clear-filter" id="clear-filter">Clear</a></div>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="chk_im_new" <?= $im_new ? 'checked' : '' ?>>
                        <label class="form-check-label <?= $im_new ? 'text-black' : '' ?> " for="chk_im_new">New Product</label>
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
                            <label class="form-check-label" <?= $favorite ? 'text-black' : '' ?> for="chk_favorite">Favourite</label>
                        </div>
                    <?php } ?>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="chk_rrp" <?= $rrp ? 'checked' : '' ?>>
                        <label class="form-check-label" <?= $rrp ? 'text-black' : '' ?> for="chk_rrp">£1.00 rrp</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="chk_pmp" <?= $pmp ? 'checked' : '' ?>>
                        <label class="form-check-label" <?= $pmp ? 'text-black' : '' ?> for="chk_pmp">PMP</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="chk_non_pmp" <?= $non_pmp ? 'checked' : '' ?>>
                        <label class="form-check-label" <?= $non_pmp ? 'text-black' : '' ?> for="chk_non_pmp">Non PMP</label>
                    </div>

                    <div class="filter-brand">
                        <label class="mb-2 fw-bold" style="color: #333;">Brand:</label>
                        <?php foreach ($brands as $brand) { ?>
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" name="filter_brand" data-brand="<?= $brand ?>" <?= (in_array($brand, $filter_brands) ? 'checked' : '') ?> />
                                <label class='form-check-label <?= in_array($brand, $filter_brands) ? 'text-black' : '' ?>'><?= $brand ?></span>
                            </div>
                        <?php } ?>
                        <div class="form-check"><label>
                            <input type="checkbox" class="form-check-input" name="filter_brand" data-brand="" <?= (in_array('', $filter_brands) ? 'checked' : '') ?> />
                            <label class="form-check-label <?= (in_array('', $filter_brands) ? 'text-black' : '') ?>">Others</label>
                        </label></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-fill">
            <?php if (!empty($sponsor)) { ?>
                <div class="sponsor-products px-4" style="background:<?= $sponsor['template']['background']['bg_color'] ?? 'transparent' ?>;">
                    <?php if(!empty($sponsor['ribbon']['content'])) { ?>

                    <?php } ?>
                </div>
            <?php } ?>

            <div class="products-table px-md-2">
                <div class="products-table-header pc d-none d-xl-flex justify-content-between align-items-center px-2">
                    <div class="flex-fill d-flex align-items-center" style="margin-left: 10px;">
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
                        
                        <div class="d-flex align-items-center ms-2 ms-xxl-3">
                            <label class="me-2">Display</label>
                            <select class="form-select" name='per_page' id="per_page" aria-label="Display Per Page" style="min-width: 80px;">
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
                            <label for="sort_key" class="me-2 fs-90">Sort By</label>
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

                <div class="products-table-header mobile d-xl-none px-2">
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center me-1">
                            <div class="d-flex align-items-center toggle-sidebar" data-toggle-target="#product-side-filter">
                                <i class="bi bi-funnel-fill me-1" style="font-size: 20px;"></i>
                                Filter
                            </div>
                        </div>

                        <div class="d-flex align-items-center flex-fill">
                            <?php if($total_rows > 0) { ?>
                                <span class="text-black"><?= $from ?> - <?= $to ?></span>
                                <label class="mx-1">of</label>
                                <span class="me-1 text-black"><?= $total_rows ?></span>
                                <label>products</label>
                            <?php } else { ?>
                                No Products
                            <?php } ?>
                        </div>

                        <div class="d-flex align-items-center view-mode">
                            <button type="button" class="btn <?= $view_mode == 'grid' ? 'active' : '' ?>" data-view-mode="grid" ><i class="bi bi-grid-3x3"></i></button>
                            <button type="button" class="btn ms-2 <?= $view_mode == 'list' ? 'active' : '' ?>" data-view-mode="list"><i class="bi bi-list-ul"></i></button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="flex-fill me-4">
                            <?= view('v2/components/SearchInput2', ['name'=>'search1', 'id'=>'search1', 'value'=>$search1 ?? '', 'placeholder' => 'Search here']) ?>
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
                        <?php foreach($products as $product) { ?>
                            <div class="p-2 mx-auto">
                                <?= view("v2/components/Product", ['product' => $product, 'view_mode' => $view_mode]) ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="text-center mt-4">No found Products</div>
                <?php } ?>
                
                <?php if($total_rows > 0) { ?>
                <div class="d-flex justify-content-end mt-1">
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
    function loadProducts() {
        const params        = new URLSearchParams(window.location.search);
        const search3       = params.get('search3');

        const data = {
            sort_key: $('#sort_key').val(),
            category_id: $('#category_id').val() ?? 0,
            offset: $("#offset").val() ?? 0,
            per_page: $('#per_page').val() ?? 30,
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
            search1: $('div.products-table-header:visible input[name="search1"]').val().replace(/[\/()|'*]/g, ' '),
            search3: search3 != null && search3 !== 'null' ? search3 : '',
        }
        const filter_brands = getFilterBrands();
        if (filter_brands?.length > 0) {
            data.filter_brands = JSON.stringify(filter_brands);
        }
        const queryParams = new URLSearchParams(data);

        let url = `/products/index?${queryParams}`;
        window.location.href = url;
    }

    function getFilterBrands() {
        const filter_brands = [];
        const brChkBoxes = $("input[name='filter_brand']");
        for (let i = 0; i < brChkBoxes.length; i++) {
            if (brChkBoxes[i].checked) {
                filter_brands.push($(brChkBoxes[i]).data('brand'));
            }
        }
        return filter_brands;
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


    $(document).ready(function() {
        $(document).on('change', '#per_page, #chk_im_new, #chk_plainprofit, #chk_own_label, #chk_favorite, #chk_rrp, #chk_pmp, #chk_non_pmp, input[name="filter_brand"], #sort_key, #search1', function(e) {
          debugger  
            $("#offset").val(0);
            loadProducts();
        })

        $(document).on('click', '#clear-filter', function(e) {
            $('#chk_im_new, #chk_plainprofit, #chk_own_label, #chk_rrp, #chk_pmp, #chk_non_pmp, input[name="filter_brand"]').prop('checked', false);
            $("#offset").val(0);

            setTimeout(function(){
                loadProducts();
            }, 500)
        })

        $(document).on('click', '.products-table .view-mode button', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const $button = $(e.target).closest('button');
            const view_mode = $button.data('view-mode');
            $('#view_mode').val(view_mode);
            loadProducts();
        })
    })
</script>
<?= $this->endSection() ?>
