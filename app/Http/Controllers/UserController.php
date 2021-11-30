<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function info(Request $request) {
        $user = $request->user();
        $user_info = User::where('id',$user->id)
                    ->with(['followers', 'following'])
                    ->withCount(['followers', 'following'])
                    ->first();
        return response()->json([
            'message' => 'success',
            'data' => $user_info
        ]);
    }

    public function follow(){
        //
        
    }
}
