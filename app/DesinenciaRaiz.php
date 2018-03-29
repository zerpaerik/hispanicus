<?php

namespace hispanicus;

use Illuminate\Database\Eloquent\Model;

class DesinenciaRaiz extends Model
{
    protected $fillable = [

    	'pronombre_id',
        'pronombre_formal_id',
    	'tiempo_verbal_id',
    	'forma_verbal_id',
    	'raiz_id',
    	'desinencia_id',
    	'negativo',
    	'pronombre_reflex_id',
    	'verbo_auxiliar_id',
    	'regla_id',
    ]; 
}
