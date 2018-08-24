<?php
namespace hispanicus\Http\Controllers\Api\Clients;

use Illuminate\Http\Request;
use hispanicus\Http\Controllers\Controller;
use hispanicus\User;
use hispanicus\AppCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\ClientRepository;

class ClientsController extends Controller
{
	public function index()
	{

    $password = \DB::table("oauth_clients")
    ->where('password_client', '=', true)
		->where('revoked' , '=', false)
    ->where('name', '!=', 'Roles-Permissions Manager Password Grant Client');

    $clients = \DB::table("oauth_clients")
    ->where('password_client', '=', false)
    ->where('personal_access_client', '=', false)
    ->where('revoked' , '=', false)
    ->union($password)
    ->get();

    return response()->json($clients, 200);

	}

	public function check(){
		return response()->json([true], 200);	
	}

	public function revokeCode(Request $request){
		$code = AppCode::where('id', '=', $request->code)->get()->first();
		$code->revoked = 1;
		$res = $code->save();
		return response()->json(["success" => $res]);
	}

	public function consumeCode(Request $request){

		$code = AppCode::where('code', '=', $request->code)->where('device_id', '=', null)->get()->first();
		
		if(!$code) return response()->json(["success" => false], 401);
		
		$code->device_id = $request->device_id;
		$success = $code->save();

		return response()->json(["success" => $success], 200);

	}

	public function getCodes(){
		$codes = AppCode::where('revoked', '=', '0')->get();
		return response()->json($codes, 200);
	}

    public function generateCode(){
	    $code = AppCode::create([
	        "code" => str_random(6)
	    ]);
	    return response()->json($code, 200);
	}

	public function store(Request $request){

		$type = $request->get('type');
		$redirect = $request->get('redirect');
		$name = $request->get('name');
		$id = null;
		$d = new ClientRepository;
		if ($type == "Password Grant") {
			$d = $d->createPasswordGrantClient($id, $name, $redirect);	
			return response()->json($d, 200);
		}

		
	}
}
