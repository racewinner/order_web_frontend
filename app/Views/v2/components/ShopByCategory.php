<div class="shop-by-category order-cms" id="<?= $cms_id ?>"
    style="background-color:<?= $data['template']['background']['bg_color'] ?? 'transparent' ?>"
>
    <?php if(!empty($data['ribbon']['content'])) { ?>
        <h5 class="p-2 px-4"
            style="background-color:<?= $data['ribbon']['bg_color'] ?>; color: <?= $data['ribbon']['txt_color'] ?>; text-align:<?= $data['ribbon']['align'] ?? 'left' ?>"
        >
            <?= $data['ribbon']['content'] ?>
        </h5> 
    <?php } ?>

    <div class="top-categories p-3 pt-0">
        <?php foreach($top_categories as $index => $category) { 
            $catname = strtolower($category['category_name']);
            $catname = preg_replace("/[.,&\s\/_]+/", "-", $catname);

            $img_src = "$img_host/images/categories/$catname/main.jpg";
            
            $logo_web    = !empty($category) && !empty($category['logo_web'   ]) ? $category['logo_web'   ] : [];
            $logo_mobile = !empty($category) && !empty($category['logo_mobile']) ? $category['logo_mobile'] : [];

            $logo_web_data    = !empty($logo_web)    ? json_decode($logo_web,    true) : [];
            $logo_mobile_data = !empty($logo_mobile) ? json_decode($logo_mobile, true) : [];

            /* this is original codebase
            if ($is_mobile == '1') {
                if (!empty($category['logo_mobile']['url']))
                    $img_src = $category['logo_mobile']['url'];
                else if (!empty($category['logo_web']['url']))
                    $img_src = $category['logo_web']['url'];
            } else {
                if (!empty($category['logo_web']['url']))
                    $img_src = $category['logo_web']['url'];
                else if (!empty($category['logo_mobile']['url']))
                    $img_src = $category['logo_mobile']['url'];
            }*/
            // this is new codebase updated by holla.ardy, 2025/11/04
            if ($is_mobile == '1') {
                if (!empty($logo_mobile_data['active_link']) && !empty($logo_mobile_data['upload_file']))
                    $img_src = env('app.uploads_baseurl')/*$logo_mobile_data['active_link']*/ . '/' . $logo_mobile_data['upload_file'];
                else if (!empty($logo_web_data['active_link']) && !empty($logo_web_data['upload_file']))
                    $img_src = env('app.uploads_baseurl')/*$logo_web_data['active_link']*/ . '/' . $logo_web_data['upload_file'];
            } else {
                if (!empty($logo_web_data['active_link']) && !empty($logo_web_data['upload_file']))
                    $img_src = env('app.uploads_baseurl')/*$logo_web_data['active_link']*/ . '/' . $logo_web_data['upload_file'];
                else if (!empty($logo_mobile_data['active_link']) && !empty($logo_mobile_data['upload_file']))
                    $img_src = env('app.uploads_baseurl')/*$logo_mobile_data['active_link']*/ . '/' . $logo_mobile_data['upload_file'];
            }
        ?>
            <div class="one-category d-flex flex-column align-items-center mt-1 mb-3 mx-2 category-link" data-category-id="<?= $category['category_id'] ?>">
                <img src="<?= $img_src ?>" class="category-logo" />
                <div class="category-name mt-2" style="color:<?= $data['data']['label']['txt_color'] ?? '#333' ?>;"><?= $category['alias'] ?></div>
            </div>
        <?php }  ?>
    </div>
</div>