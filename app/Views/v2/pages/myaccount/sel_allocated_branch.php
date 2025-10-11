<?= $this->extend('v2/layout/auth_layout') ?>

<?= $this->section('css') ?>
<style>
    .branch-select {
        padding: 50px 0px;
        width: 90%;
        max-width: 400px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="branch-select mx-auto">
    <form method='post' action="/myaccount/sel_branch">
        <label for="branch" class="fw-90">Please select branch:</label>
        <div class="mt-2">
            <select id='branch' name='branch' class="form-select">
                <?php 
                    foreach($allocated_branches as $branch) {
                        echo '<option value="' . $branch->id . '" '.( $nearest_branch_id == $branch->id ? 'selected' : '') . '>' . $branch->site_name . '</option>';
                    }
                ?>
            </select>
        </div>
        <!-- <div class="d-flex align-items-end mt-2">
          <img src="/images/multi-factory-loc.png" width="24"/>
          <a class="px-1">you can choose other branch</a>
        </div>        -->

        <button type="submit" id="btn_confirm" class="mt-4 btn btn-danger w-100">
            Confirm
        </button>
    </form>
</div>
<?= $this->endSection() ?>
