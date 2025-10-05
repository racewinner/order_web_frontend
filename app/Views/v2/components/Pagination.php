<?php
    $diff = 2;
    $from_page = max(1, $curd_page - $diff);
    $to_page = min($total_page, $curd_page + $diff);
?>

<ul class="pagination pagination-primary-soft d-flex justify-content-end m-0" 
    data-base-url="<?= $base_url ?>"
    data-per-page="<?= $per_page ?>"
    data-curd-page="<?= $curd_page ?>"
    data-total-page ="<?= $total_page ?>"
>
    <li>
        <ul class="list-unstyled">
            <li class="page-item <?= $curd_page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link prev" href="#" tabindex="-1" aria-disabled="<?= $curd_page <= 1 ? 'true' : '' ?>">Prev</a>
            </li>

            <?php for($p=$from_page; $p<$curd_page; $p++) { ?>
                <li class="page-item">
                    <a class="page-link goto-page" href="#" data-page-num="<?= $p ?>"><?= $p ?></a>
                </li>
            <?php } ?>

            <li class="page-item active"><a class="page-link" href="#"><?= $curd_page ?></a></li>

            <?php for($p=$curd_page + 1; $p <= $to_page; $p++) { ?>
                <li class="page-item">
                    <a class="page-link goto-page" href="#" data-page-num="<?= $p ?>"><?= $p ?></a>
                </li>
            <?php } ?>

            <?php if($to_page < $total_page) { ?>
                <li class="page-item"><a class="page-link" href="#">..</a></li>    
            <?php } ?>
            
            <li class="page-item <?= $curd_page >= $total_page ? 'disabled' : '' ?>">
                <a class="page-link next" href="#" aria-disabled="<?= $curd_page >= $total_page ? 'true' : '' ?>">Next</a>
            </li>
        </ul>
    </li>
</ul>