<?php
namespace hispanicus\Http\Controllers\Api\Clients;

use Illuminate\Http\Request;
use hispanicus\Http\Controllers\Controller;
use hispanicus\User;
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
