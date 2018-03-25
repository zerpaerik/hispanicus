<?php

namespace hispanicus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use hispanicus\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use hispanicus\Verbo;
use hispanicus\TipoVerbo;
use Illuminate\Database\QueryException;
use Validator;
use hispanicus\Http\Controllers\Admin\DesinenciaController;
use hispanicus\Http\Controllers\Admin\PersonasGramaticalController;

class VerbosController extends Controller
{
    public function upload(Request $request){

    	$data = $this->loadFile($request)["data"];

    		return PersonasGramaticalController::getStaticData($data);

        return response()->json(["data" => $data]);
	}

	public function store(Request $request){

		$sheetData = $this->loadFile($request)["sheet"];

		$s  = $this->storeTipoVerboData($sheetData);
		$ss = $this->storeVerboData($sheetData);
		$sss = DesinenciaController::storeDesinencia($sheetData);

        return response()->json([
        	"new_types" => $s,
        	"new_verbs" => $ss,
        	"new_des"   => $sss,
        ]);

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

	public function storeVerboData($data = array()){

		$dataVerbo = array();
		array_shift($data);	
		$tv = [''];
		$inDb = Verbo::all(['nombre'])->toArray();

		foreach ($data as $key => $value) {
			
			$raiz   	= $data[$key]["A"];
			$tipo   	= $data[$key]["K"];
			$modo   	= $data[$key]["C"];
			$cambio 	= $data[$key]["E"];
			$desinencia = $data[$key]["B"];

			$tv = TipoVerbo::where('nombre', $tipo)->get(["id"]);
			
			$nombre = $cambio ? $desinencia : $raiz . $desinencia;

			if(!$tv || $modo != 'infinitivo') continue;

			array_push($dataVerbo, [
				'infinitivo' => strtolower($nombre),
				'raiz' => strtolower($raiz),
				'tipo_verbo_id' => $tipo_id->id,
				'created_at'=>date('Y-m-d H:i:s'),
				'updated_at'=> date('Y-m-d H:i:s')
			]);
		}

		$dataVerbo = self::unique_multidim_array($dataVerbo, 'nombre');
		$res = false;

		try {
			foreach ($dataVerbo as $key => $value) {
				$v = in_array(["nombre" => $dataVerbo[$key]["nombre"]], $inDb);
				if($v){
					continue;
				}else{
					Verbo::insert($dataVerbo[$key]);
					array_push($inDb, $dataVerbo[$key]["nombre"]);
					$res = true;
				}
			}
		} catch (QueryException $e) {
			return $res;
		}
		return $res;		
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