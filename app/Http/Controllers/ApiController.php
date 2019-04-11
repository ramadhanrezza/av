<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
Use Exception;

class ApiController extends Controller
{
    private $success;
    private $data;
    private $code;
    private $message;

    public function register(Request $request) {
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            $this->code = 200;
            $this->success = true;
            $this->data = $user;
            $this->message = 'Record completed.';
        } catch (Exception $e) {
            $this->code = 400;
            $this->success = false;
            $this->data = [];
            $this->message = $e->getMessage();
        }

        return response()->json([
            'success'   => $this->success,
            'message'   => $this->message,
            'data'      => $this->data
        ], $this->code);
    }


    public function login(Request $request) {
        die('a');
    }
}
