<?php

namespace App\Http\Controllers\Api\V1\Store;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Banner;
use App\Models\Category;
use App\Models\City;
use App\Models\Collection;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Shipping;
use App\Models\User;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use MattDaneshvar\Survey\Models\Entry;
use MattDaneshvar\Survey\Models\Survey;

class HomeApiController extends Controller
{

    const EXPIRATION_TIME = 3600;

    public function index(Request $request)
    {
        switch ($request->section) {
            case 'categories':
                $categories = Cache::remember('categories', self::EXPIRATION_TIME, function () {
                    return Category::where('status', 'active')->orderBy('created_at', 'asc')->get();
                });
                return response()->json([
                    'title' => 'Categories',
                    'enabled' => true,
                    'items' => $categories,
                ]);

            case 'attributes':
                $attributes = Cache::remember('attributes', self::EXPIRATION_TIME, function () {
                    return Attribute::all();
                });
                return response()->json([
                    'title' => 'Attributes',
                    'enabled' => true,
                    'items' => $attributes,
                ]);

            case 'new_products':
                $new_products = Cache::remember('new_products', self::EXPIRATION_TIME, function () {
                    return getNewProducts();
                });
                if ($request->limit > 0) {
                    $new_products = $new_products->take($request->limit);
                }
                return response()->json([
                    'title' => 'New Products',
                    'enabled' => true,
                    'items' => $new_products,
                ]);

            case 'top_collection':
                $top_collection = Cache::remember('top_collection', self::EXPIRATION_TIME, function () {
                    return Product::where('status', 'active')->where('stock', '!=', 0)->with(['categories'])->where('is_featured', 1)->orderBy('id', 'DESC')->get();
                });
                if ($request->limit > 0) {
                    $top_collection = $top_collection->take($request->limit);
                }
                return response()->json([
                    'title' => 'Top Collection',
                    'enabled' => true,
                    'items' => $top_collection,
                ]);

            case 'popular':
                $popular = Cache::remember('popular', self::EXPIRATION_TIME, function () {
                    return getPopulars();
                });
                if ($request->limit > 0) {
                    $popular = $popular->take($request->limit);
                }
                return response()->json([
                    'title' => 'Most Popular',
                    'enabled' => true,
                    'items' => $popular,
                ]);

            case 'promotional':
                $promotional = Cache::remember('promotional', self::EXPIRATION_TIME, function () {
                    return getPromotionalsProducts();
                });
                if ($request->limit > 0) {
                    $promotional = $promotional->take($request->limit);
                }
                return response()->json([
                    'title' => 'Promotions',
                    'enabled' => true,
                    'items' => $promotional,
                ]);

            case 'return_in_stock':
                $return_in_stock = Cache::remember('return_in_stock', self::EXPIRATION_TIME, function () {
                    return getReturnInStock();
                });
                if ($request->limit > 0) {
                    $return_in_stock = $return_in_stock->take($request->limit);
                }
                return response()->json([
                    'title' => 'Return in Stock',
                    'enabled' => true,
                    'items' => $return_in_stock,
                ]);

            case 'product_category':
                $categoryTilte = $request->limit; // TODO: We temporarily sent the category title in the request limit.
                $category = Cache::remember($categoryTilte, self::EXPIRATION_TIME, function () use ($categoryTilte) {
                    return Category::with(['children', 'products' => function ($q) {
                        $q->where('status', 'active')
                        ->where('stock', '!=', 0);
                    }])->where('title', $categoryTilte)
                        ->where('status', 'active')
                        ->orderBy('id', 'DESC')
                        ->limit(30)
                        ->first();

                });
                return response()->json([
                    'title' => $category->title,
                    'enabled' => true,
                    'items' => $category->products->take(10),
                ]);

            default:
                return response()->json([]);
        }
    }

    public function getAll()
    {
        $products = Product::all();
        return response()->json([
            'products' => $products,
        ]);
    }

    public function collections()
    {
        $collections = Collection::where('status', 'active')->get();
        return response()->json([
            'title' => 'Discover',
            'enabled' => true,
            'items' => $collections,
        ]);
    }

    public function collectionDetails(Request $request)
    {
        $collection = Collection::where('id', $request->collection_id)->first();
        $products = $collection->products->toArray();
        if ($collection->type == 'category') {
            foreach ($collection->categories as $category) {
                foreach ($category->products as $product) {
                    if ($product->stock > 0) {
                        array_push($products, $product);
                    }
                }
            }
        }
        if ($request->limit > 0) {
            $products = collect($products)->take($request->limit);
        }
        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    public function categoryProducts(Request $request)
    {
        $categoryID = $request->category_id;
        $category = Cache::remember('CA_' . $categoryID, self::EXPIRATION_TIME, function () use ($categoryID) {
            return Category::with(['children', 'products' => function ($q) {
                $q->where('stock', '!=', 0);
            }])
                ->where('id', $categoryID)
                ->where('status', 'active')
                ->orderBy('updated_at', 'desc')
                ->limit(30)
                ->first();
        });

        return response()->json([
            'title' => $category->title,
            'enabled' => true,
            'items' => $category,
        ]);
    }

    public function brandProducts(Request $request)
    {
        $brandID = $request->brand_id;
        $products = Cache::remember('BR_' . $brandID, self::EXPIRATION_TIME, function () use ($brandID) {
            return Product::where('brand_id', $brandID)->where('status', 'active')->where('stock', '!=', 0)->with(['categories'])
                ->orderBy('id', 'DESC')->limit(30)->get();
        });
        return response()->json([
            'enabled' => true,
            'items' => $products,
        ]);
    }

    public function getProducts(Request $request)
    {
        $products = Cache::remember('all', self::EXPIRATION_TIME, function () {
            return Product::where('status', 'active')->where('stock', '!=', 0)
                ->orderBy('created_at', 'DESC')->limit(100)->get();
        });
        return response()->json([
            'enabled' => true,
            'items' => $products,
        ]);
    }

    public function search(Request $request)
    {
        $products = Product::where('title', 'like', '%' . $request->search . '%')->where('stock', '!=', 0)->limit(20)->get();
        return response()->json([
            'enabled' => true,
            'items' => $products,
        ]);
    }

    public function searchCategory(Request $request)
    {
        $products = Category::with(['children', 'products'])->where('status', 'active')
            ->where('title', 'like', '%' . $request->search . '%')
            ->limit(100)->get();
        return response()->json([
            'enabled' => true,
            'items' => $products,
        ]);
    }

    public function related_products(Request $request)
    {
        $products = [];
        $product = Product::findOrFail($request->product_id);
        if ($product != null) {
            $products = getRelatedProducts($product);
            if ($request->limit > 0) {
                $products = collect($products)->take($request->limit);
            }
        }
        return response()->json([
            'title' => 'Related Products',
            'enabled' => true,
            'items' => $products,
        ]);
    }

    public function product_details(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $rate = ceil($product->getReview->avg('rate'));

        return response()->json([
            'rate' => $rate,
            'total' => $product->getReview->count(),
            'reviews' => $product->getReview,
        ]);
    }

    public function product_review(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        $data = $request->all();
        $data['product_id'] = $product->id;
        $data['status'] = 'active';

        $status = ProductReview::create($data);

        $rate = 0;

        if ($status) {
            $rate = ceil($product->getReview->avg('rate'));
        }

        return response()->json([
            'success' => true,
            'rate' => $rate,
            'total' => $product->getReview->count(),
            'reviews' => $product->getReview,
        ]);
    }

    public function banners()
    {
        $banners = Banner::where('status', 'active')->get();
        return response()->json([
            'success' => true,
            'banners' => $banners,
        ]);
    }

    public function userWishlist(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $products = [];
        if ($user != null) {
            $wishs = Wishlist::with('product')->where('user_id', $user->id)->get();
            foreach ($wishs as $prod) {
                array_push($products, $prod->product);
            }
        }

        return response()->json([
            'success' => true,
            'products' => $products,
        ]);
    }

    public function productWishlist(Request $request)
    {

        $wishs = Wishlist::where('product_id', $request->product_id)->where('user_id', $request->user_id)->first();
        if ($wishs) {

            return response()->json([
                'success' => true,

            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function wishlist(Request $request)
    {
        if (empty($request->product_id) || empty($request->user_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Product required',
            ]);
        }
        $product = Product::where('id', $request->product_id)->first();
        if (empty($product)) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ]);
        }

        $already_wishlist = Wishlist::where('user_id', $request->user_id)->where('product_id', $product->id)->first();
        if ($already_wishlist) {
            $already_wishlist->delete();
            return response()->json([
                'success' => true,
                'message' => 'Product removed from Wishlist!',
            ]);
        } else {
            $wishlist = new Wishlist;
            $wishlist->user_id = $request->user_id;
            $wishlist->product_id = $product->id;
            $wishlist->save();
        }
        return response()->json([
            'success' => true,
            'message' => 'Product successfully saved!',
        ]);
    }

    public function settings()
    {
        return response()->json([
            'settings' => settings()->all(),
        ]);
    }

    public function shippings()
    {
        $shippings = Shipping::where('status', 'active')->get();
        return response()->json([
            'shippings' => $shippings,
        ]);
    }
    public function payments()
    {
        $payments = Payment::all();
        return response()->json([
            'payments' => $payments,
        ]);
    }
    public function cities()
    {
        $cities = City::where('status', 'Enabled')->get();
        return response()->json([
            'cities' => $cities,
        ]);
    }

    public function after_order_survey()
    {
        $survey = Survey::where('id', settings('after_order_survey'))->with(['questions'])->first();
        return response()->json([
            'survey' => $survey,
        ]);
    }

    public function new_survey_entry(Request $request)
    {
        $survey = Survey::where('id', $request->survey_id)->first();

        $form = [];

        foreach ($request['items'] as $key => $item) {
            $form = Arr::add($form, 'q' . $item['question_id'], $item['answer']);
        }

        (new Entry)->for($survey)->fromArray($form)->push();

        return response()->json([
            'success' => true,
            'survey' => $survey,
        ]);
    }
}
