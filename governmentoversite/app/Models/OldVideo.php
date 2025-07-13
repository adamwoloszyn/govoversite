<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OldVideo extends Model
{
    use HasFactory;
    protected $connection = 'secondary';
    protected $table = 'Highlight';

    protected $fillable = ['meetingId', 'part', 'time', 'description'];


}
