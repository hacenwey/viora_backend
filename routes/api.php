<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::middleware([
    'api',
])->prefix('v1')->name('api.')->namespace('Api\V1\Store')->group(function () {
    Route::post('/tlogin', 'AuthApiController@login');
    Route::post('/register', 'AuthApiController@register');
    Route::post('/social_signin', 'AuthApiController@social_signin');
    Route::post('/profile', 'AuthApiController@profile');

    Route::get('pos', 'PosApiController@index')->name('pos.index');
    Route::post('pos/place-order', 'PosApiController@placeOrder');
    Route::get('/pos/products', 'PosApiController@loadProducts');
    Route::get('/pos/product', 'PosApiController@loadProduct');
    Route::get('/pos/customers', 'PosApiController@loadCustomers');

    Route::get('home_page', 'HomeApiController@index');
    Route::get('banners', 'HomeApiController@banners');
    Route::get('related_products', 'HomeApiController@related_products');
    Route::get('collections', 'HomeApiController@collections');
    Route::get('collections/details', 'HomeApiController@collectionDetails');
    Route::get('category-products', 'HomeApiController@categoryProducts');
    Route::get('settings', 'HomeApiController@settings');
    Route::get('shippings', 'HomeApiController@shippings');
    Route::get('payments', 'HomeApiController@payments');
    Route::get('cities', 'HomeApiController@cities');
    Route::get('/product_details', 'HomeApiController@product_details');
    Route::post('/product_review', 'HomeApiController@product_review');
    Route::get('/after_order_survey', 'HomeApiController@after_order_survey');
    Route::post('/new_survey_entry', 'HomeApiController@new_survey_entry');

    Route::get('/wishlist','HomeApiController@userWishlist');
    Route::post('/wishlist/save','HomeApiController@wishlist');
    Route::post('/checkout', 'ClientApiController@placeOrder');
    Route::get('/coupon/check', 'ClientApiController@couponStore');
});

Route::middleware([
    'api',
    'auth:sanctum',
])->prefix('v1')->name('api.')->namespace('Api\V1\Store')->group(function () {

    Route::get('/carts', 'CartApiController@index');
    Route::post('/carts', 'CartApiController@addToCart');
    Route::post('/carts/{cart}/remove', 'CartApiController@cartDelete');

    Route::get('/user/orders', 'ClientApiController@orderHistory');
});
