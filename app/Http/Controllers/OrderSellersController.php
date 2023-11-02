<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Order;
use App\Models\SellersOrder;

class OrderSellersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = SellersOrder::query();
        $cts = City::all()->pluck('name');

        $cities = $cts->implode(',');

        if ($request->search) {
            $orders = SellersOrder::with('seller')
            ->where(function ($query) use ($request) {
                $query->whereHas('seller', function ($subquery) use ($request) {
                    $subquery->where('phone_number', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%');
                })
                ->orWhere('reference', 'like', '%' . $request->search . '%');
            })
            ->orderBy('id', 'DESC')
            ->paginate(10);
        } else {
            $orders = $orders->orderBy('created_at', 'DESC')->paginate(10);
        }
        return view('backend.orderSellers.index', compact('orders', 'cities'));
    }


    public function filter_by_status(Request $request)
    {

        $status = $request->status;
        $cts = City::all()->pluck('name');
        $cities = $cts->implode(',');
        if ($status === 'All') {
            $orders = SellersOrder::orderBy('id', 'DESC')->paginate(10);

        } else {
            $orders = SellersOrder::where('status',$status)
                ->orderBy('id', 'DESC')
                ->paginate(10);
        }

        return view('backend.orderSellers.index', compact('orders', 'cities', 'status'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = SellersOrder::with('seller','products','sellersOrderProducts')->find($id);
        // return $order;
        return view('backend.orderSellers.show')->with('order', $order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
