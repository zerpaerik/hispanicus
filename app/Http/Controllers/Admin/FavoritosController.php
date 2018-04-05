<?php

namespace hispanicus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use hispanicus\Http\Controllers\Controller;
use hispanicus\Favoritos;
class FavoritosController extends Controller
{
    public function getFavorites(){
    	$user = \Auth::user();
    	$f = Favoritos::where('user_id', $user->id)->get(['favoritos'])->first();
    	$f = json_decode($f->favoritos);
    	return response()->json([
    		"favoritos" => $f
    	]);
    }

    public function setFavorites(Request $request){
    	$user = \Auth::user();
    	$f = Favoritos::where('user_id', $user->id)->get(['favoritos'])->first();
    	$n_favs = json_encode($request['favoritos']);
    	$r = $f->update(["favoritos" => $n_favs]);
    	return response()->json(["success" => $r]);
    }

    public function authuser(){
    	return \Auth::user();
    }
}
