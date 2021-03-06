<?php

namespace hispanicus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use hispanicus\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use hispanicus\Verbo;
use hispanicus\TipoVerbo;
use hispanicus\Raiz;
use hispanicus\ConfigRegion;
use hispanicus\Tutorial;
use hispanicus\Info;
use Illuminate\Database\QueryException;
use Validator;
use hispanicus\Http\Controllers\Admin\DesinenciaController;
use hispanicus\Http\Controllers\Admin\RaizController;
use hispanicus\Http\Controllers\Admin\PersonasGramaticalController;
use hispanicus\Http\Controllers\Admin\DataStatica;
use hispanicus\Http\Controllers\Admin\RaizDesinenciaController;

class VerbosController extends Controller
{
	public function storeRegular(Request $request){
		$sheetData = $this->loadFile($request)["data"];

		$s1 = $this->storeVerbs($sheetData, $request["tipo"], $request["reflexOnly"]);
		$s2 = RaizController::storeRaiz($sheetData);
		$s3 = DesinenciaController::storeDesinencia($sheetData);
		$s4 = DataStatica::storeStaticData($sheetData, $request["region"], $request["lang"]);
		$s5 = RaizDesinenciaController::makeRelations($sheetData, $request["region"]);

		return response()->json([
			"new_verbs" 	=> $s1,
			"new_roots" 	=> $s2,
			"new_des"   	=> $s3,
			"new_static" 	=> $s4,
			"merges" 		=> $s5
		]);
	}

	public function storeDict(Request $request){
		$sheetData = $this->loadFile($request)["data"];
		$s1 = $this->setDictionaries($sheetData, $request["lang"]);

		return response()->json([
			"Dicts" => $s1,
		]);
	}

	public function storeInfo(Request $request){
		$sheetData = $this->loadFile($request)["data"];
		$s1 = $this->setInfo($sheetData, $request["lang"]);

		return response()->json([
			"Dicts" => $s1,
		]);
	}

	public function getInfo($lang, $tipo){
		$info = Info::where('tipo', '=', $tipo)->where('lang', '=', $lang)->get(['info', 'title'])->first();
		if (!$info) return response()->json([], 404);
		return response()->json([
			"title" => utf8_decode($info->title),
			"info" => utf8_decode($info->info),
		]);
	}

	public function getVerb($id, Request $request){

		$v = Verbo::where('id', $id)->get()->first();
		if (!$v) return response()->json(["message" => "not_found"], 404);

		$raices = Raiz::where('verbo_id', $id)->get(['id']);

		$reglas = \DB::table('reglas')
		->where('verbo_id', '=', $id)
		->where('region', '=', $request["modo"])
		->where("lang", '=', $request['lang'])
		->get(["regla", "tiempo", "forma"]);

		$r = array();

		foreach ($raices as $root) {
		  array_push($r, $root->id);
		}

		foreach ($reglas as $key => $value) {
			$reglas[$key]->regla = utf8_decode($reglas[$key]->regla);
		}

		return response()->json([
			"verbo" => $v->infinitivo,
			"reflexOnly" => $v->reflex_only,
			"reglas" => self::timeOrder($reglas),
			"data" => RaizDesinenciaController::getData($r, json_decode($request["region"]), $request["lang"])
		]);

	}

	public function getFreeVerb($id, Request $request){

		$v = Verbo::where('id', $id)->get()->first();
		if (!$v) return response()->json(["message" => "not_found"], 404);

		$raices = Raiz::where('verbo_id', $id)->get(['id']);

		$reglas = \DB::table('reglas')
		->where('verbo_id', '=', $id)
		->where('region', '=', $request["modo"])
		->where("lang", '=', $request['lang'])
		->get(["regla", "tiempo", "forma"]);

		$r = array();

		foreach ($raices as $root) {
		  array_push($r, $root->id);
		}

		foreach ($reglas as $key => $value) {
			$reglas[$key]->regla = utf8_decode($reglas[$key]->regla);
		}

		return response()->json([
			"verbo" => $v->infinitivo,
			"reflexOnly" => $v->reflex_only,
			"reglas" => self::timeOrder($reglas),
			"data" => RaizDesinenciaController::getFreeData($r, json_decode($request["region"]), $request["lang"])
		]);

	}	

	public function listVerbs($tipo, $lang){
		$orderby = ($tipo == 1 || $tipo == 0) ? 'infinitivo' : 'modelo';
		
		if ($tipo == 1) {
			$verbs = \DB::table('verbos')->where('tipo_verbo_id', '=', $tipo)->orderBy($orderby)->get(['id','infinitivo']);
			$verbs = self::AlphaOrder($verbs, $lang);
		}elseif($tipo == 0){
			$verbs = \DB::table('verbos')->orderBy($orderby)->get(['id','infinitivo']);
			$verbs = self::AlphaOrder($verbs, $lang);
		}else{
			$verbs = \DB::table('verbos')->where('tipo_verbo_id', '=', $tipo)->get(['id','infinitivo']);
			$verbs = self::modelOrder($verbs, $lang);
		}
		return response()->json($verbs, 200);
	}

	public function listFavs(){
		$u = \Auth::user();
		$f = ConfigRegion::where('user_id', '=', $u->id)->get(['favs'])->first();
		$v = Verbo::whereIn('id', json_decode($f->favs))->get(['id', 'infinitivo']);
		return response()->json($v, 200);
	}

	public function getTutorial($id){
		$tutorial = \DB::table('tutorials')->where('verbo_id', '=', $id)->get(['tutorial'])->first();
		if (!$tutorial) {
			return response()->json(["message" => "not found"], 404);	
		}
		$tutorial->tutorial = utf8_decode($tutorial->tutorial);
		return response()->json($tutorial, 200);
	}

	public function storeVerbs($data = array(), $type, $reflexOnly){
		
		try {

			$InfIdx = array_search('verbo', str_replace(" ", "", $data[0]));
			$RaizIdx = array_search('raíz', str_replace(" ", "", $data[0]));

		} catch (Exception $e) {
			return response()->json(["exception" => $e->getMessage]);			
		}

		array_shift($data);
		$insert = array();
		$dataVerbo = array();

		$inDb = Verbo::get(['infinitivo'])->toArray();

		foreach ($data as $key => $value) {
			if (!array_key_exists($RaizIdx, $data[$key])) continue;

			$infinitivo = str_replace(" ", "", self::quitarSe($data[$key][$InfIdx]));

			if (strlen($infinitivo) == 0) continue;

			$insert = [
				"infinitivo" => $infinitivo,
				"tipo_verbo_id" => $type,
				"reflex_only" => $reflexOnly
			];

			
			if (!in_array(["infinitivo" => self::quitarSe($data[$key][$InfIdx])], $inDb)) {
				array_push($dataVerbo, $insert);
			}
		}
		return (self::save($dataVerbo, $inDb));
	}

	public static function save($dataVerbo, $inDb){
		$dataVerbo = self::unique_multidim_array($dataVerbo, 'infinitivo');		
		$res = false;

		try {
			foreach ($dataVerbo as $key => $value) {
				$v = in_array(["infinitivo" => $dataVerbo[$key]["infinitivo"]], $inDb);
				if($v){
					continue;
				}else{
					Verbo::insert($dataVerbo[$key]);
					array_push($inDb, $dataVerbo[$key]["infinitivo"]);
					$res = true;
				}
			}
		} catch (QueryException $e) {
			return $res;
		}
		return $dataVerbo;				
	}

	public static function unique_multidim_array($array, $key) { 
	    $temp_array = array(); 
	    $i = 0; 
	    $key_array = array(); 
	    
	    foreach($array as $val) { 
	        if (!in_array($val[$key], $key_array)) { 
	            $key_array[$i] = $val[$key]; 
	            $temp_array[$i] = $val; 
	        } 
	        $i++; 
	    } 
	    return $temp_array; 
	}

	public static function quitarSe($value){
	  

	  $len = strlen($value);
	  if ($len > 2) {
		  $pos = strrpos($value, "se");
		  if ($pos == ($len - 2)) $value = substr_replace($value, "", $pos);

		  return $value;
	  }

	  return $value;
	}	

	public static function extractVal($data, $value, $utf8 = false){
		
		$d = array();

		for ($i = 0; $i < sizeof($data); $i++) {

			$v = $utf8 ? utf8_decode($data[$i][$value]) : $data[$i][$value];	
			
			array_push($d, $v);	
		
		}
		return array_values($d);
	}

	public function loadFile(Request $request){
		$file = $request->file('file') ?: '';

		try {
			$spreadsheet = IOFactory::load($file);
			$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
			
			$data = array();
			
			foreach ($sheetData as $key => $value) {
				$d = array_filter($sheetData[$key]);
				array_push($data, $d);
			}
			
			return ["data" => $data, "sheet" => $sheetData];
		} catch (Exception $e) {
		    return response()->json($e->getMessage());
		}
	}

  	public function upload(Request $request){

  	$data = $this->loadFile($request)["data"];
  	
    return response()->json(["data" => $data]);

	}

	public static function AlphaOrder($data, $lang="en"){

	    $ordered = array();
	    
	    foreach($data as $d){
	        
	        if(array_key_exists($d->infinitivo[0], $ordered)){
	          continue;
	        }else{
	          $ordered[$d->infinitivo[0]] = [];
	        }
		}

		foreach ($data as $d) {
			$def = Tutorial::where('verbo_id', '=', $d->id)->where('lang', '=', $lang)->get(['def'])->first();
			if ($def) { $def = $def->def;	}else{ $def = null; }
			array_push($ordered[$d->infinitivo[0]], [
				"id" => $d->id,
				"infinitivo" => $d->infinitivo,
				"def" => str_replace('"', " ", $def),
			]);
		}
			
		return $ordered;

	}

	public static function timeOrder($data){

		$ordered = array();

    foreach($data as $d){
	        if(array_key_exists($d->tiempo, $ordered)){
	          continue;
	        }else{
	          $ordered[$d->tiempo] = [];
	        }
		}

		foreach ($data as $d) {
			array_push($ordered[$d->tiempo], [
				"regla" => $d->regla,
				"forma" => $d->forma
			]);
		}

		return $ordered;		

	}


	public static function modelOrder($data, $lang){

		$ordered = array();
		$other = ["es" => "Otros", "en" => "Others", "pt" => "Others", "zh-CN" => "其余"];
    foreach($data as $d){
    		$def = Tutorial::where('verbo_id', '=', $d->id)->where('lang', '=', $lang)->get(['model'])->first();
      	if ($def) { $d->modelo = $def->model ?: $other[$lang];	}else{ $def = new Tutorial(); $d->modelo = $other[$lang]; }

        if(array_key_exists($d->modelo, $ordered)){
          continue;
        }else{
          $ordered[$d->modelo] = [];
        }
		}

		foreach ($data as $d) {

			$def = Tutorial::where('verbo_id', '=', $d->id)->where('lang', '=', $lang)->get(['def', 'model'])->first();
			if ($def) { $deft = $def->def; $d->modelo = $def->model;	}else{ $deft = null; $d->modelo = $other[$lang]; }
			array_push($ordered[$d->modelo], [
				"id" => $d->id,
				"infinitivo" => $d->infinitivo,
				"def" => str_replace('"', " ", $deft),
			]);
		}

		return $ordered;		

	}

	public function setInfo($data = array(), $lang){
		
		try {

			$InfoIdx   = array_search('información', str_replace(" ", "", $data[0]));
			$TipoIdx   = array_search('tipo', str_replace(" ", "", $data[0]));
			$TituloIdx   = array_search('título', str_replace(" ", "", $data[0]));

		} catch (Exception $e) {}

		array_shift($data);
		$data = array_filter($data);
		$r = false;

		foreach ($data as $key => $value) {
			if (!array_key_exists($InfoIdx, $data[$key])) continue;

				$info = Info::where('tipo', '=', $data[$key][$TipoIdx])->where('lang', '=', $lang)->get()->first();

				if ($info) {
					$info->info = utf8_encode($data[$key][$InfoIdx]);
					$info->title = utf8_encode($data[$key][$TituloIdx]);
					$r = $info->save();
				}else{
					$info = new Info();
					$info->tipo = $data[$key][$TipoIdx];
					$info->info = utf8_encode($data[$key][$InfoIdx]);
					$info->title = utf8_encode($data[$key][$TituloIdx]);
					$info->lang = $lang;
					$r = $info->save();
				}
		}
		return $r;
	} 

	public function setDictionaries($data = array(), $lang){
		try {

			$DefIdx   = array_search('definiciónapp', str_replace(" ", "", $data[0]));
			$VerboIdx = array_search('verbo', 		    str_replace(" ", "", $data[0]));
			$ModelIdx = array_search('modelo', 		    str_replace(" ", "", $data[0]));
			$TutoIdx  = array_search('tutorial', 	    str_replace(" ", "", $data[0]));

		} catch (Exception $e) {}
		
		array_shift($data);
		$data = array_filter($data);
		$r  = false;

		foreach ($data as $key => $value) {

			if (!array_key_exists($VerboIdx, $data[$key])) continue;

				$vl   = $data[$key][$VerboIdx];
				$v    = Verbo::where('infinitivo', '=', $vl)->get()->first();
				if ($v) {
					$tuto = Tutorial::where('verbo_id', '=', $v->id)
					->where('lang', '=', $lang)
					->get()->first();
					if (!$tuto) {
						$tuto = new Tutorial();
						$tuto->verbo_id = $v->id;
					}	
				}else{
					continue;
				}

					$tuto->lang = $lang;
					
					if (array_key_exists($DefIdx, $data[$key])) {
						$tuto->def = $data[$key][$DefIdx];
					}else{
						$tuto->def = '';
					}

					if (array_key_exists($ModelIdx, $data[$key])) {
						$tuto->model = $data[$key][$ModelIdx];
					}

					if (array_key_exists($TutoIdx, $data[$key])) {
						$tuto->tutorial = utf8_encode($data[$key][$TutoIdx]);
					}

					$r = $tuto->save();

				
		}
		
		return $r;

	}

	public function searchVerbo($verbo){
		$v = Verbo::where('infinitivo', '=', $verbo)->get()->first();
		
		if (!$v) return response()->json(["message" => "not_found"], 404);
		
		$raices = Raiz::where('verbo_id', $v->id)->get(['id']);

		$r = array();

		foreach ($raices as $root) {
		  array_push($r, $root->id);
		}

		return response()->json([
			"verbo" => $v->infinitivo,
			"raices" => $r,
			"data" => RaizDesinenciaController::getData($r, ["*"])
		]);
	}

	public function delRaices(Request $request){
		$affectedRows = \DB::table('desinencia_raizs')->whereIn('raiz_id', $request["raices"])->delete();
		$verboid = \DB::table('verbos')->where('infinitivo', '=', $request["verbo"])->get(['id'])->first()->id;
		$affectedRows += \DB::table('reglas')->where('verbo_id', '=', $verboid)->delete();
		$affectedRows += \DB::table('raizs')->where('verbo_id', '=', $verboid)->delete();
		$affectedRows += \DB::table('tutorials')->where('verbo_id', '=', $verboid)->delete();
		$affectedRows += \DB::table('verbos')->where('id', '=', $verboid)->delete();

		return response()->json($affectedRows, 200);
	}

	public static function array_sort($array, $on, $order=SORT_ASC)
	{
	    $new_array = array();
	    $sortable_array = array();

	    if (count($array) > 0) {
	        foreach ($array as $k => $v) {
	            if (is_array($v)) {
	                foreach ($v as $k2 => $v2) {
	                    if ($k2 == $on) {
	                        $sortable_array[$k] = $v2;
	                    }
	                }
	            } else {
	                $sortable_array[$k] = $v;
	            }
	        }

	        switch ($order) {
	            case SORT_ASC:
	                asort($sortable_array);
	            break;
	            case SORT_DESC:
	                arsort($sortable_array);
	            break;
	        }

	        foreach ($sortable_array as $k => $v) {
	            $new_array[$k] = $array[$k];
	        }
	    }

	    return $new_array;
	}

	public function showUploadView(){

        return view('admin.verbos.create');
	}

	public function showUploadDictView(){

        return view('admin.verbos.dict');
	}

	public function showVerbView(){

        return view('admin.verbos.show');
	}

	public function showInfoView(){

        return view('admin.verbos.info');
	}	
}