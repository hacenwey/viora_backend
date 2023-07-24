<?php

use Carbon\Carbon;
use Twilio\Rest\Client;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Message;
use App\Models\PostTag;
use App\Models\Product;
use App\Models\Category;
use App\Models\Shipping;
use App\Models\Wishlist;
use App\Models\Attribute;
use App\Models\User;
use App\Notifications\CartNotify;
use App\Models\ContentPage;
use App\Models\PostCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Intl\Currencies;
use Propaganistas\LaravelPhone\PhoneNumber;
use App\Notifications\DailyCartNotification;
use Illuminate\Support\Facades\Notification;


function getRelatedProducts($product)
{
    $catIds = $product->categories->pluck('id')->toArray();
    $rels = Product::whereHas('categories', function($q) use ($catIds) {
            $q->whereIn('categories.id', $catIds);
        })
        ->where('status', 'active')
        ->where('stock', '!=', 0)
        ->limit(10)->get();

    return $rels;
}

function getClientsPhoneNumbers()
{
    $data = [];
    $users = User::with(['roles'])
            ->whereHas('roles', function($q) {
                $q->where('title', 'Client');
            })
            ->whereRaw('LENGTH(phone_number) = 8')->distinct('phone_number')->get();

    $order_users = Order::whereRaw('LENGTH(phone) = 8')->distinct('phone')->get();
    foreach ($users as $key => $user) {
        array_push($data, $user->phone_number);
    }
    foreach ($order_users as $key => $cli) {
        if(!in_array($cli->phone, $data)){
            array_push($data, $cli->phone);
        }
    }

    $phone_numbers = collect($data);

    return $phone_numbers->toArray();
}

if (!function_exists('getPopulars')) {
    function getPopulars(){
        $lastMonth = Carbon::now()->subMonth();
        $currentMonth = Carbon::now();
        return Product::where('status', 'active')
        ->where('stock', '!=', 0)
        ->whereNotNull('products.price')->leftJoin('order_products', 'products.id', '=', 'order_products.product_id')
        ->selectRaw('products.*, COALESCE(sum(order_products.quantity),0) total')
        ->groupBy('products.id')
        ->orderBy('total', 'DESC')
        ->orderBy('order_products.created_at', 'DESC')
        ->where(function ($query) use ($lastMonth, $currentMonth) {
            $query->whereDate('order_products.created_at', '>=', $lastMonth)
                ->orWhereDate('order_products.created_at', '>=', $currentMonth);
        })->get();
    }
}

if (!function_exists('getAllProducts')) {
    function getAllProducts(){
        return Product::where('status', 'active')->where('stock', '!=', 0)->orderBy('id', 'DESC')->get();
    }
}

if (!function_exists('getNewProducts')) {
    function getNewProducts(){
        return Product::where('status', 'active')->where('stock', '!=', 0)->with(['categories'])->orderBy('id', 'DESC')->get();
    }
}

if (!function_exists('getStockOut')) {
    function getStockOut(){
        return Product::where('status', 'active')->where('stock', '<', 1)->orderBy('id', 'DESC')->limit(9)->get();
    }
}

if (!function_exists('getReturnInStock')) {
    function getReturnInStock(){
        return Product::where('status', 'active')->where('stock', '!=', 0)->where('return_in_stock', '>', Carbon::now()->subDays(21))->limit(9)->orderBy('return_in_stock', 'DESC')->get();
    }
}

if (!function_exists('getFeatured')) {
    function getFeatured(){
        return Product::where('status', 'active')
        ->where('is_featured', 1)
        ->where('stock', '!=', 0)
        ->orderBy('price', 'DESC')->limit(9)->get();
    }
}

if (!function_exists('setMailConfig')) {

	function setMailConfig() {
		Config::set([
            'app.name'  => settings('app_name'),
            'app.url'  => settings('app_url'),
            'mail.default' => settings('mail_driver'),
            'mail.mailers.smtp.host' 		=> settings('mail_host'),
            'mail.mailers.smtp.port'       	=> settings('mail_port'),
            'mail.mailers.smtp.encryption' 	=> settings('mail_encryption'),
            'mail.mailers.smtp.username'   	=> settings('mail_username'),
            'mail.mailers.smtp.password'   	=> settings('mail_password'),
            'mail.from' => [
                'address' => settings('mail_from_address'),
                'name'    => settings('mail_from_name')
            ],
        ]);
	}
}

/**
 * Sends sms to user using Twilio's programmable sms client
 * @param String $message Body of sms
 */
function cartNotify()
{
    $carts = Cart::all();
    $products = [];
    $users = [];
    foreach ($carts as $key => $cart) {
        $user = User::where('id', $cart->user_id)->first();
        array_push($users, $user);
        $product = Product::where('id', $cart->product_id)->first();
        array_push($products, $product);
    }

    $details['title'] = trans('global.you_have_products_in_cart');
    $details['products'] = $products;
    if(count($users) > 0){
        setMailConfig();
        Notification::send($users, new DailyCartNotification($details));
    }
}

/**
 * Sends sms to user using Twilio's programmable sms client
 * @param String $message Body of sms
 */
function sendMessage($message, $recipient, $type)
{
    if($type == 'sms'){
        $phone = PhoneNumber::make($recipient, 'MR')->formatInternational();
        $phone = preg_replace('/\s+/', '', $phone);

        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");
        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            $phone,
            [
                'from' => $twilio_number,
                'body' => $message
            ]
        );
    }elseif($type == 'email'){
        setMailConfig();
        Mail::send([], [], function ($email) use ($recipient, $message) {
            $email->to($recipient)
                    ->subject(settings('app_name'))
                    ->setBody($message);
        });
    }
}

function isProductWishlisted($product)
{
    if(Auth::guard()->check()){
        $user = Auth::guard()->user();
        return Wishlist::where('product_id', $product)->where('user_id', $user->id)->exists();
    }

    return false;
}

function getPromotionalsProducts()
{
    $now = Carbon::now();
    $products = Product::where('discount_start', '<', $now)
        ->where('discount_end', '>', $now)
        ->where('status', 'active')
        ->where('stock', '!=', 0)
        ->get();

    return $products;
}

function static_pages() {
    $pages = ContentPage::all();
    return $pages;
}

function getAppLocale()
{
    $local = app()->getLocale();
    $lang_name = $local;
    foreach (config('panel.available_languages') as $key => $lang) {
        if($lang['short_code'] == $local){
            $lang_name = $lang['title'];
        }
    }

    return $lang_name;
}

function getDiscountTimer(Product $product)
{
    $discount = $product->discount;
    $start = $product->discount_start;
    $end = $product->discount_end;

    $now = Carbon::now();

    $days = $now->diffInDays($end);
    $hours = $now->diffInHours($end);
    $minutes = $now->diffInMinutes($end);
    $seconds = $now->diffInSeconds($end);

    $diff = $now->diff($end);

    return $diff;
}

function getIsNewProducts()
{
    $now = Carbon::now()->today();
    $products = Product::where('created_at', '>', $now->subDays(2))->exists();

    if ($products) {
        return true;
    }
    return false;
}

function getFormattedPrice($price)
{
    if(settings()->get('currency_code') == 'MRU'){
        $price = number_format($price, 2).' MRU';
    }else{
        $local = app()->getLocale();
        $fmt = numfmt_create( $local, NumberFormatter::CURRENCY );
        $price = numfmt_format_currency($fmt, $price, settings()->get('currency_code'));
    }

    return $price;
}

function existsInArray($entry, $array) {
    foreach ($array as $compare) {
        if ($compare->id == $entry->id) {
            return true;
        }
    }
    return false;
}

function messageList()
{
    return Message::whereNull('read_at')->orderBy('created_at', 'desc')->get();
}
function getAllCategory()
{
    $category = new Category();
    $menu = $category->getAllParentWithChild();
    return $menu;
}

function getHeaderCategory()
{
    $category = new Category();
    $menu = $category->getAllParentWithChild();

    if ($menu) {
?>
        <?php
        foreach ($menu as $cat_info) {
            if ($cat_info->child_cat->count() > 0) {
        ?>
            <div class="col mega-box">
                <div class="link-section">
                    <div class="menu-title">
                        <a href="<?php echo route('backend.product-cat', [$cat_info->slug]); ?>">
                            <h5>
                                <?php echo $cat_info->title; ?>
                            </h5>
                        </a>
                    </div>
                    <div class="menu-content">
                        <ul>
                            <?php
                                foreach ($cat_info->child_cat as $sub_menu) {
                            ?>
                                <li>
                                    <a href="<?php echo route('backend.product-sub-cat', [$cat_info->slug, $sub_menu->slug]); ?>">
                                        <?php echo $sub_menu->title; ?>
                                    </a>
                                </li>
                            <?php
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
            } else {
            ?>
                <div class="col mega-box">
                    <div class="link-section">
                        <div class="menu-title">
                            <a href="<?php echo route('backend.product-cat', $cat_info->slug); ?>">
                                <h5>
                                    <?php echo $cat_info->title; ?>
                                </h5>
                            </a>
                        </div>
<!--                        <div class="menu-content">-->
<!--                            <ul>-->
<!--                                <li><a href="--><?php //echo route('backend.product-cat', $cat_info->slug); ?><!--">--><?php //echo $cat_info->title; ?><!--</a></li>-->
<!--                            </ul>-->
<!--                        </div>-->
                    </div>
                </div>

        <?php
            }
        }
        ?>
<?php
    }
}

function productCategoryList($option = 'all')
{
    if ($option = 'all') {
        return Category::orderBy('id', 'DESC')->get();
    }
    return Category::has('products')->orderBy('id', 'DESC')->get();
}

function postTagList($option = 'all')
{
    if ($option = 'all') {
        return PostTag::orderBy('id', 'desc')->get();
    }
    return PostTag::has('posts')->orderBy('id', 'desc')->get();
}

function postCategoryList($option = "all")
{
    if ($option = 'all') {
        return PostCategory::orderBy('id', 'DESC')->get();
    }
    return PostCategory::has('posts')->orderBy('id', 'DESC')->get();
}
// Cart Count
function cartCount($user_id = '')
{

    if (Auth::guard()->check()) {
        if ($user_id == "") $user_id = Auth::guard()->user()->id;
        return Cart::where('user_id', $user_id)->where('order_id', null)->sum('quantity');
    } else {
        if(session('cart')){
            return count(session('cart'));
        }
        return 0;
    }
}

function getAllProductFromCart($user_id = '')
{
    if (Auth::guard()->check()) {
        if ($user_id == "") $user_id = Auth::guard()->user()->id;
        return Cart::with('product')->where('user_id', $user_id)->where('order_id', null)->get();
    } else {
        if(session('cart')){
            return session('cart');
        }
        return [];
    }
}
// Total amount cart
function totalCartPrice($user_id = '')
{
    if (Auth::guard()->check()) {
        if ($user_id == "") $user_id = Auth::guard()->user()->id;
        return Cart::where('user_id', $user_id)->where('order_id', null)->sum('amount');
    } else {
        if(session('cart')){
            $total_price = 0;
            foreach (session('cart') as $key => $item) {
                $total_price += session('cart')[$key]['amount'];
            }
            return $total_price;
        }
        return 0;
    }
}
// Wishlist Count
function wishlistCount($user_id = '')
{

    if (Auth::guard()->check()) {
        if ($user_id == "") $user_id = Auth::guard()->user()->id;
        return Wishlist::where('user_id', $user_id)->where('cart_id', null)->sum('quantity');
    } else {
        return 0;
    }
}
function getAllProductFromWishlist($user_id = '')
{
    if (Auth::guard()->check()) {
        if ($user_id == "") $user_id = Auth::guard()->user()->id;
        return Wishlist::with('product')->where('user_id', $user_id)->get();
    } else {
        return [];
    }
}
function totalWishlistPrice($user_id = '')
{
    if (Auth::guard()->check()) {
        if ($user_id == "") $user_id = Auth::guard()->user()->id;
        return Wishlist::where('user_id', $user_id)->where('cart_id', null)->sum('amount');
    } else {
        return 0;
    }
}



// Admin home
function earningPerMonth()
{
    $month_data = Order::where('status', 'delivered')->get();
    // return $month_data;
    $price = 0;
    foreach ($month_data as $data) {
        $price = $data->cart_info->sum('price');
    }
    return number_format((float)($price), 2, '.', '');
}

function shipping()
{
    return Shipping::where('status', 'active')->orderBy('id', 'DESC')->get();
}

// Send signed url ===================================

function signed($url, $timestamp)
{
    $hashed_url = Hash::make($url);
    $expiry = Carbon::parse($timestamp)->timestamp;

    return url($url . '?expiry=' . $expiry . '&signature=' . $hashed_url);
}

function hasValidSignature($url, $request)
{
    if (!Hash::check($url, $request['signature']) || Carbon::createFromTimestamp($request['expiry'])->isPast()) {
        return false;
    }

    return true;
}

function checkPromoCode($code)
{   
    if($code === null){
        return false;
    }
    
    $coupon = Coupon::byCode($code)->first();
    if ($coupon === null || $coupon->isExpired() || $coupon->isOverAmount()) {
        return false;
    }

    return $coupon;
}

/* Points fidelite helpers */
function getUserPoints(){
    if (Auth::guard()->check()) {
        $user = Auth::guard()->user();
        return $user->getPointFideliteSolde();
    } 
    return 0;
}

function getUserPointsToCurrency(){
    if (Auth::guard()->check()) {
        $user = Auth::guard()->user();
        return $user->getPointsToCurrency();
    } 
    return 0;
}
?>
