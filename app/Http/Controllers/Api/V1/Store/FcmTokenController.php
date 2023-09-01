<?php

namespace App\Http\Controllers\Api\V1\Store;

use App\Http\Controllers\Controller;
use App\Http\Resources\FcmTokenResource;
use App\Models\FcmToken;
use Illuminate\Http\Request;

class FcmTokenController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'device_uid' => 'required|string',
            'token' => 'required|string',
        ]);
        try {
            $fcm = FcmToken::create($request->all());
        } catch (Exception $ex) {
            Log::info("Problème lors de la création" . json_encode($request->all()));
            Log::error($ex->getMessage());

            return response(['errors' => $ex->getMessage()], 500);
        }
        $response = [
            'message' => "token  a été créer avec succès",
            'device' => new FcmTokenResource($fcm),
        ];
        return response($response, 201);
    }
}