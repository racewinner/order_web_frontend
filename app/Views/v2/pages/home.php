<?= $this->extend('v2/layout/main_layout') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" type="text/css" href="/assets/css/page/home.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php  
foreach($cms as $index => $one) {
    switch($one['type']) {
        case CmsType['home_banner']:
            if(count($one['data']) > 0) {
                echo view("v2/components/BannerCarousel", ['data' => $one['data'], 'cms_id' => 'home_banners_carousel_' . $index]);
            }
            break;
        case CmsType['category_carousel']:
            echo view("v2/components/CategoryCarousel", ['data' => $one['data'], 'cms_id' => 'home_categories_carousel_' . $index]);
            break;
        case CmsType['products_carousel']:
            echo view("v2/components/ProductCarousel", ['data' => $one['data'], 'cms_id' => 'products_carousel_' . $index]);
            break;
        case CmsType['brochure']:
            echo view("v2/components/BrochureCarousel", ['data' => $one['data'], 'cms_id' => 'brochure_' . $index]);
            break;
        case CmsType['brand']:
            echo view("v2/components/BrandCarousel", ['data' => $one['data'], 'cms_id' => 'brands_carousel_' . $index]);
            break;
        case CmsType['bottom_banner']:
            echo view("v2/components/3BannerCarousel", ['data' => $one['data'], 'cms_id' => '3_banners_carousel' . $index]);
            break;
        case CmsType['shop_by_category']:
            echo view("v2/components/ShopByCategory", ['data' => $one['data'], 'cms_id' => 'show_by_category_' . $index]);
            break;
    }
}
?>
<?= $this->endSection() ?>