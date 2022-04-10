<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    protected $product = null;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function wishlist(Request $request)
    {
        // dd($request->all());
        if (empty($request->slug)) {
            request()->session()->flash('error', 'Invalid Products');
            return back();
        }
        $product = Product::where('slug', $request->slug)->first();
        // return $product;
        if (empty($product)) {
            request()->session()->flash('error', 'Invalid Products');
            return back();
        }

        $already_wishlist = Wishlist::where('user_id', Auth::guard()->user()->id)->where('product_id', $product->id)->first();
        // return $already_wishlist;
        if ($already_wishlist) {
            request()->session()->flash('error', 'You already placed in wishlist');
            return back();
        } else {

            $wishlist = new Wishlist;
            $wishlist->user_id = Auth::guard()->user()->id;
            $wishlist->product_id = $product->id;
            $wishlist->save();
        }
        request()->session()->flash('success', 'Product successfully added to wishlist');
        return back();
    }

    public function wishlistDelete(Request $request)
    {
        $wishlist = Wishlist::find($request->id);
        if ($wishlist) {
            $wishlist->delete();
            request()->session()->flash('success', 'Wishlist successfully removed');
            return back();
        }
        request()->session()->flash('error', 'Error please try again');
        return back();
    }
}
