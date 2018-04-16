<?php

namespace hispanicus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use hispanicus\Http\Controllers\Controller;
use hispanicus\Raiz;
use hispanicus\Verbo;
use hispanicus\Http\Controllers\Admin\VerbosController;

class RaizController extends Controller
{
    public static function storeRaiz($data){
			try {

				$RaizIdx = array_search('Raíz', str_replace(" ", "", $data[0]));
				$InfIdx  = array_search('Verbo', str_replace(" ", "", $data[0]));				
				$NegIdx  = array_search('Negación', str_replace(" ", "", $data[0]));

			} catch (Exception $e) {
				return response()->json(["exception" => $e->getMessage]);			
			}
			array_shift($data);
			$insert = array();
			$dataRaiz = array();    	

			$inDb = Raiz::get(['nombre'])->toArray();

		foreach ($data as $key => $value) {
			if (!array_key_exists($RaizIdx, $data[$key])) continue;

			$nombre = str_replace(" ", "", $data[$key][$RaizIdx]);
			$nombre = str_replace("[", '<b class="rc">', $nombre);
			$nombre = str_replace("]", '</b>', $nombre);
			$verbo  = Verbo::where('infinitivo', '=', utf8_encode($data[$key][$InfIdx]))->get(['id'])->first();

			if ($verbo) {
				$insert = [
					"nombre" => utf8_encode($nombre),
					"verbo_id" => $verbo->id
				];

				if (!in_array(["nombre" => utf8_encode($data[$key][$RaizIdx])], $inDb)) {
					array_push($dataRaiz, $insert);
				}				
			}

		}
		return (self::save($dataRaiz, $inDb));			
   }

	public static function save($dataRaiz, $inDb){
		$dataRaiz = VerbosController::unique_multidim_array($dataRaiz, 'nombre');		
		$res = false;

		try {
			foreach ($dataRaiz as $key => $value) {
				$v = in_array(["nombre" => $dataRaiz[$key]["nombre"]], $inDb);
				if($v){
					continue;
				}else{
					Raiz::insert($dataRaiz[$key]);
					array_push($inDb, $dataRaiz[$key]["nombre"]);
					$res = true;
				}
			}
		} catch (QueryException $e) {
			return $res;
		}
		return $dataRaiz;				
	}

}
