<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'shop';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['product/(:num)'] = 'shop/product/$1';
$route['cart/add'] = 'cart/add';
$route['cart/update'] = 'cart/update';
$route['cart/remove/(:num)'] = 'cart/remove/$1';
$route['checkout/success/(:any)'] = 'checkout/success/$1';