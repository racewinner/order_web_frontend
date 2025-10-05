<?php  echo view("partial/header_layout1"); ?>
<div class="branch-select">
    <form method='post' action="<?=base_url()?>myaccount/sel_branch">
        <p>Please select branch:</p>
        <div class="mt-4" style="width: 300px;">
            <select id='branch' name='branch'>
                <?php 
                    foreach($all_branches as $branch) {
                        echo '<option value="' . $branch['id'] . '" '.( $nearest_branch_id == $branch['id'] ? 'selected' : '') . '>' . $branch['site_name'] . '</option>';
                    }
                ?>
            </select>
        </div>
        <button type="submit" id="btn_confirm" class="mt-4 btn btn-primary">
            Confirm
        </button>
    </form>
</div>
<?php  echo view("partial/footer"); ?>