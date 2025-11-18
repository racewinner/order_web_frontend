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
    <form id="login-form" action="/login" method="post">
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
            <button class="btn mb-0 btn-danger" id="btn-login" type="button">
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
            <button id="btn-customer-register" class="btn mb-0 btn-outline-dark w-100" type="button">Apply for an account</button>
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
            add_loadingSpinner_to_button(e.target);
            window.location.href = '/login/guest_login';
        })
        $(document).on('click', '#btn-customer-register', function(e) {
            add_loadingSpinner_to_button(e.target);
            window.location.href = '/customer-register';
        })
        $(document).on('keyup', '#username, #password', function(e) {
            if (e.key === 'Enter') {
                $('#btn-login').click();
            }
        })
        $(document).on('click', '#btn-login', function(e) {
            add_loadingSpinner_to_button(e.target);

            const payload = {
                username: $('#username').val()
            }
            $.ajax({
                type: "POST"
                , async: true
                , url: "/login/pre-login"
                , dataType: "json"
                , timeout: 30000
                , cache: false
                , data: payload
                , error: function (xhr, status, error) {
                    if (xhr.status == 401) {
                        window.location.href = '/login'; 
                        return;
                    } else {
                        console.log("An error occured: " + xhr.status + " " + xhr.statusText);
                    }}
                , success: function (response, status, request) {
                    if (response.data == 1) {
                        $('#login-form').submit();
                        return;
                    } else if (response.data == 0) {
                        alert_message(
                            'Your password has expired, on the next screen please enter your account number and the registered email address.\n'+
                            'A security code will then be emailed to you, so that you can set up a new password and log in.', 
                            'Security Alert', 
                            'user-has-not-got-password', 
                            function(e) {
                                window.location.href = '/forgot-password';
                                return;
                            });
                    } else {
                        showToast({
                            type: 'error',
                            message: "There is no user with that username.",
                        });
                        remove_loadingSpinner_from_button(e.target);
                        return;
                    }
                }
                , complete: function() {
                }
            });
        })
    })
</script>
<?= $this->endSection() ?>
