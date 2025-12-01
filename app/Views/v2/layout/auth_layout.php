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
	<script src="/assets/js/dark_mode.js"></script>

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
	<link rel="stylesheet" type="text/css" href="/assets/vendor/jquery/jquery-ui.min.css">
		
	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/css/style.css?v=<?= env('app.asset.version') ?>">
	<link rel="stylesheet" type="text/css" href="/assets/css/app.css?v=<?= env('app.asset.version') ?>">
	<!-- <link rel="stylesheet" type="text/css" href="/assets/css/festive.css?v=<= env('app.asset.version') ?>"> -->

	<?= $this->renderSection('css') ?>
</head>

<body class="auth-layout">
	<!-- <= view('v2/partials/snow_falling') ?> -->
    <!-- <= view('v2/partials/santas_sleigh') ?> -->
    <?= view('v2/partials/auth_header') ?>

    <!-- **************** MAIN CONTENT START **************** -->
    <main style="position: relative;">
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
	<div id="question-msg-container"></div>
	<!-- alert message container end -->

    <?= view('v2/partials/main_footer') ?>

    <!-- Bootstrap JS -->
	<script src="/assets/vendor/jquery/jquery-3.7.1.min.js"></script>
	<script src="/assets/vendor/jquery/jquery-ui.min.js"></script>
    <script src="/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!--Vendors-->
    <script src="/assets/vendor/isotope/isotope.pkgd.min.js"></script>
    <script src="/assets/vendor/imagesLoaded/imagesloaded.pkgd.min.js"></script>
	<script src="/assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Theme Functions -->
    <script src="/assets/js/functions.js"></script>
	<script src="/assets/js/app.js?v=<?= env('app.asset.version') ?>"></script>
    <script src="/assets/js/pagination.js?v=<?= env('app.asset.version') ?>"></script>
	<script src="/assets/js/pg_nav_spinner.js"></script>
	<!-- <script src="/assets/js/snow_falling.js"></script>
	<script src="/assets/js/santas_sleigh.js"></script> -->

	<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />

	<?= $this->renderSection('javascript') ?>
</body>
</html>