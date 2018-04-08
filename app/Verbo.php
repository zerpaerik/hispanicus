<?php

namespace hispanicus;

use Illuminate\Database\Eloquent\Model;

class Verbo extends Model
{
    protected $fillable = ['tipo_verbo_id', 'infinitivo', 'raiz', 'raiz_2', "def", "modelo", "tutorial"];
}
