<div class="header-ribbon d-none d-lg-block" 
    style="background: <?= $topRibbonConfig['style']['bg_color'] ?? 'rgb(4, 49, 79)' ?>; color:<?= $topRibbonConfig['style']['txt_color'] ?? 'white' ?>"
>
    <div class="d-flex">
        <div class="flex-fill introduce-boxed-express d-flex align-items-center">
            <?= $topRibbonConfig['content_html'] ?? '' ?>
        </div>
        <div class="business-contacts d-flex align-items-center px-4">
            <?php if (!empty($branchTelephone)) { ?>
                <span class="phone"><i class="bi bi-telephone me-1"></i><span class="fs-80"><?= $branchTelephone??'' ?></span></span>
            <?php } ?>
            <span class="twitter" style="margin-left: 30px;"><i class="bi bi-twitter"></i></span>
            <span class="facebook" style="margin-left: 30px;"><i class="bi bi-facebook"></i></i></span>
            <span class="wifi" style="margin-left: 30px;"><i class="bi bi-wifi"></i></span>
        </div>
    </div>
</div>