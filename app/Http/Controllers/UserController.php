<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * Login User
     *
     * @bodyParam email string Email of User
     * @bodyParam password string Password of User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @group User Access Control
     */
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
        $user = JWTAuth::user();
        return returnMessage(true, 'Login Successful', ['token'=>$token, 'user'=>$user]);
    }

    /**
     * Get User Detail
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @group User Access Control
     */
    public function userDetail() {
        if (isset($_GET['user'])) {
            $user = User::find($_GET['user']); 
        }
        else 
            $user = JWTAuth::parseToken()->authenticate();
        if (!$user) 
            return returnMessage(false, 'Cannot retrieve user details!');
        return returnMessage(true, 'User details retrieved!', $user->only('email', 'name', 'access', 'id', 'address', 'dob'));
    }

    /**
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     *
     * @group User Access Control
     */
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
        $req['password'] = Hash::make($req['password']);
        $user = User::create($req);

        if ($user) 
            return returnMessage(true, 'User: '.$req['name'].' has been added!', $user);
        else 
            return returnMessage(false, 'User cannot be added!');

    }

    /**
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     *
     * @group User Access Control
     */
    public function updateUser(Request $req) {
        $data = $this->updateDetailsOrPassword($req, 'detail');
        if ($data['validate']->fails()) {
            $error = validateErrorMessage($data['validate']);
            return returnMessage(false, $error);
        }
        $currentUser = JWTAuth::parseToken()->authenticate();

        if ($currentUser->access !== 0 && $currentUser->id === $data['req']['id']) 
            return returnMessage(false, 'You are not authorized for this action!');
        
        $user = User::find($data['req']['id'])->update($data['req']);
        if ($user) 
            return returnMessage(true, 'Profile update successful!', $user);
        else 
            return returnMessage(false, 'Something went wrong. Please try again!');
    }

    /**
     * Update Details or Password
     *
     * @bodyParam id int User ID
     * @bodyParam password string New Password
     * @bodyParam password_confirmation string New Password Confirmation
     *
     * @param Request $req
     * @return \Illuminate\Http\JsonResponse
     *
     * @group User Access Control
     */
    public function updateUserPassword(Request $req) {
        $data = $this->updateDetailsOrPassword($req, 'password');
        if ($data['validate']->fails()) {
            $error = validateErrorMessage($data['validate']);
            return returnMessage(false, $error);
        }
        $currentUser = JWTAuth::parseToken()->authenticate();

        if ($currentUser->access !== 0 && $currentUser->id === $data['req']['id']) 
            return returnMessage(false, 'You are not authorized for this action!');
        
        $data['req']['password'] = Hash::make($data['req']['password']);
        $user = User::find($data['req']['id'])->update($data['req']);
        if ($user) 
            return returnMessage(true, 'Password change successful!', $user);
        else 
            return returnMessage(false, 'Something went wrong. Please try again!');
    }

    /**
     * Get user details
     * @return \Illuminate\Http\JsonResponse
     *
     * @group User Access Control
     */
    public function listUsers() {
        $user = User::get();
        if (!$user) 
            return returnMessage(false, 'Cannot retrieve user details!');
        return returnMessage(true, 'User details retrieved!', $user);
    }


    /**
     * Update Details or Password
     *
     * @bodyParam id int User ID
     * @bodyParam password string New Password
     * @bodyParam password_confirmation string New Password Confirmation
     * @bodyParam name string Updated Name
     * @bodyParam address string Updated Address
     * @bodyParam dob date Updated Date of Birth
     *
     * @param $req
     * @param $_option
     * @return array
     *
     * @group User Access Control
     */
    public function updateDetailsOrPassword($req, $_option) {
        $data = [];

        if ($_option === 'password') {
            $req = $req->only('id', 'password', 'password_confirmation');
            $validate = Validator::make($req, [
                'id' => 'required|exists:users',
                'password' => 'required|string|min:6|required_with:password_confirmation|same:password_confirmation',
                'password_confirmation' => 'required'
            ]);
        } else if ($_option === 'detail') {
            $req = $req->only('id', 'name', 'address', 'dob');
            $validate = Validator::make($req, [
                'id' => 'required|exists:users',
                'name' => 'required|string',
                'address' => 'required|string', 
                'dob' => 'required|date', 
            ]);
        }
        $data['req'] = $req;
        $data['validate'] = $validate;
        return $data;
    }
}
