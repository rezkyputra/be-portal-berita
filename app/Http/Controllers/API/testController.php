<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class testController extends Controller
{
    public function dashboard()
    {
        return response([
            "message"=>"Test123"
        ],200);
    }
}
