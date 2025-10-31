<?= $this->extend('v2/layout/auth_layout') ?>

<?= $this->section('css') ?>
<style>
.login-panel {
    /* width: 90%; */
    max-width: 450px;
    padding-left: 40px;
    padding-right: 40px;
    button#btn-login {
        margin-top: 20px !important;
        width: 100%;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="login-panel mx-auto">
    <form action="/reinstall-password" method="post">
        <h4 class="text-center mb-4 pt-4-5">Reset your password</h4>

        <input type="hidden" name="email" id="email" value="<?= $email ?>">
        <input type="hidden" name="username" id="username" value="<?= $username ?>">

        <div class="mb-4">
            <label for="pin_verify_number" class="required fs-90">Enter your verify number</label>
            <input type="text" class="form-control" id="pin_verify_number" name="pin_verify_number" required>
        </div>

        <div class="mb-4">
            <label for="password" class="required fs-90">Enter your New Password</label>
            <input type="password" class="form-control" id="password" name="password" size="20" required>
        </div>
        <div class="mb-4">
            <label for="password2" class="required fs-90">Enter your Confirm Password</label>
            <input type="password" class="form-control" id="password2" name="password2" size="20" required>
        </div>

        <div class="mb-4">
            <button class="btn mb-0 btn-danger" style="width: 100%" id="btn-reset-password" type="submit">
                Save and Login
            </button>
        </div>
    </form>
    <div class="mb-4">
        <button class="btn mb-0 btn-danger" style="width: 100%" id="btn-go-back">
            GO BACK
        </button>
    </div>

    <div style="margin-bottom: 5px">If your details have been matched you will be sent an email containing a one time passcode to allow the resetting of your password.</div>
    <div style="margin-bottom: 5px">Please enter that code on this screen.</div>
    <div style="margin-bottom: 5px">Note: The code is only valid for 10 minutes.</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function(e) {
        $(document).on('click', '#btn-go-back', function(e) {
            window.history.back();
        })
    })
</script>
<?= $this->endSection() ?>
