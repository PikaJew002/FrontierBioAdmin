<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/', 'PagesContoller@index');
Route::get('/home', 'PagesContoller@home');
Route::get('/customers', 'PagesContoller@customers');
Route::get('/orders', 'PagesContoller@orders');
Route::get('/products', 'PagesContoller@products');
Route::get('/items', 'PagesContoller@items');
Route::get('/product_file/{type}/{id}', 'FileDownloadController@product');
Route::get('/item_file/{type}/{id}', 'FileDownloadController@item');

// Route::resource('customers', 'CustomersController');
// Route::get('/contacts/create/{customer_id}/', ['as' => 'contacts.create', 'uses' => 'ContactsController@create']);
// Route::resource('contacts', 'ContactsController', ['except' => ['create']]);
// Route::resource('orders', 'OrdersController');
// Route::resource('product_families', 'ProductFamiliesController');
// Route::get('/products/create/{family_id}', ['as' => 'products.create', 'uses' => 'ProductsController@create']);
// Route::resource('products', 'ProductsController', ['except' => ['create']]);
// Route::get('/product_inventory_items', ['as' => 'product_inventory_items.index', 'uses' => 'ProductInventoryItemsController@indexPage']);
// //Route::resource('product_inventory_items', 'ProductInventoryItemsController', ['except' => ['index']]);
