<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderProduct;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProductResource;

class PosController extends Controller
{


    public function index()
    {
        return view('backend.pos.index');
    }

    public function loadProducts(Request $request)
    {
        $products = Product::query();

        if($request->searchQuery){
            $products = $products->where('sku', 'LIKE', '%'.$request->searchQuery.'%')
                                    ->orWhere('title', 'LIKE', '%'.$request->searchQuery.'%')
                                    ->get();
        }

        return ProductResource::collection($products->paginate(20));
    }

    public function loadProduct(Request $request)
    {
        $product = Product::where('sku', $request->sku)->first();

        if($product){
            return response()->json([
                'success' => true,
                'data' => new ProductResource($product)
            ]);
        }

        return response()->json([
            'success' => false,
        ]);
    }

    public function loadCustomers(Request $request)
    {
        $customers = getClients();

        return $customers;
    }

    public function placeOrder(Request $request)
    {
        $customer = 0;
        if($request->customer != 0){
            $cust = User::find($request->customer);
            $customer = $cust->id;
            $order_data['first_name'] = $cust->first_name;
            $order_data['last_name'] = $cust->last_name;
            $order_data['phone'] = $cust->phone;
        }

        if (count($request->items) < 1) {
            return response()->json([
                'success' => false,
                'message'  =>  'Cart is empty!'
            ], 200);
        }

        $order = new Order();
        $order_data['sub_total'] = $request->total;
        $order_data['total_amount'] = $request->total;
        $order_data['status'] = "delivered";
        $order_data['payment_method'] = 'cod';
        $order_data['payment_status'] = 'Paid';
        $order_data['payment_status'] = 'Paid';
        $order_data['user_id'] = $customer;

        $order->fill($order_data);

        $status = $order->save();

        if($status){
            foreach ($request->items as $key => $prod) {
                $order->products()->save(
                    new OrderProduct([
                        'order_id'      => $order->id,
                        'product_id'    => $prod['id'],
                        'price'         => $prod['price'],
                        'quantity'      => $prod['quantity'],
                        'sub_total'     => $prod['totalPrice'],
                    ])
                );
            }
        }

        return response()->json([
            'success' => true,
            'order'  =>  $order
        ], 200);
    }
}
