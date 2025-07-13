<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video_AgendaItemTimeStamps extends Model
{
    use HasFactory;

    protected $table = 'video_agenda_item_time_stamps';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'video_id',
        'comment',
        'video_jump_point',
        'is_enabled',
    ];

    public function AssociatedVideo()
    {
        return $this->belongsTo('App\Models\Videos', 'video_id');
    }

}   // end of Video_AgendaItemTimeStamps class
