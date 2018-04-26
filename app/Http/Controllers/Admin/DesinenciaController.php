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

		try {

			$DesIdx = array_search('desinencia', str_replace(" ", "", $data[0]));
			$NegIdx  = array_search('negación', str_replace(" ", "", $data[0]));

		} catch (Exception $e) {
			return response()->json(["exception" => $e->getMessage]);			
		}

		array_shift($data);
		$insert = array();
		$dataDesinencia = array();		

		$inDb = Desinencia::get(['desinencia'])->toArray();

		foreach ($data as $key => $value) {
			if (!array_key_exists($DesIdx, $data[$key])) continue;
		
			$desinencia = str_replace(" ", "", $data[$key][$DesIdx]);
			$desinencia = str_replace(["[", "]"], "", $data[$key][$DesIdx]);

			$insert = [
				"desinencia" => utf8_encode($desinencia),
			];

			if (!in_array(["desinencia" => utf8_encode($data[$key][$DesIdx])], $inDb)) {
				array_push($dataDesinencia, $insert);
			}		

		}

		return (self::save($dataDesinencia, $inDb));

	}

	public static function save($dataDesinencia, $inDb){
		$dataDesinencia = VerbosController::unique_multidim_array($dataDesinencia, 'desinencia');		
		$res = false;

		try {
			foreach ($dataDesinencia as $key => $value) {
				$v = in_array(["desinencia" => $dataDesinencia[$key]["desinencia"]], $inDb);
				if($v){
					continue;
				}else{
					Desinencia::insert($dataDesinencia[$key]);
					array_push($inDb, $dataDesinencia[$key]["desinencia"]);
				}
			}
		} catch (QueryException $e) {
			return $res;
		}
		return $dataDesinencia;				
	}	

}