<?php

namespace App\Http\Controllers;

use App\Models\FcmToken;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;
use App\Jobs\SendNotificationJob;


class NotificationsfirebaseController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseNotificationService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function sendNotification(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'message' => 'required|string',
            'photo' => 'string|nullable',
        ]);

        $title = $request->input('title');
        $message = $request->input('message');
        $photo = $request->input('photo');
        $productId = $request->id;

        $tokens = FcmToken::distinct()->pluck('token')->toArray();        
        SendNotificationJob::dispatch($title, $message, $photo, $productId, $tokens);


        request()->session()->flash('success', 'A notification has been added to the system.');

        return redirect()->route('backend.notificationsfirebase.index');
    }

    public function index()
    {
        return FirebaseNotificationService::displayNotification();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'message' => 'required|string',
            'photo' => 'string',
            'status' => 'boolean',
        ]);
        FirebaseNotificationService::storeNotification($request->all());
    }

    public function show($id)
    {
        return FirebaseNotificationService::showNotification($id);
    }

    public function update(Request $request, $id)
    {
        return FirebaseNotificationService::updateNotification($request->validated(), $id);
    }

    public function destroy($id)
    {
        return FirebaseNotificationService::deleteNotification($id);
    }

}
