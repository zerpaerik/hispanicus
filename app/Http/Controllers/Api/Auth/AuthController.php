<?php

namespace hispanicus\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use hispanicus\Http\Controllers\Controller;
use hispanicus\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
	public function register(Request $request)
	{
	    $validator = Validator::make($request->all(), [
	        'name' => 'required',
	        'email' => 'required|email|unique:users',
	        'password' => 'required',
	        'confirm_password' => 'required|same:password',
	    ]);

	    if ($validator->fails()) {
	        return response()->json(['error'=>$validator->errors()], 422);
	    }

	    $input = $request->all();
	    $input['password'] = bcrypt($request->get('password'));
	    $user = User::create($input);
	    $token =  $user->createToken('hispanicus')->accessToken;

	    return response()->json([
	        'token' => $token,
	        'user' => [
            	"name" => $user->name,
            	"email" => $user->email,
	        ],	
	    ], 200);
	}

	public function login(Request $request)
	{
	    if (Auth::attempt($request->only('email', 'password'))) {
	        $user = Auth::user();
	        $token =  $user->createToken('hispanicus')->accessToken;
	        return response()->json([
	            'token' => $token,
	            'user' => [
	            	"name" => $user->name,
	            	"email" => $user->email,
	            ],	           
	        ], 200);
	    } else {
	        return response()->json(['error' => 'Unauthorized'], 401);
	    }
	}	

	public function logout(Request $request)
    {
	 	
 		$request->user()->token()->revoke();
		$value = $request->bearerToken();
		$id= (new \Lcobucci\JWT\Parser())->parse($value)->getHeader('jti');
		$token= $request->user()->tokens->find($id);
		$token->delete(); 		
 		return response()->json(["message" => "Logged Out"], 200);
    }

}