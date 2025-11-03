<!DOCTYPE html>
<html lang="en">
<head>
	<title><?= !empty($title) ? esc($title) : esc(lang('Main.main_title')); ?></title>

	<!-- Meta Tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Webestica.com">
	<meta name="description" content="Technology and Corporate Bootstrap Theme">

	<!-- Dark mode -->
	<script>
		const storedTheme = localStorage.getItem('theme')
 
		const getPreferredTheme = () => {
			if (storedTheme) {
				return storedTheme
			}
			return window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'light'
		}

		const setTheme = function (theme) {
			if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
				document.documentElement.setAttribute('data-bs-theme', 'dark')
			} else {
				document.documentElement.setAttribute('data-bs-theme', theme)
			}
		}

		setTheme(getPreferredTheme())

		window.addEventListener('DOMContentLoaded', () => {
		    var el = document.querySelector('.theme-icon-active');
			if(el != 'undefined' && el != null) {
				const showActiveTheme = theme => {
				const activeThemeIcon = document.querySelector('.theme-icon-active use')
				const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
				const svgOfActiveBtn = btnToActive.querySelector('.mode-switch use').getAttribute('href')

				document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
					element.classList.remove('active')
				})

				btnToActive.classList.add('active')
				activeThemeIcon.setAttribute('href', svgOfActiveBtn)
			}

			window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
				if (storedTheme !== 'light' || storedTheme !== 'dark') {
					setTheme(getPreferredTheme())
				}
			})

			showActiveTheme(getPreferredTheme())

			document.querySelectorAll('[data-bs-theme-value]')
				.forEach(toggle => {
					toggle.addEventListener('click', () => {
						const theme = toggle.getAttribute('data-bs-theme-value')
						localStorage.setItem('theme', theme)
						setTheme(theme)
						showActiveTheme(theme)
					})
				})

			}
		})
		
	</script>

	<!-- Favicon -->
	<link rel="shortcut icon" href="/assets/images/favicon.ico">

	<!-- Google Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

	<!-- Plugins CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/vendor/font-awesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/bootstrap-icons/bootstrap-icons.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/glightbox/css/glightbox.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/swiper/swiper-bundle.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/jquery/jquery-ui.min.css">
		
	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/css/style.css?v=<?= env('app.asset.version') ?>">
	<link rel="stylesheet" type="text/css" href="/assets/css/app.css?v=<?= env('app.asset.version') ?>">

	<?= $this->renderSection('css') ?>
</head>

<body class="main-layout">
    <?= view('v2/partials/main_header') ?>

    <!-- **************** MAIN CONTENT START **************** -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>
    <!-- **************** MAIN CONTENT END **************** -->

	<!-- toast start -->
	<div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 9999"></div>
	<template id="toastTemplate">
		<div class="toast mb-2" role="status" aria-live="polite" aria-atomic="true">
			<div class="toast-header">
				<span class="me-2" id="toastIcon" aria-hidden="true"></span>
				<strong class="me-auto toast-title"></strong>
				<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
			<div class="toast-body"></div>
		</div>
	</template>
	<!-- toast end -->

	<!-- alert message container start -->
	<div id="alert-msg-container"></div>
	<!-- alert message container end -->


    <?= view('v2/partials/main_footer') ?>

	<?php if(!empty($user_info)) { ?>
		<input type="hidden" name="logon_person_id" id="logon_person_id" value="<?= $user_info->person_id ?>" />
	<?php } ?>

    <!-- Back to top -->
    <div class="back-top"></div>

	<?= view("v2/partials/cmslink_modal"); ?>
	<?= view("v2/partials/view_modal"); ?>

	<?php if(!empty($user_info)) {
		echo view("v2/partials/my_branches_modal");
		echo view("v2/partials/my_cart_sidebar");
	}?>
	
    <!-- Bootstrap JS -->
	<script src="/assets/vendor/jquery/jquery-3.7.1.min.js"></script>
	<script src="/assets/vendor/jquery/jquery-ui.min.js"></script>
    <script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!--Vendors-->
    <script src="/assets/vendor/isotope/isotope.pkgd.min.js"></script>
    <script src="/assets/vendor/imagesLoaded/imagesloaded.pkgd.min.js"></script>
	<script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>

    <script src="/assets/js/functions.js"></script>
	<script src="/assets/js/app.js?v=<?= env('app.asset.version') ?>"></script>
	<script src="/assets/js/pagination.js?v=<?= env('app.asset.version') ?>"></script>

	<?= $this->renderSection('javascript') ?>
</body>
</html>