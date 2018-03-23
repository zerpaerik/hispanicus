<?php

namespace hispanicus;

use Illuminate\Database\Eloquent\Model;

class PersonasGramatical extends Model
{
    protected $fillable = [
    	'pronombre',
    	'persona_gramatical',
    	'region_id',
    	'plural',
    	'formal',
    ];
}