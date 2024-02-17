<?php

use App\Http\Controllers\Api\V1\Store\AuthApiController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\StoreV2Controller;
use App\Http\Controllers\SupplyController;
use App\Http\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Order;
use App\Models\SellersOrder;
use App\Models\SellerTransaction;



Route::middleware('auth:sanctum')->get('/v1/user', function (Request $request) {
    $user = $request->user();
    $transactions = SellerTransaction::where('seller_id', $user->id)->get();

    $totalGain = $transactions->where('type', 'IN')->sum('solde') - $transactions->where('type', 'OUT')->sum('solde');
    $user->solde = $totalGain;
    $user->order_delivered = SellersOrder::where('seller_id', $user->id)
        ->where('status', 'delivered')
        ->count();

    $user->order_in_delivered = SellersOrder::where('seller_id', $user->id)->where('status', '!=', 'delivered')
        ->count();
    $user->transactions = $transactions;
    return $user;
});
Route::middleware([
    'api',
])->prefix('v1')->name('api.')->namespace('Api\V1\Store')->group(function () {
    Route::post('/tlogin', 'AuthApiController@login');
    Route::post('/register', 'AuthApiController@register');
    Route::post('/social_signin', 'AuthApiController@social_signin');
    Route::post('/profile', 'AuthApiController@profile');
    Route::post('/destroy/{id}', 'AuthApiController@destroy');
    Route::get('pos', 'PosApiController@index')->name('pos.index');
    Route::post('pos/place-order', 'PosApiController@placeOrder');
    Route::get('/pos/products', 'PosApiController@loadProducts');
    Route::get('/pos/product', 'PosApiController@loadProduct');
    Route::get('/pos/customers', 'PosApiController@loadCustomers');
    Route::get('home_page', 'HomeApiController@index');
    Route::get('products/section', 'HomeApiController@allSection');

    Route::get('banners', 'HomeApiController@banners');
    Route::get('related_products', 'HomeApiController@related_products');
    Route::get('collections', 'HomeApiController@collections');
    Route::get('collections/details', 'HomeApiController@collectionDetails');
    Route::get('category-products', 'HomeApiController@categoryProducts');
    Route::get('brands-products', 'HomeApiController@brandProducts');
    Route::get('products', 'HomeApiController@getProducts');
    Route::post('product', 'HomeApiController@getProduct');
    Route::post('search', 'HomeApiController@search');
    Route::post('searchCategory', 'HomeApiController@searchCategory');
    Route::get('settings', 'HomeApiController@settings');
    Route::get('shippings', 'HomeApiController@shippings');
    Route::get('payments', 'HomeApiController@payments');
    Route::get('cities', 'HomeApiController@cities');
    Route::get('/product_details', 'HomeApiController@product_details');
    Route::post('/product_review', 'HomeApiController@product_review');
    Route::get('/after_order_survey', 'HomeApiController@after_order_survey');
    Route::post('/new_survey_entry', 'HomeApiController@new_survey_entry');
    Route::get('/all', 'HomeApiController@getAll');
    Route::get('/wishlist', 'HomeApiController@userWishlist');
    Route::post('/wishlist/save', 'HomeApiController@wishlist');
    Route::post('/wishlist/check', 'HomeApiController@productWishlist');
    Route::post('/checkout', 'ClientApiController@placeOrder');
    Route::get('/coupon/check', 'ClientApiController@couponStore');
    Route::post('/supply/confirm', [SupplyController::class, 'confirm']);
    Route::patch('/supply/{id}', [SupplyController::class, 'update']);
    Route::post('/supply-order/confirm', [SupplyController::class, 'confirmSupplyOrder']);
    Route::patch('/sorders/{id}', [SupplyController::class, 'updateSupplyOrder']);
    Route::patch('/supply-orders/{id}', [SupplyController::class, 'supplyOrderItemUpdate']);
    Route::apiResource('brands', BrandsController::class);
    Route::apiResource('categories', CategorysController::class);
    Route::apiResource('sub-categories', SubCategoryController::class);
    Route::get('search/{name}', 'BrandsController@search');
    Route::get('/getProductLink', 'CartApiController@index');
    Route::post('Forget_Password', [AuthApiController::class, 'ForgetPassword']);
    Route::post('reset-password', [AuthApiController::class, 'ResetPassword']);
    Route::post('chekValidateCode', [AuthApiController::class, 'chekValidateCode']);

    Route::post('fcm-token', 'FcmTokenController@store');


});
Route::post('storeV2', [StoreV2Controller::class, 'index']);
Route::middleware([
    'api',
    'auth:sanctum',
])->prefix('v1')->name('api.')->namespace('Api\V1\Store')->group(function () {
    Route::get('/carts', 'CartApiController@index');
    Route::post('/carts', 'CartApiController@addToCart');
    Route::post('/carts/{cart}/remove', 'CartApiController@removeProductFromCart');
    Route::get('/user/orders', 'ClientApiController@orderHistory');
    Route::get('/sallersHistory', 'CartApiController@sellersIndex');

    Route::post('/switchToSeller', [AuthApiController::class, 'switchToSeller']);


});

Route::post('sendNotification', function (Request $request) {
    NotificationService::sendNotification($request->token, $request->messege);
});

//token

Route::post('bankily/payment', 'PaymentController@processPayment');
Route::post('bankily/checkTransaction', 'PaymentController@checkTransaction');
Route::post('filters', [ProductController::class, 'filter']);
Route::get('historiques/{id}', [OrderController::class, 'historiqueOrder']);
Route::post('related_products', [StoreV2Controller::class, 'related_products']);
Route::post('search_products', [StoreV2Controller::class, 'search']);
Route::get('products', [StoreV2Controller::class, 'getProducts']);
Route::get('product/{id}', [StoreV2Controller::class, 'getProduct']);
Route::get('categoryProducts/{title}', [StoreV2Controller::class, 'categoryProducts']);
Route::get('states', [StateController::class, 'index']);
Route::get('states/{id}', [StateController::class, 'show']);
Route::post('states', [StateController::class, 'store']);
Route::put('states/{id}', [StateController::class, 'update']);
Route::delete('states/{id}', [StateController::class, 'destroy']);
Route::get('provinces', [ProvinceController::class, 'index']);
Route::get('provinces/{id}', [ProvinceController::class, 'show']);
Route::post('provinces', [ProvinceController::class, 'store']);
Route::put('provinces/{id}', [ProvinceController::class, 'update']);
Route::delete('provinces/{id}', [ProvinceController::class, 'destroy']);
Route::post('stateProvinces', [StateController::class, 'stateProvince']);

Route::post('emwali/walletInquiryAndGenerateOtp', 'PaymentController@walletInquiryAndGenerateOtp');
Route::post('emwali/pay', 'PaymentController@Pay');




Route::get('/fixSellersTransactions', function (Request $request) {
    $sellersOrders = SellersOrder::with('sellersOrderProducts')->get();

    foreach ($sellersOrders as $sellersOrder) {
        $totalGain = $sellersOrder->sellersOrderProducts->sum('gain');

        $transaction = SellerTransaction::where('order_id', $sellersOrder->order_id)->first();
        if ($transaction) {
            $transaction->update([
                'solde' => $totalGain,
                'seller_id' => $sellersOrder->seller_id,
                'order_id' => $sellersOrder->order_id,
                'type' => 'IN',
            ]);
        }
    }

    dd('La liste des transactions du vendeur est corrigée avec le montant gagné dans chaque commande.');
});