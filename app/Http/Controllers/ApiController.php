<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
Use Exception;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiController extends Controller
{
    public function register(Request $request) {
        $code = 200;
        $success = true;

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            $data = $user;
            $message = 'Record completed.';
        } catch (Exception $e) {
            $code = 400;
            $success = false;
            $data = [];
            $message = $e->getMessage();
        }

        return response()->json([
            'success'   => $this->success,
            'message'   => $this->message,
            'data'      => $this->data
        ], $this->code);
    }


    public function login(Request $request) {
        $input = $request->only('email', 'password');
        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);
    }
}
