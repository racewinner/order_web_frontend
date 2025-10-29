<?php
    $menus = [];
    if(empty($user_info)) {
        $menus = [
            'home' => ['icon' => 'bi-house-fill', 'label' => 'Home', 'url' => '/home'],
            'branch' => ['icon' => 'bi-geo-alt-fill', 'label' => 'Select Branch', 'url' => '/myaccount/sel_branch'],
            'login' => ['icon' => 'bi-person-fill', 'label' => 'Log In', 'url' => '/login'],
        ];
    } else{
        $allowed_module_ids = array_map(function($module) {
          return $module['module_id'];
        }, $allowed_modules);
    
        $menus = [
            'home' => ['icon' => 'bi-house', 'label' => 'Home', 'url' => '/home'],
            'products' => ['icon' => 'bi-list-ul', 'label' => 'Products', 'url' => '/products'],
            'spresell' => ['icon' => 'bi-tag-fill', 'label' => 'Seasonal Presell', 'url' => '/seasonal_presell/index?category_id=0&spresell=1'],
            'promos' => ['icon' => 'bi-tag-fill', 'label' => 'Promos', 'url' => '/promos/index/du'],
            'orders' => ['icon' => 'bi-clock', 'label' => 'Orders', 'url' => '/pastorders'],
            'contact_us' => ['icon' => 'bi-envelope', 'label' => 'Contact Us', 'url' => '/contactus'],
            'separator1' => ['label' => 'separator'],
            'trolley' => ['icon' => 'bi-cart3', 'label' => 'Trolley', 'url' => '/orders']
        ];

        $menus['employees'] = ['icon' => 'bi-person-fill', 'label' => 'User', 'url' => '/employees'];

        if(in_array('branch', $allowed_module_ids)) {
            $menus['branch'] = ['icon' => 'bi-geo-alt-fill', 'label' => 'Select Branch', 'url' => '/myaccount/sel_allocated_branch'];
        }

        $menus['myaccount'] = ['icon' => 'bi-person-fill', 'label' => 'My Account', 'url' => '/', 'submenus' => LOGON_USER_MENUES];
        $menus['separator2'] = ['label' => 'separator'];
        $menus['logout'] = ['icon' => 'bi-box-arrow-left', 'label' => 'Log Out', 'url' => '/home/logout'];
    }


?>
<div class="sidebar collapsed" id="main-sidebar-menu">
    <div class="sidebar-content">
        <div class="sidebar-header d-flex justify-content-center align-items-center">
            <i class="bi bi-truck" style="font-size: 25px;"></i>
            <span class="ms-3">UWS Web Ordering</span>
        </div>
        <ul>
            <?php foreach($menus as $id => $m) { 
                if($m['label'] == 'separator') { ?>
                <li class="separator"></li>
            <?php } else{ ?>
                <li id="main_sidebar_menu_<?= $id ?>">
                    <?php if(!empty($m['submenus'])) { ?>
                        <a class="d-flex align-items-center position-relative">
                            <i class="bi <?= $m['icon'] ?> me-4"></i>
                            <?= $m['label'] ?>
                            
                            <div 
                                class="toggle-show-hide" 
                                data-show-hide-target="#main_sidebar_menu_<?= $id ?> ul.submenus"
                                style="right: 10px; top: 12px;"
                            >
                            </div>
                        </a>
                        <ul class="submenus d-none">
                            <?php foreach($m['submenus'] as $id => $sm) { ?>
                                <li id="main_sidebar_submenu_<?=$id ?>">
                                    <a class="d-flex align-items-center" href="<?= $sm['url'] ?>">
                                        <?= $sm['label'] ?>
                                    </a>                                    
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <a class="d-flex align-items-center" href="<?= $m['url'] ?>">
                            <i class="bi <?= $m['icon'] ?> me-4"></i>
                            <?= $m['label'] ?>
                        </a>
                    <?php } ?>
                </li>
            <?php }} ?>

            <li class="separator">&nbsp;</li>
            <li><a class="d-flex align-items-center close" data-dismiss="sidebar">
                <i class="bi bi-x-lg me-4"></i>
                Close
            </a></li>
        </ul>
    </div>
</div>