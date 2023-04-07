<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name'=>'required|string|max:50',
                'email'=>'required|string|max:250|unique:users,email',
                'password'=>'required|string|min:6|confirmed',
                'age'=>'required|integer',
                'profile_img_url'=>'string',
                'facebook_url'=>'string',
                'whatsapp_url'=>'string',

            ]);

            //$request['password'] = Hash::make($request['password']); //Method 1
            $request['password'] =bcrypt($request['password']);  //Method 2

            $user = User::query()->create([
                'email'=>$request['email'],
                'password'=>$request['password'],
                'name'=>$request['name'],
                'age'=>$request['age'],
                'profile_img_url'=>$request['profile_img_url'],
                'facebook_url'=>$request['facbook_url'],
                'whatsapp_url'=>$request['whatsapp_url'],
            ]);

            $token = $user->createToken('Personal Access Token')->plainTextToken;
            
            $data['user']=$user;
            $data['token_type']='bearer';
            $data['access_token']=$token;
            
            return response()->json(['message'=>'Created successfuly','status_code'=>201,'data'=>$data]);
            //201 means success and something created

        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'status_code'=>400,]);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'=>'required|string',
                'password'=>'required|string',
            
            ]);


            $user = User::where('email',$request['email'])->first();
            if(!$user || !Hash::check($request['password'],$user->password)){
            
                return response()->json(['message'=>"Incorrect credentials",'status_code'=>400,]);
            }

            $token = $user->createToken('Personal Access Token')->plainTextToken;
            $data['user']=$user;
            $data['token_type']='bearer';
            $data['access_token']=$token;
            
            return response()->json(['message'=>'Logged in successfuly','status_code'=>200,'data'=>$data]);

           
        } catch (\Throwable $th) {
            return response()->json(['message'=>$th->getMessage(),'status_code'=>400,]);
        }
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response()->json(['message'=>"Logout successfuly",'status_code'=>200,]);
    }
}
