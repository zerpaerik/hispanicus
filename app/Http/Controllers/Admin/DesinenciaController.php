<?php

namespace hispanicus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use hispanicus\Http\Controllers\Controller;
use hispanicus\Http\Controllers\Admin\VerbosController;
use hispanicus\Desinencia;
use hispanicus\PersonasGramatical;
use hispanicus\Verbo;
use hispanicus\TipoDesinencia;

class DesinenciaController extends Controller
{
	public static function storeDesinencia($data = array()){

	$dataDesinencias = array();
	array_shift($data);	
	$inDb = Desinencia::all(['desinencia'])->toArray();
	$inDb = Verbo::all(['raiz', 'id'])->toArray();

	foreach ($data as $key => $value) {

		$SSraiz     = $data[$key]["A"];
		$desinencia = $data[$key]["B"];
		$SSmodo     = $data[$key]["C"];
		$SSRegion	= $data[$key]["D"];
		$cambiaRaiz = $data[$key]["E"];
		$SSpronom   = $data[$key]["F"];
		$tiempo     = $data[$key]["G"];
		$cambiaNeg  = $data[$key]["H"];
		$SSpronom  = explode("/", $SSpronom);


		$SSpronom =  json_encode($SSpronom);

		$pronom = PersonasGramatical::where('pronombre', $SSpronom)->get(['id', 'region_id'])->first();
		$modo = TipoDesinencia::where('modo', $SSmodo)->get(['id'])->first();

		array_push($dataDesinencias, [
			'desinencia' => utf8_encode(strtolower($desinencia)),
			'pronombre_id' => $pronom->id,
			'verbo_id' => $verbo->id,
			'tiempo' => $tiempo,
			'tipo_desinencia_id' => $modo->id,
			'region_id' => $pronom->region_id,
			'created_at'=>date('Y-m-d H:i:s'),
			'updated_at'=> date('Y-m-d H:i:s')
		]);
	}

	$dataDesinencias = VerbosController::unique_multidim_array($dataDesinencias, 'desinencia');
	$res = false;
	try {
		foreach ($dataDesinencias as $key => $value) {
			$v = in_array(["desinencia" => $dataDesinencias[$key]["desinencia"]], $inDb);
			if($v){
				continue;
			}else{
				Desinencia::insert($dataDesinencias[$key]);
				array_push($inDb, $dataDesinencias[$key]["desinencia"]);
				$res = true;
			}
		}
	} catch (QueryException $e) {
		return $res;
	}
	return $res;		

	}
}