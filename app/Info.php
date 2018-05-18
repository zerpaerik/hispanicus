<?php

namespace hispanicus;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    protected $fillable = ['tipo', 'info', 'lang', 'title'];
    public $timestamps = false;
}
