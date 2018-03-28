<?php

namespace hispanicus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use hispanicus\Http\Controllers\Controller;
use hispanicus\Http\Controllers\Admin\VerbosController;
use hispanicus\FormaVerbal;
use hispanicus\TiempoVerbal;
use hispanicus\PersonasGramatical;
use hispanicus\PronombreReflex;
use hispanicus\VerboAuxiliar;
use hispanicus\Regla;

class DataStatica extends Controller
{
    public static function storeStaticData($data = array()){
			try {

				$FvIdx   = array_search('Formaverbal', str_replace(" ", "", $data[0]));	
				$TvIdx   = array_search('Tiempoverbal', str_replace(" ", "", $data[0]));			
				$PiIdx   = array_search('Pronombreinformal', str_replace(" ", "", $data[0]));	
				$PfIdx   = array_search('Pronombreformal', str_replace(" ", "", $data[0]));
				$PrIdx   = array_search('Pronombrereflexivo', str_replace(" ", "", $data[0]));
				$PgIdx   = array_search('Pers.gram.', str_replace(" ", "", $data[0]));
				$VaIdx	 = array_search('Verboauxiliar', str_replace(" ", "", $data[0]));
				$RuleIdx = array_search('Regla', str_replace(" ", "", $data[0]));

			} catch (Exception $e) {
				return response()->json(["exception" => $e->getMessage]);			
			}

			array_shift($data);

    	$inDbFv = FormaVerbal::get(["forma_verbal"])->toArray();
    	$inDbTv = TiempoVerbal::get(["tiempo"])->toArray();
    	$inDbPg = PersonasGramatical::get(["*"])->toArray();
    	$inDbPr = PronombreReflex::get(["pronombre_reflex"])->toArray();
    	$inDbVa = VerboAuxiliar::get(["verbo_auxiliar"])->toArray();
    	$inDbRule = Regla::get(["regla"])->toArray();

    	$dataFv 	= array();
    	$dataTv 	= array();
    	$dataPg 	= array();
    	$dataPr 	= array();
    	$dataVa 	= array();
    	$dataRule = array();

		foreach ($data as $key => $value) {

			if ($FvIdx) {
				if (!array_key_exists($FvIdx, $data[$key])) continue;
			}else{
				break;
			}
			

			$forma_verbal = str_replace(" ", "", $data[$key][$FvIdx]);

			$insert = [
				"forma_verbal" => utf8_encode($forma_verbal),
			];

			if (!in_array(["forma_verbal" => $data[$key][$FvIdx]], $inDbFv)) {
				array_push($dataFv, $insert);
			}
		}

		foreach ($data as $key => $value) {
			
			if ($TvIdx) {
				if (!array_key_exists($TvIdx, $data[$key])) continue;
			}else{
				break;
			}
			

			$tiempo = str_replace(" ", "", $data[$key][$TvIdx]);

			$insert = [
				"tiempo" => utf8_encode($tiempo),
			];

			if (!in_array(["tiempo" => $data[$key][$TvIdx]], $inDbTv)) {
				array_push($dataTv, $insert);
			}
		}

		foreach ($data as $key => $value) {

			if ($PrIdx) {
				if (!array_key_exists($PrIdx, $data[$key])) continue;
			}else{
				break;
			}			
			

			$pronombre_reflex = str_replace(" ", "", $data[$key][$PrIdx]);

			$insert = [
				"pronombre_reflex" => utf8_encode($pronombre_reflex),
			];

			if (!in_array(["pronombre_reflex" => $data[$key][$PrIdx]], $inDbPr)) {
				array_push($dataPr, $insert);
			}
		}		

		foreach ($data as $key => $value) {
			
			if ($VaIdx) {
				if (!array_key_exists($VaIdx, $data[$key])) continue;
			}else{
				break;
			}				
			

			$verbo_auxiliar = str_replace(" ", "", $data[$key][$VaIdx]);

			$insert = [
				"verbo_auxiliar" => utf8_encode($verbo_auxiliar),
			];

			if (!in_array(["verbo_auxiliar" => $data[$key][$VaIdx]], $inDbVa)) {
				array_push($dataVa, $insert);
			}
		}

		foreach ($data as $key => $value) {
			
			if ($RuleIdx) {
				if (!array_key_exists($RuleIdx, $data[$key])) continue;
			}else{
				break;
			}	


			$regla = str_replace(" ", "", $data[$key][$RuleIdx]);

			$insert = [
				"regla" => utf8_encode($regla),
			];

			if (!in_array(["regla" => $data[$key][$RuleIdx]], $inDbRule)) {
				array_push($dataRule, $insert);
			}
		}

		return [
			self::save($dataRule, $inDbRule, new Regla, 					"regla"),
			self::save($dataVa,   $inDbVa,   new VerboAuxiliar, 	"verbo_auxiliar"),
			self::save($dataPr,   $inDbPr,   new PronombreReflex, "pronombre_reflex"),
			self::save($dataTv,   $inDbTv,   new TiempoVerbal, 		"tiempo"),
			self::save($dataFv,   $inDbFv,   new FormaVerbal, 		"forma_verbal")
		];
    
    }

    public static function save($data = array(), $inDb = array(), $Obj, $unique = ""){
			$data = VerbosController::unique_multidim_array($data, $unique);		
			$res = false;

			try {
				foreach ($data as $key => $value) {
					$v = in_array([$unique => $data[$key][$unique]], $inDb);
					if($v){
						continue;
					}else{
						$Obj::insert($data[$key]);
						array_push($inDb, $data[$key][$unique]);
						$res = true;
					}
				}
			} catch (QueryException $e) {
				return $res;
			}
			return $data;	    	
    }
}
