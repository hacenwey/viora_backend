<?php

namespace App\Http\Controllers\Api\V1\Store;

use DB;
use Helper;
use Validator;
use App\Models\Cart;
use App\Models\Role;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Shipping;
use App\Models\Attribute;
use App\Models\User;
use App\Models\OrderProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Notifications\StatusNotification;
use Illuminate\Support\Facades\Notification;
use PDF;
use Carbon\Carbon;
class ClientApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function placeOrder(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
        ]);

        if($validation->fails()){
            return response()->json([
                'errors' => $validation->errors()
            ]);
        } else {

            $order = new Order();
            $order_data = $request->all();

            try {
                $user = User::findOrFail($request['user_id']);
                $order_data['user_id'] = $user->id;
            }catch (\Exception $e){
                $order_data['user_id'] = NULL;
            }

            $order_data['shipping_id'] = $request->shipping_id;
            $order_data['urgent'] = $request->urgent == true ? '1' : '0';
//            $shipping = Shipping::where('id', $order_data['shipping_id'])->pluck('price');
            $order_data['sub_total'] = $request['sub_total'];
            $order_data['total_amount'] = $request['total'];
            $order_data['coupon'] = $request->coupon ? $request->coupon : NULL;

            if ($request->coupon) {
                $order_data['sub_total'] = $request['sub_total'] - $request->coupon;
                $order_data['total_amount'] = $request['total'] - $request->coupon;
            }

            $order_data['status'] = "new";
            if(is_null($request->payment_method)){
             $order_data['payment_method'] = 'cod';
            }
            $order_data['payment_status'] = 'Unpaid';

            $order->fill($order_data);

            $status = $order->save();

            try{
                if($status){
                    if($request->user_id != null){
                        foreach ($request['items'] as $key => $prod) {
                            $attributes = [];
                            foreach ($prod['attributes'] as $att){
                                if($att['selected']){
                                    $attribute = Attribute::findOrFail($att["attribute_id"]);
                                    $attributes[$attribute->code] = $att['value'];
                                }
                            }
                            $order->products()->save(
                                new OrderProduct([
                                    'order_id'      => $order->id,
                                    'product_id'    => $prod['id'],
                                    'price'         => $prod['price'],
                                    'quantity'      => $prod['cartQuantity'],
                                    'sub_total'     => $prod['price'] * $prod['cartQuantity'],
                                    'attributes'    => json_encode($attributes),
                                ])
                            );
                        }
                    }else{
                        foreach ($request['items'] as $key => $prod) {
                            $attributes = [];
                            foreach ($prod['attributes'] as $att){
                                if($att['selected']){
                                    $attribute = Attribute::findOrFail($att["attribute_id"]);
                                    $attributes[$attribute->code] = $att['value'];
                                }
                            }
                            $order->products()->save(
                                new OrderProduct([
                                    'order_id'      => $order->id,
                                    'product_id'    => $prod['id'],
                                    'price'         => $prod['price'],
                                    'quantity'      => $prod['cartQuantity'],
                                    'sub_total'     => $prod['price'] * $prod['cartQuantity'],
                                    'attributes'    => json_encode($attributes),
                                ])
                            );
                        }
                    }
                }
            }catch(\Throwable $th){

            }

            try{
                if ($order){
                    if($request->user_id != null){
                        $carts = Cart::where('user_id', $user->id)->delete();
                    }
                    $users = User::with(['permissions'])
                                        ->whereHas('permissions', function($q) {
                                            $q->where('title', 'can_receive_orders_notifications');
                                        })
                                        ->get();
                    $details = [
                        'title' => trans('global.new_order_arrived'),
                        'reference' => '#'.$order->reference,
                        'actionURL' => route('backend.order.show', $order->id),
                        'fas' => 'fa-file-alt'
                    ];
                    $pdf = PDF::loadview('backend.order.pdf', compact('order'));
    
                    $path = public_path();
                    $fileName =  'Facture #'.$order->reference . '.' . 'pdf' ;
                    $pdf->save($path . '/' . $fileName);
    
                    if($users->count() > 0){
                        setMailConfig();
                        Notification::send($users, new StatusNotification($details));
                    }
                }
            }catch(\Throwable $th){

            }

            try {
                File::delete('Facture '.$details['reference'].".pdf");
            } catch (\Throwable $th) {

            }

           
            try {
                if(isset($user)){
                    Cart::where('user_id', $user->id)->delete();
                }            
            } catch (\Throwable $th) {

            }

            return response()->json([
                'success' => true,
                'order' => $order
            ]);
        }
    }

    public function couponStore(Request $request)
    {
        $coupon = Coupon::where(DB::raw('BINARY `code`'), $request->code)->where('status', 'active')->first();

        if (Carbon::now()->gte($coupon->expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code, Please try again'
            ]);
        } else {
            return response()->json([
                'success' =>  true,
                 'coupon' => $coupon
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
                'message' => 'Orders not found, Please try again'
            ]);
        }
        if($orders){
            return response()->json([
               'success' =>  true,
                'orders' => $orders
            ]);
        }
    }

}
