<?php

namespace App\Http\Controllers\Api\V1\Store;


use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{



    public function getImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
            'width' => 'sometimes|integer',
            'height' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $imagePath = '';

        try {
            $validatedData = $validator->validated();

            $imagePath = $validatedData['path'];
            $width = $validatedData['width'] ?? 250;
            $height = $validatedData['height'] ?? 250;

            $imagePath = strstr($imagePath, '/storage');
            $imagePath = str_replace('/storage', '', $imagePath);
            $fullPath = storage_path('app/public' . $imagePath);

            if (!file_exists($fullPath)) {
                return response()->file(storage_path('app/public' . $imagePath));
            }

            $image = Image::make($fullPath);

            // Resize the image
            $image->resize($width, $height);

            return $image->response('jpeg');
        } catch (\Exception $e) {
            \Log::error("Error resizing image: " . $e->getMessage());

            return response()->file(storage_path('app/public' . $imagePath));
        }
    }
}
