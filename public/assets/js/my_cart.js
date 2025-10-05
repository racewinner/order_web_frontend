function handleCheckout() {
    window.location.href = "/opayo/checkout";
}

function handleSendOrder() {
    const data = {
        order_action: 2
    };
    $.ajax({
        type : "POST"
        , async : true
        , url : "/orders/send_order/<?= $type ?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : post_data
        , error : function(request, status, error) {
            alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
            if(response == 100)        { 
                showToast({
                    type: 'warning',
                    message: "You must added products to cart."
                });	
                return;	
            } else if(response == -100)  { 
                showToast({
                    type: 'warning',
                    message: "Error 100.Unable to make trolley into order"
                }); 
                return; 
            } else if(response == -101)  { 
                showToast({
                    type: 'warning',
                    message: "Error 101.Unable to create order header"
                }); 
                return; 
            } else if(response == -102)  { 
                showToast({
                    type: 'warning',
                    message: "Error 102.Unable to create order file"
                }); 
                return; 
            } else if(response == -2)    { 
                showToast({
                    type: 'warning',
                    message: "Error 2.Prepare new order"
                }); 
                return; 
            } else if(response == -3)    { 
                showToast({
                    type: 'warning',
                    message: "Error 3.Prepare an existing order"
                }); 
                return; 
            } else if(response == -4)    { 
                showToast({
                    type: 'warning',
                    message: "Error 4.Clear DB for trolley contents"
                }); 
                return; 
            } else if(response == -5)    { 
                showToast({
                    type: 'warning',
                    message: "Error 5.Copying trolley to order"
                });	
                return;	
            } else if(response == -6)    { 
                showToast({
                    type: 'warning',
                    message: "Error 6.Clearing trolley"
                });	
                return;	
            } else if(response == -103)  { 
                showToast({
                    type: 'warning',
                    message: "Error 103.During write order file"
                }); 
                return; 
            } else if(response == -104)  { 
                showToast({
                    type: 'warning',
                    message: "Error 6.Updating order with filename"
                }); 
                return;	
            } else if(response == -105)  { 
                showToast({
                    type: 'warning',
                    message: "Error 6.Close and complete order"
                }); 
                    return;	
            } else if(response == -1)    { 
                showToast({
                    type: 'warning',
                    message: "Send fail."
                }); 
                return; 
            }

            location.reload();
        }
    });
}

function handleSaveForLater()
{
    var data = {
        order_action: 1
    }
    $.ajax({
        type : "POST"
        , async : true
        , url : "/orders/save_for_later/<?= $type ?>"
        , dataType : "html"
        , timeout : 30000
        , cache : false
        , data : data
        , error : function(request, status, error) {
            alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success : function(response, status, request) {
            if(response == true) alert("Order saved.");
            else if(response == 100) alert('Nothing in cart to save.');
            else alert("Save fail.");

            location.reload();
        }
    });
}

function load_orders(type) {
    $.ajax({
        url: `/orders/${type}`,
        success: function (response, status, request) {
            $("#my-cart-sidebar .my-cart-body .cart-info").html(response);
        }
    })
}

function cart_action(mode, prod_id, prod_code, quantity=1) {
    const data = {
        type: "<?= $type ?>",
        prod_code,
        prod_id,
        mode,
        quantity: quantity,
    }

    $.ajax({
        type: "POST", 
        async: true, 
        url: "/orders/to_cart_quantity", 
        dataType: "html", 
        timeout: 30000, 
        cache: false, 
        data: data, 
        error: function (request, status, error) {
            alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }, 
        success: function (response, status, request) {
            window.location.reload();
        }
    });
}

$(document).ready(function(e) {
    $(document).on('sidebar.open', '#my-cart-sidebar', function(e) {
        load_orders('general');
    });

    $(document).on('click', ".order-table table tbody td i.add-qty", function(e) {
        const prod_id = $(e.target).data('prod-id');
        const prod_code = $(e.target).data('prod-code');

        $(e.target).removeClass("bi-plus-circle-fill cursor-pointer add-qty");
        $(e.target).addClass("rotate-spinner bi-arrow-repeat");

        cart_action(1, prod_id, prod_code);
    })

    $(document).on('click', ".order-table table tbody td i.minus-qty", function(e) {
        const prod_id = $(e.target).data('prod-id');
        const prod_code = $(e.target).data('prod-code');

        $(e.target).removeClass("bi-dash-circle-fill cursor-pointer minus-qty");
        $(e.target).addClass("rotate-spinner bi-arrow-repeat");

        cart_action(2, prod_id, prod_code);
    })

    $(document).on('click', ".order-table table tbody td i.remove-order", function(e) {
        const prod_id = $(e.target).data('prod-id');
        const prod_code = $(e.target).data('prod-code');

        $(e.target).removeClass("bi-x cursor-pointer remove-order");
        $(e.target).addClass("rotate-spinner bi-arrow-repeat");

        cart_action(3, prod_id, prod_code);
    })

    $(document).on('change', ".order-table table tbody td input.order-qty", function(e) {
        const prod_id = $(e.target).data('prod-id');
        const prod_code = $(e.target).data('prod-code');

        cart_action(4, prod_id, prod_code, $(e.target).val());
    })
})