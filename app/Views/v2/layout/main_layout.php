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
	<link rel="stylesheet" type="text/css" href="/assets/vendor/swiper/swiper-bundle.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/vendor/jquery/jquery-ui.min.css">
		
	<!-- Theme CSS -->
	<link rel="stylesheet" type="text/css" href="/assets/css/style.css?v=<?= env('app.asset.version') ?>">
	<link rel="stylesheet" type="text/css" href="/assets/css/app.css?v=<?= env('app.asset.version') ?>">

	<!-- WhatsApp Widget CSS -->
	<style>
		.whatsapp-widget {
			position: fixed;
			bottom: 20px;
			right: 20px;
			z-index: 1000;
			width: 60px;
			height: 60px;
			background-color: #25D366;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
			cursor: pointer;
			transition: all 0.3s ease;
			text-decoration: none;
		}
		.whatsapp-widget:hover {
			background-color: #20BA5A;
			transform: scale(1.1);
			box-shadow: 0 6px 16px rgba(37, 211, 102, 0.5);
		}
		.whatsapp-widget i {
			color: #FFFFFF;
			font-size: 32px;
		}
		@media (max-width: 768px) {
			.whatsapp-widget {
				bottom: 15px;
				right: 15px;
				width: 55px;
				height: 55px;
			}
			.whatsapp-widget i {
				font-size: 28px;
			}
		}
	</style>

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
	<div id="question-msg-container"></div>
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
		
		// WhatsApp Widget - Only show for authenticated users
		// Get default WhatsApp number and message from app config
		$db = \Config\Database::connect();
		$whatsapp_number = '';
		$whatsapp_message = '';
		
		// Helper function to clean WhatsApp number
		$cleanWhatsAppNumber = function($number) {
			if (empty($number)) {
				return '';
			}
			// Remove all non-numeric characters (spaces, dashes, plus signs, etc.)
			$cleaned = preg_replace('/[^0-9]/', '', $number);
			// Remove leading '0' if present
			$cleaned = ltrim($cleaned, '0');
			return $cleaned;
		};
		
		// Get default WhatsApp number from app config
		$result = $db->table('epos_app_config')->where('key', 'whatsapp_number')->get()->getRow();
		if (!empty($result) && !empty($result->value)) {
			$whatsapp_number = $cleanWhatsAppNumber($result->value);
		}
		
		// Get default WhatsApp message from app config
		$result = $db->table('epos_app_config')->where('key', 'whatsapp_message')->get()->getRow();
		if (!empty($result) && !empty($result->value)) {
			$whatsapp_message = urlencode($result->value);
		} else {
			$whatsapp_message = urlencode('Hi, I need assistance.'); // Fallback message
		}
		
		// Try to get whatsapp number from branch (overrides default if available)
		$branch = session()->get('branch');
		if (!empty($branch)) {
			$Branch = new \App\Models\Branch();
			$branch_whatsapp_number = $Branch->getBranchWhatsappNumberById($branch);
			if (!empty($branch_whatsapp_number)) {
				// Clean the branch whatsapp number
				$whatsapp_number = $cleanWhatsAppNumber($branch_whatsapp_number);
			}
		}
		?>
		<!-- WhatsApp Widget -->
		<?php
		$whatsapp_url = '';
		if (!empty($whatsapp_number)) {
			$whatsapp_url = "https://wa.me/{$whatsapp_number}";
			if (!empty($whatsapp_message)) {
				$whatsapp_url .= "?text={$whatsapp_message}";
			}
		}
		?>
		<a href="<?= !empty($whatsapp_url) ? $whatsapp_url : '#' ?>" 
		   class="whatsapp-widget" 
		   data-whatsapp-number="<?= htmlspecialchars($whatsapp_number ?? '', ENT_QUOTES, 'UTF-8') ?>"
		   <?= !empty($whatsapp_url) ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
		   aria-label="Contact us on WhatsApp">
			<i class="fab fa-whatsapp"></i>
		</a>
	<?php } // End if user_info ?>
	
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
	<script src="/assets/js/pg_nav_spinner.js"></script>

	<script>
		// WhatsApp widget click handler
		$(document).ready(function() {
			$('.whatsapp-widget').on('click', function(e) {
				const whatsappNumber = $(this).data('whatsapp-number');
				
				if (!whatsappNumber || whatsappNumber.trim() === '') {
					e.preventDefault();
					e.stopPropagation();
					
					showToast({
						type: 'warning',
						title: 'Warning',
						message: 'Sorry, There is not linked phone number',
						delay: 4000
					});
					
					return false;
				}
			});
		});
	</script>

	<?= $this->renderSection('javascript') ?>
</body>
</html>