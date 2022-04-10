<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Currency;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Models\User;
use Shipu\Themevel\Facades\Theme;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use MattDaneshvar\Survey\Models\Survey;
use Spatie\Activitylog\Models\Activity;

class AdminController extends Controller
{
    private $theme;


    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('theme:'.setting('theme_name'));
    }

    public function index()
    {
        $data = User::select(\DB::raw("COUNT(*) as count"), \DB::raw("DAYNAME(created_at) as day_name"), \DB::raw("DAY(created_at) as day"))
            ->where('created_at', '>', Carbon::today()->subDay(6))
            ->groupBy('day_name', 'day')
            ->orderBy('day')
            ->get();
        $array[] = ['Name', 'Number'];
        foreach ($data as $key => $value) {
            $array[++$key] = [$value->day_name, $value->count];
        }
        //  return $data;
        return view('backend.index')->with('users', json_encode($array));
    }

    public function profile()
    {
        $profile = Auth::guard()->user();
        $roles = Role::all()->pluck('title', 'id');
        $profile->load('roles');
        return view('backend.users.profile', compact('profile', 'roles'));
    }

    public function profileUpdate(Request $request, $id)
    {
        // return $request->all();
        $user = User::findOrFail($id);
        $data = $request->all();
        $status = $user->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', 'Successfully updated your profile');
        } else {
            request()->session()->flash('error', 'Please try again!');
        }
        return redirect()->back();
    }

    public function settings()
    {
        // dd(setting('app_name'));
        // setMailConfig();
        // dd(config('app.url'));
        $currencies = Currency::where('status', 1)->pluck('name', 'code');
        $surveys = Survey::pluck('name', 'id');
        return view('backend.setting', compact('currencies', 'surveys'));
    }

    public function settingsUpdate(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'app_name' => 'sometimes',
            'short_des' => 'sometimes|string',
            'description' => '',
            'logo' => 'sometimes',
            'address' => 'sometimes|string',
            'email' => 'sometimes|email',
            'phone' => 'sometimes|string',
        ]);

        if($request->input('app_url')){
            settings()->set('app_url', $request->app_url);
        }
        if($request->input('app_name')){
            settings()->set('app_name', $request->app_name);
        }
        if($request->input('description')){
            settings()->set('description', $request->description);
        }
        if($request->input('short_des')){
            settings()->set('short_des', $request->short_des);
        }
        if($request->input('logo')){
            settings()->set('logo', $request->logo);
        }
        if($request->input('favicon')){
            settings()->set('favicon', $request->favicon);
        }
        if($request->input('signature')){
            settings()->set('signature', $request->signature);
        }
        if($request->input('address')){
            settings()->set('address', $request->address);
        }
        if($request->input('email')){
            settings()->set('email', $request->email);
        }
        if($request->input('phone')){
            settings()->set('phone', $request->phone);
        }
        if($request->input('facebook')){
            settings()->set('facebook', $request->facebook);
        }
        if($request->input('twitter')){
            settings()->set('twitter', $request->twitter);
        }
        if($request->input('snapchat')){
            settings()->set('snapchat', $request->snapchat);
        }
        if($request->input('whatsapp')){
            settings()->set('whatsapp', $request->whatsapp);
        }
        if($request->input('currency')){
            settings()->set('currency_code', $request->currency);
        }
        if($request->input('under_value_section')){
            settings()->set('under_value_section', $request->under_value_section);
        }
        if($request->input('middle_background')){
            settings()->set('middle_background', $request->middle_background);
        }
        if($request->input('middle_section_content')){
            settings()->set('middle_section_content', $request->middle_section_content);
        }
        if($request->input('newsletter_title')){
            settings()->set('newsletter_title', $request->newsletter_title);
        }
        if($request->input('newsletter_desc')){
            settings()->set('newsletter_desc', $request->newsletter_desc);
        }
        if($request->input('copyrights')){
            settings()->set('copyrights', $request->copyrights);
        }
        if($request->input('maps')){
            settings()->set('maps', $request->maps);
        }
        if($request->input('after_order_survey')){
            settings()->set('after_order_survey', $request->after_order_survey);
        }
        if($request->input('mail_driver')){
            settings()->set('mail_driver', $request->mail_driver);
        }
        if($request->input('mail_host')){
            settings()->set('mail_host', $request->mail_host);
        }
        if($request->input('mail_port')){
            settings()->set('mail_port', $request->mail_port);
        }
        if($request->input('mail_encryption')){
            settings()->set('mail_encryption', $request->mail_encryption);
        }
        if($request->input('mail_username')){
            settings()->set('mail_username', $request->mail_username);
        }
        if($request->input('mail_password')){
            settings()->set('mail_password', $request->mail_password);
        }
        if($request->input('mail_from_address')){
            settings()->set('mail_from_address', $request->mail_from_address);
        }
        if($request->input('mail_from_name')){
            settings()->set('mail_from_name', $request->mail_from_name);
        }

        if($request->input('sms_driver')){
            settings()->set('sms_driver', $request->sms_driver);
        }
        if($request->input('twilio_sid')){
            settings()->set('twilio_sid', $request->twilio_sid);
        }
        if($request->input('twilio_auth_token')){
            settings()->set('twilio_auth_token', $request->twilio_auth_token);
        }
        if($request->input('twilio_number')){
            settings()->set('twilio_number', $request->twilio_number);
        }
        if($request->input('twilio_username')){
            settings()->set('twilio_username', $request->twilio_username);
        }
        if($request->input('twilio_password')){
            settings()->set('twilio_password', $request->twilio_password);
        }
        if($request->input('twilio_alpha_sender')){
            settings()->set('twilio_alpha_sender', $request->twilio_alpha_sender);
        }
        if($request->input('twilio_sms_service_sid')){
            settings()->set('twilio_sms_service_sid', $request->twilio_sms_service_sid);
        }

        // if($request->input('theme_name')){
        //     settings()->set('theme_name', $request->theme_name);
        //     tenant()->update([
        //         'theme' => $request->theme_name
        //     ]);
        //     Theme::set($request->theme_name);
        // }

        request()->session()->flash('success', 'Setting successfully updated');

        return redirect()->back();
    }

    public function changePassword()
    {
        return view('backend.layouts.changePassword');
    }
    public function changPasswordStore(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('admin')->with('success', 'Password successfully changed');
    }

    // Pie chart
    public function userPieChart(Request $request)
    {
        // dd($request->all());
        $data = User::select(\DB::raw("COUNT(*) as count"), \DB::raw("DAYNAME(created_at) as day_name"), \DB::raw("DAY(created_at) as day"))
            ->where('created_at', '>', Carbon::today()->subDay(6))
            ->groupBy('day_name', 'day')
            ->orderBy('day')
            ->get();
        $array[] = ['Name', 'Number'];
        foreach ($data as $key => $value) {
            $array[++$key] = [$value->day_name, $value->count];
        }
        //  return $data;
        return view('backend.index')->with('course', json_encode($array));
    }

    // public function activity(){
    //     return Activity::all();
    //     $activity= Activity::all();
    //     return view('backend.layouts.activity')->with('activities',$activity);
    // }
}
