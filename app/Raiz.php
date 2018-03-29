<?php

namespace hispanicus;

use Illuminate\Database\Eloquent\Model;

class Raiz extends Model
{
  protected $fillable = [
  	"raiz"
  ];

	public function desinencias()
	{
	    return $this->belongsToMany('hispanicus\Desinencia', $table="desinencia_raizs");
	}    

	public function tiempoVerbal()
	{
	    return $this->belongsToMany('hispanicus\TiempoVerbal', $table="desinencia_raizs");
	}

	public function formaVerbal()
	{
	    return $this->belongsToMany('hispanicus\FormaVerbal', $table="desinencia_raizs");
	}

	public function pronombreReflex()
	{
	    return $this->belongsToMany('hispanicus\PronombreReflex', $table="desinencia_raizs");
	}

}
