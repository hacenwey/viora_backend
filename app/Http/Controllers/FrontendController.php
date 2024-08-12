<?php

namespace App\Http\Controllers;


use App\Models\Collection;
use App\Models\Order;
use Carbon\Carbon;
use DB;
use Helper;
use App\Models\Post;
use App\Models\Role;
use Illuminate\Support\Str;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\PostTag;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\NewsLetter;
use App\Models\User;
use App\Models\ContentPage;
use App\Models\PostCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Services\Collection as CustomCollection;

class FrontendController extends Controller
{

    public function index(Request $request)
    {
        return redirect()->route('backend.home');
    }

    public function home()
    {

        return view('frontend.pages.no-content');
    }

    public function staticPages()
    {
        $page = ContentPage::where('slug', request()->page)->firstOrFail();
		return view('frontend.pages.static_page', compact('page'));
    }

    public function aboutUs()
    {
        return view('frontend.pages.about-us');
    }

    public function contact()
    {
        return view('frontend.pages.contact');
    }

    public function productDetail($slug)
    {
        $product = Product::getProductBySlug($slug);
        $attributes = Attribute::all();

        return view('frontend.pages.product_detail', compact('product', 'attributes'));
    }

    public function collectionDetails($slug)
    {
        $collection = Collection::where('slug', $slug)->first();
        $attributes = Attribute::all();
        $collection->load('products');
        $products = [];
        foreach ($collection->products as $key => $value) {
            array_push($products, $value);
        }
        if ($collection->type == 'category'){
            foreach ($collection->categories as $category) {
                foreach ($category->products as $product) {
                    array_push($products, $product);
                }
            }
        }

        $products = (new CustomCollection($products))->paginate(12);

        return view('frontend.pages.collection', compact('collection', 'products', 'attributes'));
    }

    public function sectionProducts($slug)
    {
        $attributes = Attribute::all();

        switch ($slug) {
            case 'popular_products':
                $products = getPopulars();
                break;
            case 'return_in_stock':
                $products = getReturnInStock();
                break;
            case 'top_collection':
                $products = getFeatured();
                break;
            case 'populars':
                $products = getPopulars();
                break;
            case 'new':
                $products = getNewProducts();
                break;

            default:
                $products = getAllProducts();
                break;
        }

        $products = (new CustomCollection($products))->paginate(12);

        return view('frontend.pages.section', compact('slug', 'products', 'attributes'));
    }

    public function addToCompare(Request $request)
    {
        $ids = session()->get('compares');

        if ($request->slug){
            $product = Product::where('slug', $request->slug)->first();
            if ($product != null){
                if (!$ids){
                    $ids = [
                        $product->id => $product
                    ];
                    session()->put('compares', $ids);
                    request()->session()->flash('success', 'Product added to compare list.');
                    return back();
                }else{
                    if (isset($ids[$product->id])){
                        request()->session()->flash('success', 'Product already added to compare list.');
                        return back();
                    }else{
                        $ids[$product->id] = $product;
                        session()->put('compares', $ids);
                        request()->session()->flash('success', 'Product added to compare list.');
                        return back();
                    }
                }
            } else {
                request()->session()->flash('error', 'Something went wrong!.');
                return back();
            }
        }
        request()->session()->flash('error', 'Something went wrong!.');
        return back();
    }

    public function removeFromCompare(Request $request)
    {
        $ids = session()->get('compares');

        if ($request->slug){
            $product = Product::where('slug', $request->slug)->first();
            if ($product != null){
                if (isset($ids[$product->id])){
                    unset($ids[$product->id]);
                    session()->put('compares', $ids);
                    request()->session()->flash('error', 'Product removed from compare list.');
                }
                return back();
            }
        } else {
            request()->session()->flash('error', 'Something went wrong!.');
            return back();
        }
        request()->session()->flash('error', 'Something went wrong!.');
        return back();
    }

    public function productCompare()
    {
        $products = session()->get('compares');
        $attributes = Attribute::all();
        if ($products){
            return view('frontend.pages.compare', compact('products', 'attributes'));
        }else{
            request()->session()->flash('error', 'Compare lists are empty!');
            return redirect()->route('backend.home');
        }
    }

    public function productGrids()
    {
        $products = Product::query();
        $brands = Brand::all();
        $attributes = Attribute::all();
        $newproducts = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(9)->get();

        $max_price = Product::max('price');

        // dd($max_price);

        if (!empty($_GET['category'])) {
            $slug = explode(',', $_GET['category']);
            // dd($slug);
            $cat_ids = Category::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            // dd($cat_ids);
            $products->whereIn('cat_id', $cat_ids);
            // return $products;
        }
        if (!empty($_GET['brands'])) {
            $slugs = explode(',', $_GET['brands']);
            $brand_ids = Brand::select('id')->whereIn('slug', $slugs)->pluck('id')->toArray();
            // dd($brand_ids);
            $products->whereIn('brand_id', $brand_ids);
        }
        if (!empty($_GET['sortBy'])) {
            if ($_GET['sortBy'] == 'title') {
                $products = $products->where('status', 'active')->orderBy('title', 'ASC');
            }
            if ($_GET['sortBy'] == 'price') {
                $products = $products->orderBy('price', 'ASC');
            }
        }

        if (!empty($_GET['price'])) {
            $price = explode('-', $_GET['price']);
            // return $price;
            // if(isset($price[0]) && is_numeric($price[0])) $price[0]=floor(base_amount($price[0]));
            // if(isset($price[1]) && is_numeric($price[1])) $price[1]=ceil(base_amount($price[1]));

            $products->whereBetween('price', $price);
        }

        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        // Sort by number
        if (!empty($_GET['show'])) {
            $products = $products->where('status', 'active')->paginate($_GET['show']);
        } else {
            $products = $products->where('status', 'active')->paginate(12);
        }
        // Sort by name , price, category


        return view('frontend.pages.product-grids', compact('products', 'brands', 'recent_products', 'newproducts', 'attributes', 'max_price'));
    }
    public function productLists()
    {
        $products = Product::query();

        if (!empty($_GET['category'])) {
            $slug = explode(',', $_GET['category']);
            // dd($slug);
            $cat_ids = Category::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            // dd($cat_ids);
            $products->whereIn('cat_id', $cat_ids)->paginate;
            // return $products;
        }
        if (!empty($_GET['brand'])) {
            $slugs = explode(',', $_GET['brand']);
            $brand_ids = Brand::select('id')->whereIn('slug', $slugs)->pluck('id')->toArray();
            return $brand_ids;
            $products->whereIn('brand_id', $brand_ids);
        }
        if (!empty($_GET['sortBy'])) {
            if ($_GET['sortBy'] == 'title') {
                $products = $products->where('status', 'active')->orderBy('title', 'ASC');
            }
            if ($_GET['sortBy'] == 'price') {
                $products = $products->orderBy('price', 'ASC');
            }
        }

        if (!empty($_GET['price'])) {
            $price = explode('-', $_GET['price']);
            // return $price;
            // if(isset($price[0]) && is_numeric($price[0])) $price[0]=floor(base_amount($price[0]));
            // if(isset($price[1]) && is_numeric($price[1])) $price[1]=ceil(base_amount($price[1]));

            $products->whereBetween('price', $price);
        }

        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        // Sort by number
        if (!empty($_GET['show'])) {
            $products = $products->where('status', 'active')->paginate($_GET['show']);
        } else {
            $products = $products->where('status', 'active')->paginate(6);
        }
        // Sort by name , price, category


        return view('frontend.pages.product-lists')->with('products', $products)->with('recent_products', $recent_products);
    }

    public function productFilter(Request $request)
    {
        $data = $request->all();
        // return $data;
        $showURL = "";
        if (!empty($data['show'])) {
            $showURL .= '&show=' . $data['show'];
        }

        $sortByURL = '';
        if (!empty($data['sortBy'])) {
            $sortByURL .= '&sortBy=' . $data['sortBy'];
        }

        $catURL = "";
        if (!empty($data['category'])) {
            foreach ($data['category'] as $category) {
                if (empty($catURL)) {
                    $catURL .= '&category=' . $category;
                } else {
                    $catURL .= ',' . $category;
                }
            }
        }

        $brandURL = "";
        if (!empty($data['brands'])) {
            foreach ($data['brands'] as $brand) {
                if (empty($brandURL)) {
                    $brandURL .= '&brands=' . $brand;
                } else {
                    $brandURL .= ',' . $brand;
                }
            }
        }
        // return $brandURL;

        $priceRangeURL = "";
        if (!empty($data['price_range'])) {
            $priceRangeURL .= '&price=' . $data['price_range'];
        }

        return redirect()->route('backend.product-grids', $catURL . $brandURL . $priceRangeURL . $showURL . $sortByURL);

    }
    public function productSearch(Request $request)
    {
        $brands = Brand::all();
        $attributes = Attribute::all();
        $newproducts = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(9)->get();

        $max_price = Product::max('price');

        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $products = Product::orwhere('title', 'like', '%' . $request->search . '%')
            ->orwhere('slug', 'like', '%' . $request->search . '%')
            ->orwhere('description', 'like', '%' . $request->search . '%')
            ->orwhere('summary', 'like', '%' . $request->search . '%')
            ->orwhere('price', 'like', '%' . $request->search . '%')
            ->orwhere('sku', 'like', '%' . $request->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate('12');
        return view('frontend.pages.product-grids', compact('products', 'recent_products', 'brands', 'attributes', 'newproducts', 'max_price'));
    }

    public function productBrand(Request $request)
    {
        $products = Brand::getProductByBrand($request->slug);
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        if (request()->is('e-shop.loc/product-grids')) {
            return view('frontend.pages.product-grids')->with('products', $products->products)->with('recent_products', $recent_products);
        } else {
            return view('frontend.pages.product-lists')->with('products', $products->products)->with('recent_products', $recent_products);
        }
    }
    public function productCat(Request $request)
    {
        $cat = Category::where('slug', $request->slug)->first();
        $products = $cat->products()->paginate(12);
        $brands = Brand::all();
        $attributes = Attribute::all();
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $newproducts = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(9)->get();

        $max_price = Product::max('price');

        return view('frontend.pages.product-grids', compact('products', 'newproducts', 'max_price', 'recent_products', 'brands', 'attributes'));
    }
    public function productSubCat(Request $request)
    {
        $cat = Category::where('slug', $request->slug)->first();
        $products = $cat->products()->paginate(12);
        $brands = Brand::all();
        $attributes = Attribute::all();
        $recent_products = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $newproducts = Product::where('status', 'active')->orderBy('id', 'DESC')->limit(9)->get();

        $max_price = Product::max('price');

        return view('frontend.pages.product-grids', compact('products', 'newproducts', 'max_price', 'recent_products', 'brands', 'attributes'));
    }

    public function blog()
    {
        $post = Post::query();

        if (!empty($_GET['category'])) {
            $slug = explode(',', $_GET['category']);
            // dd($slug);
            $cat_ids = PostCategory::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            return $cat_ids;
            $post->whereIn('post_cat_id', $cat_ids);
            // return $post;
        }
        if (!empty($_GET['tag'])) {
            $slug = explode(',', $_GET['tag']);
            // dd($slug);
            $tag_ids = PostTag::select('id')->whereIn('slug', $slug)->pluck('id')->toArray();
            // return $tag_ids;
            $post->where('post_tag_id', $tag_ids);
            // return $post;
        }

        if (!empty($_GET['show'])) {
            $post = $post->where('status', 'active')->orderBy('id', 'DESC')->paginate($_GET['show']);
        } else {
            $post = $post->where('status', 'active')->orderBy('id', 'DESC')->paginate(9);
        }
        // $post=Post::where('status','active')->paginate(8);
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        return view('frontend.pages.blog')->with('posts', $post)->with('recent_posts', $rcnt_post);
    }

    public function blogDetail($slug)
    {
        $post = Post::getPostBySlug($slug);
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        // return $post;
        return view('frontend.pages.blog-detail')->with('post', $post)->with('recent_posts', $rcnt_post);
    }

    public function blogSearch(Request $request)
    {
        // return $request->all();
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        $posts = Post::orwhere('title', 'like', '%' . $request->search . '%')
            ->orwhere('quote', 'like', '%' . $request->search . '%')
            ->orwhere('summary', 'like', '%' . $request->search . '%')
            ->orwhere('description', 'like', '%' . $request->search . '%')
            ->orwhere('slug', 'like', '%' . $request->search . '%')
            ->orderBy('id', 'DESC')
            ->paginate(8);
        return view('frontend.pages.blog')->with('posts', $posts)->with('recent_posts', $rcnt_post);
    }

    public function blogFilter(Request $request)
    {
        $data = $request->all();
        // return $data;
        $catURL = "";
        if (!empty($data['category'])) {
            foreach ($data['category'] as $category) {
                if (empty($catURL)) {
                    $catURL .= '&category=' . $category;
                } else {
                    $catURL .= ',' . $category;
                }
            }
        }

        $tagURL = "";
        if (!empty($data['tag'])) {
            foreach ($data['tag'] as $tag) {
                if (empty($tagURL)) {
                    $tagURL .= '&tag=' . $tag;
                } else {
                    $tagURL .= ',' . $tag;
                }
            }
        }
        // return $tagURL;
        // return $catURL;
        return redirect()->route('blog', $catURL . $tagURL);
    }

    public function blogByCategory(Request $request)
    {
        $post = PostCategory::getBlogByCategory($request->slug);
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        return view('frontend.pages.blog')->with('posts', $post->post)->with('recent_posts', $rcnt_post);
    }

    public function blogByTag(Request $request)
    {
        // dd($request->slug);
        $post = Post::getBlogByTag($request->slug);
        // return $post;
        $rcnt_post = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        return view('frontend.pages.blog')->with('posts', $post)->with('recent_posts', $rcnt_post);
    }

    public function getFormattedPrice(Request $request)
    {
        $price = $request['price'];

        $formatted_price = getFormattedPrice($price);
        return response()->json([
            'formatted_price' => $formatted_price
        ], 200);
    }
    // Login
    public function login()
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('backend.admin');
        } else {
            return view('frontend.pages.login');
        }
    }
    public function loginSubmit(Request $request)
    {
        $data = $request->all();
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
        if (Auth::guard()->attempt([$fieldType => $data['email'], 'password' => $data['password'], 'status' => 'active'])) {
            Session::put('user', $data['email']);
            request()->session()->flash('success', 'Successfully login');
            return redirect()->route('backend.admin');
        } else {
            request()->session()->flash('error', 'Invalid email and password pleas try again!');
            return redirect()->back();
        }
    }

    public function logout()
    {
        Session::forget('user');
        Auth::guard()->logout();
        request()->session()->flash('success', 'Logout successfully');
        return redirect()->route('backend.home');
    }

    public function register()
    {
        return view('frontend.pages.register');
    }
    public function registerSubmit(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'name' => 'string|required|min:2',
            'email' => 'string|unique:users,email',
            'phone_number' => 'string|required|unique:users,phone_number',
            'password' => 'required|min:6|confirmed',
        ]);
        $data = $request->all();
        // dd($data);
        $check = $this->create($data);
        $user_role = Role::where('title', 'Client')->first();
        $check->roles()->attach($user_role);
        Session::put('user', $data['email']);
        if ($check) {
            request()->session()->flash('success', 'Successfully registered');
            return redirect()->route('backend.home');
        } else {
            request()->session()->flash('error', 'Please try again!');
            return back();
        }
    }
    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => Hash::make($data['password']),
            'status' => 'active'
        ]);
    }
    // Reset password
    public function showLinkRequestForm()
    {
        return view('frontend.pages.passwords.o-reset');
    }

    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'email' => 'email|required',
        ]);

        if (Newsletter::isSubscribed($request->email)) {
            request()->session()->flash('error', 'Thanks for your interests. Already Subscribed!');
            return back();
        } else {
            NewsLetter::create($request->all());
            request()->session()->flash('success', 'Subscribed! Please check your email');
            return back();
        }
    }

    public function changeLocale(Request $request)
    {
        if (request('locale')) {
            session()->put('language', request('locale'));
            $language = request('locale');
        } elseif (session('language')) {
            $language = session('language');
        } elseif (config('app.locale')) {
            $language = config('app.locale');
        }

        if (isset($language)) {
            app()->setLocale($language);
        }

        return redirect()->back();
    }
}
