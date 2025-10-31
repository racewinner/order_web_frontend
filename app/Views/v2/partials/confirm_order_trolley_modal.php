<?= $this->section('css') ?>
<style>
    .confirm-content-panel {
        display: flex;
        gap: 10px;
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
</style>
<?= $this->endSection() ?>

<div id="confirm_order_trolley_dialog" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered max-w-700">
        <div class="modal-auto-width-content">
            <div class="modal-header">
                <div class="w-100 text-center">What do you want to do?</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-40 py-5 d-flex flex-column align-items-center">
                <div class="confirm-content-panel mt-0">
                    <!-- <div class="comment mt-2 text-black mb-2">
                        Please select a trolley container
                    </div> -->

                    <div class="one-trolley-container d-flex align-items-center p-2 mb-2">
                        <div>
                            <input class="form-check-input mr-5" type="radio" name="trolley_container" 
                                id="all" value="all" checked>
                        </div>
                        <div class="flex-fill">All</div>
                    </div>
                    <?php if (strpos($cart_typename, "general") !== false) { ?>
                    <div class="one-trolley-container d-flex align-items-center p-2 mb-2">
                        <div>
                            <input class="form-check-input mr-5" type="radio" name="trolley_container" 
                                id="general" value="general">
                        </div>
                        <div class="flex-fill">General</div>
                    </div>
                    <?php } ?>
                    <?php if (strpos($cart_typename, "tobacco") !== false) { ?>
                    <div class="one-trolley-container d-flex align-items-center p-2 mb-2">
                        <div>
                            <input class="form-check-input mr-5" type="radio" name="trolley_container" 
                                id="tobacco" value="tobacco">
                        </div>
                        <div class="flex-fill">Tobacco</div>
                    </div>
                    <?php } ?>
                    <?php if (strpos($cart_typename, "chilled") !== false) { ?>
                    <div class="one-trolley-container d-flex align-items-center p-2 mb-2">
                        <div>
                            <input class="form-check-input mr-5" type="radio" name="trolley_container" 
                                id="chilled" value="chilled">
                        </div>
                        <div class="flex-fill">Chilled</div>
                    </div>
                    <?php } ?>
                    <?php if (strpos($cart_typename, "spresel") !== false) { ?>
                    <div class="one-trolley-container d-flex align-items-center p-2 mb-2">
                        <div>
                            <input class="form-check-input mr-5" type="radio" name="trolley_container" 
                                id="spresell" value="spresell">
                        </div>
                        <div class="flex-fill">Seasonal Presell</div>
                    </div>
                    <?php } ?>
                </div>

                <button type='button' class="btn btn-danger order-complete w-50 border mt-4" data-bs-dismiss="modal">
                    Complete
                </button>
            </div>
        </div>
    </div>
</div>