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
    <form action="/reset-password" method="post">
        <h4 class="text-center mb-4 pt-4-5">Reset your password</h4>

        <div class="mb-4">
            <label for="username" class="required fs-90">Enter your username</label>
            <input type="text" class="form-control" id="username" name="username" size="20" required>
        </div>
        <div class="mb-4">
            <label for="email" class="required fs-90">Enter your email</label>
            <input type="text" class="form-control" id="email" name="email" size="20" required>
        </div>

        <div class="mb-4">
            <button class="btn mb-0 btn-danger" style="width: 100%" id="btn-reset-password" type="submit">
                RESET
            </button>
        </div>
    </form>
    <div class="mb-4">
        <button class="btn mb-0 btn-danger" style="width: 100%" id="btn-go-back">
            GO BACK
        </button>
    </div>

    <div>If you forgot your password, don't worry.</div>
    <div>Please reset your password and try to <a href="/login" style="color: red; text-decoration: underline;">Log In</a>.</div>
    
    
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
