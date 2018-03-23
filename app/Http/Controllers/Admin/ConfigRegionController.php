<?php

namespace hispanicus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use hispanicus\Http\Controllers\Controller;
use hispanicus\ConfigRegion;

class ConfigRegionController extends Controller
{
    public function store(Request $request){
	    $validator = Validator::make($request->all(), [
	        'region' => 'required',
	    ]);

	    if ($validator->fails()) 
	        return response()->json(['error'=>$validator->errors()], 422);    	

    	$cr = ConfigRegion::create($request->all());
    	return response()->json($cr, 200);
    }

    public function update(Request $request, $id){

        $cr = ConfigRegion::findOrFail($id);
        $cr->update($request->all());
        return response()->json($cr);        
    }

    public function destroy($id){
        
        $cr = ConfigRegion::findOrFail($id);
        $cr->delete();
        return response()->json(["Msg" = "deleted"]);
    }

    public function index(){
        
        $cr = ConfigRegion::all();
        return response()->json($cr);
    }

    public function create(){

    }

    public function edit(){

    }

    public function delete(){

    }    
}
