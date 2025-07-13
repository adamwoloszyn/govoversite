<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\DJB2_Hash;

class WordContractions extends Model
{
    use HasFactory;

    protected $table = 'word_contractions';

    protected $primaryKey = 'id';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->hash = DJB2_Hash::Hash($model->contraction);
        });
    }   // end of boot()
}   // end of WordContractions class
