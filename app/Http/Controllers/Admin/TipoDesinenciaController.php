<?php

namespace hispanicus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use hispanicus\Http\Controllers\Controller;
use hispanicus\TipoDesinencia;

class TipoDesinenciaController extends Controller
{

    public function store(){

	    $validator = Validator::make($request->all(), [
	        'modo' => 'required',
	    ]);

	    if ($validator->fails()) 
	        return response()->json(['error'=>$validator->errors()], 422);    	

    	$td = TipoDesinencia::create($request->all());
    	return response()->json($td, 200);

    }

    public function update(Request $request, $id){

        $td = TipoDesinencia::findOrFail($id);
        $td->update($request->all());
        return response()->json($td);
    }

    public function destroy(){

        $td = TipoDesinencia::findOrFail($id);
        $td->delete();
        return response()->json(["Msg" = "deleted"]);

    }

    public function index(){

        $td = TipoDesinencia::all();
        return response()->json($td);
    }
    
    public function create(){

    }

    public function edit(){

    }

    public function delete(){

    }

}
