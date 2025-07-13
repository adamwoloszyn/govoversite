<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $table = 'configurations';

    protected $primaryKey = 'id';

    public static function GetConfigurationValue($key)
    {
        return Configuration::where( "key", "=", $key )->
            where( "is_enabled", "=", "1" )->
            get();
    }   // end of GetConfigurationValue()
}   // end of Configuration class
