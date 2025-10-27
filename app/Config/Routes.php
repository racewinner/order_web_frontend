<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->addRedirect('/', '/home');
$routes->get('/test/send_email', 'TestController::sendEmail');

$routes->get('/home/preview', 'Home::preview');
$routes->get('/home/logout', 'Home::logout');
$routes->get('/login', 'Login::index');
$routes->post('/login', 'Login::login_check');
$routes->get('/login/guest_login', 'Login::guest_login');

$routes->get('/myaccount/sel_allocated_branch', 'MyAccount::getAllocatedSelectBranch');
$routes->get('/myaccount/sel_branch', 'MyAccount::getSelectBranch');
$routes->post('/myaccount/sel_branch', 'MyAccount::postSelectBranch');
$routes->post('/myaccount/my_branches', 'MyAccount::postMyBranches');

$routes->group('', ['filter' => 'branchFilter'], function($routes) {
    $routes->get('/home', 'Home::index');
    $routes->post('/home/to_cart', 'Home::to_cart');
    $routes->get('/products', 'Products::index');
    $routes->get('/products/brand/(:segment)', 'Products::brand/$1');
    $routes->get('/products/(:num)/show', 'Products::show/$1');
    $routes->get('/products/(:num)/show_by_code', 'Products::show_by_code/$1');
    $routes->get('/products/index', 'Products::index');
    $routes->get('/seasonal_presell/index', 'Products::index');
    $routes->post('/seasonal_presell/to_cart', 'Products::to_cart');
    $routes->post('/products/to_cart', 'Products::to_cart');
    $routes->post('/products/suggest2', 'Products::suggest2');
    $routes->get('/products/reload_product', 'Products::reload_product');

    $routes->get('/pastorders', 'Pastorders::index');
    $routes->get('/pastorders/index/(:segment)/(:any)', 'Pastorders::index');
    $routes->post('/pastorders/get_order', 'Pastorders::get_order');
    $routes->post('/pastorders/to_cart', 'Pastorders::to_cart');
    $routes->get('/pastorders/continue_order/(:segment)/(:segment)', 'Pastorders::continue_order/$1/$2');
    $routes->get('/pastorders/reuse_order/(:segment)', 'Pastorders::reuse_order/$1');

    $routes->get('/promos/index/(:segment)', 'Promos::index');
    $routes->post('/promos/fetch_subcategory', 'Promos::fetch_subcategory');
    $routes->post('/promos/sort_product/(:any)', 'Promos::sort_product');

    $routes->get('/favorites', 'Favorites::index');

    $routes->post('/presells_import/load_data', 'Presells_import::load_data');
    $routes->post('/presells_import/load_ref', 'Presells_import::load_ref');

    $routes->get('/orders/cartinfo', 'Orders::cartinfo');
    $routes->get('/orders/orders/(:segment)', 'Orders::index/$1');
    $routes->post('/orders/to_cart_quantity', 'Orders::to_cart_quantity');
    $routes->post('/orders/send_order/(:segment)', 'Orders::send_order/$1');
    $routes->post('/orders/save_for_later/(:segment)', 'Orders::save_for_later/$1');
    $routes->post('/orders/resend_orders/(:segment)', 'Orders::resend_orders/$1');
    $routes->get('/orders/checkout', 'Orders::checkout');
    $routes->get('/orders/payment', 'Orders::payment');
    $routes->get('/orders/mini_cart', 'Orders::mini_cart');
    $routes->get('/orders', 'Orders::index');

    $routes->post('/home/check_both_promos', 'Home::check_both_promos');
    $routes->post('/home/get_total_items_cart', 'Home::get_total_items_cart');
    $routes->post('/home/check_daytoday', 'Home::check_daytoday');
    $routes->post('/home/check_usave', 'Home::check_usave');
    $routes->post('/products/favorite', 'Products::favorite');
    $routes->post('/home/refresh_products', 'Home::refresh_products');
    $routes->post('/home/mobile', 'Home::mobile');
    $routes->post('/favorites/bulk_favorites', 'Favorites::bulk_favorites');

    $routes->get('/myaccount/credit_account/(:segment)', 'MyAccount::credit_account/$1');
    $routes->post('/myaccount/sendpayment', 'MyAccount::send_payment');
    $routes->get('/myaccount/invoice_history', 'MyAccount::invoice_history');
    $routes->get('/myaccount/invoice_detail', 'MyAccount::invoice_detail');
    $routes->get('/myaccount/order_history', 'MyAccount::order_history');
    $routes->get('/myaccount/order_detail', 'MyAccount::order_detail');
    $routes->get('/myaccount/ledger', 'MyAccount::ledger');
    $routes->get('/myaccount/loyalty', 'MyAccount::loyalty');
});


$routes->get('/clogin', 'CLogin::index');
$routes->post('/clogin', 'CLogin::login_check');
$routes->get('/cpanel', 'Cpanel::index');
$routes->post('/cpanel/refresh_all_products', 'Cpanel::refresh_all_products');
$routes->post('/cpanel/update_featured', 'Cpanel::update_featured');
$routes->post('/cpanel/do_uploader', 'Cpanel::do_uploader');
$routes->post('/cpanel/do_uploader/(:segment)', 'Cpanel::do_uploader/$1');
$routes->post('/presells_import/process', 'Presells_import::process');
$routes->post('/cpanel/push_scount', 'Cpanel::push_scount');

$routes->get('/contactus', 'Contactus::index');
$routes->post('/contactus/send_message', 'Contactus::send_message');

$routes->get('/employees', 'Employees::index');
$routes->get('/employees/index', 'Employees::index');
$routes->post('/employees/suggest', 'Employees::suggest');
$routes->post('/employees/save', 'Employees::save');
$routes->get('/employees/generate_key/(:any)', 'Employees::generate_key/$1');
$routes->get('/employees/generate_key', 'Employees::generate_key');
$routes->get('/employees/check_exist', 'Employees::checkExist');
$routes->get('/employees/edit/(:any)', 'Employees::edit/$1');
$routes->get('/employees/create', 'Employees::edit');

$routes->get('/unknown_products', 'UnknownProducts::index');
$routes->delete('/unknown_products/(:segment)', 'UnknownProducts::delete/$1');

$routes->get('/test/1', 'TestController::test1');

$routes->get('opayo/checkout', 'Opayo::checkout');
$routes->post('opayo/initiate', 'Opayo::initiatePayment');
$routes->post('opayo/notification', 'Opayo::notification');
$routes->get('opayo/success', 'Opayo::success');
$routes->get('opayo/failure', 'Opayo::failure');
