<?php

namespace App\Http\Controllers\Auth;


use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Database\Seeders\StoreSeeder;


class StoreLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout']]);
    }

    public function credentials(Request $request){
        return ['email'=>$request->email,'password'=>$request->password];
    }

    public function redirect($provider)
    {
        // dd($provider);
     return Socialite::driver($provider)->redirect();
    }

    public function Callback($provider)
    {
        $userSocial =   Socialite::driver($provider)->stateless()->user();
        $users      =   User::where(['email' => $userSocial->getEmail()])->first();
        // dd($users);
        if($users){
            Auth::guard()->login($users);
            return redirect('/')->with('success','You are login from '.$provider);
        }else{
            $user = User::create([
                'name'          => $userSocial->getName(),
                'email'         => $userSocial->getEmail(),
                // 'image'         => $userSocial->getAvatar(),
                'provider_id'   => $userSocial->getId(),
                'provider'      => $provider,
            ]);
         return redirect()->route('backend.home');
        }
    }

    public function showLoginForm()
    {
        return view('auth.o-login');
    }

    public function login(Request $request)
    {
        // Validate form data
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required|min:8'
        ]);

        try {
            // Attempt to log the user in
            // dd(Auth::guard()->user());
            if(Auth::guard()->attempt(['email' => $request->email, 'password' => $request->password], $request->remember))
            {
                return redirect()->intended(route('backend.dashboard'));
            }
        } catch (\Throwable $th) {

            return redirect()->back()->withInput($request->only('email','remember'));
        }

        // if unsuccessful
        request()->session()->flash('danger','Failed');
        return redirect()->back()->withInput($request->only('email','remember'));
    }

    public function logout()
    {
        Session::forget('user');
        Auth::guard()->logout();
        return redirect()->route('backend.home');
    }

    public function register(){
        return view('frontend.pages.register');
    }

    public function registerSubmit(Request $request){
        // return $request->all();
        $this->validate($request,[
            'name'=>'string|required|min:2',
            'email'=>'string|unique:users,email',
            'phone_number'=>'string|required|unique:users,phone_number',
            'password'=>'required|min:6|confirmed',
        ]);
        $data = $request->all();

        $user = $this->create($data);

        if($user){
            $role = Role::where('title', 'User')->first();
            $user->roles()->sync($role->id);
            request()->session()->flash('success','Successfully registered');
            return redirect()->route('backend.home');
        }
        else{
            request()->session()->flash('error','Please try again!');
            return back();
        }
    }

    public function create(array $data){
        return User::create([
            'name'=>$data['name'],
            'first_name'=>$data['first_name'],
            'last_name'=>$data['last_name'],
            'email'=>$data['email'],
            'phone_number'=>$data['phone_number'],
            'password'=>Hash::make($data['password']),
            'status'=>'active'
        ]);
    }

    // Reset password
    public function showResetForm(){
        return view('auth.passwords.o-reset');
    }
}
