<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\StoreV2Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Services\NotificationService;
use App\Models\Category;
use Carbon\Carbon;
use App\Models\Product;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\StateController;

// use BD;
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


Route::post('storeV2',[StoreV2Controller::class,'index']);

Route::get('/migrateProduct', function (Request $request) {

    // $wp_aws_index = DB::table('wp_aws_index')->where('id',803)->where('term_source','title')->pluck('term');
    // $wp_terms = DB::table('wp_terms')->get();
    // $wp_wc_product_meta_lookup= DB::table('wp_wc_product_meta_lookup')->get();
    $products = DB::connection('mysql2')->table('wp_posts')->select('id','post_title')->where('post_type','product')->orderBy('id', 'DESC')->get();

    $collect =array();
    $category=DB::connection('mysql2')->table('wp_terms')
    ->join('wp_term_taxonomy', 'wp_terms.term_id', '=', 'wp_term_taxonomy.term_id')
    ->where('wp_term_taxonomy.taxonomy', 'product_cat')->get();
    // foreach($category as $item){

    //    Category::create([
    //         'id' => $item->term_id,
    //         'title'=> $item->name,
    //         'slug'=> $item->slug,
    //     ]);
    // }

    foreach($products as $product){
     $image = DB::connection('mysql2')->table('wp_posts')->select('guid')->where('post_parent',$product->id)->first();
     $price = DB::connection('mysql2')->table('wp_postmeta')->select('meta_value')->where('meta_key','_regular_price')->where('post_id',$product->id)->first();
     $price_good = DB::connection('mysql2')->table('wp_postmeta')->select('meta_value')->where('meta_key','_price')->where('post_id',$product->id)->first();
    $sku = DB::connection('mysql2')->table('wp_postmeta')->select('meta_value')->where('meta_key','_sku')->where('post_id',$product->id)->first();
    $des = DB::connection('mysql2')->table('wp_posts')->select('post_excerpt')->where('ID',$product->id)->first();
    $description = $des ?? '';
    $stock= DB::connection('mysql2')->table('wp_postmeta')->select('meta_value')->where('meta_key','_stock')->where('post_id',$product->id)->first();
    $_stock = $stock ?? 0;
    $stock_status= DB::connection('mysql2')->table('wp_postmeta')->select('meta_value')->where('meta_key','_stock_status')->where('post_id',$product->id)->first();
    $_stock_s = $stock_status ?? 0;
    $brands = DB::connection('mysql2')->table('wp_aws_index')->select('id','term_id')->where('id',$product->id)->first();
     $brand = $brands ?? null;
     $prid=$brand->id ?? null;
    // dd($brand);
     $prid ? $slugs= DB::connection('mysql2')->table('wp_terms')->select('slug')->where('term_id', $prid)->first(): null;
    $slg = $slugs->slug ?? Str::random(8);
    // dd($slugs);
    // $brand = $brands ?? null;
    $brp = $brand->term_id ?? null;
       $brp ? 0 : 1 ;
    //   if($brp != null && $brp > 0 ){

    //     $product = Product::find($product->id);
    //     $product->categories()->attach($brp);
    //     // DB::table('product_categories')->insert(
    //     //     ['category_id' => (int)$brp, 'product_id' => (int)$product->id]
    //     // );
    //    }


     if($image && $price && $price_good && $sku) {

        array_push($collect,['id'=>$product->id,'title'=>$product->post_title, 'photo'=>$image->guid,'price'=>$price->meta_value,'price_of_goods'=>$price_good->meta_value,'sku'=>$sku->meta_value ,'description'=> $description->post_excerpt,'stock'=>1,'brand_id'=>1,'slug'=>$slg,'summary'=> '','discount'=>(($price->meta_value - $price_good->meta_value)/$price->meta_value)*100,'discount_start'=> null,'discount_end'=> null,'stock_last_update'=> Carbon::now()->format('Y-m-d H:i:s'),'free_shipping'=>0,'is_featured'=>0]);
     }

    }
    foreach($collect as $item){
    //    Product::create($item);
    DB::table('order_products')->insertOrIgnore([
        'id'=> $item->id ,
        'title'=>$item->title,
        'photo'=>$item->photo,
        'price'=> $item->price,
        'price_of_goods'=>$item->price_of_goods,
        'sku'=>$item->sku,
        'description'=>$item->description,
        "stock"=>1,
        "brand_id"=>1,
        "slug"=>"",
        'summary'=> '',
        'discount'=>$item->discount,
        'discount_start'=> null,
        'discount_end'=> null,
        'stock_last_update'=> Carbon::now()->format('Y-m-d H:i:s'),
        'free_shipping'=>0,
        'is_featured'=>0,
    ]);
    }


    return response([
        'data' =>  'done',
     ], 200);
});


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
    Route::get('brands-products', 'HomeApiController@brandProducts');
    Route::get('products', 'HomeApiController@getProducts');
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


    Route::apiResource('brands',BrandsController::class);
    Route::apiResource('categories',CategorysController::class);
    Route::apiResource('sub-categories',SubCategoryController::class);
    Route::get('search/{name}','BrandsController@search');
    // Route filter brands





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




Route::post('sendNotification', function (Request $request) {
    NotificationService::sendNotification($request->token, $request->messege);
});




//////

Route::get('/db', function (Request $request) {
    ini_set('max_execution_time', '0');
    $orders=array();
 $data =  DB::connection('mysql2')->table('wp_posts')->select('id','post_title')->where('post_type','shop_order')->orderBy('id', 'DESC')->get();
//  $users =  DB::connection('mysql2')->table('wp_users')->select('ID','user_login','user_email','user_pass')->orderBy('id', 'DESC')->get();

//  foreach($users as $user){
//     // dd($user);
//     $f_name = DB::connection('mysql2')->table('wp_usermeta')->select('meta_value')->where('meta_key','first_name')->where('user_id',$user->ID)->first();
//     $l_name = DB::connection('mysql2')->table('wp_usermeta')->select('meta_value')->where('meta_key','last_name')->where('user_id',$user->ID)->first();


//     DB::table('users')->insertOrIgnore([
//         'id'=> $user->ID,
//         'name'=>  $user->user_login ?? null,
//        'first_name'=>$f_name->meta_value ?? null,
//        'email'=> $user->user_email ?? null,
//        'last_name'=>$l_name->meta_value  ?? null,
//        'password'=>$user->user_pass ?? null,

//        ]);



//  }

 foreach($data as $dt){

if($dt->id > 0 && $dt->id != null){
   $user = DB::connection('mysql2')->table('wp_postmeta')->select('meta_value')->where('meta_key','_customer_user')->where('post_id',$dt->id)->first();
   $email = DB::connection('mysql2')->table('wp_postmeta')->select('meta_value')->where('meta_key','_billing_email')->where('post_id',$dt->id)->first();
  $city = DB::connection('mysql2')->table('wp_postmeta')->select('meta_value')->where('meta_key','_shipping_city')->where('post_id',$dt->id)->first();
   $phone = DB::connection('mysql2')->table('wp_postmeta')->select('meta_value')->where('meta_key','_billing_phone')->where('post_id',$dt->id)->first();
   $f_name = DB::connection('mysql2')->table('wp_postmeta')->select('meta_value')->where('meta_key','_shipping_first_name')->where('post_id',$dt->id)->first();
   $l_name = DB::connection('mysql2')->table('wp_postmeta')->select('meta_value')->where('meta_key','_shipping_last_name ')->where('post_id',$dt->id)->first();
   $lp = DB::connection('mysql2')->table('wp_postmeta')->select('meta_value')->where('meta_key','_payment_method')->where('post_id',$dt->id)->first();
   $to = DB::connection('mysql2')->table('wp_postmeta')->select('meta_value')->where('meta_key','_order_total')->where('post_id',$dt->id)->first();
   $cn = DB::connection('mysql2')->table('wp_postmeta')->select('meta_value')->where('meta_key','_billing_country')->where('post_id',$dt->id)->first();
   $ad = DB::connection('mysql2')->table('wp_postmeta')->select('meta_value')->where('meta_key','_billing_address_1')->where('post_id',$dt->id)->first();
    $re = DB::connection('mysql2')->table('wp_postmeta')->select('meta_value')->where('meta_key','post_id')->where('post_id',$dt->id)->first();
   $ps = DB::connection('mysql2')->table('wp_posts')->select('post_status')->where('post_type','shop_order')->where('ID',$dt->id)->first();
   $pys = DB::connection('mysql2')->table('wp_posts')->select('ping_status')->where('post_type','shop_order')->where('ID',$dt->id)->first();
   $created = DB::connection('mysql2')->table('wp_posts')->select('post_modified')->where('ID',$dt->id)->first();
   $toto = DB::connection('mysql2')->table('wp_postmeta')->select('meta_value')->where('meta_key','total_amount ')->where('post_id',$dt->id)->first();



DB::table('orders')->insertOrIgnore([
    'id'=> $dt->id,
   'user_id'=>null,
   'town_city'=>$city->meta_value ?? null,
   'phone'=>$phone->meta_value ?? null,
   'first_name'=>$f_name->meta_value ?? null,
   'email'=> $email->meta_value  ?? null,
   'last_name'=>$l_name->meta_value  ?? null,
   'payment_method'=>$lp->meta_value  ?? null,
   'sub_total'=> $to->meta_value ?? null,
   'country'=>$cn->meta_value ?? null,
   'address1'=>$ad->meta_value ?? null,
   'reference'=>$dt->id ?? null,
   'payment_status'=>$ps->post_status === 'wc-completed' ? 'paid' :'unpaid',
   'total_amount'=>$to->meta_value ?? 0,
    'status'=>$ps->post_status,
    'created_at'=> $created->post_modified,

   ]);

   $order_item = DB::connection('mysql2')->table('wp_woocommerce_order_items')->select('order_item_id')->where('order_id',$dt->id)->first();
   if($order_item != null){
    $product_name = DB::connection('mysql2')->table('wp_woocommerce_order_items')->select('order_item_name')->where('order_item_type','line_item ')->where('order_id',$dt->id)->first();
   $product_id = DB::connection('mysql2')->table('wp_woocommerce_order_itemmeta')->select('meta_value')->where('meta_key','_product_id ')->where('order_item_id',$order_item->order_item_id)->first();
   $price = DB::connection('mysql2')->table('wp_woocommerce_order_itemmeta')->select('meta_value')->where('meta_key','_line_total ')->where('order_item_id',$order_item->order_item_id)->first();
   $qt = DB::connection('mysql2')->table('wp_woocommerce_order_itemmeta')->select('meta_value')->where('meta_key','_qty')->where('order_item_id',$order_item->order_item_id)->first();
   $subt = DB::connection('mysql2')->table('wp_woocommerce_order_itemmeta')->select('meta_value')->where('meta_key','_line_subtotal')->where('order_item_id',$order_item->order_item_id)->first();
   DB::table('order_products')->insertOrIgnore([
      'order_id'=> $dt->id ?? null,
      'product_name'=>$product_name->order_item_name ?? null,
      'product_id'=>$product_id->meta_value ?? null,
      'price'=> $price->meta_value  ?? null,
      'quantity'=>$qt->meta_value  ?? null,
      'sub_total'=>$subt->meta_value  ?? null,

      ]);
   }

}


 }
   return response()->json(['message' =>   'Done ...!!']);
});



//route filter brands
Route::post('filters', [ProductController::class, 'filter']);
//route historiqueOrder
Route::get('historiques/{id}', [OrderController::class, 'historiqueOrder']);
Route::post('related_products', [StoreV2Controller::class, 'related_products']);
Route::post('search_products', [StoreV2Controller::class, 'search']);
Route::get('products', [StoreV2Controller::class, 'getProducts']);
Route::get('product/{id}', [StoreV2Controller::class, 'getProduct']);
Route::get('categoryProducts/{title}', [StoreV2Controller::class, 'categoryProducts']);



//Route states
Route::get('states', [StateController::class, 'index']);
Route::get('states/{id}', [StateController::class, 'show']);
Route::post('states', [StateController::class, 'store']);
Route::put('states/{id}', [StateController::class, 'update']);
Route::delete('states/{id}', [StateController::class, 'destroy']);


//Route province
Route::get('provinces', [ProvinceController::class, 'index']);
Route::get('provinces/{id}', [ProvinceController::class, 'show']);
Route::post('provinces', [ProvinceController::class, 'store']);
Route::put('provinces/{id}', [ProvinceController::class, 'update']);
Route::delete('provinces/{id}', [ProvinceController::class, 'destroy']);



// state and province
Route::post('stateProvinces', [StateController::class, 'stateProvince']);
