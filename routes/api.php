<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Set Customer API endpoints
Route::put('customer', ['as' => 'customer.store', 'uses' => 'CustomersController@store']);
Route::get('customer/blank', ['as' => 'customer.blank', 'uses' => 'CustomersController@blank']);
Route::get('customer/describe', ['as' => 'customer.describe', 'uses' => 'CustomersController@describe']);

Route::resource('customer', 'CustomersController', [
  'except' => ['create', 'edit', 'update']
]);

// Set Contact API endpoints
Route::put('contact', ['as' => 'contact.store', 'uses' => 'ContactsController@store']);
Route::get('contact/blank', ['as' => 'contact.blank', 'uses' => 'ContactsController@blank']);
Route::get('contact/describe', ['as' => 'contact.describe', 'uses' => 'ContactsController@describe']);

Route::resource('contact', 'ContactsController', [
  'except' => ['create', 'edit', 'update']
]);

// Set Order API endpoints
Route::put('order', ['as' => 'order.store', 'uses' => 'OrdersController@store']);
Route::get('order/blank', ['as' => 'order.blank', 'uses' => 'OrdersController@blank']);
Route::get('order/describe', ['as' => 'order.describe', 'uses' => 'OrdersController@describe']);

Route::resource('order', 'OrdersController', [
  'except' => ['create', 'edit', 'update']
]);

// Set ProductFamily API endpoints
Route::put('product_family', ['as' => 'product_family.store', 'uses' => 'ProductFamiliesController@store']);
Route::get('product_family/blank', ['as' => 'product_family.blank', 'uses' => 'ProductFamiliesController@blank']);
Route::get('product_family/describe', ['as' => 'product_family.describe', 'uses' => 'ProductFamiliesController@describe']);

Route::resource('product_family', 'ProductFamiliesController', [
  'except' => ['create', 'edit', 'update']
]);

// Set Product API endpoints
Route::put('product', ['as' => 'product.store', 'uses' => 'ProductsController@store']);

Route::get('product/blank', ['as' => 'product.blank', 'uses' => 'ProductsController@blank']);
Route::get('product/describe', ['as' => 'product.describe', 'uses' => 'ProductsController@describe']);

Route::resource('product', 'ProductsController', [
  'except' => ['create', 'edit', 'update']
]);

// Set ProductInventoryItem API endpoints
Route::put('product_inventory_item', ['as' => 'product_inventory_item.store', 'uses' => 'ProductInventoryItemsController@store']);
Route::get('product_inventory_item/check/{lotNumber}', ['as' => 'product_inventory_item.showByLotNumber', 'uses' => 'ProductInventoryItemsController@showByLotNumber']);
Route::get('product_inventory_item/blank', ['as' => 'product_inventory_item.blank', 'uses' => 'ProductInventoryItemsController@blank']);
Route::get('product_inventory_item/describe', ['as' => 'product_inventory_item.describe', 'uses' => 'ProductInventoryItemsController@describe']);

Route::resource('product_inventory_item', 'ProductInventoryItemsController', [
  'except' => ['create', 'edit', 'update']
]);

//List product inventory items
// Route::get('product_inventory_item', 'ProductInventoryItemsController@index');
//
// //List single product inventory item
// Route::get('product_inventory_item/{id}', 'ProductInventoryItemsController@show');
//
// //Create new product inventory item
// Route::post('product_inventory_item', 'ProductInventoryItemsController@store');
//
// //Update product inventory item
// Route::put('product_inventory_item', 'ProductInventoryItemsController@store');
//
// //Delete product inventory item
// Route::delete('product_inventory_item/{id}', 'ProductInventoryItemsController@destroy');
