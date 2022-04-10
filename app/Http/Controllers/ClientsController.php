<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cls = getClients();

        $clients = $cls->paginate(10);

        return view('backend.clients.index')->with('clients', $clients);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all()->pluck('title', 'id');
        return view('backend.clients.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'string|required|max:30',
                'first_name' => 'string|required|max:30',
                'phone_number' => 'string|required|unique:users',
                'email' => 'string|unique:users',
                'password' => 'string|required',
                'roles' => 'required|array',
                'status' => 'required|in:active,inactive',
                'photo' => 'nullable|string',
            ]
        );

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        $status = User::create($data);
        $status->roles()->sync($request->input('roles', []));

        if ($status) {
            request()->session()->flash('success', 'Successfully added user');
        } else {
            request()->session()->flash('error', 'Error occurred while adding user');
        }
        return redirect()->route('backend.clients.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        $client = User::where('id', $request->id)->where('phone_number', $request->phone)->first();
        if($client){
            $client->load(['orders']);

            $products = Order::where('user_id', $client->id)
                ->leftJoin('order_products','orders.id','=','order_products.order_id')
                ->leftJoin('products','products.id','=','order_products.product_id')
                ->selectRaw('products.*, COALESCE(sum(order_products.quantity),0) total')
                ->groupBy('products.id')
                ->orderBy('total','desc')
                ->take(15)
                ->get();
        }else{
            $client = Order::where('phone', $request->phone)->first();
            $products = Order::where('phone', $request->phone)
                ->leftJoin('order_products','orders.id','=','order_products.order_id')
                ->leftJoin('products','products.id','=','order_products.product_id')
                ->selectRaw('products.*, COALESCE(sum(order_products.quantity),0) total')
                ->groupBy('products.id')
                ->orderBy('total','desc')
                ->take(15)
                ->get();
        }
        // dd($products);

        return view('backend.clients.profile', compact('client', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $client)
    {
        $roles = Role::all()->pluck('title', 'id');
        $client->load('roles');

        return view('backend.clients.edit', compact('client', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $client)
    {
        $this->validate(
            $request,
            [
                'name' => 'string|required|max:30',
                'email' => 'unique:users,email,' . request()->route('user')->id,
                'phone_number' => 'required|unique:users,phone_number,' . request()->route('user')->id,
                'roles.*' => 'integer',
                'roles' => 'required|array',
                'status' => 'required|in:active,inactive',
                'photo' => 'nullable|string',
            ]
        );

        $client->update($request->all());
        $client->roles()->sync($request->input('roles', []));

        request()->session()->flash('success', 'Successfully updated');

        return redirect()->route('backend.clients.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = User::findorFail($id);
        $status = $delete->delete();
        if ($status) {
            request()->session()->flash('success', 'User Successfully deleted');
        } else {
            request()->session()->flash('error', 'There is an error while deleting clients');
        }
        return redirect()->route('clients.index');
    }
}
