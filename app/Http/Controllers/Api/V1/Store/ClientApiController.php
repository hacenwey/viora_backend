<?php

namespace App\Http\Controllers\Api\V1\Store;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\SellersOrder;
use App\Models\SellersOrderProduct;
use App\Models\Shipping;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;
use App\Models\Product;

class ClientApiController extends Controller
{
    public function placeOrder(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'errors' => $validation->errors(),
            ]);
        }
        $saleID = $request->saleId;
        $order = new Order();
        $sellersOrder = new SellersOrder();
        $order_data = $request->all();

        try {
            $user = User::findOrFail($request->input('user_id'));
            $order_data['user_id'] = $user->id;
        } catch (\Exception $e) {
            $order_data['user_id'] = null;
        }

        try {
            DB::beginTransaction();
            $discountcoupon = 0;

            if ($request->input('coupon')) {
                $discountcoupon = $request->input('coupon');
            }
            $ship = Shipping::find($request->shipping_id);
            $subTotal = $request['sub_total'];
            $totalAmount = ($subTotal - $discountcoupon) + $ship->price;

            $order_data['shipping_id'] = $request->shipping_id;
            $order_data['urgent'] = $request->urgent == true ? '1' : '0';
            $order_data['sub_total'] = $subTotal;
            $order_data['total_amount'] = $totalAmount;
            $order_data['coupon'] = $request->coupon ? $request->coupon : null;
            $order_data['shippingSelectdZonePrice'] = $request->shippingSelectdZonePrice;
            $order_data['shippingSelectdZone'] = $request->shippingSelectdZone;

            $order->fill($order_data);
            $order->save();

            $items = $request->input('items', []);
            \Log::info($items);
            $orderProducts = [];
            $sellersOrderProducts = [];

            foreach ($items as $key => $prod) {
                $price = $prod['discount'] > 0 ? $prod['price'] - ($prod['price'] * ($prod['discount'] / 100)) : $prod['price']; // Apply discount to product price
                $subTotal = $price * $prod['cartQuantity'];

                $orderProducts[] = new OrderProduct([
                    'order_id' => $order->id,
                    'product_id' => $prod['id'],
                    'price' => $price,
                    'quantity' => $prod['cartQuantity'],
                    'sub_total' => $subTotal,
                    'attributes' => json_encode($prod['attributes']),
                ]);

                $order->products()->saveMany($orderProducts);

               $productOrder = Product::find($prod['id']);
               if($productOrder && $productOrder->stock > 0){
               $stock = $productOrder->stock -  $prod['cartQuantity'];
               $productOrder->update(['stock' => $stock]);
               }
            }
            DB::commit();

            try {
                DB::beginTransaction();

                if (!is_null($saleID)) {
                    $sellersOrder->seller_id = $saleID;
                    $sellersOrder->order_id = $order->id;

                    $sellersOrder->save();
                }

                foreach ($items as $key => $prod) {
                    $price = $prod['discount'] > 0 ? $prod['price'] - ($prod['price'] * ($prod['discount'] / 100)) : $prod['price']; // Apply discount to product price
                    $subTotal = $price * $prod['cartQuantity'];

                    if (!is_null($saleID) && !is_null($prod['seller_id']) && $prod['seller_id'] === $saleID) {
                        $sellersOrderProducts[] = new SellersOrderProduct([
                            'sellers_order_id' => $sellersOrder->id,
                            'product_id' => $prod['id'],
                            'price' => $price,
                            'quantity' => $prod['cartQuantity'],
                            'sub_total' => $subTotal,
                            'commission' => $prod['commission'] ?? settings('commission_global'),
                            'gain' => is_null($prod['commission']) ? ($subTotal * settings('commission_global') / 100) : ($subTotal * $prod['commission'] / 100),
                        ]);
                    }
                }

                $sellersOrder->sellersOrderProducts()->saveMany($sellersOrderProducts);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();

                \Log::error($e->getMessage());
            }

        } catch (\Throwable $th) {
            DB::rollback();
            \Log::error('An error occurred while placing the order  ========> : ' . var_export($request->all(), 1));
            \Log::error($th->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while placing the order.',
            ]);
        }

        return response()->json([
            'success' => true,
            'order' => $order,
        ]);
    }

    public function couponStore(Request $request)
    {
        $coupon = Coupon::where(DB::raw('BINARY `code`'), $request->code)->where('status', 'active')->first();

        if (Carbon::now()->gte($coupon->expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code, Please try again',
            ]);
        } else {
            return response()->json([
                'success' => true,
                'coupon' => $coupon,
            ]);
        }
    }

    public function orderHistory(Request $request)
    {
        $user = $request->user();

        $orders = Order::where('user_id', $user->id)->with('products')->get();
        if (!$orders) {
            return response()->json([
                'success' => false,
                'message' => 'Orders not found, Please try again',
            ]);
        }
        if ($orders) {
            return response()->json([
                'success' => true,
                'orders' => $orders,
            ]);
        }
    }

}
