<?php

namespace hispanicus;

use Illuminate\Database\Eloquent\Model;

class RaizDesinencias extends Model
{
    protected $fillable = [

    	'pronombre_id',
    	'tiempo_verbal_id',
    	'forma_verbal_id',
    	'raiz_id',
    	'desinencia_id',
    	'negativo',
    	'pronombre_reflex_id'

    ]; 
}
