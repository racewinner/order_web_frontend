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
        <div class="flex-fill user-login-info card full-width-on-mobile">
                <div class="card-header p-2">
                    <div class='m-0'>User Login Info</div>
                </div>
                <div class="card-body p-3">
                    <div class="mb-2">
                        <label class="required">E-Mail:</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="email" name="email" 
                            placeholder="User Email" 
                            value="xxx" 
                            required 
                            /> 
                        
                    </div>
                    <div class="mb-3">
                        <label class="required">Username:</label>
                        <input type="text" class="form-control" 
                            id="username" name="username" 
                            placeholder="username" 
                            value="yyy" 
                            required 
                            />
                        
                    </div>

                    
                </div>
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
