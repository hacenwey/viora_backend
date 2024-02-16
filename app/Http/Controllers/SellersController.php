<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SellerTransaction;
use App\Models\SellersOrder;


use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class SellersController extends Controller
{
 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::where('type', '=', 'SELLER')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            if ($request->search) {
                $users = User::where('type', '=', 'SELLER')->where('phone_number', 'like', '%' . $request->search . '%')
                ->orWhere('name', 'like', '%' . $request->search . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            }
            foreach ($users as $user) {
                $transactions = SellerTransaction::where('seller_id', $user->id)->orderBy('id', 'DESC')->get();

                $totalGain = $transactions->where('type', 'IN')->sum('solde') - $transactions->where('type', 'OUT')->sum('solde');
                $user->solde = $totalGain;
            }

        return view('backend.sellers.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all()->pluck('title', 'id');
        $permissions = Permission::all()->pluck('title', 'id');
        return view('backend.users.create', compact('roles', 'permissions'));
    }

    public function filter_by_status(Request $request)
    {

        $status = $request->status;
        if ($status === 'All') {
            $users = User::where('type', '=', 'SELLER')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        } else {

                $users = User::where('type', '=', 'SELLER')->where('status',$status)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('backend.sellers.index')->with('users', $users);
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
                'phone_number' => 'string|required|unique:users',
                'email' => 'string|unique:users',
                'password' => 'string|required',
                'roles' => 'required|array',
                'status' => 'required|in:active,inactive',
                'photo' => 'nullable|string',
            ]
        );
        // dd($request->all());
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        // dd($data);
        $status = User::create($data);
        $status->roles()->sync($request->input('roles', []));
        $status->permissions()->sync($request->input('permissions', []));
        // dd($status);
        if ($status) {
            request()->session()->flash('success', 'Successfully added user');
        } else {
            request()->session()->flash('error', 'Error occurred while adding user');
        }
        return redirect()->route('backend.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $seller)
    {

        $user = $seller;
         $transactions = SellerTransaction::where('seller_id', $user->id)->orderBy('id', 'DESC')->get();

        $totalGain =  $transactions->where('type', 'IN')->sum('solde') -  $transactions->where('type', 'OUT')->sum('solde');
        $user->solde = $totalGain;
        $user->order_delivered = SellersOrder::where('seller_id', $user->id)
        ->where('status', 'delivered')
        ->count();

        $user->order_in_delivered = SellersOrder::where('seller_id', $user->id)
        ->where('status', '!=', 'delivered')
        ->count();
        $transactions = SellerTransaction::where('seller_id', $user->id)->orderBy('id', 'DESC')->paginate(10);
        return view('backend.sellers.edit', compact('user','transactions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $seller)
    {
        $this->validate($request, [
            'status' => 'required|in:active,inactive',
        ]);
    
        $transactions = SellerTransaction::where('seller_id', $seller->id)->orderByDesc('id')->get();
    
        $totalGain = $transactions->where('type', 'IN')->sum('solde') - $transactions->where('type', 'OUT')->sum('solde');
    
        if (!is_null($request->avance) && abs($request->avance) <= $totalGain) {
            SellerTransaction::create([
                'solde' => $request->avance,
                'seller_id' => $seller->id,
                'type' => 'OUT',
            ]);
        }
    
        if ($request->status == 'active' && $seller->status == 'inactive') {
            SellerTransaction::create([
                'solde' => 100,
                'seller_id' => $seller->id,
                'type' => 'IN',
            ]);
        }
    
        $seller->update($request->all());
    
        $user = $seller;
        $user->solde = $totalGain;
        $user->order_delivered = SellersOrder::where('seller_id', $user->id)->where('status', 'delivered')->count();
        $user->order_in_delivered = SellersOrder::where('seller_id', $user->id)->where('status', '!=', 'delivered')->count();
    

         if(!is_null($request->avance) && abs($request->avance) > $totalGain){
            session()->flash('error', 'The advance amount exceeds the total gain. Please enter a valid advance amount.');
        }else{
            session()->flash('success', 'Successfully updated');
        }
    
        $transactions = SellerTransaction::where('seller_id', $user->id)->orderByDesc('id')->paginate(10);
    
        return view('backend.sellers.edit', compact('user', 'transactions'));
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
            request()->session()->flash('error', 'There is an error while deleting users');
        }
        return redirect()->route('users.index');
    }
}
