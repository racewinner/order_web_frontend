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
    .modal-auto-width-content {
        position: relative;
        display: flex;
        flex-direction: column;
        /* width: 100%; */
        color: var(--bs-modal-color);
        pointer-events: auto;
        background-color: var(--bs-modal-bg);
        background-clip: padding-box;
        border: var(--bs-modal-border-width) solid var(--bs-modal-border-color);
        border-radius: var(--bs-modal-border-radius);
        border-top-left-radius: 0.313rem;
        border-top-right-radius: 0.313rem;
        border-bottom-right-radius: 0.313rem;
        border-bottom-left-radius: 0.313rem;
        outline: 0;
    }
    .px-40 {
        padding-left: 40px;
        padding-right: 40px;
    }
    .px-100 {
        padding-left: 100px;
        padding-right: 100px;
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