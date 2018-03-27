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

class VerbosController extends Controller
{
	public function storeRegular(Request $request){
		$sheetData = $this->loadFile($request)["data"];

		$ss = $this->storeVerbs($sheetData);
		$s  = RaizController::storeRaiz($sheetData);
		$sss = DesinenciaController::storeDesinencia($sheetData);
		return response()->json([
			"new_verbs" => $ss,
			"new_roots" => $s,
			"new_des"   => $sss
		]);
	}

  public function upload(Request $request){

  	$data = $this->loadFile($request)["data"];

  	//return self::storeVerboData($data);

    return response()->json(["data" => $data]);
	}

	public function listVerbs(){
		$verbs = Verbo::all();
		return response()->json($verbs);
	}

	public function storeVerbs($data = array()){
		
		try {

			$InfIdx = array_search('Verbo', $data[0]);
			$RaizIdx = array_search('Raíz ', $data[0]);

		} catch (Exception $e) {
			return response()->json(["exception" => $e->getMessage]);			
		}

		array_shift($data);
		$insert = array();
		$dataVerbo = array();

		$inDb = Verbo::get(['infinitivo'])->where('tipo_verbo_id', '=', 1)->toArray();

		foreach ($data as $key => $value) {
			if (!array_key_exists($RaizIdx, $data[$key])) continue;

			$infinitivo = self::quitarSe($data[$key][$InfIdx]);
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
}