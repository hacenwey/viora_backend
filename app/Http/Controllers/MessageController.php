<?php

namespace App\Http\Controllers;

use Twilio\Rest\Client;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Propaganistas\LaravelPhone\PhoneNumber;
use App\Services\SmsService;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::paginate(20);
        $clients = getClients();

        return view('backend.message.index', compact('messages', 'clients'));
    }
    public function messageFive()
    {
        $message = Message::whereNull('read_at')->limit(5)->get();
        return response()->json($message);
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
        $this->validate($request, [
            'name' => 'string|required|min:2',
            'email' => 'email|required',
            'message' => 'required|min:20|max:200',
            'subject' => 'string|required',
            'phone' => 'numeric|required'
        ]);
        // return $request->all();

        $message = Message::create($request->all());
        // return $message;
        $data = array();
        $data['url'] = route('backend.message.show', $message->id);
        $data['date'] = $message->created_at->format('F d, Y h:i A');
        $data['name'] = $message->name;
        $data['email'] = $message->email;
        $data['phone'] = $message->phone;
        $data['message'] = $message->message;
        $data['subject'] = $message->subject;
        $data['photo'] = Auth::guard()->check() ? Auth::guard()->user()->photo : '';
        // return $data;
        event(new MessageSent($data));
        exit();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id)
    {
        $message = Message::find($id);
        if ($message) {
            $message->read_at = \Carbon\Carbon::now();
            $message->save();
            return view('backend.message.show')->with('message', $message);
        } else {
            return back();
        }
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
        $message = Message::find($id);
        $status = $message->delete();
        if ($status) {
            request()->session()->flash('success', 'Successfully deleted message');
        } else {
            request()->session()->flash('error', 'Error occurred please try again');
        }
        return back();
    }

    public function newMessage(Request $request)
    {
        $clients = $request->clients;
        $phones = [];
        $validPhones = [];

        foreach($clients as $client){
            $phone = PhoneNumber::make($client, 'MR')->formatInternational();
            $phone = preg_replace('/\s+/', '', $phone);
            
            if(strlen($phone) === 12){
                $validPhones[] = $phone;
            }
        }
        
        $payload = [
            'phone_numbers' => $validPhones,
            'message' =>  $request->message
        ];

        try {
            SmsService::sendSms($payload);
            request()->session()->flash('success', 'Message Sent Successfully');
        } catch (\Exception $e) {
            Log::error('Error sending SMS: ' . $e->getMessage());
            request()->session()->flash('error', 'Failed to send message.');
        }

        return redirect()->back();
    }


}
