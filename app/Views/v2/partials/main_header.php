<?php
$uri = service('request')->uri;
$uri_segments = $uri->getSegments();

if(empty($user_info)) {
	$top_menus = [
		['id'=>'home', 'icon' => 'bi-house-fill', 'label' => 'Home', 'url' => '/home', 'active'=>$uri_segments[0] == 'home'],
    ['id'=>'branch', 'icon' => 'bi-geo-alt-fill', 'label' => 'Select Branch', 'url' => '/myaccount/sel_branch', 'active'=>count($uri_segments) > 1 && $uri_segments[1] == 'sel_branch'],
		['id'=>'login', 'icon' => 'bi-person-fill', 'label' => 'Log In', 'url' => '/login', 'active'=>$uri_segments[0] == 'login'],
	];
	$logon_user_menues = [];
} else {
	$top_menus = [];

	$allowed_module_ids = array_map(function($module) {
		return $module['module_id'];
	}, $allowed_modules);

	if(in_array('home', $allowed_module_ids)) $top_menus[] = ['id'=>'home', 'icon' => 'bi-house-fill', 'label' => 'Home', 'url' => '/home', 'active'=>$uri_segments[0] == 'home'];
	if(in_array('products', $allowed_module_ids)) $top_menus[] = ['id'=>'products', 'icon' => 'bi-list-ul', 'label' => 'Products', 'url' => '/products', 'active'=>$uri_segments[0] == 'products'];
	if(in_array('seasonal_presell', $allowed_module_ids)) $top_menus[] = ['id'=>'presell', 'icon' => 'bi-tag-fill', 'label' => 'Seasonal Presell', 'url' => '/seasonal_presell/index?category_id=0&spresell=1', 'active'=>$uri_segments[0]=='seasonal_presell'];
	if(in_array('promos', $allowed_module_ids)) $top_menus[] = ['id'=>'promos', 'icon' => 'bi-tag-fill', 'label' => 'Promos', 'url' => '/promos/index/du', 'active'=>$uri_segments[0] == 'promos'];
	if(in_array('favorites', $allowed_module_ids)) $top_menus[] = ['id'=>'my_favorite', 'icon' => 'bi-heart', 'label' => 'Favourite', 'url' => '/favorites', 'active'=>$uri_segments[0] == 'favorites'];
	if(in_array('orders', $allowed_module_ids)) $top_menus[] = ['id'=>'my_cart', 'icon' => 'bi-cart3', 'label' => 'Empty', 'label_class'=>'cart-amount', 'active'=>$uri_segments[0] == 'orders'];
	if(in_array('employees', $allowed_module_ids)) $top_menus[] = ['id'=>'employees', 'icon'=>'bi-person-fill', 'label'=>'Users', 'url'=>'/employees', 'active' => $uri_segments[0] == 'employees'];
	// if(in_array('branches', $allowed_module_ids)) 
    $top_menus[] = ['id'=>'branch', 'icon'=>'bi-geo-alt-fill', 'label'=>'Select Branch', 'url'=>'/myaccount/sel_branch', 'active' => count($uri_segments) > 1 && $uri_segments[1] == 'sel_branch'];

	$logon_user_menues = LOGON_USER_MENUES;
	if(empty($credit_account)) {
		unset($logon_user_menues['my_account']);
		unset($logon_user_menues['my_orders']);
		unset($logon_user_menues['my_order_history']);
		unset($logon_user_menues['my_invoice_history']);
		unset($logon_user_menues['credit_ledger']);
	}
}

$active_categories = [];
if(!empty($category_id) && $category_id > 0) {
	foreach($top_categories as $top_category) { 
		if($top_category['category_id'] == $category_id) {
			$active_categories['top'] = $top_category;
			break;
		} 
		
		foreach($top_category['sub_categories'] as $sub_category) {
			if($sub_category['category_id'] == $category_id) {
				$active_categories['top'] = $top_category;
				$active_categories['sub'] = $sub_category;
				break;
			}
		}

		if(!empty($active_category)) break;
	}
}
?>

<!-- Header START -->
<header class="">
	<input type="hidden" name="category_id" id="category_id" value="<?= $category_id ?? 0 ?>" />
    <input type="hidden" id="view_mode" name="view_mode" value="<?= $view_mode ?? 'grid' ?>" />

	<div class="position-fixed top-0 start-0 end-0 d-flex justify-content-center d-none" id="ajax-call-indicator">
		<div class="spinner-border text-primary spinner-border-sm">
		</div>
	</div>

	<?= view("v2/partials/header_ribbon") ?>

	<div class="header-logo d-flex align-items-center">
		<!-- Logo START -->
		<a class="logo-image" href="/">
			<img class="logo" src="/assets/images/uws-logo.jpg" alt="logo">
		</a>
		<!-- Logo END -->

		<div class="search-product-header flex-fill">
			<?= view('v2/components/SearchInput', ['name'=>'search_product', 'id'=>'search0', 'value'=>$search0 ?? '', 'placeholder' => 'Search Products']) ?>
		</div>

		<div class="header-logo-mainmenu mobile d-flex d-xl-none align-items-center p-2">
			<div class="">
				<i class="bi bi-tags toggle-sidebar" data-toggle-target="#category-sidebar"></i>
			</div>
			<?php if(!empty($user_info)) { ?>
			<div class="d-flex flex-column align-items-center ms-2">
				<a href="/orders"><i class="bi bi-cart3"></i></a>
			</div>
			<?php } ?>
			<div class="ms-2">
				<i class="bi bi-list toggle-sidebar" data-toggle-target="#main-sidebar-menu"></i>
			</div>
		</div>

		<div class="header-logo-mainmenu pc align-items-center d-none d-xl-flex ps-4 pe-2">
			<div class="top-menu d-flex align-items-center">
				<?php foreach($top_menus as $tm) { 
					if($tm['id'] == 'my_cart') { 
				?>
					<a 
						class="one-top-menu my-cart d-flex flex-column align-items-center justify-content-center toggle-sidebar <?= $tm['class'] ?? '' ?> <?= $tm['active'] ? 'active' : '' ?> cursor-pointer"
						data-toggle-target="#my-cart-sidebar"
					>
						<i class="bi <?= $tm['icon'] ?>"></i>
						<label class="<?= $tm['label_class'] ?? '' ?>"><?= $tm['label'] ?></label>
					</a>					
				<?php } else { ?>
					<a 
						href="<?= $tm['url'] ?? '#' ?>"
						class="one-top-menu d-flex flex-column align-items-center justify-content-center <?= $tm['class'] ?? '' ?> <?= $tm['active'] ? 'active' : '' ?>"
					>
						<i class="bi <?= $tm['icon'] ?>"></i>
						<label class="<?= $tm['label_class'] ?? '' ?>"><?= $tm['label'] ?></label>
					</a>
				<?php } } ?>
			</div>

			<?php if(!empty($user_info)) { ?>
			<div class="ms-2 d-flex align-items-center">
				<a class="nav-link dropdown-toggle p-2" 
					href="#" 
					data-bs-auto-close="outside" 
					data-bs-toggle="dropdown" 
					aria-haspopup="true" 
					aria-expanded="false"
					style="text-decoration: none;"
				>
					<img src='/assets/images/icons/png/profile-boy.png' style="width: 30px; height:30px;" />
					<span class="balance ms-2">Account</span>
				</a>
				<div class="logon-user-menu dropdown-menu dropdown-menu-size-lg p-3">
					<ul class="list-unstyled">
					<?php foreach($logon_user_menues as $menu) { ?>
						<li> <a class="dropdown-item" href="<?= $menu['url'] ?>">
							<img src="<?= $menu['icon'] ?>" />
							<span class="ms-1"><?= $menu['label'] ?></span>
						</a> </li>
					<?php } ?>
					</ul>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>

	<!-- Nav START -->
	<nav class="navbar navbar-expand-xl d-none d-xl-block">
		<!-- Main navbar START -->
		<div class="navbar-collapse collapse" id="navbarCollapse">
			<ul class="navbar-nav navbar-nav-scroll dropdown-hover mx-auto">
				<?php 
					$index = 0;
					foreach($top_categories as $top_category) { 
						$index++;
				?>
				<li class="nav-item dropdown <?= ($index > count($top_categories) - 5) ? 'dropstart' : '' ?>">
					<a class="nav-link dropdown-toggle category-link <?= !empty($active_categories['top']) && $active_categories['top']['category_id'] == $top_category['category_id'] ? 'active' : '' ?>" 
						href="#" 
						data-bs-auto-close="outside" 
						data-bs-toggle="dropdown" 
						aria-haspopup="true" 
						aria-expanded="false"
						data-category-id="<?= $top_category['category_id'] ?>"
					>
						<?= $top_category['alias'] ?>
					</a>
					<div class="dropdown-menu dropdown-menu-size-lg p-3">
						<ul class="list-unstyled">
							<?php foreach($top_category['sub_categories'] as $sub_category) { ?>
								<li> 
									<a class="dropdown-item category-link <?= !empty($active_categories['sub']) && $active_categories['sub']['category_id'] == $sub_category['category_id'] ? 'active' : '' ?>" 
										href="#" 
										data-category-id="<?= $sub_category['category_id'] ?>"
									>
										<?= $sub_category['category_name'] ?>
									</a> 
								</li>
							<?php } ?>
						</ul>
					</div>
				</li>
				<?php } ?>
			</ul>
		</div>
		<!-- Main navbar END -->
	</nav>
	<!-- Logo Nav END -->
</header>

<?= view("v2/partials/main_sidebar") ?>
<?= view("v2/partials/category_sidebar", ['active_categories' => $active_categories, 'top_categories' => $top_categories]) ?>

<!-- Header END -->