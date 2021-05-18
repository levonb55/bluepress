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

//Public routes
Route::get('/', 'ProductController@index')->name('home');
Route::get('/cart', 'CartController@index')->name('cart');
Route::get('/cart/add/{product}', 'CartController@add')->name('cart.add');
Route::post('/cart/change/{product}', 'CartController@change')->name('cart.change');
Route::get('/cart/remove/{product}', 'CartController@remove')->name('cart.remove');

//Private routes
Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['checkCart']], function () {
        Route::get('checkout', 'CheckoutController@getCheckout')->name('checkout');
        Route::post('checkout', 'CheckoutController@postCheckout')->name('checkout');
    });
    Route::get('user', 'UserController@show')->name('users.show');
});

//Authentication routes
Auth::routes();
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
