<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video_Keywords extends Model
{
    use HasFactory;

    protected $table = 'video_keywords';

    protected $primaryKey = 'id';

    // public function KeywordVideos()
    // {
    //     return $this->belongsTo('App\Models\Keywords', 'keyword_id');
    // }   // end of KeywordVideos related class

    // public function Video_Keywords()
    // {
    //     return $this->belongsTo('App\Models\Videos', 'videos_id');
    // }   // end of VideoKeywords related class
}   // end of Video_Keywords class
