<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $product = null;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function addToCart(Request $request)
    {
        // dd($request->all());
        if (empty($request->slug)) {
            request()->session()->flash('error', 'Invalid Products');
            return back();
        }
        $product = Product::where('slug', $request->slug)->first();
        // return $product;
        if ($product->stock == 0) {
            return back()->with('error', 'Out of stock, You can add other products.');
        }
        if (($request->quant < 1) || empty($product)) {
            request()->session()->flash('error', 'Invalid Products');
            return back();
        }

        $after_discount = ($product->price - ($product->price * $product->discount) / 100);

        if (Auth::guard()->check()) {
            $already_cart = Cart::where('user_id', Auth::guard()->user()->id)->where('order_id', null)->where('product_id', $product->id)->first();
            // return $already_cart;
            if ($already_cart) {
                $already_cart->quantity = $already_cart->quantity + 1;
                $already_cart->amount = $already_cart->price + $already_cart->amount;
                if ($already_cart->product->stock < $already_cart->quantity || $already_cart->product->stock <= 0) return back()->with('error', 'Stock not sufficient!.');
                $already_cart->save();
            } else {

                $cart = new Cart;
                $cart->user_id = Auth::guard()->user()->id;
                $cart->product_id = $product->id;
                $cart->price = $product->discount > 0 ? $after_discount : $product->price;
                $cart->quantity = 1;
                $cart->amount = $cart->price * $cart->quantity;
                if ($cart->product->stock == 0) return back()->with('error', 'Stock not sufficient!.');
                $cart->save();
                $wishlist = Wishlist::where('user_id', Auth::guard()->user()->id)->where('cart_id', null)->update(['cart_id' => $cart->id]);
            }
        } else {
            $cart = session()->get('cart');
            if(!$cart){
                // Create new cart and save it in session
                $cart = [
                    $product->id => [
                        'product_id' => $product->id,
                        'price'      => $product->discount > 0 ? $after_discount : $product->price,
                        'quantity'   => 1,
                        'amount'     => $product->discount > 0 ? $after_discount : $product->price,
                        'product'    => $product,
                    ]
                ];
                if ($product->stock == 0) return back()->with('error', 'Stock not sufficient!.');

                session()->put('cart', $cart);
            }else{
                // if cart already created in session then update it
                if (isset($cart[$product->id])) {
                    // If the same product exist, just update it
                    $qty = $cart[$product->id]['quantity'] + 1;
                    $cart[$product->id]['quantity']++;
                    $cart[$product->id]['amount'] == $cart[$product->id]['price'] * $qty;

                    if ($product->stock == 0) return back()->with('error', 'Stock not sufficient!.');

                    session()->put('cart', $cart);

                    request()->session()->flash('success', 'Product successfully updated.');
                    return back();
                }
                // else add the product to cart
                $cart[$product->id] = [
                    'product_id' => $product->id,
                    'price'      => $product->discount > 0 ? $after_discount : $product->price,
                    'quantity'   => 1,
                    'amount'     => $product->discount > 0 ? $after_discount : $product->price,
                    'product'    => $product,
                ];
                session()->put('cart', $cart);
            }
        }

        if(session()->has('coupon')){
            $code = session()->get('coupon')['code'];
            if ($coupon = checkPromoCode($code)) {
                session()->remove('coupon');
                if(Auth::guard()->check()){
                    $total_price = Cart::where('user_id', Auth::guard()->user()->id)->where('order_id', null)->sum('amount');
                } else {
                    $total_price = totalCartPrice();
                }

                session()->put('coupon', [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'value' => $coupon->discount($total_price)
                ]);
            }
        }

        request()->session()->flash('success', 'Product successfully added to cart');
        return back();
    }

    public function singleAddToCart(Request $request)
    {
        if($request->slug || $request->productId){
            $product = Product::where('slug', $request->slug)->orWhere('id', $request->productId)->first();
        } else {
            request()->session()->flash('error', 'Invalid Products');
            return back();
        }

        $attrs = Attribute::all();

        if ($product->stock == 0) {
            return back()->with('error', 'Out of stock, You can add other products.');
        }
        if (($request->quantity < 1) || empty($product)) {
            request()->session()->flash('error', 'Invalid Products');
            return back();
        }

        // return $already_cart;

        $after_discount = ($product->price - ($product->price * $product->discount) / 100);

        if (Auth::guard()->check()) {
            $already_cart = Cart::where('user_id', Auth::guard()->user()->id)->where('product_id', $product->id)->first();
            if ($already_cart) {
                $already_cart->quantity = $request->quantity;
                // $already_cart->price = ($product->price * $request->quant) + $already_cart->price ;
                $already_cart->amount = ($already_cart->price * $request->quantity);

                if ($already_cart->product->stock == 0) return back()->with('error', 'Stock not sufficient!.');

                $already_cart->save();
            } else {

                $cart = new Cart;
                $cart->user_id = Auth::guard()->user()->id;
                $cart->product_id = $product->id;
                $cart->price = $product->discount > 0 ? $after_discount : $product->price;
                $cart->quantity = $request->quantity;
                $cart->amount = ($cart->price * $request->quantity);
                $cart->attributes = [];

                $attributes = [];
                foreach ($attrs as $key => $attr) {
                    $atr = $attr->code;
                    $attributes[$atr] = $request->$atr;
                }
                $cart->attributes = json_encode($attributes);

                if ($cart->product->stock == 0) return back()->with('error', 'Stock not sufficient!.');

                // return $cart;
                // dd($cart);
                $cart->save();
            }
        } else {
            $cart = session()->get('cart');
            if(!$cart){
                $cart = [
                    $product->id => [
                        'product_id' => $product->id,
                        'price'      => $product->discount > 0 ? $after_discount : $product->price,
                        'quantity'   => $request->quantity,
                        'amount'     => (($product->discount > 0 ? $after_discount : $product->price) * $request->quantity),
                        'product'    => $product,
                        'attributes'    => [],
                    ]
                ];

                $attributes = [];
                foreach ($attrs as $key => $attr) {
                    $atr = $attr->code;
                    $attributes[$atr] = $request->$atr;
                }
                $cart[$product->id]['attributes'] = json_encode($attributes);


                // dd($cart);
                if ($product->stock == 0) return back()->with('error', 'Stock not sufficient!.');

                session()->put('cart', $cart);
            }else{
                if (isset($cart[$product->id])) {
                    $qty = $cart[$product->id]['quantity'] + 1;
                    $cart[$product->id]['quantity']++;
                    $cart[$product->id]['amount'] == $cart[$product->id]['price'] * $qty;

                    if ($product->stock == 0) return back()->with('error', 'Stock not sufficient!.');

                    session()->put('cart', $cart);

                    request()->session()->flash('success', 'Product successfully added to cart.');
                    return back();
                }

                $cart[$product->id] = [
                    'product_id' => $product->id,
                    'price'      => $product->discount > 0 ? $after_discount : $product->price,
                    'quantity'   => $request->quantity,
                    'amount'     => (($product->discount > 0 ? $after_discount : $product->price) * $request->quantity),
                    'product'    => $product,
                    'attributes' => [],
                ];

                $attributes = [];
                foreach ($attrs as $key => $attr) {
                    $atr = $attr->code;
                    $attributes[$atr] = $request->$atr;
                }
                $cart[$product->id]['attributes'] = json_encode($attributes);

                // dd($cart);
                if ($product->stock == 0) return back()->with('error', 'Stock not sufficient!.');

                session()->put('cart', $cart);

            }
        }

        if(session()->has('coupon')){
            $code = session()->get('coupon')['code'];
            if ($coupon = checkPromoCode($code)) {
                session()->remove('coupon');
                if(Auth::guard()->check()){
                    $total_price = Cart::where('user_id', Auth::guard()->user()->id)->where('order_id', null)->sum('amount');
                } else {
                    $total_price = totalCartPrice();
                }

                session()->put('coupon', [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'value' => $coupon->discount($total_price)
                ]);
            }
        }

        request()->session()->flash('success', 'Product successfully added to cart.');
        return back();
    }

    public function cartDelete(Request $request)
    {
        if(Auth::guard()->check()){
            $cart = Cart::find($request->id);
            if ($cart) {
                $cart->delete();

                if(session()->has('coupon')){
                    $code = session()->get('coupon')['code'];
                 if ($coupon = checkPromoCode($code)) {
                     session()->remove('coupon');

                     $total_price = Cart::where('user_id', Auth::guard()->user()->id)->where('order_id', null)->sum('amount');

                     session()->put('coupon', [
                         'id' => $coupon->id,
                         'code' => $coupon->code,
                         'value' => $coupon->discount($total_price)
                     ]);
                 }
                }

                request()->session()->flash('success', 'Cart successfully removed');
                return back();
            }
        }else{
            $cart = session('cart');
            if(isset($cart[$request->id])){
                unset($cart[$request->id]);
                session()->put('cart', $cart);

               if(session()->has('coupon')){
                   $code = session()->get('coupon')['code'];
                if ($coupon = checkPromoCode($code)) {
                    session()->remove('coupon');

                    $total_price = totalCartPrice();

                    session()->put('coupon', [
                        'id' => $coupon->id,
                        'code' => $coupon->code,
                        'value' => $coupon->discount($total_price)
                    ]);
                }
               }

                request()->session()->flash('success', 'Cart successfully removed');
            }
            return back();
        }
        request()->session()->flash('error', 'Error please try again');
        return back();
    }

    public function cartUpdate(Request $request)
    {
        // dd($request->all());
        if ($request->quantity) {
            if(session()->has('coupon')){
                session()->remove('coupon');
            }
            $error = array();
            $success = '';
            // return $request->quant;
            if (Auth::guard()->check()) {
                foreach ($request->quantity as $k => $quant) {
                    // return $k;
                    $id = $request->qty_id[$k];

                    $cart = Cart::find($id);
                    if ($quant > 0 && $cart) {
                        if ($cart->product->stock < $quant) {
                            request()->session()->flash('error', 'Out of stock');
                            return back();
                        }
                        $cart->quantity = ($cart->product->stock > $quant) ? $quant  : $cart->product->stock;
                        // return $cart;

                        if ($cart->product->stock <= 0) continue;
                        $after_price = ($cart->product->price - ($cart->product->price * $cart->product->discount) / 100);
                        $cart->amount = $after_price * $quant;
                        // return $cart->price;
                        $cart->save();
                        $success = 'Cart successfully updated!';
                    } else {
                        $error[] = 'Cart Invalid!';
                    }
                }
            } else {
                $cart = session()->get('cart');

                if($cart){
                    for ($i=0; $i < count($cart); $i++) {
                        $id = $request->qty_id[$i];

                        if(isset($cart[$id]) && $request->quantity[$id] > 0){
                            $cart[$id]['quantity'] = $request->quantity[$id];
                            $cart[$id]['amount'] = $cart[$id]['price'] * $request->quantity[$id];

                            if ($cart[$id]['product']['stock'] < $cart[$id]['quantity'] || $cart[$id]['product']['stock'] <= 0) return back()->with('error', 'Stock not sufficient!.');

                            $success = 'Cart successfully updated!';
                            session()->put('cart', $cart);
                        } else {
                            $error[] = 'Cart Invalid!';
                        }
                    }
                }
            }
            return back()->with($error)->with('success', $success);
        } else {
            return back()->with('Cart Invalid!');
        }
    }

    // public function addToCart(Request $request){
    //     // return $request->all();
    //     if(Auth::check()){
    //         $qty=$request->quantity;
    //         $this->product=$this->product->find($request->pro_id);
    //         if($this->product->stock < $qty){
    //             return response(['status'=>false,'msg'=>'Out of stock','data'=>null]);
    //         }
    //         if(!$this->product){
    //             return response(['status'=>false,'msg'=>'Product not found','data'=>null]);
    //         }
    //         // $session_id=session('cart')['session_id'];
    //         // if(empty($session_id)){
    //         //     $session_id=Str::random(30);
    //         //     // dd($session_id);
    //         //     session()->put('session_id',$session_id);
    //         // }
    //         $current_item=array(
    //             'user_id'=>Auth::guard()->user()->id,
    //             'id'=>$this->product->id,
    //             // 'session_id'=>$session_id,
    //             'title'=>$this->product->title,
    //             'summary'=>$this->product->summary,
    //             'link'=>route('product-detail',$this->product->slug),
    //             'price'=>$this->product->price,
    //             'photo'=>$this->product->photo,
    //         );

    //         $price=$this->product->price;
    //         if($this->product->discount){
    //             $price=($price-($price*$this->product->discount)/100);
    //         }
    //         $current_item['price']=$price;

    //         $cart=session('cart') ? session('cart') : null;

    //         if($cart){
    //             // if anyone alreay order products
    //             $index=null;
    //             foreach($cart as $key=>$value){
    //                 if($value['id']==$this->product->id){
    //                     $index=$key;
    //                 break;
    //                 }
    //             }
    //             if($index!==null){
    //                 $cart[$index]['quantity']=$qty;
    //                 $cart[$index]['amount']=ceil($qty*$price);
    //                 if($cart[$index]['quantity']<=0){
    //                     unset($cart[$index]);
    //                 }
    //             }
    //             else{
    //                 $current_item['quantity']=$qty;
    //                 $current_item['amount']=ceil($qty*$price);
    //                 $cart[]=$current_item;
    //             }
    //         }
    //         else{
    //             $current_item['quantity']=$qty;
    //             $current_item['amount']=ceil($qty*$price);
    //             $cart[]=$current_item;
    //         }

    //         session()->put('cart',$cart);
    //         return response(['status'=>true,'msg'=>'Cart successfully updated','data'=>$cart]);
    //     }
    //     else{
    //         return response(['status'=>false,'msg'=>'You need to login first','data'=>null]);
    //     }
    // }

    // public function removeCart(Request $request){
    //     $index=$request->index;
    //     // return $index;
    //     $cart=session('cart');
    //     unset($cart[$index]);
    //     session()->put('cart',$cart);
    //     return redirect()->back()->with('success','Successfully remove item');
    // }

    public function checkout(Request $request)
    {
        $cities = City::where('status', 'Enabled')->get();
        $payments = Payment::all();
        return view('frontend.pages.checkout', compact('payments', 'cities'));
    }

    public function buyNow(Request $request)
    {
        if($request->slug || $request->productId){
            $product = Product::where('slug', $request->slug)->orWhere('id', $request->productId)->first();
        } else {
            request()->session()->flash('error', 'Invalid Products');
            return back();
        }

        $attrs = Attribute::all();

        if ($product->stock == 0) {
            return back()->with('error', 'Out of stock, You can add other products.');
        }
        // if (($request->quantity < 1) || empty($product)) {
        //     request()->session()->flash('error', 'Invalid Products');
        //     return back();
        // }

        // return $already_cart;

        $after_discount = ($product->price - ($product->price * $product->discount) / 100);

        if (Auth::guard()->check()) {
            $already_cart = Cart::where('user_id', Auth::guard()->user()->id)->where('product_id', $product->id)->first();
            if ($already_cart) {
                $already_cart->quantity = 1;
                // $already_cart->price = ($product->price * $request->quant) + $already_cart->price ;
                $already_cart->amount = ($already_cart->price * 1);

                if ($already_cart->product->stock == 0) return back()->with('error', 'Stock not sufficient!.');

                $already_cart->save();
            } else {

                $cart = new Cart;
                $cart->user_id = Auth::guard()->user()->id;
                $cart->product_id = $product->id;
                $cart->price = $product->discount > 0 ? $after_discount : $product->price;
                $cart->quantity = 1;
                $cart->amount = ($cart->price * 1);
                $cart->attributes = [];

                $attributes = [];
                foreach ($attrs as $key => $attr) {
                    $atr = $attr->code;
                    $attributes[$atr] = $request->$atr;
                }
                $cart->attributes = json_encode($attributes);

                if ($cart->product->stock == 0) return back()->with('error', 'Stock not sufficient!.');

                // return $cart;
                // dd($cart);
                $cart->save();
            }
        } else {
            $cart = session()->get('cart');
            if(!$cart){
                $cart = [
                    $product->id => [
                        'product_id' => $product->id,
                        'price'      => $product->discount > 0 ? $after_discount : $product->price,
                        'quantity'   => 1 ?? 1,
                        'amount'     => $product->discount > 0 ? $after_discount : ($product->price * 1),
                        'product'    => $product,
                        'attributes'    => [],
                    ]
                ];

                $attributes = [];
                foreach ($attrs as $key => $attr) {
                    $atr = $attr->code;
                    $attributes[$atr] = $request->$atr;
                }
                $cart[$product->id]['attributes'] = json_encode($attributes);


                // dd($cart);
                if ($product->stock == 0) return back()->with('error', 'Stock not sufficient!.');

                session()->put('cart', $cart);
            }else{
                if (isset($cart[$product->id])) {
                    $qty = $cart[$product->id]['quantity'] + 1;
                    $cart[$product->id]['quantity']++;
                    $cart[$product->id]['amount'] == $cart[$product->id]['price'] * $qty;

                    if ($product->stock == 0) return back()->with('error', 'Stock not sufficient!.');

                    session()->put('cart', $cart);

                    return redirect()->route('backend.checkout');
                }

                $cart[$product->id] = [
                    'product_id' => $product->id,
                    'price'      => $product->discount > 0 ? $after_discount : $product->price,
                    'quantity'   => 1,
                    'amount'     => $product->discount > 0 ? $after_discount : ($product->price * 1),
                    'product'    => $product,
                    'attributes' => [],
                ];

                $attributes = [];
                foreach ($attrs as $key => $attr) {
                    $atr = $attr->code;
                    $attributes[$atr] = $request->$atr;
                }
                $cart[$product->id]['attributes'] = json_encode($attributes);

                // dd($cart);
                if ($product->stock == 0) return back()->with('error', 'Stock not sufficient!.');

                session()->put('cart', $cart);

            }
        }

        if(session()->has('coupon')){
            $code = session()->get('coupon')['code'];
            if ($coupon = checkPromoCode($code)) {
                session()->remove('coupon');
                if(Auth::guard()->check()){
                    $total_price = Cart::where('user_id', Auth::guard()->user()->id)->where('order_id', null)->sum('amount');
                } else {
                    $total_price = totalCartPrice();
                }

                session()->put('coupon', [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'value' => $coupon->discount($total_price)
                ]);
            }
        }

        return redirect()->route('backend.checkout');
    }
}
