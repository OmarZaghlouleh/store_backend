<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{

    public function register(Request $request)
    {

        $validation = Validator::make($request->only('email', 'name', 'password', 'password_confirmation'), [
            'name'     => ['required', 'string', 'unique:users,name', 'between:2,255'],
            'email'    => [
                'required',
                'email',
                'unique:users,email'
            ],
            'password' => ['required', 'string', 'confirmed', 'min:6'],
        ]);

        if ($validation->fails()) {
            return response()->json(['sucess' => false, 'status_code' => 400, 'message' => $validation->errors()->first()]);
        } else {
            $user = User::create([
                'email'    => $request->email,
                'name'     => $request->name,
                'password' => bcrypt($request->password),

            ]);

            $token         = $user->createToken('MyAppToken')->plainTextToken;
            $user['token'] = $token;

            return response()->json(['sucess' => true, 'status_code' => 200, 'data' => $user]);
        }
    }
    public function login(Request $request)
    {

        $validation = Validator::make($request->only('email', 'password'), [

            'email'    => [
                'required',
                'email',
                'exists:users,email',
            ],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if ($validation->fails()) {
            return response()->json(['sucess' => false, 'status_code' => 400, 'message' => $validation->errors()->first()]);
        } else {

            $user = User::where('email', $request->email)->first();
            if ($user) {
                $password = Hash::check($request['password'], $user->password);
                if ($password) {
                    $token         = $user->createToken('MyAppToken')->plainTextToken;
                    $user['token'] = $token;

                    return response()->json(['sucess' => true, 'status_code' => 200, 'data' => $user]);
                } else {
                    return response()->json(['sucess' => false, 'status_code' => 400, 'message' => "Invalid credentials"]);

                }
            } else {
                return response()->json(['sucess' => false, 'status_code' => 400, 'message' => "Invalid credentials"]);

            }




        }
    }

    public function logout()
    {
        $status = auth()->user()->tokens()->delete();
        if ($status) {
            return response()->json([
                'success'     => true,
                'status_code' => 200,
                'message'     => 'Logged out successfully.'
            ]);
        } else {
            return response()->json([
                'success'     => false,
                'status_code' => 400,
                'message'     => 'Something went wrong'
            ]);
        }
    }
}