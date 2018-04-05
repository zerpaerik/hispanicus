<?php

namespace hispanicus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use hispanicus\DesinenciaRaiz;
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

				$RaizIdx = array_search('Raíz', str_replace(" ", "", $data[0]));
				$DesIdx  = array_search('Desinencia', str_replace(" ", "", $data[0]));
				$FvIdx   = array_search('Formaverbal', str_replace(" ", "", $data[0]));	
				$TvIdx   = array_search('Tiempoverbal', str_replace(" ", "", $data[0]));			
				$PiIdx   = array_search('Pronombreinformal', str_replace(" ", "", $data[0]));	
				$PfIdx   = array_search('Pronombreformal', str_replace(" ", "", $data[0]));
				$PrIdx   = array_search('Pronombrereflexivo', str_replace(" ", "", $data[0]));
				$NegIdx  = array_search('Negación', str_replace(" ", "", $data[0]));
				$PgIdx   = array_search('Pers.gram.', str_replace(" ", "", $data[0]));
				$VaIdx	 = array_search('Verboauxiliar', str_replace(" ", "", $data[0]));
				$RuleIdx = array_search('Regla', str_replace(" ", "", $data[0]));

			} catch (Exception $e) {
				return response()->json(["exception" => $e->getMessage]);			
			}

			$raiz_desinencia_data = array();
			$inDb = DesinenciaRaiz::all()->toArray();
			array_shift($data);

			foreach ($data as $key => $value) {
				
				if (!array_key_exists($RaizIdx, $data[$key])) continue;

				$r = str_replace(" ", "", $data[$key][$RaizIdx]);

				$raiz = (array_key_exists($RaizIdx, $data[$key]))
				? self::getFromDb(new Raiz, ['id'], 'nombre', utf8_encode($r)) : null;


				if (array_key_exists($DesIdx, $data[$key])) {

					$d = str_replace(" ", "", $data[$key][$DesIdx]);

					$desinencia = self::getFromDb(new Desinencia, ['id'], 'desinencia', utf8_encode($d));
					
				}else{
					$desinencia = null;
				}
				
				$fv = (array_key_exists($FvIdx, $data[$key]))
				? self::getFromDb(new FormaVerbal, ['id'], 'forma_verbal', utf8_encode($data[$key][$FvIdx])) : null;

				$tiempo = $data[$key][$TvIdx];

				$tv = (array_key_exists($TvIdx, $data[$key]))
				? self::getFromDb(new TiempoVerbal, ['id'], 'tiempo', utf8_encode($tiempo)) : null;

				if ($PiIdx){

						$p1 = $PiIdx;

						if (array_key_exists($p1, $data[$key])) {
							
							$pronombre1 = str_replace(" ", "", $data[$key][$p1]);
							$pronombre1 = utf8_encode($pronombre1);
							$pronombre1 = explode(",", $pronombre1);
							$pronombre1 = array_filter($pronombre1);
							$pronombre1 = json_encode($pronombre1);							

							$pi = self::getFromDb(new PersonasGramatical, ['id', 'persona_gramatical'], 'pronombre', $pronombre1);

						}else{ $pi = null; }

				}else{
					$pi = null;
				}

					if ($PfIdx){

						if (array_key_exists($PfIdx, $data[$key])) {

						$p2 = $PfIdx;

						$pronombre2 = str_replace(" ", "", $data[$key][$p2]);
						$pronombre2 = utf8_encode($pronombre2);
						$pronombre2 = explode(",", $pronombre2);
						$pronombre2 = array_filter($pronombre2);
						$pronombre2 = json_encode($pronombre2);

						$pf = self::getFromDb(new PersonasGramatical, ['id', 'persona_gramatical'], 'pronombre', $pronombre2);

					}else{
						$pf = null;
					}

				}else{
					$pf = null;
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

    public static function getData($id){
    	$desra = DesinenciaRaiz::where('raiz_id', $id)->where('negativo', '=', '0')->get(['desinencia_id', 'tiempo_verbal_id', 'forma_verbal_id', 'pronombre_reflex_id', 'negativo', 'pronombre_id', 'pronombre_formal_id', 'raiz_id', 'regla_id', 'verbo_auxiliar_id']);
    	$a = array();

    	foreach ($desra as $dr) {
    		$tiempo = self::getValue($dr->tiempo_verbal_id, new TiempoVerbal, ['tiempo']);

	        if(array_key_exists($tiempo, $a)){
	          continue;
	        }else{
	          $a[$tiempo] = [];
	        }
    	}

    	foreach ($desra as $dr) {

    		$tiempo = self::getValue($dr->tiempo_verbal_id, new TiempoVerbal, ['tiempo']);

	    	array_push($a[$tiempo], [
	    	"raiz" => self::getValue($dr->raiz_id, new Raiz, ['nombre']),
    		"desinencia" => self::getValue($dr->desinencia_id, new Desinencia, ['desinencia']),
    		"forma_verbal" => self::getValue($dr->forma_verbal_id, new FormaVerbal, ['forma_verbal']),
    		'verbo_auxiliar' => self::getValue($dr->verbo_auxiliar_id, new VerboAuxiliar, ['verbo_auxiliar']),
    		"pronombre_reflex" => self::getValue($dr->pronombre_reflex_id, new PronombreReflex, ['pronombre_reflex']),
    		"pronombre" => self::getValue($dr->pronombre_id, new PersonasGramatical, ['pronombre', 'plural', 'persona_gramatical']),
    		"pronombre_formal_id" => self::getValue($dr->pronombre_formal_id, new PersonasGramatical, ['pronombre', 'plural', 'persona_gramatical']),
    		'regla' => self::getValue($dr->regla_id, new Regla, ['regla']),
    		"negativo" => $dr->negativo,

    	]);
			}

    	return $a;
    }

    public static function getValue($id, $Obj, $values = ['*'], $utf8 = true){
    	
    	if (is_null($id)) return $id;
    	
    	$r = $Obj::where('id', $id)->get($values)->first();

    	if ($utf8) {
    		$r[$values[0]] = utf8_decode($r[$values[0]]);
    		$r = $r[$values[0]];
    		if (sizeof($values) > 1) {
    			$r = utf8_decode(implode(",", json_decode($r)));
    			$r = ($r == 'nosotros,nosotras') ? "nosotros/as" : $r;
    			$r = ($r == 'ellos,ellas') ? "ellos/as" : $r;
    			$r = ($r == 'vosotros,vosotras') ? "vosotros/as" : $r;
    			$r = str_replace(",", "/", $r);
    		}
    	}

    	return $r;
    }

    public static function save($data, $inDb){
			$res = false;

			try {
				foreach ($data as $key => $value) {
					$v = in_array($data[$key], $inDb);
					if($v){
						continue;
					}else{
						DesinenciaRaiz::insert($data[$key]);
						array_push($inDb, $data[$key]);
						$res = true;
					}
				}
			} catch (QueryException $e) {
				return $res;
			}
			return $res;	    	    	
    }

    public static function getFromDb($obj, $getFields, $where, $cVal){
    	$r = $obj::where($where, '=', $cVal)->get($getFields)->first();
    	
    	if ($r){
    		return $r->id;
    	}
    	return null;
    }
}
