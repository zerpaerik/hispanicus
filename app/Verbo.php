<?php

namespace hispanicus;

use Illuminate\Database\Eloquent\Model;

class Verbo extends Model
{
    protected $fillable = ['tipo_verbo_id', 'infinitivo', 'raiz', 'traduccion_1', 'traduccion_2'];
}
