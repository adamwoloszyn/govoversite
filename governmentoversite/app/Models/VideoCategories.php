<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoCategories extends Model
{
    use HasFactory;

    protected $table = 'video_categories';

    protected $primaryKey = 'id';

    public function parent()
    {
        return $this->belongsTo(VideoCategories::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(VideoCategories::class, 'parent_id');
    }

    public function Videos()
    {
        return $this->hasMany('App\Models\Videos', 'video_category_id');
    }   // end of Videos related classes

    // return a list of videocategories that are enabled, meaning they can be 
    // assigned to new videos
    public static function GetAllEnabledForAdmin()
    {
        $videoCategories = VideoCategories::with('children')
            ->withCount('children')
            ->whereNotNull('parent_id')
            ->orderBy('id')
            ->get();
            return $videoCategories;
    }   // end of GetAllEnabled()
    public static function GetAllEnabled()
    {
        $videoCategories = VideoCategories::with('children')
            ->withCount('children')
            ->whereNull('parent_id')
            ->orderBy('id')
            ->get();
            return $videoCategories;
    }   // end of GetAllEnabled()

}   // end of VideoCategories class