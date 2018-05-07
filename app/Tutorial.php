<?php

namespace hispanicus;

use Illuminate\Database\Eloquent\Model;

class Tutorial extends Model
{
    protected $fillable = ['tutorial', 'lang', 'model', 'def', 'verbo_id'];
}
