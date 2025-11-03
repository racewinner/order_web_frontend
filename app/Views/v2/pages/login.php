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
    <form action="/login" method="post">
        <h4 class="text-center mb-4 pt-4-5">Welcome</h4>

        <div class="text-red mb-4">
            <?php if (isset($validation) && $validation->getErrors()): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($validation->getErrors() as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>
            <?php if (session()->getFlashdata('error')): ?>						
                <?= session()->getFlashdata('error') ?>							
            <?php endif; ?>
        </div>
        
        <div class="mb-4">
            <label for="username" class="required fs-90">Enter your Login Username</label>
            <input type="text" class="form-control" id="username" name="username" size="20" data-listener-added_c5e2aeed="true" required>
        </div>

        <div class="mb-4">
            <label for="password" class="required fs-90">Enter your Login Password</label>
            <input type="password" class="form-control" id="password" name="password" size="20" data-listener-added_c5e2aeed="true" required>
        </div>

        <div class="mb-4">
            <button class="btn mb-0 btn-danger" id="btn-login" type="submit">
                LOGIN
            </button>
        </div>
        <div class="mb-4" style="margin-top: -10px; text-align: right;">
            <a href="/forgot-password" style="color: red; text-decoration: underline;">forgot password?</a>
        </div>


        <div class="mb-4 text-center">OR</div>
        
        <div class="mb-4">
            <button id="btn-guest-login" class="btn mb-0 btn-outline-dark w-100" type="button">Guest Login</button>
        </div>
        <div class="mb-4" style="margin-top: -10px; text-align: right;">
            <a href="/customer-register" style="color: red; text-decoration: underline;">customer register</a>
        </div>


        <p>
            If you don't have an account, please contact Telesales<br/>
            telesales@uniteduk.com.
        </p>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
    $(document).ready(function(e) {
        $(document).on('click', '#btn-guest-login', function(e) {
            window.location = 'login/guest_login';
        })
    })
</script>
<?= $this->endSection() ?>
