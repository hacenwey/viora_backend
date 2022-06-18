<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UploadService
{

    static function uploadFile($file, &$payload, $key)
    {

        $allowdExtentions = ['csv', 'xls' , 'xlsx'];

        if ($file && in_array($file->getClientOriginalExtension(), $allowdExtentions)) {
            $fname = $file->getClientOriginalName();
            $file->move(storage_path("/app/public/cdn/files"), $fname);
            $payload[$key] = $fname;
        }
    }
}
