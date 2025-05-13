<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

class HelloWorldController extends Controller
{
    public function index()
    {
        return response()->json([
            'message'=>'hello world',
        ]);
    }

}
