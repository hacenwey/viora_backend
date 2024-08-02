<?php

namespace App\Http\Controllers;


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
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $validatedData = $validator->validated();

        $imagePath = $validatedData['path'];
        $width = $validatedData['width'] ?? 250;
        $height = $validatedData['height'] ?? 250;

        $imagePath = str_replace('/storage', '', $imagePath);
        $fullPath = storage_path('app/public' . $imagePath );

        if (!file_exists($fullPath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        $image = Image::make($fullPath);

        // Resize the image
        $image->resize($width, $height);

        return $image->response('jpeg');
    }
}
