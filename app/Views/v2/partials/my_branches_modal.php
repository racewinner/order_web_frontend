<div id="my_branches_dialog" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="w-100 text-center">My Branches</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-5 d-flex flex-column align-items-center">
                <ul>
                    <?php foreach ($all_branches as $branch) { ?>
                        <li>
                            <label>
                                <input type="checkbox" class="filled-in" data-branch="<?= $branch['id'] ?>"
                                    <?= in_array($branch['id'], $user_info->branches) ? 'checked' : '' ?> />
                                <span class="ms-2"><?= $branch['site_name'] ?></span>
                            </label>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>