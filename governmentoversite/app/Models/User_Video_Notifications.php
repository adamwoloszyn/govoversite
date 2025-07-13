<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Video_Notifications extends Model
{
    use HasFactory;

    protected $table = 'user_video_notifications';

    protected $primaryKey = 'id';

    public function Videos()
    {
        return $this->belongsTo('App\Models\Videos', 'video_id');
    }

    public function Users()
    {
        return $this->belongsTo('App\Models\Users', 'user_id');
    }

    public static function GetEmailsToSendOut()
    {
        return User_Video_Notifications::where("was_email_sent_out", "=", false)
            ->where("was_email_body_built", "=", true)
            ->get();
    }   // end of GetEmailsToSendOut()

    public static function GetEmailsToBuild()
    {
        return User_Video_Notifications::where("was_email_sent_out", "=", false)
            ->where("was_email_body_built", "=", false)
            ->get();
    }   // end of GetEmailsToBuild()
}   // end of User_Video_Notifications class