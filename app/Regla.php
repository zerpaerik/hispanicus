<?php

namespace hispanicus;

use Illuminate\Database\Eloquent\Model;

class Regla extends Model
{
    protected $fillable = [
    	'regla',
    	"region",   
		"lang",     
		"verbo_id", 
		"tiempo",   
		"forma"    
    ];
}
