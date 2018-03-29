<?php

namespace hispanicus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use hispanicus\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use hispanicus\Verbo;
use hispanicus\TipoVerbo;
use hispanicus\Raiz;
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

		$s1 = $this->storeVerbs($sheetData);
		$s2 = RaizController::storeRaiz($sheetData);
		$s3 = DesinenciaController::storeDesinencia($sheetData);
		$s4 = DataStatica::storeStaticData($sheetData);
		$s5 = RaizDesinenciaController::makeRelations($sheetData);

		return response()->json([
			"new_verbs" 		=> $s1,
			"new_roots" 		=> $s2,
			"new_des"   		=> $s3,
			"new_static" 	  => $s4,
			"merges" 				=> $s5
		]);
	}

	public function getVerb($id){
		$v = Verbo::where('id', $id)->get(['infinitivo'])->first();
		$raices = Raiz::where('verbo_id', $id)->get(['id', 'nombre']);
		$desinencias = array();

		foreach ($raices as $raiz) {
			array_push($desinencias, [
				"raiz" => $raiz->nombre,
				"data" => RaizDesinenciaController::getData($raiz->id),
			]);	
		}

		return response()->json([
			"verbo" => $v->infinitivo,
			"data" => $desinencias
		]);
	}	

	public function listVerbs(){
		$verbs = \DB::table('verbos')->orderBy('infinitivo')->get(['id','infinitivo']);
		$verbs = self::AlphaOrder($verbs);
		return response()->json($verbs);
	}

	public function storeVerbs($data = array()){
		
		try {

			$InfIdx = array_search('Verbo', str_replace(" ", "", $data[0]));
			$RaizIdx = array_search('Raíz', str_replace(" ", "", $data[0]));

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
			$tipo_verbo = 1;

			$insert = [
				"infinitivo" => utf8_encode($infinitivo),
				"tipo_verbo_id" => $tipo_verbo
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
      $pos = strrpos($value, "se");
      if ($pos == ($len - 2)) $value = substr_replace($value, "", $pos);
      return $value;

  }	

	public function showUploadView(){

        return view('admin.verbos.create');
	}

	public static function extractVal($data, $value, $utf8 = false){
		
		$d = array();

		for ($i = 0; $i < sizeof($data); $i++) {

			$v = $utf8 ? utf8_decode($data[$i][$value]) : $data[$i][$value];	
			
			array_push($d, $v);	
		
		}
		error_log(sizeof($d));
		return array_values($d);
	}

	public function loadFile(Request $request){
		$file = $request->file('file') ?: '';
		
		/*
	    $validator = Validator::make($request->all(), [
	        'file' => 'required|mimes:xls,xlsx,ods,csv'
	    ]);

	    if ($validator->fails()) 
	        return response()->json(['error'=>$validator->errors()], 422);
        
        $file->store('files');
		*/

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

  	//return self::storeVerboData($data);

    return response()->json(["data" => $data]);
	}

	public static function AlphaOrder($data){

		$ordered = array();

    foreach($data as $d){
        if(array_key_exists($d->infinitivo[0], $ordered)){
          continue;
        }else{
          $ordered[$d->infinitivo[0]] = [];
        }
		}

		foreach ($data as $d) {
			array_push($ordered[$d->infinitivo[0]], $d);
		}

		return $ordered;

	}

}
