<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function register(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'name' => ['required', 'min:5'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:3'],
        ]);

        //validate JSON
        if ($validate->fails()) {
            return response(['data' => ['message' => $validate->errors()]]);
        }

        $data = new User;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = Hash::make($request->password);
        $data->token = Str::random(64);
        $data->save();

        return response()->json([
            'data' => [
                'token' => $data->token,
                'status' => 'success'
            ]
        ], 201);
    }
}
