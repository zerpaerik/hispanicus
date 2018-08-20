<?php

namespace hispanicus;

use Illuminate\Database\Eloquent\Model;

class AppCode extends Model
{
    protected $fillable = [
    	"code", "device_id", "user_id"
    ];
}
