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
    public static function makeRelations($data = array(), $region){
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
				$ctvIdx	 = array_search('CTV', str_replace(" ", "", $data[0]));
				$nIdx	 	 = array_search('Nº', str_replace(" ", "", $data[0]));

			} catch (Exception $e) {
				return response()->json(["exception" => $e->getMessage]);			
			}

			$raiz_desinencia_data = array();
			$inDb = DesinenciaRaiz::all(["pronombre_id",
			"pronombre_formal_id",
			"tiempo_verbal_id",
			"forma_verbal_id",
			"raiz_id",
			"desinencia_id",
			"negativo",
			"pronombre_reflex_id",
			"verbo_auxiliar_id",
			"region",
			"ctv"])->toArray();
			array_shift($data);

			foreach ($data as $key => $value) {
				
				if (!array_key_exists($RaizIdx, $data[$key])) continue;

				$r = str_replace([" ", "[", "]"], ["", "", ""], $data[$key][$RaizIdx]);
				$reg = 0;

				if ($PiIdx && array_key_exists($PiIdx, $data[$key])) {
					$p = str_replace(" ", "", $data[$key][$PiIdx]);
					
					if (json_encode($p) == '"[ustedes]"') {
					 	$reg = 1;
					}else if (json_encode($p) == '"[vosotros,vosotras]"') {
					 	$reg = 2;
					}else if (json_encode($p) == '"[vos]"') {
						$reg = 3;
					}else if (json_encode($p) == '"t\u00fa"'){
						$reg = 4;
					}else{
						$reg = 0;
					} 
				}else{
					if ($nIdx && array_key_exists($nIdx, $data[$key])) {
						if ($region == 1 && $data[$key][$nIdx] == 5) {
							$reg = 2;
						}else if($region == 2 && $data[$key][$nIdx] == 5){
							$reg = 1;
						}else if($region == 3  && $data[$key][$nIdx] == 2){
							$reg = 3;
						}
					}
				}

				$raiz = (array_key_exists($RaizIdx, $data[$key]))
				? self::getFromDb(new Raiz, ['id'], 'nombre', utf8_encode($r)) : null;


				if (array_key_exists($DesIdx, $data[$key])) {

					$d = str_replace([" ", "[", "]"], ["", "", ""], $data[$key][$DesIdx]);

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
							
							$pronombre1 = str_replace([" ", "[", "]"], "", $data[$key][$p1]);
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

						$pronombre2 = str_replace([" ", "[", "]"], "", $data[$key][$p2]);
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
				
					$pronombre_reflex = str_replace([" ", "[", "]"], "", $data[$key][$PrIdx]);

					$pr = (array_key_exists($PrIdx, $data[$key]))
					? self::getFromDb(new PronombreReflex, ['id'], 'pronombre_reflex', utf8_encode($pronombre_reflex)) : null;

				}else{
					$pr = null;
				}
			}else{
				$pr = null;
			}

			if ($ctvIdx) {
			
				if (array_key_exists($ctvIdx, $data[$key])) {
				
					$ctv = str_replace(" ", "", $data[$key][$ctvIdx]);

				}else{
					$ctv = null;
				}
			}else{
				$ctv = null;
			}			

			if ($VaIdx) {
				
				if (array_key_exists($VaIdx, $data[$key])) {

				$verbo_auxiliar = str_replace([" ", "[", "]"], "", $data[$key][$VaIdx]);

				$va = (array_key_exists($VaIdx, $data[$key]))
				? self::getFromDb(new VerboAuxiliar, ['id'], 'verbo_auxiliar', utf8_encode($verbo_auxiliar)) : null;
				
				}else{
					$va = null;
				}				
			}else{
				$va = null;
			}


				$neg = (array_key_exists($NegIdx, $data[$key])) ? true:false;

				if (!$raiz) {
					
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
						"region" => $reg,
						"ctv" => $ctv

					];

				}

				array_push($raiz_desinencia_data, $insert);
			}

			return(self::save($raiz_desinencia_data, $inDb));

    }

    public static function getData($id, $region, $lang="es"){
    	
    	if (sizeof($region) > 1) {
	    	$desra = DesinenciaRaiz::whereIn('raiz_id', $id)
	    	->whereIn('region', $region)
	    	->orderBy('ctv', 'desc')
	    	->get(['desinencia_id', 'tiempo_verbal_id', 'forma_verbal_id', 'pronombre_reflex_id', 'negativo', 'pronombre_id', 'pronombre_formal_id', 'raiz_id', 'verbo_auxiliar_id', 'region', 'ctv']);
    	}else{
	    	$desra = DesinenciaRaiz::whereIn('raiz_id', $id)
	    	->orderBy('ctv', 'desc')
	    	->get(['desinencia_id', 'tiempo_verbal_id', 'forma_verbal_id', 'pronombre_reflex_id', 'negativo', 'pronombre_id', 'pronombre_formal_id', 'raiz_id', 'verbo_auxiliar_id', 'region', 'ctv']);
    	}

    	$times = [
	     "en" => [
	       "simple tenses",
	       "compound tenses",
	    	 ],
	     "es" => [
	       "tiempos simples",
	       "tiempos compuestos",
	  	   ],
		   ];

    	$a = array("indicativo" => [$times[$lang][0] => [], $times[$lang][1] => []],
    						 "subjuntivo" => [$times[$lang][0] => [], $times[$lang][1] => []], 
    						 "imperativo" => [$times[$lang][0] => []], 
    						 "F.N.P."     => [$times[$lang][0] => []]);

    	foreach ($desra as $dr) {
    		$tiempo = self::getValue($dr->tiempo_verbal_id, new TiempoVerbal, ['tiempo']);
    		$mv = self::getWhere($dr->ctv, $lang, $times);

	        if(in_array([$tiempo => []], $a[$mv[1]][$mv[0]])){
	          continue;
	        }else{
	          $a[$mv[1]][$mv[0]] += [$tiempo => []];
	        }
    	}

    	foreach ($desra as $dr) {

    		$tiempo = self::getValue($dr->tiempo_verbal_id, new TiempoVerbal, ['tiempo']);
    		$mv = self::getWhere($dr->ctv, $lang, $times);
    		
	    	array_push($a[$mv[1]][$mv[0]][$tiempo], [
	    	"raiz" => self::getValue($dr->raiz_id, new Raiz, ['nombre']),
    		"desinencia" => self::getValue($dr->desinencia_id, new Desinencia, ['desinencia']),
    		"forma_verbal" => self::getValue($dr->forma_verbal_id, new FormaVerbal, ['forma_verbal']),
    		'verbo_auxiliar' => self::getValue($dr->verbo_auxiliar_id, new VerboAuxiliar, ['verbo_auxiliar']),
    		"pronombre_reflex" => self::getValue($dr->pronombre_reflex_id, new PronombreReflex, ['pronombre_reflex']),
    		"pronombre" => self::getValue($dr->pronombre_id, new PersonasGramatical, ['pronombre', 'plural', 'persona_gramatical']),
    		"pronombre_formal_id" => self::getValue($dr->pronombre_formal_id, new PersonasGramatical, ['pronombre', 'plural', 'persona_gramatical']),
    		"negativo" => $dr->negativo,
    		"region" => $dr->region,
    		"plural" => (int)self::getValue($dr->pronombre_formal_id ?: $dr->pronombre_id, new PersonasGramatical, ['plural']),
    		"pg" => self::getValue($dr->pronombre_formal_id ?: $dr->pronombre_id, new PersonasGramatical, ['persona_gramatical']),
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

    public static function getWhere($val, $lang, $times){
    	$val = str_replace("tv", "", $val);
    	if ($val > 0 && $val < 6) {
    		return [$times[$lang][0], "indicativo"];
    	}else if($val > 5 && $val < 10){
    		return [$times[$lang][0], "subjuntivo"];
    	}else if($val == 10 || $val == 12 || $val == 13 || $val == 17 || $val == 16){
    		return [$times[$lang][1], "indicativo"];
    	}else if($val == 11 || $val == 14 || $val == 15 || $val == 18){
    		return [$times[$lang][1], "subjuntivo"];
    	}else if($val > 19 && $val < 25){
    		return [$times[$lang][0], "F.N.P."];
    	}else{
    		return [$times[$lang][0], "imperativo"];
    	}
    }

    public static function getFromDb($obj, $getFields, $where, $cVal){
    	$r = $obj::where($where, '=', $cVal)->get($getFields)->first();
    	
    	if ($r){
    		return $r->id;
    	}
    	return null;
    }

    public static function save($data, $inDb){
			$res = false;

			try {
				foreach ($data as $key => $value) {

					$v = self::unique($inDb, $data[$key]);
					error_log($v);
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

    public static function unique($a, $b){
    	
    	$r = false;

    	foreach ($a as $key => $value) {
    		if ($a[$key] == $b) {
    			return true;
    		}
    	}
    	return $r;
    }
}