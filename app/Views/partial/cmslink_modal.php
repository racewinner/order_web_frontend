<div id="cmslink_dialog" class="modal" style="width: 400px; height: 200px;">
    <div class="modal-content">
        <p>What do you want to do?</p>
        <div class="close-btn">
            <img 
                class="modal-close"
                src="/images/icons/close-round-line.svg" 
                style="width:25px; height:25px;" 
            />
        </div>
        <div class="mt-4">
            <button type='button' class="btn view-link" style="width: 250px; background:white; color:#0063ff;">
                View Link
            </button>
        </div>
        <div class="mt-4">
            <button type='button' class="btn show-me-products" style="width: 250px; background:white; color:#0063ff;">
                Show Me Products
            </button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on('click', '#cmslink_dialog .view-link', function(e) {
            const link = $(e.target).data('link');
            $("#cmslink_dialog").modal("close");
            window.open(link, '_blank');
        })

        $(document).on('click', '#cmslink_dialog .show-me-products', function(e) {
            const id = $(e.target).data('id');
            const prod_codes = $(e.target).data('prodcodes');
            $("#cmslink_dialog").modal("close");
            searchProductsByProdCodes(prod_codes);
        })
    })
</script>