<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            return returnMessage(false, $error);
        }
        try {
            if (! $token = JWTAuth::attempt($req)) {
                return returnMessage(false, 'Login credentials are invalid.');
            }
        } catch (JWTException $e) {
            return returnMessage(false, 'Could not create token.');
        }
        return returnMessage(true, 'Login Successful', ['token'=>$token]);
    }

    public function userDetail() {
        $user = JWTAuth::parseToken()->authenticate();
        if (!$user) 
            return returnMessage(false, 'Cannot retrieve user details!');
        return returnMessage(true, 'User details retrieved!', $user->only('email', 'name', 'access'));
    }
    
    public function addUser(Request $req) {
        $req = $req->only('email', 'password', 'name', 'address', 'dob');
        $validate = Validator::make($req, [
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'name' => 'required|string',
            'address' => 'required|string', 
            'dob' => 'required|date', 
        ]);
        if ($validate->fails()) {
            $error = validateErrorMessage($validate);
            return returnMessage(false, $error);
        }
        $req['access'] = 1;
        $user = User::create($req);

        if ($user) 
            return returnMessage(true, 'User: '.$req['name'].' has been added!', $user);
        else 
            return returnMessage(false, 'User cannot be added!');

    }
}
