<?php

declare(strict_types=1);

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/


Route::middleware([
    'web',
    'admin',
])->name('backend.')->prefix('/admin')->group(function () {
    Route::get('/','AdminController@index')->name('admin');
    Route::post('/search','HomeController@search')->name('search');
    Route::get('/file-manager',function(){
        return view('backend.layouts.file-manager');
    })->name('file-manager');
     // user fournisseurs
    Route::get('/fournisseurs',function(){
        return view('backend.fournisseurs');
    })->name('fournisseurs');
   
    
    // user route
    Route::resource('users','UsersController');
    Route::resource('clients','ClientsController');
    // Permissions
    Route::resource('permissions','PermissionController');
    // Roles
    Route::resource('roles','RoleController');
    // Payments
    Route::resource('payments','PaymentController');
    // Cities
    Route::resource('cities','CityController');
    // Banner
    Route::resource('banner','BannerController');
    // Brand
    Route::resource('brand','BrandController');
    // provider
    Route::resource('provider','FournisseursController');
    // lignesCommands
    Route::resource('commandes','CommandesController');
    // lignesCommands
    Route::resource('lingesCommandes','LingesCommandesController');
     // lignesCommands
     Route::resource('productsSuppliers','ProductSuppliersController');
    // lignesCommands
    Route::resource('currencys','CurrencyController');
    // Collection
    Route::resource('collections','CollectionController');
    // Profile
    Route::get('/profile','AdminController@profile')->name('admin-profile');
    Route::post('/profile/{id}','AdminController@profileUpdate')->name('profile-update');
    // Category
    Route::resource('/category','CategoryController');
    // Product
    Route::resource('/product','ProductController');
    Route::post('/product/search','ProductController@getProductsFiltered')->name('product-search');
    Route::post('/products/delete-all', 'ProductController@deleteAll');
    Route::post('/products/import', 'ProductController@import')->name('products.import');
    // Ajax for sub category
    Route::post('/category/{id}/child','CategoryController@getChildByParent');
    // POST category
    Route::resource('/post-category','PostCategoryController');
    // Post tag
    Route::resource('/post-tag','PostTagController');
    // Post
    Route::resource('/post','PostController');
    // Message
    Route::resource('/message','MessageController');
    Route::get('/message/five','MessageController@messageFive')->name('messages.five');
    Route::post('new-message', 'MessageController@newMessage')->name('new-message');



    // Order
    Route::resource('/order','OrderController');
    Route::get('get-orders', [OrderController::class, 'getOrders'])->name('get-orders');
    Route::post('/orders/pdf','OrderController@multiPdf')->name('orders.pdf');
    Route::post('/orders/bl-pdf','OrderController@blPdf')->name('orders.blpdf');
    Route::put('/orders/{order}/update/{product}', 'OrderController@updateItem')->name('order.update-item');
    Route::put('/orders/{order}/add-product', 'OrderController@addItem')->name('order.add-item');
    Route::delete('/orders/{order}/delete/{product}', 'OrderController@removeItem')->name('order.remove-item');
    Route::post('/orders/status-change', 'OrderController@statusChange');
    Route::post('/orders/import', 'OrderController@import')->name('orders.import');
    Route::post('/orders/import-order-products', 'OrderController@importOrderProducts')->name('orders.products.import');

    // Surveys
    Route::resource('/surveys','SurveyController');
    Route::post('/surveys/{survey}/question','SurveyController@NewQuestion')->name('surveys.question');
    Route::post('/surveys/{survey}/{question}/update','SurveyController@UpdateQuestion')->name('surveys.question.update');
    Route::post('/surveys/{survey}/update','SurveyController@NewEntry')->name('surveys.new-entries');
    // Shipping
    Route::resource('/shipping','ShippingController');
    // Attributes
    Route::resource('/attribute','AttributeController');
    Route::post('/attributes/get-values', 'AttributeValueController@getValues');
    Route::post('/attributes/add-values', 'AttributeValueController@addValues');
    Route::post('/attributes/update-values', 'AttributeValueController@updateValues');
    Route::post('/attributes/delete-values', 'AttributeValueController@deleteValues');

    // Load attributes on the page load
    Route::get('attributes/load', 'ProductAttributeController@loadAttributes');
    // Load product attributes on the page load
    Route::post('product/attributes', 'ProductAttributeController@productAttributes');
    // Load option values for a attribute
    Route::post('attributes/values', 'ProductAttributeController@loadValues');
    // Add product attribute to the current product
    Route::post('attributes/add', 'ProductAttributeController@addAttribute');
    // Delete product attribute from the current product
    Route::post('attributes/delete', 'ProductAttributeController@deleteAttribute');

    // Coupon
    Route::resource('/coupon','CouponController');
    // Settings
    Route::get('settings','AdminController@settings')->name('settings');
    Route::post('setting/update','AdminController@settingsUpdate')->name('settings.update');

    // Notification
    Route::get('/notification/{id}','NotificationController@show')->name('admin.notification');
    Route::get('/notifications','NotificationController@index')->name('all.notification');
    Route::delete('/notification/{id}','NotificationController@delete')->name('notification.delete');
    // Password Change
    Route::get('change-password', 'AdminController@changePassword')->name('change.password.form');
    Route::post('change-password', 'AdminController@changPasswordStore')->name('change.password');

    // Content Categories
    Route::delete('content-categories/destroy', 'ContentCategoryController@massDestroy')->name('content-categories.massDestroy');
    Route::resource('content-categories', 'ContentCategoryController');

    // Content Tags
    Route::delete('content-tags/destroy', 'ContentTagController@massDestroy')->name('content-tags.massDestroy');
    Route::resource('content-tags', 'ContentTagController');

    // Content Pages
    Route::delete('content-pages/destroy', 'ContentPageController@massDestroy')->name('content-pages.massDestroy');
    Route::post('content-pages/ckmedia', 'ContentPageController@storeCKEditorImages')->name('content-pages.storeCKEditorImages');
    Route::resource('content-pages', 'ContentPageController');


});


Route::middleware([
    'web',
])->name('backend.')->group(function () {

    Route::get('locale/{locale}', 'FrontendController@changeLocale')->name('locale');

    Route::get('account/verify/{id}', 'Auth\StoreLoginController@verify')->name('verify');

    Route::get('/e-login','Auth\StoreLoginController@showLoginForm')->name('e-login');
    Route::post('/e-login','Auth\StoreLoginController@login')->name('e-login.submit');
    Route::get('/e-logout','Auth\StoreLoginController@logout')->name('e-logout');

    // Route::get('/register','Auth\StoreLoginController@register')->name('e-register');
    // Route::post('/register','Auth\StoreLoginController@registerSubmit')->name('e-register.submit');
    // Reset password
    // Route::get('/e-password-request', 'Auth\StoreLoginController@showResetForm')->name('e-password.request');
    // Route::post('/e-password-reset', 'Auth\StoreLoginController@submitResetForm')->name('e-password.reset');
    // Reset password
    Route::get('/u-password/reset', 'Auth\StoreForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::get('/u-password/reset/{token}', 'Auth\StoreResetPasswordController@showResetForm')->name('password.reset');
    Route::post('/u-password/email','Auth\StoreResetPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('/u-password/reset', 'Auth\StoreResetPasswordController@reset')->name('password.update');
    // Socialite
    Route::get('/login/{provider}/', 'Auth\StoreLoginController@redirect')->name('login.redirect');
    Route::get('/login/{provider}/callback/', 'Auth\StoreLoginController@Callback')->name('login.callback');
});

Route::middleware([
    'web',
])->name('backend.')->group(function () {

    Route::get('/','FrontendController@home')->name('home');

    Route::get('/u-login','FrontendController@login')->name('login');
    Route::post('/u-login','FrontendController@loginSubmit')->name('login.submit');
    Route::get('/u-logout','FrontendController@logout')->name('logout');

    Route::get('/u-register','FrontendController@register')->name('register');
    Route::post('/u-register','FrontendController@registerSubmit')->name('register.submit');


    // Frontend Routes
    // Route::get('/home', 'FrontendController@index')->name('dashboard');

    Route::get('pages/{page}', 'FrontendController@staticPages')->name('pages');
    Route::get('/about-us','FrontendController@aboutUs')->name('about-us');
    Route::get('/contact','FrontendController@contact')->name('contact');
    Route::post('/contact/message','MessageController@store')->name('contact.store');
    Route::get('product-detail/{slug}','FrontendController@productDetail')->name('product-detail');
    Route::post('/product/search','FrontendController@productSearch')->name('product.search');
    Route::get('/product-cat/{slug}','FrontendController@productCat')->name('product-cat');
    Route::get('/collection/{slug}','FrontendController@collectionDetails')->name('collection-details');
    Route::get('/section/{slug}','FrontendController@sectionProducts')->name('section.products');
    Route::get('/product-sub-cat/{slug}/{sub_slug}','FrontendController@productSubCat')->name('product-sub-cat');
    Route::get('/product-brand/{slug}','FrontendController@productBrand')->name('product-brand');
    Route::get('/add-product-compare/{slug}','FrontendController@addToCompare')->name('add-product-compare');
    Route::get('/remove-product-compare/{slug}','FrontendController@removeFromCompare')->name('remove-product-compare');
    Route::get('/product-compare','FrontendController@productCompare')->name('product-compare');
    // Cart section
    Route::get('/add-to-cart/{slug}','CartController@addToCart')->name('add-to-cart');
    Route::get('/buy-now/{slug}','CartController@buyNow')->name('buy-now');
    Route::post('/add-to-cart/{slug}','CartController@singleAddToCart')->name('single-add-to-cart');
    Route::get('cart-delete/{id}','CartController@cartDelete')->name('cart-delete');
    Route::post('cart-update','CartController@cartUpdate')->name('cart.update');

    Route::get('/get-formatted-price','FrontendController@getFormattedPrice')->name('get-formatted-price');

    Route::get('/cart',function(){
        return view('frontend.pages.cart');
    })->name('cart');

    Route::get('/after-order-survey/{order}', 'OrderController@afterOrderSurvey');
    Route::get('/checkout','CartController@checkout')->name('checkout');
    Route::post('/checkout','OrderController@placeOrder')->name('placeOrder');
    Route::get('/order-success/{order}','OrderController@success')->name('order-success');
    // Wishlist
    Route::get('/wishlist',function(){
        return view('frontend.pages.wishlist');
    })->name('wishlist');
    Route::get('/wishlist/{slug}','WishlistController@wishlist')->name('add-to-wishlist')->middleware('user');
    Route::get('wishlist-delete/{id}','WishlistController@wishlistDelete')->name('wishlist-delete');
    Route::post('cart/order','OrderController@store')->name('cart.order');
    Route::get('order/pdf/{id}','OrderController@pdf')->name('order.pdf');
    Route::get('/income','OrderController@incomeChart')->name('product.order.income');
    // Route::get('/user/chart','AdminController@userPieChart')->name('user.piechart');
    Route::get('/product-grids','FrontendController@productGrids')->name('product-grids');
    Route::get('/product-lists','FrontendController@productLists')->name('product-lists');
    Route::match(['get','post'],'/filter','FrontendController@productFilter')->name('shop.filter');
    // Order Track
    Route::get('/product/track','OrderController@orderTrack')->name('order.track');
    Route::post('product/track/order','OrderController@productTrackOrder')->name('product.track.order');
    // Blog
    Route::get('/blog','FrontendController@blog')->name('blog');
    Route::get('/blog-detail/{slug}','FrontendController@blogDetail')->name('blog.detail');
    Route::get('/blog/search','FrontendController@blogSearch')->name('blog.search');
    Route::post('/blog/filter','FrontendController@blogFilter')->name('blog.filter');
    Route::get('blog-cat/{slug}','FrontendController@blogByCategory')->name('blog.category');
    Route::get('blog-tag/{slug}','FrontendController@blogByTag')->name('blog.tag');

    // NewsLetter
    Route::post('/subscribe','FrontendController@subscribe')->name('subscribe');

    // Product Review
    Route::resource('review','ProductReviewController');
    Route::post('product/{slug}/review','ProductReviewController@store')->name('review.store');

    // Post Comment
    Route::post('post/{slug}/comment','PostCommentController@store')->name('post-comment.store');
    Route::resource('/comment','PostCommentController');
    // Coupon
    Route::post('/coupon-store','CouponController@couponStore')->name('coupon-store');
    // Payment
    Route::get('payment', 'PayPalController@payment')->name('payment');
    Route::get('cancel', 'PayPalController@cancel')->name('payment.cancel');
    Route::get('payment/success', 'PayPalController@success')->name('payment.success');
});

Route::middleware([
    'web',
    'user',
])->name('users.')->prefix('/user')->group(function () {
    Route::get('/','HomeController@index')->name('user');
    // Profile
    Route::get('/profile','HomeController@profile')->name('user-profile');
    Route::post('/profile/{id}','HomeController@profileUpdate')->name('user-profile-update');
    //  Order
    Route::get('/order',"HomeController@orderIndex")->name('order.index');
    Route::get('/order/show/{id}',"HomeController@orderShow")->name('order.show');
    Route::delete('/order/delete/{id}','HomeController@userOrderDelete')->name('order.delete');
    // Product Review
    Route::get('/user-review','HomeController@productReviewIndex')->name('productreview.index');
    Route::delete('/user-review/delete/{id}','HomeController@productReviewDelete')->name('productreview.delete');
    Route::get('/user-review/edit/{id}','HomeController@productReviewEdit')->name('productreview.edit');
    Route::patch('/user-review/update/{id}','HomeController@productReviewUpdate')->name('productreview.update');

    // Post comment
    Route::get('user-post/comment','HomeController@userComment')->name('post-comment.index');
    Route::delete('user-post/comment/delete/{id}','HomeController@userCommentDelete')->name('post-comment.delete');
    Route::get('user-post/comment/edit/{id}','HomeController@userCommentEdit')->name('post-comment.edit');
    Route::patch('user-post/comment/udpate/{id}','HomeController@userCommentUpdate')->name('post-comment.update');

    // Password Change
    Route::get('change-password', 'HomeController@changePassword')->name('change.password.form');
    Route::post('change-password', 'HomeController@changPasswordStore')->name('change.password');
});


Route::middleware([
    'web',
])->prefix('filemanager')->group(function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});


