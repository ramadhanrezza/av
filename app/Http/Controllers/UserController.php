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
}
