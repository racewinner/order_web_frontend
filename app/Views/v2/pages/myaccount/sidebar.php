<?php
	$allowed_module_ids = array_map(function($module) {
		return $module['module_id'];
	}, $allowed_modules);

	$logon_user_menues = LOGON_USER_MENUES;
    if(!in_array('employees', $allowed_module_ids)) {
		unset($logon_user_menues['my_orders']); //2
	}
	if(!in_array('employees', $allowed_module_ids)) {
		unset($logon_user_menues['add_employee']); //2
	}

    $current_url = request()->getUri()->getPath();    
?>

<div class="my-account-side-menu d-none d-md-block">
    <ul>
        <?php foreach($logon_user_menues as $menu) { ?>
            <li class="<?= !empty($menu['url']) && str_contains($current_url, $menu['url']) ? 'active' : '' ?>">
                <a href="<?= $menu['url'] ?>">
                    <img src="<?= $menu['icon'] ?>" />
                    <span class="ms-1"><?= $menu['label'] ?></span>
                </a> 
            </li>
        <?php } ?>
    </ul>
</div>