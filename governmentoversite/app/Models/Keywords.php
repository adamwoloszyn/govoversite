<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keywords extends Model
{
    use HasFactory;

    protected $table = 'keywords';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'keyword',
        'is_enabled',
    ];

    public static function GetAllEnabledKeywords($entriesPerPage)
    {
        if ($entriesPerPage == -1) 
        {
            return Keywords::where('is_enabled', true)->get();
        }
        else
        {
            return Keywords::where('is_enabled', true)->paginate($entriesPerPage);
        }
    }   // end of GetAllEnabledKeywordsPaginate()

    public static function GetAllDisabledKeywords()
    {
        return Keywords::where('is_enabled', false)->get();
    }   // end of GetAllDisabledKeywords()

    public static function GetAllKeywords()
    {
        return Keywords::all();
    }   // end of GetAllKeywords()

    public function KeywordVideos()
    {
        return $this->hasMany('App\Models\Video_Keywords', 'keyword_id');
    }   // end of KeywordVideos related class

    public function Users()
    {
        return $this->belongsToMany(User::class);
    }   // Users many-to-many
}   // end of Keywords class
