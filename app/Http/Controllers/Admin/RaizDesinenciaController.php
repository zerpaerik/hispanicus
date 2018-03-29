<?php

namespace hispanicus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use hispanicus\RaizDesinencias;
use hispanicus\Raiz;
use hispanicus\Verbo;
use hispanicus\Desinencia;
use hispanicus\PersonasGramatical;
use hispanicus\VerboAuxiliar;
use hispanicus\PronombreReflex;
use hispanicus\TiempoVerbal;
use hispanicus\Regla;
use hispanicus\FormaVerbal;
use hispanicus\Http\Controllers\Controller;

class RaizDesinenciaController extends Controller
{
    public static function makeRelations($data = array()){
			try {

				$RaizIdx = array_search('Raíz', 			   		 str_replace(" ", "", $data[0]));
				$DesIdx  = array_search('Desinencia',  		   str_replace(" ", "", $data[0]));
				$FvIdx   = array_search('Formaverbal', 		   str_replace(" ", "", $data[0]));	
				$TvIdx   = array_search('Tiempoverbal', 		 str_replace(" ", "", $data[0]));			
				$PiIdx   = array_search('Pronombreinformal', str_replace(" ", "", $data[0]));	
				$PfIdx   = array_search('Pronombreformal',	 str_replace(" ", "", $data[0]));
				$PrIdx   = array_search('Pronombrereflexivo',str_replace(" ", "", $data[0]));
				$NegIdx  = array_search('Negación', 				 str_replace(" ", "", $data[0]));
				$PgIdx   = array_search('Pers.gram.', 			 str_replace(" ", "", $data[0]));
				$VaIdx	 = array_search('Verboauxiliar', 		 str_replace(" ", "", $data[0]));
				$RuleIdx = array_search('Regla', 						 str_replace(" ", "", $data[0]));

			} catch (Exception $e) {
				return response()->json(["exception" => $e->getMessage]);			
			}

			$raiz_desinencia_data = array();
			$inDb = RaizDesinencias::all()->toArray();
			array_shift($data);

			foreach ($data as $key => $value) {
				
				if (!array_key_exists($RaizIdx, $data[$key])) continue;

				$r = str_replace(" ", "", $data[$key][$RaizIdx]);

				$raiz = (array_key_exists($RaizIdx, $data[$key]))
				? self::getFromDb(new Raiz, ['id'], 'nombre', utf8_encode($r)) : null;

				$d = str_replace(" ", "", $data[$key][$DesIdx]);

				$desinencia = (array_key_exists($DesIdx, $data[$key]))
				? self::getFromDb(new Desinencia, ['id'], 'desinencia', utf8_encode($d)) : null;
				
				$fv = (array_key_exists($FvIdx, $data[$key]))
				? self::getFromDb(new FormaVerbal, ['id'], 'forma_verbal', utf8_encode($data[$key][$FvIdx])) : null;

				$tiempo = $data[$key][$TvIdx];

				$tv = (array_key_exists($TvIdx, $data[$key]))
				? self::getFromDb(new TiempoVerbal, ['id'], 'tiempo', utf8_encode($tiempo)) : null;

				if (array_key_exists($PiIdx, $data[$key]) || array_key_exists($PfIdx, $data[$key])){

					if ($PiIdx) {

						$p1 = $PiIdx;

						if (array_key_exists($p1, $data[$key])) {
							
							$pronombre1 = str_replace(" ", "", $data[$key][$p1]);
							$pronombre1 = utf8_encode($pronombre1);
							$pronombre1 = explode(",", $pronombre1);
							$pronombre1 = array_filter($pronombre1);
							$pronombre1 = json_encode($pronombre1);							

						}else{ $pronombre1 = null; }

					}else{
						$pronombre1 = null;
					}

					if ($PfIdx){
						$p2 = $PfIdx;

						if (array_key_exists($p2, $data[$key])) {
							
							$pronombre2 = str_replace(" ", "", $data[$key][$p2]);
							$pronombre2 = utf8_encode($pronombre2);
							$pronombre2 = explode(",", $pronombre2);
							$pronombre2 = array_filter($pronombre2);
							$pronombre2 = json_encode($pronombre2);

						}else{ $pronombre2 = null; }

					}else{
						$pronombre2 = null;
					}

					$pi = self::getFromDb(new PersonasGramatical, ['id', 'persona_gramatical'], 'pronombre', $pronombre1);


					$pf = self::getFromDb(new PersonasGramatical, ['id', 'persona_gramatical'], 'pronombre', $pronombre2);

				}

			if ($PrIdx) {
			
				if (array_key_exists($PrIdx, $data[$key])) {
				
					$pronombre_reflex = str_replace(" ", "", $data[$key][$PrIdx]);

					$pr = (array_key_exists($PrIdx, $data[$key]))
					? self::getFromDb(new PronombreReflex, ['id'], 'pronombre_reflex', utf8_encode($pronombre_reflex)) : null;

				}else{
					$pr = null;
				}
			}else{
				$pr = null;
			}

			if ($VaIdx) {
				
				if (array_key_exists($VaIdx, $data[$key])) {

				$verbo_auxiliar = str_replace(" ", "", $data[$key][$VaIdx]);

				$va = (array_key_exists($VaIdx, $data[$key]))
				? self::getFromDb(new VerboAuxiliar, ['id'], 'verbo_auxiliar', utf8_encode($verbo_auxiliar)) : null;
				
				}else{
					$va = null;
				}				
			}else{
				$va = null;
			}

			if ($RuleIdx) {
				
				if (array_key_exists($RuleIdx, $data[$key])) {
											
					$regla = utf8_encode($data[$key][$RuleIdx]);

					$rule = (array_key_exists($RuleIdx, $data[$key]))
					? self::getFromDb(new Regla, ['id'], 'regla', $regla) : null;
				
				}	else {
					$rule = null;
				}
			}else{
				$rule = null;
			}

				$neg = (array_key_exists($NegIdx, $data[$key])) ? true:false;

				if (!$raiz || !$desinencia) {
					
					continue;

				}else{

					$insert = [

						"pronombre_id" => $pi,
						"pronombre_formal_id" => $pf,
						"tiempo_verbal_id" => $tv,
						"forma_verbal_id" => $fv,
						"raiz_id" => $raiz,
						"desinencia_id" => $desinencia,
						"negativo" => $neg,
						"pronombre_reflex_id" => $pr,
						"verbo_auxiliar_id" => $va,
						"regla_id" => $rule,

					];

				}

				array_push($raiz_desinencia_data, $insert);	
			}

			return(self::save($raiz_desinencia_data, $inDb));

    }


    public static function save($data, $inDb){
			$res = false;

			try {
				foreach ($data as $key => $value) {
					$v = in_array($data[$key], $inDb);
					if($v){
						continue;
					}else{
						RaizDesinencias::insert($data[$key]);
						array_push($inDb, $data[$key]);
						$res = true;
					}
				}
			} catch (QueryException $e) {
				return $res;
			}
			return $data;	    	    	
    }

    public static function getFromDb($obj, $getFields, $where, $cVal){
    	$r = $obj::where($where, '=', $cVal)->get($getFields)->first();
    	
    	if ($r){
    		return $r->id;
    	}
    	return null;
    }
}
