<!-- ======================= Footer START -->
<?php if (!empty($footerconfig)) { ?>
<footer class="bg-dark" data-bs-theme="dark" style="background-color: <?= $footerconfig['style']['bg_color'] ?> !important">
	<div class="sitemap">
		<div class="row g-4 g-lg-6 g-xl-8 justify-content-between">
			<div class="col-12 col-lg-6">
				<div>
					<img class="logo d-none d-md-block" src="<?= $footerconfig['logo_web']['url'] ?? $footerconfig['logo_mobile']['url'] ?? '' ?>" alt="" />
					<img class="logo d-md-none" src="<?= $footerconfig['logo_mobile']['url'] ?? $footerconfig['logo_web']['url'] ?? '' ?>" alt="" />
				</div>
				<div class="mt-2">
					<?= $footerconfig['content_html'] ?? '' ?>
				</div>
			</div>

			<div class="col-12 col-lg-6">
				<div class="row g-2 mb-4 mb-sm-5">
					<?php if(!empty($footerconfig['column1']['links']) && count($footerconfig['column1']['links']) > 0) { ?>
					<div class="col-6">
						<h6 style="color:<?=$footerconfig['column1']['header']['txt_color']?>">
							<?= $footerconfig['column1']['header']['content'] ?>
						</h6>
						<ul class="list-inline mb-0 mt-3">
							<?php foreach($footerconfig['column1']['links'] as $link) { ?>
								<li><a href="<?= $link['url'] ?>" style="color: <?= $footerconfig['style']['txt_color'] ?> !important"><?= $link['label'] ?></a></li>
							<?php } ?>
						</ul>						
					</div>
					<?php } ?>

					<?php if(!empty($footerconfig['column2']['links']) && count($footerconfig['column2']['links']) > 0) { ?>
					<div class="col-6">
						<h6 style="color:<?=$footerconfig['column2']['header']['txt_color']?>">
							<?= $footerconfig['column2']['header']['content'] ?>
						</h6>
						<ul class="list-inline mb-0 mt-3">
							<?php foreach($footerconfig['column2']['links'] as $link) { ?>
								<li><a href="<?= $link['url'] ?>" style="color: <?= $footerconfig['style']['txt_color'] ?> !important"><?= $link['label'] ?></a></li>
							<?php } ?>
						</ul>						
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

	<div class="copyright">
		<div class="d-flex flex-column flex-md-row px-5 py-3">
			<div class="flex-fill text-center">
				<?= $footerconfig['bottom_html'] ?>
			</div>
			<div class="contact mt-2 mt-md-0 text-center">
				<span class="phone"><i class="bi bi-telephone me-1"></i><span class="fs-80">0141 781 6608</span></span>
				<span class="twitter" style="margin-left: 30px;"><i class="bi bi-twitter"></i></span>
				<span class="facebook" style="margin-left: 30px;"><i class="bi bi-facebook"></i></i></span>
				<span class="wifi" style="margin-left: 30px;"><i class="bi bi-wifi"></i></span>
			</div>
		</div>
	</div>

</footer>
<?php } ?>
<!-- ======================= Footer END -->
