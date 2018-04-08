<?php

namespace hispanicus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use hispanicus\Http\Controllers\Controller;
use hispanicus\ConfigRegion;
use hispanicus\User;

class ConfigRegionController extends Controller
{



    public function getLang(){

        $u = \Auth::user();
        $f = ConfigRegion::where('user_id', '=', $u->id)->get(['lang'])->first();
        if ($f) {
            return response()->json(["success" => true, "lang" => json_decode($f->lang)], 200);
        }else{
            return response()->json(["success" => true, "lang" => 'en'], 200);
        }
    }

    public function setLang(Request $request){
        $u = \Auth::user();
        $f = ConfigRegion::where('user_id', '=', $u->id)->get()->first();
        if ($f) {
            $f->lang = $request['lang'];
            $r = $f->save();
            if ($r) return response()->json(["success" => true, "lang" => $f->lang], 200);
            return response()->json(["success" => false], 442);
        }else{
            $r = ConfigRegion::insert(["user_id" => $u->id, "lang" => $request['lang']]);
            if ($r) return response()->json(["success" => true, "lang" => $request['lang']], 200);
            return response()->json(["success" => false], 442);
        }
    }


    public function getRegion(){

        $u = \Auth::user();
        $f = ConfigRegion::where('user_id', '=', $u->id)->get(['modo'])->first();
        if ($f) {
            return response()->json(["success" => true, "modo" => $f->modo], 200);
        }else{
            return response()->json(["success" => true, "modo" => [1, 2, 0]], 200);
        }
    }

    public function setRegion(Request $request){
        $u = \Auth::user();
        $f = ConfigRegion::where('user_id', '=', $u->id)->get()->first();
        if ($f) {
            $f->modo = $request['modo'];
            $r = $f->save();
            if ($r) return response()->json(["success" => true, "modo" => $f->modo], 200);
            return response()->json(["success" => false], 442);
        }else{
            $r = ConfigRegion::insert(["user_id" => $u->id, "modo" => $request['modo']]);
            if ($r) return response()->json(["success" => true, "modo" => $r->modo], 200);
            return response()->json(["success" => false], 442);
        }
    }

    public function getFavs(){

        $u = \Auth::user();
        $f = ConfigRegion::where('user_id', '=', $u->id)->get(['favs'])->first();
        if ($f) {
            return response()->json(["success" => true, "favs" => json_decode($f->favs)], 200);
        }else{
            return response()->json(["success" => true, "favs" => []], 200);
        }
    }

    public function setFav(Request $request){
        $u = \Auth::user();
        $f = ConfigRegion::where('user_id', '=', $u->id)->get()->first();
        if ($f) {
            $f->favs = $request['favs'];
            $r = $f->save();
            if ($r) return response()->json(["success" => true, "favs" => json_decode($f->favs)], 200);
            return response()->json(["success" => false], 442);
        }else{
            $r = ConfigRegion::insert(["user_id" => $u->id, "favs" => $request['favs']]);
            if ($r) return response()->json(["success" => true, "favs" => $r->favs], 200);
            return response()->json(["success" => false], 442);
        }
    }

}
