<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{    
    public function login(Request $request) {
        $req = $request->only('email', 'password');
        $validate = Validator::make($req, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error, ['email'=>$req['email'], 'token'=>'asd']);
        }
        try {
            if (! $token = JWTAuth::attempt($req)) {
                return returnMessage(false, 'Login credentials are invalid.');
            }
        } catch (JWTException $e) {
            return returnMessage(false, 'Could not create token.');
        }
        return returnMessage(true, 'Login Successful', ['email'=>$req['email'], 'token'=>$token]);
    }

    public function userDetail() {
        return returnMessage(false, 'dis');
    }
    
}
