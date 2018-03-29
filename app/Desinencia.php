<?php

namespace hispanicus;

use Illuminate\Database\Eloquent\Model;

class Desinencia extends Model
{
    protected $fillable = [
    	'desinencia',
    	'pronombre_id',
		'tipo_desinencia_id',
    	'verbo_id',
    	'tiempo',
    	'region_id',
    	'negativo',
    	'cambia_neg'
    ];

public function raices()
{
    return $this->belongsToMany("hispanicus\Raiz", $table="desinencia_raizs");
    
}    


}
