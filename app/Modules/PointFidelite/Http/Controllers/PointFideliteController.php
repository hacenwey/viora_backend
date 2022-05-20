<?php

namespace App\Modules\PointFidelite\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PointFideliteController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("PointFidelite::welcome");
    }
}
