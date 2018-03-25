<?php

namespace hispanicus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use hispanicus\Http\Controllers\Controller;
use hispanicus\PersonasGramatical;
use hispanicus\Http\Controllers\Admin\VerbosController;

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
    	return response()->json(["Msg" => "deleted"]);

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

    public static function getStaticData($data){

        $pro = array();
        $vforms = array();
        $vtime = array();
        $verbs = array();

        foreach ($data as $key => $value) {

            if(isset($data[$key]["F"])){
                $proVal = $data[$key]["F"];
            }else if(isset($data[$key]["G"])){
                $proVal = $data[$key]["G"];
            }

            if (is_array($proVal)) {
                $proVal = explode(",", $proVal);
                $proVal = array_filter($proVal);
                if (!in_array(json_encode($proVal), $pro)) {
                    array_push($pro , json_encode($proVal));   
                }
            }            
            if (isset($data[$key]["B"])) {
                $vtimeVal = $data[$key]["B"];
                if (!in_array($vtimeVal, $vtime)) {
                    array_push($vtime, $vtimeVal);        
                }    
            }

            if (isset($data[$key]["D"])) {
                $vformVal = $data[$key]["D"];
                if (!in_array($vformVal, $vforms)) {
                    array_push($vforms, $vformVal);        
                }    
            }

            if (isset($data[$key]["A"])) {
                $verb = VerbosController::quitarSe($data[$key]["A"]);
                if (!in_array($verb, $verbs)) {
                    array_push($verbs, $verb);        
                }    
            }

        }

        array_shift($pro);
        array_shift($vtime);
        array_shift($vforms);
        array_shift($verbs);

        return [$pro, $vtime, $vforms, $verbs];
    }

}