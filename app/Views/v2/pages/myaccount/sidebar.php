<?php
	$logon_user_menues = LOGON_USER_MENUES;
    $current_url = request()->uri->getPath();    
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