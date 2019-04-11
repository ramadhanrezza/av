<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\User;

class UserController extends Controller
{
    protected $user;

    function __construct() {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index() {
        return $this->user
            ->get(['id', 'name', 'email'])
            ->toArray();
    }

    public function show($id) {
        $user = $this->user->find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, User with id ' . $id . ' not found.'
            ],400);
        }

        return response()->json([
            'success'   => true,
            'data'  => $user
        ]);
    }

    public function store(Request $request) {
        $code = 200;
        $success = true;

        try {
            $this->validate($request, [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6|max:10'
            ]);

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
            'success'   => $success,
            'message'   => $message,
            'data'      => $data
        ], $code);
    }

    public function update(Request $request, $id) {
        $user = $this->user->find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, User with id ' . $id . ' not found.'
            ],400);
        }

        $save_data = [
            'name'  => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ];

        $updated = $user->fill($save_data)->save();

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Record updated.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user could not be updated.'
            ], 500);
        }
    }

    public function destroy($id) {
        $user = $this->user->find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, User with id ' . $id . ' not found.'
            ],400);
        }

        if ($user->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Record deleted.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user could not be deleted.'
            ], 500);
        }
    }
}
