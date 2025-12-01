var current_prod_id = 0;
var current_prod_code = '';

function add_loadingSpinner_to_button(target) {
    $(target).addClass('disabled has-loading-spinner');
    $(target).prepend("<i class='bi bi-arrow-repeat rotate-spinner' style='position:absolute; left:10px; top:calc(50% - 10px); width:20px; line-height:20px; font-size:20px;'></i>");    
}
function remove_loadingSpinner_from_button(target) {
    $(target).removeClass('disabled has-loading-spinner');
    const el_working_btn = $(target).find(".disabled.has-loading-spinner");
    if (el_working_btn) {
        el_working_btn.removeClass('disabled');
        el_working_btn.removeClass('has-loading-spinner');
    }
    $(target).find("i.rotate-spinner").remove();
}

function alert_message(msg='Hello!', title='Alert', cls='', hiddenCallback=f=>f) {

    if (typeof msg === 'string' && msg.includes('\n')) {
        const lines = msg.split('\n');
        const paragraphs = lines.map(line => `<p>${line}</p>`);
        msg = paragraphs.join('');
    }
    
    const container = document.getElementById('alert-msg-container');
    container.innerHTML = `<div class="modal fade ${cls}" id="alert_message_dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered max-w-700">
                                    <div class="modal-auto-width-content">
                                        <div class="modal-header">
                                            <div class="w-100 text-center">${title}</div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body px-100 py-5 d-flex flex-column align-items-center">
                                            <div class="confirm-content-panel mt-0">
                                                <div class="">
                                                    ${msg}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-danger full-fill border mt-4" style="width: 150px" data-bs-dismiss="modal" >
                                                    OK
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

    const alert_message_modal = $("#alert_message_dialog");
    const modal = new bootstrap.Modal(alert_message_modal[0]);
    modal.show();

    alert_message_modal.off('shown.bs.modal');
    alert_message_modal.on ('shown.bs.modal', function (e) {
        // Apply z-index: 9998 to the last element with modal-backdrop class
        // $('.modal-backdrop').last().css('z-index', '9998');
        // $('#alert_message_dialog').last().css('background-color', '#00000000');
        // Set focus to OK button
        $(this).find('.modal-footer button[data-bs-dismiss="modal"]').focus();
    })

    $(`.${cls}`).off('hidden.bs.modal');
    $(`.${cls}`).on ('hidden.bs.modal', function (e) {
        // do something...
        hiddenCallback();
    })
}

function question_message(msg='Hello!', title='Alert', cls='', okCallback=f=>f) {
    const container = document.getElementById('question-msg-container');
    container.innerHTML = `<div class="modal fade ${cls}" id="question_message_dialog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered max-w-700">
                                    <div class="modal-auto-width-content">
                                        <div class="modal-header">
                                            <div class="w-100 text-center">${title}</div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body px-100 py-5 d-flex flex-column align-items-center">
                                            <div class="confirm-content-panel mt-0">
                                                <div class="">
                                                    ${msg}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-danger full-fill border mt-4 ok-btn" style="width: 150px" data-bs-dismiss="modal" >
                                                    OK
                                            </button>
                                            <button class="btn btn-general full-fill border mt-4" style="width: 150px" data-bs-dismiss="modal" >
                                                    Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

    const question_message_modal = $("#question_message_dialog");
    const modal = new bootstrap.Modal(question_message_modal[0]);
    debugger
    modal.show();

    $(`.${cls} .ok-btn`).on ('click', function (e) {
        // do something...
        okCallback();
    })
}

function searchProductsByProdCodes(prod_codes) {
    debugger
    let url  = '/products/index?';
        url += "&sort_key=0";
        url += "&category_id=0";
        url += "&offset=0";
        url += "&per_page=50";
        url += "&view_mode=" + ($("#view_mode").val() ?? 'grid');
        url += '&search3=' + encodeURIComponent(prod_codes.replace(/[\/()|'*]/g, ' '));
    window.location.href = url;
}

function searchProductsByCms(cms_itm_id, cms_itm_tp, cms_itm_nm) {
    let url  = '/products/index?';
        url += "&sort_key=0";
        url += "&offset=0";
        url += "&per_page=50";
        url += "&view_mode=" + ($("#view_mode").val() ?? 'grid');
        url += '&search1=';
    if (cms_itm_nm) {
      if (cms_itm_tp == 'brand') {
        url += "&category_id=0";
        url += `&filter_brands=["${cms_itm_nm}"]`;
      } else if (cms_itm_tp == 'category_carousel') {
        url += `&category_id=${cms_itm_nm}`;
      }
    }
    window.location.href = url;
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
        let header_height = 0;
        const w = window.visualViewport.width;
        if(w >= 1740) {
            header_height = 162;
        } else if(w < 1740 && w >= 1200) {
            header_height = 152;
        } else if(w < 1200 && w >= 992) {
            header_height = 102;
        } else if(w < 992) {
            header_height = 63
        }
        
        const topPos = element.getBoundingClientRect().top + window.scrollY;
        window.scrollTo({
            top: topPos - header_height - 10,
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

function cart_action(mode, prod_id, prod_code, prod_desc, quantity, type, spresell, onSuccess) {
    const post_data = {
        mode: mode,
        prod_code: prod_code,
        spresell: spresell ?? 0,
        quantity: quantity,
        type: type
    };

    $.ajax({
        type: "POST"
        , async: true
        , url: "/products/to_cart"
        , dataType: "html"
        , timeout: 30000
        , cache: false
        , data: post_data
        , error: function (xhr, status, error) {
            if (xhr.status == 401) {
                window.location.href = '/login'; return;
            } else {
				console.log("An error occured: " + xhr.status + " " + xhr.statusText);
	    	}}
        , success: function (response, status, request) {
            if (response < 0) {
                return;
            }
            // update lines & items in cart
            let lines = 0;
            let items = 0;
            if(type) {
                $tab_pane = $(`.my-cart .tab-pane.${type}`);
                
                lines = parseInt($tab_pane.data('lines') ?? 0);
                items = parseInt($tab_pane.data('items') ?? 0);
                const prod_old_qty = parseInt($(`.one-product[data-prod-id=${prod_id}] input.cart-quantity`).val() ?? 0);

                if(mode == 4) {
                    lines--;
                    items -= prod_old_qty;
                } else {
                    items += (parseInt(response) - prod_old_qty);
                }

                $tab_pane.data('lines', lines);
                $tab_pane.data('items', items);

                $tab_pane.find('.cart-lines-items').html(`${lines} Lines ${items} Items`);
            }

            $(`.one-product[data-prod-id=${prod_id}] input.cart-quantity`).val(response);

            showToast({
                title: 'Trolley Updated',
                type: 'success',
                message: (response == 0) ? `You have removed '${prod_desc}'` : `You now have ${response} of '${prod_desc}'`
            })

            if(onSuccess) onSuccess(response);

            if (mode == 4 && lines >= 10) {
                $('#my-cart-sidebar').trigger("sidebar.open");
            } else {
                update_cart();
            }
        }
    });
}

function update_cart() {
    $.ajax({
        'url' : '/orders/cartinfo',
        'type': 'GET', //the way you want to send data to your URL
        'data': false,
        'error': function (xhr, status, error) {
            if (xhr.status == 401) {
                window.location.href = '/login'; return;
            } else {
				console.log("An error occured: " + xhr.status + " " + xhr.statusText);
	    	}},
        'success': function (data) {
            if (data) {
                let total_amount    = data.total_amount;
                let total_lines     = data.total_lines;
                let total_quantity  = data.total_quantity;
                let total_vats      = data.total_vats;
                /**
                 * set real cart amount to 
                 * [
                 *      1. cartIcon in topMenu, 
                 *      2. cartText in footerBar of trolleyPopupDlg
                 * ]
                 */
                // 1. cartIcon in topMenu
                let cart_amount = total_amount;/* + total_vats + delivery_charge;*/
                if(Number.isNaN(cart_amount) || cart_amount.toFixed() == 0) {
                    $(".header-logo .cart-amount").text('Empty');
                } else {
                    $(".header-logo .cart-amount").text('£' + parseFloat(cart_amount).toFixed(2));
                }
                // 2. cartText in footerBar of trolleyPopupDlg
                $('.my-cart-footer #cart_subtotal').text('£' + parseFloat(cart_amount).toFixed(2));

                /**
                 * set total lines & quantity to footerBar of trolleyPopupDlg like this:
                 * X Lines Y items
                 */
                $('.my-cart-footer .cart-total.cart-total-desc span').text(`${total_lines} Lines ${total_quantity} items`);

                /**
                 * set item&trolley total amount in checkout page's Billing details
                 */
                $('#cur_trolley_total_amount_for_recent').text('£' + parseFloat(total_amount).toFixed(2));
                $('#cur_trolley_total_for_recent').text('£' + (parseFloat(total_amount) + parseFloat(total_vats)).toFixed(2));
                $('#cur_trolley_total_vats_for_recent').text('£' + parseFloat(total_vats).toFixed(2));

                if ($('.one-cart-type.active').length > 0) {
                    const cart_active_typename = $('.one-cart-type.active').attr('id').slice(4);
                    const cur_trolley_cart_amount = data.cart_types && data.cart_types[cart_active_typename] && data.cart_types[cart_active_typename].amount ? 
                                                    data.cart_types[cart_active_typename].amount : 0;
                    const cur_trolley_cart_vat    = data.cart_types && data.cart_types[cart_active_typename] && data.cart_types[cart_active_typename].vat ? 
                                                    data.cart_types[cart_active_typename].vat : 0;

                    $('#cur_trolley_total_amount').text('£' + parseFloat(cur_trolley_cart_amount).toFixed(2));
                    $('#cur_trolley_total').text('£' + (parseFloat(cur_trolley_cart_amount) + parseFloat(cur_trolley_cart_vat)).toFixed(2));
                    $('#cur_trolley_total_vats').text('£' + parseFloat(cur_trolley_cart_vat).toFixed(2));
                }
                
                /**
                 * set checkout button in footer of trolleyPopupDlg
                 */
                if(total_amount == 0) {
                    $('.my-cart-footer .checkout-button').addClass('d-none');
                } else {
                    $('.my-cart-footer .checkout-button').removeClass('d-none');
                }
            }
        }
    });
}

function favorite(pid, prod_id, prod_code, state, onSuccess) {
    var post_data = "pid=" + pid + "&prod_code=" + prod_code + "&state=" + state;

    $.ajax({
        type: 'POST'
        , async: true
        , url: '/products/favorite'
        , dataType: "html"
        , timeout: 30000
        , cache: false
        , data: post_data
        , error: function (xhr, status, error) {
            if (xhr.status == 401) {
                window.location.href = '/login'; return;
            } else {
				console.log("An error occured: " + xhr.status + " " + xhr.statusText);
	    	}}
        , success: function (data) {
            if(onSuccess) onSuccess();
        }
    });
}

function floatV(v, decimal_pos=2) {
    const fv = parseFloat(v);
    return fv.toFixed(decimal_pos);
}

function showToast({ type = 'info', title = '', message = '', delay = 3000, containerId = 'toastContainer' } = {}) {
    const container = document.getElementById(containerId);
    const tmpl = document.getElementById('toastTemplate');
    const toastEl = tmpl.content.cloneNode(true).querySelector('.toast');
  
    // Style variants
    const header = toastEl.querySelector('.toast-header');
    const toastBody = toastEl.querySelector('.toast-body');
    const toastTitle = toastEl.querySelector('.toast-title');
    const toastIcon = toastEl.querySelector('#toastIcon');
  
    // Basic mapping
    const variants = {
      success: { headerClass: 'bg-success text-white', icon: '✅' },
      error:   { headerClass: 'bg-danger text-white',  icon: '❌' },
      warning: { headerClass: 'bg-warning text-dark',  icon: '⚠️' },
      info:    { headerClass: 'bg-info text-dark',     icon: 'ℹ️' }
    };
  
    const v = variants[type] || variants.info;
  
    // Apply styles and content
    header.className = 'toast-header ' + v.headerClass;
    toastTitle.textContent = title ? title : 'Notification';
    toastIcon.textContent = v.icon;
    toastBody.textContent = message;
  
    // Optional: allow per-toast delays
    const t = new bootstrap.Toast(toastEl, {
      autohide: true,
      delay
    });
  
    container.appendChild(toastEl);
    t.show();
  
    toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
}

function load_products(filter) {
    debugger
    const data = {
        sort_key: filter?.sort_key ?? $('#sort_key').val() ?? 3,
        category_id: filter?.category_id ?? $("#category_id").val() ?? 0,
        offset: filter?.offset ?? 0,
        per_page: filter?.per_page ?? $('#per_page').val() ?? 50,
        view_mode: filter?.view_mode ?? $("#view_mode").val() ?? 'grid',
        im_new: filter?.im_new ?? ($("#chk_im_new").is(':checked') ? 1 : 0),
        plan_profit: filter?.plan_profit ?? ($("#chk_plainprofit").is(':checked') ? 1 : 0),
        own_label: filter?.own_label ?? ($("#chk_own_label").is(':checked') ? 1 : 0),
        favorite: filter?.favorite ?? ($("#chk_favorite").is(':checked') ? 1 : 0),
        rrp: filter?.rrp ?? ($("#chk_rrp").is(':checked') ? 1 : 0),
        pmp: filter?.pmp ?? ($("#chk_pmp").is(':checked') ? 1 : 0),
        non_pmp: filter?.non_pmp ?? ($("#chk_non_pmp").is(':checked') ? 1 : 0),
        spresell: filter?.spresell ?? 0,
        search0: filter?.search0 ?? $('#search0').val().replace(/[\/()|'*]/g, ' '),
    }
    if (filter?.brands?.length > 0) {
        data.filter_brands = JSON.stringify(filter.brands);
    }
    /*
    // switch between search0 and category ---------//
    if (!filter && data.search0) {                  //
        data.category_id = '';                      //
    }                                               //
    if (parseInt(data.category_id)) {               //
        data.search0 = '';                          //   
    }                                               //
    // ---------------------------------------------//
    */
    const queryParams = new URLSearchParams(data);

    let url = `/products/index?${queryParams}`;
    window.location.href = url;
}

// To initialize swipers
function initializeSwipes() {
    const swipers = $(".swiper");
    for(let i=0; i < swipers.length; i++) {
        const delay = $(swipers[i]).data('autoplay-delay');
        const slidesPerView = $(swipers[i]).data('slidesperview') ?? 1;
        const breakpoints = $(swipers[i]).data('breakpoints');

        new Swiper(swipers[i], { 
                slidesPerView: slidesPerView, 
                spaceBetween: 16, 
                grabCursor: true,
                speed: 1000,
                loop: true, 
                autoplay: {
                    enabled: true,
                    delay: delay,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true
                },
                navigation: { 
                    nextEl: '.swiper-button-next', 
                    prevEl: '.swiper-button-prev', 
                }, 
                breakpoints: breakpoints ?? { 
                    576: { slidesPerView: 2, spaceBetween: 16 }, 
                    768: { slidesPerView: 3, spaceBetween: 24 }, 
                    992: { slidesPerView: 4, spaceBetween: 32 }, 
                } 
            }
        );
    }
}

function initializeToggleShowHide() {
    const $elments = $(".toggle-show-hide");

    for(let i=0; i<$elments.length; i++) {
        const el = $elments[i];
        let $i;
        if($(el).hasClass('show')) {
            $i = $(`<i class="bi bi-chevron-up" >`);
        } else {
            $i = $(`<i class="bi bi-chevron-down" >`);
        }
        $(el).append($i);
    }
}

function sendIsMobile() {
    const data = {
        is_mobile: (window.visualViewport.width < 992) ? 1 : 0
    }
    $.ajax({
        'url': '/home/mobile',
        'type': 'POST', //the way you want to send data to your URL
        'data': data,
        'error': function (xhr, status, error) {
            if (xhr.status == 401) {
                window.location.href = '/login'; return;
            } else {
				console.log("An error occured: " + xhr.status + " " + xhr.statusText);
	    	}},
        'success': function (data) {
        }
    });
}

$(document).ready(function () {
    $(document).on({
        ajaxStart: function() {
            console.log('ajax start----')
            $("#ajax-call-indicator").removeClass("d-none");
        },
        ajaxStop: function() {
            console.log('ajax stop-------');
        },
        ajaxComplete: function(event, jqXHR, status) {
            $("#ajax-call-indicator").addClass("d-none");
        },
        ajaxError: function (xhr, status, error) {
            if (xhr.status == 401) {
                window.location.href = '/login'; return;
            } else {
				console.log("An error occured: " + xhr.status + " " + xhr.statusText);
	    	}
        },
        ajaxSuccess: function(event, jqXHR, settings) {
        },
        ajaxProgress: function(e, xhr, settings) {
        },
    });

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
        add_loadingSpinner_to_button($submit);
    })

    $(document).on('click', '.checkout-button', function () {
        const $btn = $(this);
        if ($btn.hasClass('has-loading-spinner')) {
            return;
        }
        add_loadingSpinner_to_button($btn);
    })

    $(document).on('click', '.cms-content', function (e) {
        const cms = $(e.target).closest('.cms-content');
        const cms_itm_id = cms.data('cms-itm-id');
        const cms_itm_tp = cms.data('cms-itm-tp');
        const cms_itm_nm = cms.data('cms-itm-nm');
        const link = cms.data('link');
        const prod_codes = cms.data('prodcodes');

        if (link && (cms_itm_nm || prod_codes)) {
            const cmslink_dialog = $("#cmslink_dialog");
            cmslink_dialog.find(".view-link").attr('data-link', link);
            cmslink_dialog.find('.show-me-products').attr('data-prodcodes', prod_codes);
            cmslink_dialog.find('.show-me-products').attr('data-id', cms_itm_id);
            cmslink_dialog.find('.show-me-products').attr('data-cms-itm-tp', cms_itm_tp);
            cmslink_dialog.find('.show-me-products').attr('data-cms-itm-nm', cms_itm_nm);

            const modal = new bootstrap.Modal(cmslink_dialog[0]);
            modal.show();
        } else if (link && !(cms_itm_nm || prod_codes)) {
            window.open(link, '_blank');
        } else if (!link && (cms_itm_nm || prod_codes)) {
            if (cms_itm_nm) {
                searchProductsByCms(cms_itm_id, cms_itm_tp, cms_itm_nm);
            } else {
                searchProductsByProdCodes(prod_codes);
            }
        }
    })

    $(document).on('click', '#cmslink_dialog .view-link', function(e) {
        const link = $(e.target).data('link');
        window.open(link, '_blank');
    })

    $(document).on('click', '#cmslink_dialog .show-me-products', function(e) {
      debugger
        const prod_codes = $(e.target).data('prodcodes');
        const cms_itm_id = $(e.target).data('id');
        const cms_itm_tp = $(e.target).data('cms-itm-tp');
        const cms_itm_nm = $(e.target).data('cms-itm-nm');
        // searchProductsByProdCodes(prod_codes);
        searchProductsByCms(cms_itm_id, cms_itm_tp, cms_itm_nm);
      })

    $(document).on('click', '.one-product i.minus-cart', function(e) {
        const productEl = $(e.target).closest('.one-product');
        const prod_id = $(productEl).data('prod-id');
        const prod_code = $(productEl).data('prod-code');
        const prod_desc = $(productEl).data('prod-desc');
        const order_type = $(productEl).data('order-type');
        const current_qty = $(`.one-product[data-prod-id=${prod_id}] input.cart-quantity`).val();

        if(current_qty <= 0) return;

        $(e.target).removeClass('bi-dash');
        $(e.target).addClass('bi-arrow-repeat rotate-spinner disabled');

        cart_action(2, prod_id, prod_code, prod_desc, 1, order_type, 0, function() {
            $(e.target).addClass('bi-dash');
            $(e.target).removeClass('bi-arrow-repeat rotate-spinner disabled');
        });
    })

    $(document).on('click', '.one-product i.add-cart', function(e) {
        const productEl = $(e.target).closest('.one-product');
        const prod_id = $(productEl).data('prod-id');
        const prod_code = $(productEl).data('prod-code');
        const prod_desc = $(productEl).data('prod-desc');
        const order_type = $(productEl).data('order-type');

        $(e.target).removeClass('bi-plus-lg');
        $(e.target).addClass('bi-arrow-repeat rotate-spinner disabled');

        cart_action(1, prod_id, prod_code, prod_desc, 1, order_type, 0, function() {
            $(e.target).removeClass('bi-arrow-repeat rotate-spinner disabled');
            $(e.target).addClass('bi-plus-lg');
        });
    })

    $(document).on('click', '.one-cart-item button.remove-item i.bi-trash', function(e) {
      debugger
        const productEl = $(e.target).closest('.one-cart-item');
        const prod_id = $(productEl).data('prod-id');
        const prod_code = $(productEl).data('prod-code');
        const prod_desc = $(productEl).data('prod-desc');
        const order_type = $(productEl).data('order-type');

        $(e.target).removeClass('bi-trash');
        $(e.target).addClass('bi-arrow-repeat rotate-spinner disabled');

        cart_action(4, prod_id, prod_code, prod_desc, 0, order_type, 0, function() {
            $(productEl).remove();
            // if there is only 10 items showing in the sidebar, (20 = 10 * 2 items in total)
            // trigger the sidebar.open event to load the new sidebar content
            // if ($('.sidebar-content .one-cart-item.one-product').length == 20) { 
            //     $('#my-cart-sidebar').trigger("sidebar.open");
            // } 
            // remove the product item from the sidebar
            // else {
            //     $(productEl).remove();
            // }
        });
    });

    $(document).on('change', '.one-product input.cart-quantity', function(e) {
      debugger
        const productEl = $(e.target).closest('.one-product');
        const prod_id = $(productEl).data('prod-id');
        const prod_code = $(productEl).data('prod-code');
        const prod_desc = $(productEl).data('prod-desc');
        const order_type = $(productEl).data('order-type');

        $(e.target).addClass('disabled');

        cart_action(3, prod_id, prod_code, prod_desc, e.target.value, order_type, 0, function() {
            $(e.target).removeClass('disabled');
        })
    })

    $(document).on('click', '.one-product i.favorite', function(e) {
        const productEl = $(e.target).closest('.one-product');
        const prod_id = $(productEl).data('prod-id');
        const prod_code = $(productEl).data('prod-code');
        const person_id = $('input#logon_person_id').val();
        const state = $(e.target).hasClass('active') ? 'active' : '';

        $(e.target).removeClass('bi-heart');
        $(e.target).addClass('bi-arrow-repeat rotate-spinner disabled');

        favorite(person_id, prod_id, prod_code, state, function() {
            $(e.target).removeClass('bi-arrow-repeat rotate-spinner disabled');
            $(e.target).addClass('bi-heart')

            if(state == 'active') {
                $(e.target).removeClass('active');
            } else {
                $(e.target).addClass('active');
            }
        })
    })

    $(document).on('click', '.category-link', function(e) {
        const category_id = $(e.target).closest('.category-link').data('category-id');
        load_products({sort_key: 9, // Best Selling (desc)
                       search0: '',
                       category_id
        })
    });

    // Function to extract search0 parameter from URL string
    function getSearch0FromUrl(urlString) {
        try {
            // First, get the <a> tag string
            const aTagMatch = urlString.match(/<a[^>]*>[\s\S]*?<\/a>/i);
            if (!aTagMatch) {
                console.error('No <a> tag found in string');
                return null;
            }
            
            const aTagString = aTagMatch[0];
            
            // Second, get substring from the a tag string, after search0= to "
            const search0Match = aTagString.match(/search0=([^"]+)/i);
            if (!search0Match) {
                console.error('No search0 parameter found in <a> tag');
                return null;
            }
            
            let substring = search0Match[1];
            
            // Third, remove xxx=yyy patterns from the substring
            // Remove any &xxx=yyy or &xxx=yyy& patterns
            substring = substring.replace(/&[^&=]+=[^&]*/g, '');
            // Also remove any trailing & if present
            substring = substring.replace(/&$/, '');
            
            // Replace '%25' with '%' before returning
            substring = substring.replace(/%25/g, '%');
            
            // Return remaining string
            return substring.trim();
        } catch (e) {
            console.error('Error extracting search0:', e);
            return null;
        }
    }

    if ($("#search0").length > 0) {
        $("#search0").autocomplete({minLength:2 ,
            select: function (event, ui) { 
                console.log(this.val());
                this.val(); 
            },
            // focus: function(event, ui) {
            //     event.preventDefault();
            //     const search0 = getSearch0FromUrl(ui.item.value);
            //     $('input#search0').val(search0);
            // },
            source: function( request, response ) {
                const category_id = $("input[name='category_id']").val();
    
                $.ajax({
                    type : "POST" ,
                    url: `/products/suggest2`,
                    dataType: "json" ,
                    data: {term:request.term, category_id} ,
                    error : function (xhr, status, error) {
                        if (xhr.status == 401) {
                            window.location.href = '/login'; return;
                        } else {
                            console.log("An error occured: " + xhr.status + " " + xhr.statusText);
                        }},
                    success: function(data) {
                        response(data)
                    },
                })	
            }
        }).data("ui-autocomplete")._renderItem = function(ul, item) {
            const $li = $("<li>").html(item.label);
            return $li.appendTo(ul);
        };
    }

    $(document).on('keydown', '#search0', function(e) {
        if(e.key == 'Enter') {
            $(".search-product-header button.btn-search").trigger('click');
        }
    })

    $(document).on('click', '.search-product-header .btn-search', function(e) {
        debugger;
        const search0 = $('#search0').val();
        load_products({sort_key: 9, // Best Selling (desc)
                       search0,
                       category_id: '',
        }); 
    });

    $(document).on('click', '.one-product img.prod-image', function(e) {
        const $productEl = $(e.target).closest(".one-product");
        const prod_id = $productEl.data('prod-id');
        const url = `/products/${prod_id}/show`;
        window.location.href = url;
    })

    $(document).on('click', '.toggle-sidebar', function(e) {
        $(".sidebar").addClass('collapsed');

        const $toggle = $(e.target).closest('.toggle-sidebar');
        const $target = $($toggle.data('toggle-target'));
        $target.removeClass('collapsed');
    })

    $(document).on('click', '.sidebar', function(e) {
        if($(e.target).closest(".sidebar-content").length == 0)  {
            $(".sidebar").addClass('collapsed');
        }
    })

    // event handler for mouse-click for toggle show/hide button
    $(document).on('click', '.toggle-show-hide', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $el = $(e.target).closest('.toggle-show-hide');
        const $i = $el.find('i');
        const $target = $($el.data('show-hide-target'));
        if($el.hasClass('show')) {
            $el.removeClass('show');
            $target.addClass('d-none');
            $i.addClass('bi-chevron-down');
            $i.removeClass('bi-chevron-up');
        } else {
            $el.addClass('show');
            $target.removeClass('d-none');
            $i.addClass('bi-chevron-up');
            $i.removeClass('bi-chevron-down');
        }
    })

    // event handler for mouse-click on close button
    $(document).on('click', '.close', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $el = $(e.target).closest('.close');
        const dismiss = $el.data('dismiss');
        if(dismiss == 'sidebar') {
            const $target = $(e.target).closest(".sidebar");
            $target.addClass("collapsed");
        }
    })

    $(document).on('sidebar.open', '#my-cart-sidebar', function(e) {
        $my_cart_sidebar_content = $('#my-cart-sidebar .sidebar-content');
        $my_cart_sidebar_content.addClass('loading');

        $.ajax({
            url: `/orders/mini_cart`,
            error: function (xhr, status, error) {
                if (xhr.status == 401) {
                    window.location.href = '/login'; return;
                } else {
                    console.error(`An error occured in $(document).on('sidebar.open', '#my-cart-sidebar', function(e) {...}`);
                    console.error(`Error information is in detail below:`);
                    console.error(xhr);
                    window.location.href = '/login'; return;
                }},
            success: function (response, status, request) {
                $my_cart_sidebar_content.removeClass('loading');
                $my_cart_sidebar_content.find(".my-cart-content").html(response);
            }
        })
    })

    // event handler for mouse-click on my-cart menu in top menu
    $(document).on('click', '.one-top-menu.my-cart', function(e) {
        $('#my-cart-sidebar').trigger("sidebar.open");
    })

    $(document).on('click', '.back', function(e) {
        e.preventDefault();
        e.stopPropagation();

        window.history.back();
    })

    initializeSwipes();

    initializeToggleShowHide();

    if($('input#logon_person_id').val()) update_cart();

    sendIsMobile();  
})