<?php

namespace hispanicus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use hispanicus\RaizDesinencias;
use hispanicus\Raiz;
use hispanicus\Verbo;
use hispanicus\Desinencia;
use hispanicus\Http\Controllers\Controller;

class RaizDesinenciaController extends Controller
{
    public static function makeRelations($data = array()){
			try {

				$RaizIdx = array_search('Raíz ', $data[0]);
				$DesIdx  = array_search('Desinencia ', $data[0]);
				$FvIdx   = array_search('Forma verbal ', $data[0]);	
				$TvIdx   = array_search('Tiempo verbal', $data[0]);			
				$PiIdx   = array_search('Pronombre informal', $data[0]);	
				$PfIdx   = array_search('Pronombre formal', $data[0])
				$PrIdx   = array_search('Pronombre reflexivo', $data[0])
				$NegIdx  = array_search('Negación', $data[0])
				$PgIdx   = array_search('Pers. gram. ', $data[0])
				$VaIdx	 = array_search('Verbo auxiliar', $data[0]);
				$RuleIdx = array_search('Regla', $data[0]);

			} catch (Exception $e) {
				return response()->json(["exception" => $e->getMessage]);			
			}



    }
}
