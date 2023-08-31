<?php

namespace App\Services;

use App\Http\Resources\FirebaseNotificationResource;
use App\Models\FcmToken;
use App\Models\FirebaseNotification;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseNotificationService
{

    public static function displayNotification()
    {
        $products = Product::where('status', 'active')->where('stock', '!=', 0)->get();
        return view('backend.notificationsfirebase.index', compact('products'));
    }

    public static function storeNotification($data)
    {
        try {
            FirebaseNotification::create($data);
        } catch (Exception $ex) {
            Log::info("Problème lors de la création" . json_encode($data));
            Log::error($ex->getMessage());

        }
    }

    public static function showNotification($id)
    {
        $notification = FirebaseNotification::find($id);
        $response = [
            'message' => $notification ? "existante" : "Aucune  Notification  n\'est associé avec cet identifiant",
            'data' => $notification ? new FirebaseNotificationResource($notification) : null,
        ];
        return response($response);
    }

    public static function updateNotification($data, $id)
    {
        try {
            $notification = FirebaseNotification::find($id);
            if ($notification) {
                $notification->update($data);
            } else {
                return response(['message' => "Notification  que vous essayé de modifier n\'existe pas"], 400);
            }
        } catch (Exception $ex) {
            Log::info("Un problème est survenu lors du mise à jour de cette Notification ." . json_encode($data));
            Log::error($ex->getMessage());

            return response(['message' => "Un problème est survenu lors du mise à jour de cette Notification ."], 500);
        }
        $response = [
            'message' => "Notification  a été modifié avec succèss",
            'object' => new FirebaseNotificationResource($notification),
        ];
        return response($response);
    }

    public static function deleteNotification($id)
    {
        $notification = FirebaseNotification::find($id);
        try {
            if ($notification) {
                $notification->delete();
            } else {
                return response(['message' => "Notification que vous essayé de suppriemr n\'existe pas"], 400);
            }
        } catch (Exception $ex) {
            Log::info("Un problème est survenu lors de la suppression de cette Notification ." . $id);
            Log::error($ex->getMessage());

            return response(['message' => "Un problème est survenu lors de la suppression de cette Notification ."], 500);
        }
        $response = [
            'message' => "Notification  à été supprimée avec succèss",
            'data' => null,
        ];
        return response($response);
    }

    public static function sendNotificationFirebase($title, $message, $photo)
    {
        $tokens = FcmToken::pluck('token');
        $SERVER_API_KEY = config('helper.firebase_key');
        $data = [
            "registration_ids" => $tokens,
            "notification" => [
                "title" => $title,
                "body" => $message,
                "sound" => "default",
                'image' => $photo
            ],
        ];
        $url = config('helper.firebase_server');
        $request = Http::withHeaders([
            'Authorization' => 'key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ])->withOptions(
                [
                    'verify' => false,
                ]
            )->post($url, $data);
        Log::info($request);
        FirebaseNotificationService::storeNotification([
            'title' => $title,
            'message' => $message,
            'photo' => $photo,
            'status' => $request->status(),
        ]);
        return $request->status();
    }
}