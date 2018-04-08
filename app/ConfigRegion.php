<?php

namespace hispanicus;

use Illuminate\Database\Eloquent\Model;

class ConfigRegion extends Model
{
    protected $fillable = [
    	'lang',
    	'modo',
    	'favs',
    	'user_id'
    ];
}
