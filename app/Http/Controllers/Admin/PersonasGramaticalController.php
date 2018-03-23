<?php

namespace hispanicus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use hispanicus\Http\Controllers\Controller;
use hispanicus\PersonasGramatical;
class PersonasGramaticalController extends Controller
{
    public function store(Request $request){

	    $validator = Validator::make($request->all(), [
	        'pronombre' => 'required',
	        'persona_gramatical' => 'required',
	        'region_id' => 'required'
	    ]);

	    if ($validator->fails()) 
	        return response()->json(['error'=>$validator->errors()], 422);    	

    	$pg = PersonasGramatical::create($request->all());
    	return response()->json($pg, 200);
    }

    public function update(Request $request, $id){
    	$pg = PersonasGramatical::findOrFail($id);
    	$pg->update($request->all());
    	return response()->json($pg);
    }

    public function destroy(){
    	$pg = PersonasGramatical::findOrFail($id);
    	$pg->delete();
    	return response()->json(["Msg" = "deleted"]);

    }

    public function index(){
    	$pg = PersonasGramatical::all();
    	return response()->json($pg);
    }

    public function create(){

    }

    public function edit(){

    }

    public function delete(){

    }
}
