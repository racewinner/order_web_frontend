var current_prod_id = 0;
var current_prod_code = '';

$(document).ready(function () {
    $(document).on('click', '.collapsible-menu a', function (e) {
        const m = $(e.target).closest('.collapsible-menu');
        if (m.hasClass('active')) {
            m.removeClass('active')
        } else {
            m.addClass('active');
        }
    })

    $(document).on('submit', 'form', function (e) {
        $submit = $(e.target).find("button[type='submit']");
        $submit.addClass('disabled d-flex align-items-center justify-content-center');
        $submit.prepend("<img src='/images/gifs/loading.gif' style='width:20px; height:20px; margin-right: 10px;' />");
    })

    $(document).on('click', '.cms-content', function (e) {
      debugger
        const cms = $(e.target).closest('.cms-content');
        const id = cms.data('id');
        const link = cms.data('link');
        const prod_codes = cms.data('prodcodes');

        if (link && prod_codes) {
            const cmslink_dialog = $("#cmslink_dialog");
            cmslink_dialog.find(".view-link").attr('data-link', link);
            cmslink_dialog.find('.show-me-products').attr('data-prodcodes', prod_codes);
            cmslink_dialog.find('.show-me-products').attr('data-id', id);
            cmslink_dialog.modal("open");
        } else if (link && !prod_codes) {
            window.open(link, '_blank');
        } else if (!link && prod_codes) {
            searchProductsByProdCodes(prod_codes);
        }
    })
})

function searchProductsByProdCodes(prod_codes) {
    let url = '/products/index?';
    url += "&sort_key=0";
    url += "&category_id=0";
    url += "&offset=0";
    url += "&per_page=30";
    url += "&view_mode=" + ($("#view_mode").val() ?? 'grid');
    url += '&search1=' + encodeURIComponent(prod_codes.replace(/[\/()|'*]/g, ' '));
    window.location.href = url;
}

function toast(type, msg) {
    let t_html = `<div class='msg ${type}'>${msg}</div>`;
    M.toast({
        html: t_html,
    });
}

const formatDate = (date, format) => {
    const Y = date.getFullYear();
    const M = date.getMonth() + 1;
    const D = date.getDate();
    const h = date.getHours();
    const m = date.getMinutes();
    const s = date.getSeconds();

    switch (format) {
        case 'YYYY/MM/DD HH:mm':
            return `${Y}/${M}/${D} ${h}:${m}`;
        case 'YYYY-MM-DD HH:mm':
            return `${Y}-${M}-${D} ${h}:${m}`;
        case 'YYYY/MM/DD':
            return `${Y}/${M}/${D}`;
        case 'YYYY-MM-DD':
            return `${Y}-${M}-${D}`;
        case 'MM/DD':
            return `${M}/${D}`;
        case 'DD/MM':
            return `${D}/${M}`;
        case 'HH:mm':
            return `${h}:${m}`;
    }
}

function scrollToElement(element) {
    if (element) {
        const topPos = element.getBoundingClientRect().top + window.scrollY;
        window.scrollTo({
            top: topPos - 80,
            behavior: 'smooth'
        });
    }
}

function removeNumberSymbols(str) {
    let ret = str.replace(/[^a-zA-Z\s]/g, '');
    ret = ret.replace(/[\s\t]+/g, ' ');
    ret = ret.trim();
    return ret;
}

function cart_inc_quantity(mode, prod_id, prod_code, spresell, prod_desc, onSuccess) {
    const post_data = {
        mode: mode,
        prod_code: prod_code,
        spresell: spresell ?? 0,
        quantity: 1
    };

    $.ajax({
        type: "POST"
        , async: true
        , url: "products/to_cart"
        , dataType: "html"
        , timeout: 30000
        , cache: false
        , data: post_data
        , error: function (request, status, error) {
            alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }
        , success: function (response, status, request) {
            if (onSuccess) {
                onSuccess();
            } else {
                var prod_td = "#prod_" + prod_id;
                var prod_td2 = "#prod__" + prod_id;

                if (response < 0) {
                    return;
                }
                if (response == 0) {
                    $(prod_td).attr('class', 'price_per_pack_empty quantity');
                } else {
                    $(prod_td).attr('class', 'price_per_pack quantity');
                }

                // $(prod_td).find('>span').text(response);
                // $(prod_td2).find('>span').text(response);
                $(`.quantity[data-prod-id=${prod_id}]`).find('>span').text(response);

                if (mode == 1) {
                    M.toast({ html: '<strong>Added&nbsp;</strong> "' + prod_desc + '"' });
                } else {
                    M.toast({ html: '<strong>Removed&nbsp;</strong> "' + prod_desc + '"' });
                }
                update_cart();
            }
        }
    });
}

function update_cart() {
    $.ajax({
        'url': '/orders/cartinfo',
        'type': 'GET', //the way you want to send data to your URL
        'data': false,
        'success': function (data) {
            if (data) {
                $("#combined .cart-amount").text(parseFloat(data.total_amount - data.cart_types['spresell']?.amount).toFixed(2));
            }
        }
    });
}

function cart_edit_quantity(prod_id, prod_code) {
    if (current_prod_id != 0) {
        cart_update_quantity(current_prod_id, current_prod_code);
    }

    var input_id = "#input_" + prod_id;
    var span_id = "#span_" + prod_id;

    $(span_id).css('display', 'none');
    $(input_id).css('display', 'block');
    $(input_id).val($(span_id).text());
    $(input_id).focus();

    current_prod_id = prod_id;
    current_prod_code = prod_code;
}

function cart_change_quantity(prod_id, prod_code, e) {
    var result;
    if (window.event) {
        result = window.event.keyCode;
    } else if (e) {
        result = e.which;
    }

    if (result == 13) {
        cart_update_quantity(prod_id, prod_code);
    }
}

function cart_update_quantity(prod_id, prod_code) {
    input_id = "#input_" + prod_id;
    span_id = "#span_" + prod_id;
    current_qty = $(input_id).val();
    if (isNaN(Number(current_qty))) {
        $(input_id).val('');
        return;
    }
    $('#current_id').val('0');
    $(span_id).text(Math.round(Number(current_qty)));
    $(span_id).css('display', '');
    $(input_id).css('display', 'none');

    if (Number(current_qty) != 0) {
        $(span_id).parent().attr('class', 'price_per_pack quantity');
    } else {
        $(span_id).parent().attr('class', 'price_per_pack_empty quantity');
    }

    post_data = "prod_code=" + prod_code + "&mode=3" + "&quantity=" + Math.round(Number(current_qty));
    $.ajax({
        type: "POST"
        , async: true
        , url: "products/to_cart"
        , dataType: "html"
        , timeout: 30000
        , cache: false
        , data: post_data
        , error: function (request, status, error) {
            alert("code : " + request.status + "\r\nmessage : " + request.reponseText);
        }, success: function (response, status, request) {
            update_cart();
        }
    });
}

function floatV(v, decimal_pos=2) {
    const fv = parseFloat(v);
    return fv.toFixed(decimal_pos);
}
