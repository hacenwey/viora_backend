<?php

namespace App\Http\Controllers\Api\V1\Store;

use Helper;
use App\Models\Cart;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Attribute;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CartApiController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();

        if($user){
            $carts = Cart::where('user_id', $user->id)->with('product')->get();

            return response()->json([
                'success'   => true,
                'carts'   => $carts
            ]);
        }

        return response()->json([
            'success'   => false,
            'message'   => "Unauthenticated!"
        ]);
    }

    public function addToCart(Request $request)
    {
        // dd($request->all());

        $user = $request->user();

        if (empty($user)) {
            return response()->json([
                'success' => false,
                'message'   => "User Not Found!"
            ]);
        }

        if (empty($request->product_id)) {
            return response()->json([
                'success' => false,
                'message'   => "Product Not Found!"
            ]);
        }
        $product = Product::where('id', $request->product_id)->first();

        if (($request->quant && $request->quant < 1) || empty($product)) {
            return response()->json([
                'success' => false,
                'message'   => "Product Not Found!"
            ]);
        }

        if ($product->stock == 0) {
            return response()->json([
                'success'   => false,
                'message'   => "Out of stock, You can add other products."
            ]);
        }


        $after_discount = ($product->price - ($product->price * $product->discount) / 100);

        $already_cart = Cart::where('user_id', $user->id)->where('order_id', null)->where('product_id', $product->id)->first();

        if ($already_cart) {
            $already_cart->quantity = $request->quant ?? ($already_cart->quantity + 1);
            $already_cart->amount = $already_cart->price * $already_cart->quantity;
            if ($already_cart->product->stock != '-1' && $already_cart->product->stock <= $already_cart->quantity){
                return response()->json([
                    'success'   => false,
                    'message'   => "Stock not sufficient!."
                ]);
            }
            $already_cart->save();
            return response()->json([
                'success'   => true,
                'message'   => "Cart updated successfully!"
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
                    'success'   => false,
                    'message'   => "Stock not sufficient!."
                ]);
            };
            $cart->save();
            // $wishlist = Wishlist::where('user_id', $user->id)->where('cart_id', null)->update(['cart_id' => $cart->id]);


            return response()->json([
                'success'   => true,
                'message'   => "Product added to Cart successfully!"
            ]);
        }
    }

    public function cartDelete(Request $request)
    {

        $user = $request->user();

        if($user){
            $cart = Cart::find($request->cart_id);
            if ($cart) {
                $cart->delete();

                return response()->json([
                    'success'   => true,
                    'message'   => "Product removed from Cart successfully!"
                ]);
            }
        }

        return response()->json([
            'success'   => false,
            'message'   => "Error!"
        ]);
    }
}
