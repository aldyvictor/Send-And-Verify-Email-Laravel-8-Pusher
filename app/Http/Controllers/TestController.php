<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(Request $request) {
        
        $data = [
            "status" => "001",
            "name" => "Abduh"
        ];
        
        return response()->json();
    }
}
