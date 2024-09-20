<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        $validator = $request->validate([
            'email'=>'required|email|max:255',
            'password'=>'required|max:3',
        ]);
        $user = User::where('email',$request->email)->firstOrfail();
        return $user;
        if($user){
            return response()->json([
                'msg'=>'test',
            ]);
            $token = $user->createToken('appToken')->plainTextToken;
            return response()->json([
                'user'=>$request->user(),
                'token'=>$token,
            ]);
        }
        return  response()->json([
            'erorr'=>'User Not Authenticated',
        ]);
    }
}
