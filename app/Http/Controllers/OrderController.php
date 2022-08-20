<?php

namespace App\Http\Controllers;

use PDF;
use Mpdf\Mpdf;
use Helper;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Cart;
use App\Models\City;
use App\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Imports\OrdersImport;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipping;
use Illuminate\Support\Facades\DB;
use App\Models\OrderProduct;
use App\Http\Controllers\Controller;
use App\Imports\OrderProductsImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use MattDaneshvar\Survey\Models\Survey;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\StatusNotification;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\View;

class OrderController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = Order::query();
        $cts = City::all()->pluck('name');

        $cities = $cts->implode(',');
        // dd($cts);

        if($request->search){
            $orders = $orders->where('phone', 'like', '%'.$request->search.'%')
                            ->orWhere('reference', 'like', '%'.$request->search.'%')
                            ->orWhere('town_city', 'like', '%'.$request->search.'%')
                            ->orWhere('status', 'like', '%'.$request->search.'%')
                            ->orderBy('id', 'DESC')
                            ->paginate(10);
        }else{
            $orders = $orders->orderBy('id', 'DESC')->paginate(10);
        }
        return view('backend.order.index', compact('orders', 'cities'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function placeOrder(Request $request)
    {

        $this->validate($request, [
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'address1' => 'string|required',
            'coupon' => 'nullable|numeric',
            'phone' => 'required',
            'email' => ''
        ]);
                try{
                    $phone = PhoneNumber::make($request->phone, 'MR')->formatInternational();
                    $phoneCC = PhoneNumber::make($request->phone, 'MR')->formatForMobileDialingInCountry('MR');
                    $phone = preg_replace('/\s+/', '', $phone);
        
                }catch(Exception $e){

                    request()->session()->flash('error', 'veuillez verifier votre numéro de téléphone');
                    return back();

                }
       

        if (cartCount() < 1) {
            request()->session()->flash('error', 'Cart is Empty !');
            return back();
        }

        $order_data = $request->all();

        $client = User::where('phone_number', $phone)->orWhere('phone_number', $phoneCC)->orWhere('email', $request->email)->first();

        if($client){
            $order_data['user_id'] = $client->id;
        }else{
            $client = new User();
            $client->name = Str::slug($request->first_name);
            $client->first_name = $request->first_name;
            $client->last_name = $request->last_name;
            $client->email = $request->email;
            $client->phone_number = $phone;

            $client->save();

            $client_role = Role::where('title', 'Client')->first();
            $client->roles()->attach($client_role);

            $order_data['user_id'] = $client->id;
        }

        $order = new Order();
        $order_data['phone'] = $phone;
        $order_data['shipping_id'] = $request->shipping != 0 ? $request->shipping : NULL;
        $order_data['urgent'] = $request->urgent ? '1' : '0';
        $shipping = $request->urgent ? Shipping::where('id', $order_data['shipping_id'])->pluck('urgent_price') : Shipping::where('id', $order_data['shipping_id'])->pluck('price');
        $order_data['sub_total'] = totalCartPrice();
        if (session('coupon')) {
            $order_data['coupon'] = session('coupon')['value'];
            $code = session()->get('coupon')['code'];
            if ($coupon = checkPromoCode($code)) {
                $coupon->quantity -= 1;
                $coupon->save();
            }
        }
        if ($request->shipping) {
            if (session('coupon')) {
                $order_data['total_amount'] = totalCartPrice() + $shipping[0] - session('coupon')['value'];
            } else {
                $order_data['total_amount'] = totalCartPrice() + $shipping[0];
            }
        } else {
            if (session('coupon')) {
                $order_data['total_amount'] = totalCartPrice() - session('coupon')['value'];
            } else {
                $order_data['total_amount'] = totalCartPrice();
            }
        }
        // return $order_data['total_amount'];
        $order_data['status'] = "new";
        if($request->payment_method == "0"){
            $order_data['payment_method'] = "cod";
            $order_data['payment_status'] = 'Unpaid';
        }else{
            $payment = Payment::find($request->payment_method);
            if($payment){
                $order_data['payment_method'] = $payment->name;
                $order_data['payment_status'] = 'Pending';
            }
        }

        $order->fill($order_data);

        $status = $order->save();

        if($status){
            foreach (getAllProductFromCart() as $key => $prod) {
                $order->products()->save(
                    new OrderProduct([
                        'order_id'      => $order->id,
                        'product_id'    => $prod['product_id'],
                        'price'         => $prod['price'],
                        'quantity'      => $prod['quantity'],
                        'sub_total'     => $prod['amount'],
                        'attributes'    => json_encode($prod['attributes']),
                    ])
                );
            }
        }

        session()->forget('cart');
        session()->forget('coupon');
        if(Auth::guard()->check()){
            Cart::where('user_id', Auth::guard()->user()->id)->delete();
        }

        if($request->payment_method != "0"){
            $payment = Payment::find($request->payment_method);
            if($payment && $payment->has_api == 1 && $payment->api_key != null){
                $amount = $order->total_amount * 100;
                $url = "/";
                return redirect()->away($url);
            }
        }

        request()->session()->flash('success', 'Your product successfully placed in order');
        return redirect()->route('backend.order-success', ['order' => $order]);
    }

    public function success(Request $request, Order $order)
    {
        // dd($order->products);
        return view('frontend.pages.order-success', compact('order'));
    }

    public function afterOrderSurvey(Request $request, Order $order)
    {
        return view('frontend.pages.after-order-survey', compact('order'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $products = [];

        array_push($products, $order->products);
        // $order->load('products');
        // dd($order);
        return view('backend.order.show')->with('order', $order);
    }

    public function import()
    {
        (new OrdersImport)->queue(request()->file('file'), null, \Maatwebsite\Excel\Excel::XLSX);

        return redirect()->route('backend.order.index');
    }

    public function importOrderProducts()
    {
        (new OrderProductsImport)->queue(request()->file('file'), null, \Maatwebsite\Excel\Excel::XLSX);

        return redirect()->route('backend.order.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $order->load('products');
        $clients = User::with(['roles'])
                    ->whereHas('roles', function($q) {
                        $q->where('title', 'Client');
                    })
                    ->get();

        $shipping = Shipping::pluck('type', 'id');
        $products = Product::all();

        $order_products = [];
        foreach ($order->products as $key => $item) {
            array_push($order_products, $item->product);
        }

        return view('backend.order.edit', compact('order', 'clients', 'shipping', 'products', 'order_products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $this->validate($request, [
            'status' => 'required|in:new,process,delivered,cancel'
        ]);
        $data = $request->all();
        // return $request->status;
        if ($request->status == 'delivered') {
            foreach ($order->cart as $cart) {
                $product = $cart->product;
                if($product->stock > 0){
                    $product->stock -= $cart->quantity;
                }
                $product->save();
            }
        }
        $status = $order->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', 'Successfully updated order');
        } else {
            request()->session()->flash('error', 'Error while updating order');
        }
        return redirect()->route('backend.order.index');
    }

    /**
     * Update the specified item in order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateItem(Request $request, Order $order, Product $product)
    {
        $item = OrderProduct::where('order_id', $order->id)->where('product_id', $product->id)->first();

        if($item && $request->quantity != $item->quantity){
            $item->quantity = $request->quantity;
            $item->sub_total = $request->quantity * $item->price;
        }

        $status = $item->save();

        if ($status) {
            $sub_total = $order->products()->sum('sub_total');
            $total = $sub_total;
            $order->sub_total = $sub_total;
            if($order->delivery_price){
                $total += $order->delivery_price;
            }
            if($order->coupon){
                $total -= $order->coupon;
            }
            $order->total_amount = $total;
            $order->save();
            // dd($sub_total);
            request()->session()->flash('success', 'Successfully updated order');
        } else {
            request()->session()->flash('error', 'Error while updating order');
        }
        return redirect()->back();
    }

    public function addItem(Request $request, Order $order)
    {
        $product = Product::findOrFail($request->product_id);
        if($product){
            $status = $order->products()->save(
                new OrderProduct([
                    'order_id'      => $order->id,
                    'product_id'    => $product->id,
                    'price'         => $product->price,
                    'quantity'      => $request->quantity,
                    'sub_total'     => $request->quantity * $product->price,
                    'attributes'    => "{\"size\": null, \"color\": null}",
                ])
            );
            if($status){
                $sub_total = $order->products()->sum('sub_total');
                $total = $sub_total;
                $order->sub_total = $sub_total;
                if($order->delivery_price){
                    $total += $order->delivery_price;
                }
                if($order->coupon){
                    $total -= $order->coupon;
                }
                $order->total_amount = $total;
                $order->save();
                request()->session()->flash('success', 'Successfully updated order');
            } else {
                request()->session()->flash('error', 'Error while updating order');
            }
        } else {
            request()->session()->flash('error', 'Product not found!');
        }
        return redirect()->back();
    }

    /**
     * Remove specified item in order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeItem(Request $request, Order $order, Product $product)
    {
        $item = OrderProduct::where('order_id', $order->id)->where('product_id', $product->id)->first();

        $status = $item->delete();

        if ($status) {
            $sub_total = $order->products()->sum('sub_total');
            $total = $sub_total;
            $order->sub_total = $sub_total;
            if($order->delivery_price){
                $total += $order->delivery_price;
            }
            if($order->coupon){
                $total -= $order->coupon;
            }
            $order->total_amount = $total;
            $order->save();
            request()->session()->flash('success', 'Item Successfully removed!');
        } else {
            request()->session()->flash('error', 'Error while removing product');
        }
        return redirect()->back();
    }



    public function statusChange(Request $request)
    {
        $ids = $request->ids;
        $status = $request->status;

        // return response()->json([
        //     'ids' => $ids,
        //     'st' => $status,
        // ]);

        if($status == 'delete'){
            Order::whereIn('id', $ids)->delete();
            return response()->json([
                'success' => true
            ]);
        }

        $stat = Order::whereIn('id', $ids)->update([
            'status' => $status
        ]);

        if ($stat) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        if ($order) {
            $status = $order->delete();
            if ($status) {
                request()->session()->flash('success', 'Order Successfully deleted');
            } else {
                request()->session()->flash('error', 'Order can not deleted');
            }
            return redirect()->route('backend.order.index');
        } else {
            request()->session()->flash('error', 'Order can not found');
            return redirect()->back();
        }
    }

    public function orderTrack()
    {
        return view('frontend.pages.order-track');
    }

    public function productTrackOrder(Request $request)
    {
        // return $request->all();
        $order = Order::where('user_id', Auth::guard()->user()->id)->where('reference', $request->reference)->first();
        if ($order) {
            if ($order->status == "new") {
                request()->session()->flash('success', 'Your order has been placed. please wait.');
                return redirect()->route('backend.home');
            } elseif ($order->status == "process") {
                request()->session()->flash('success', 'Your order is under processing please wait.');
                return redirect()->route('backend.home');
            } elseif ($order->status == "delivered") {
                request()->session()->flash('success', 'Your order is successfully delivered.');
                return redirect()->route('backend.home');
            } else {
                request()->session()->flash('error', 'Your order canceled. please try again');
                return redirect()->route('backend.home');
            }
        } else {
            request()->session()->flash('error', 'Invalid order numer please try again');
            return back();
        }
    }

    // PDF generate
    public function pdf(Request $request)
    {
        $order = Order::getAllOrder($request->id);
        // return view('backend.order.pdf', compact('order'));
        // return $order;
        $file_name = $order->reference . '-' . $order->first_name . '.pdf';
        // return $file_name;
        $pdf = PDF::loadview('backend.order.pdf', compact('order'));
        return $pdf->setPaper('a4', 'portrait')->download($file_name);
    }
    // Multiple PDF generate
    public function multiPdf(Request $request)
    {
        set_time_limit(300);
        $mpdf = new Mpdf([
            'tempDir' => __DIR__ . '/tmp',
            'format' => 'A4',
                'orientation' => 'P',
          
        ]);
      
        // $mpdf->SetFooter('
        // <div class="float-right mt-5" style="margin-right: 50px;">
        //                 <p style="border-top:1px solid #b3b3b3;margin-right: -50px"></p>
        //                 <img src="{{ settings("signature") }}" alt="" width="150" style="margin-left: -50px">
        //             </div>');
        $file_name = Carbon::now()->format('d-m-Y h:m') . '.pdf';
        $orders = Order::whereIn('id', explode(',', $request->ids))->get();        
        $html = '';
        $html= view('backend.order.pdf', compact('orders',$orders));
        $mpdf->writeHtml($html);
    
       return $mpdf->Output($file_name, \Mpdf\Output\Destination::DOWNLOAD);
    }

    // BL PDF generate
    public function blPdf(Request $request)
    {
        set_time_limit(300);

        $mpdf = new Mpdf([
            'tempDir' => __DIR__ . '/tmp',
            'format' => 'A4',
                'orientation' => 'P',
          
        ]);
        $file_name = Carbon::now()->format('d-m-Y h:m') . '.pdf';
        $orders = Order::whereIn('id', explode(',', $request->ids))->get();


        $view = view('backend.order.blpdf', compact('orders'));
            // dd($request->ids);
        // return view('backend.order.blpdf', compact('orders'));
        $mpdf->writeHtml($view->render());
        // $pdf = PDF::loadHTML($view->render());
        // return $pdf->setPaper('a4', 'portrait')->download($file_name);
        return $mpdf->Output($file_name, \Mpdf\Output\Destination::DOWNLOAD);
    }

    // Income chart
    public function incomeChart(Request $request)
    {
        $year = \Carbon\Carbon::now()->year;
        // dd($year);
        $items = Order::with(['cart_info'])->whereYear('created_at', $year)->where('status', 'delivered')->get()
            ->groupBy(function ($d) {
                return \Carbon\Carbon::parse($d->created_at)->format('m');
            });
        // dd($items);
        $result = [];
        foreach ($items as $month => $item_collections) {
            foreach ($item_collections as $item) {
                $amount = $item->cart_info->sum('amount');
                // dd($amount);
                $m = intval($month);
                // return $m;
                isset($result[$m]) ? $result[$m] += $amount : $result[$m] = $amount;
            }
        }
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = date('F', mktime(0, 0, 0, $i, 1));
            $data[$monthName] = (!empty($result[$i])) ? number_format((float)($result[$i]), 2, '.', '') : 0.0;
        }
        return $data;
    }
}
