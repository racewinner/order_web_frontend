<?= $this->section('css') ?>
<style>
    .confirm-content-panel {
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: center;
    }
    .max-w-700 {
        max-width: 700px;
        justify-content: center;
    }
    .mr-5 {
        margin-right: 5px;
    }
    .mt-20 {
        margin-top: 20px;
    }
    .px-40 {
        padding-left: 40px;
        padding-right: 40px;
    }
    
</style>
<?= $this->endSection() ?>
<div class="modal fade" id="confirm_order_dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered max-w-700">
        <div class="modal-auto-width-content">
            <div class="modal-header">
                <div class="w-100 text-center">Can you confirm Order No.?</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-100 py-5 d-flex flex-column align-items-center">
                <form id="confirm_order_form">
                    <div class="confirm-content-panel mt-0">
                        <div class="">
                            <label for="pin_verify_number" class="required fs-90">Enter your verify number</label>
                            <input type="text" class="form-control" id="pin_verify_number" name="pin_verify_number" required>
                        </div>
                        <button type='submit' id="send-gen-order-number-btn" class="btn btn-danger full-fill border mt-4" >
                            Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>