<?php

namespace App\Http\Controllers\Api\V1\Store;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\SellersOrder;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SellerTransaction;

class CartApiController extends Controller
{

    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $compteId = $request->saleId === 'null' ? $user->id : $request->saleId;

            $carts = Cart::where('user_id', $compteId)->with('product')->get();

            return response()->json([
                'success' => true,
                'carts' => $carts,
            ]);
        } catch (\QueryException $e) {
            \Log::error('Database Query Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching data. Please try again later.',
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Unexpected Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }

    public function addToCart(Request $request)
    {
        // dd($request->all());

        $user = $request->user();

        if (empty($user)) {
            return response()->json([
                'success' => false,
                'message' => "User Not Found!",
            ]);
        }

        if (empty($request->product_id)) {
            return response()->json([
                'success' => false,
                'message' => "Product Not Found!",
            ]);
        }
        $product = Product::where('id', $request->product_id)->first();

        if (($request->quant && $request->quant < 1) || empty($product)) {
            return response()->json([
                'success' => false,
                'message' => "Product Not Found!",
            ]);
        }

        if ($product->stock == 0) {
            return response()->json([
                'success' => false,
                'message' => "Out of stock, You can add other products.",
            ]);
        }

        $after_discount = ($product->price - ($product->price * $product->discount) / 100);

        $already_cart = Cart::where('user_id', $user->id)->where('order_id', null)->where('product_id', $product->id)->first();

        if ($already_cart) {
            $already_cart->quantity = $request->quant ?? ($already_cart->quantity + 1);
            $already_cart->amount = $already_cart->price * $already_cart->quantity;
            if ($already_cart->product->stock != '-1' && $already_cart->product->stock <= $already_cart->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock not sufficient!.",
                ]);
            }
            $already_cart->save();
            return response()->json([
                'success' => true,
                'message' => "Cart updated successfully!",
            ]);
        } else {

            $cart = new Cart;
            $cart->user_id = $user->id;
            $cart->product_id = $product->id;
            $cart->price = $product->discount > 0 ? $after_discount : $product->price;
            $cart->quantity = $request->quant ?? 1;
            $cart->amount = $cart->price * $cart->quantity;
            if ($cart->product->stock == 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock not sufficient!.",
                ]);
            };
            $cart->save();
            // $wishlist = Wishlist::where('user_id', $user->id)->where('cart_id', null)->update(['cart_id' => $cart->id]);

            return response()->json([
                'success' => true,
                'message' => "Product added to Cart successfully!",
            ]);
        }
    }

    public function cartDelete(Request $request)
    {

        $user = $request->user();

        if ($user) {
            $cart = Cart::find($request->cart_id);
            if ($cart) {
                $cart->delete();

                return response()->json([
                    'success' => true,
                    'message' => "Product removed from Cart successfully!",
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => "Error!",
        ]);
    }


    public function removeProductFromCart(Request $request)
    {

        $user = $request->user();
        $productId = $request->product_id;

        if ($user) {
            $cart = Cart::where('product_id',$productId)->where('user_id', $user->id)->first();
            if ($cart) {
                $cart->delete();

                return response()->json([
                    'success' => true,
                    'message' => "Product removed from Cart successfully!",
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => "Error!",
        ]);
    }

    public function sellersIndex(Request $request)
    {
        $user = $request->user();

        $sallersOrders = SellersOrder::with('products','sellersOrderProducts.product')->where('seller_id', $user->id)->orderBy('created_at', 'desc')->get();
        $transactions = SellerTransaction::where('seller_id', $user->id)->get();

        $totalGain =  $transactions->where('type', 'IN')->sum('solde') -  $transactions->where('type', 'OUT')->sum('solde');
     

        return response()->json([
            'solde' => $totalGain,
            'orders' => $sallersOrders,
        ]);
    }
}
