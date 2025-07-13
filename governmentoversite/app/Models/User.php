<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Models\User_Video_Notifications;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'password',
        'checkout_session_id',
        'role',
        'image',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function role() : Attribute
    {
        return new Attribute(
            get : fn($value) => ["__pl8aceHolder__", "guest", "subscriber", "admin"][$value],
        );
    }   // end of role()

    #[Attribute]
    public function isAdmin() : bool
    {
        return $this->role == 'admin';
    }

    #[Attribute]
    public function isSubscriber() : bool
    {
        return $this->role == 'subscriber';
    }

    #[Attribute]
    public function isGuest() : bool
    {
        return $this->role == 'guest';
    }

    public function GetAllEnabled()
    {
        return VideoCategories::where('is_enabled', '=', true)->get();
    }   // end of GetAllEnabled()

    public function ValidSubscriptions()
    {
        return UserSubscription::where('user_id', '=', $this->id)
            ->where('is_enabled', '=', true)
            ->get();
    }   // end of ValidSubscriptions related class

    public function HasAValidSubscription()
    {
        $allSubscriptions = $this->ValidSubscriptions();

        $today = date('Y-m-d');
        $filteredCollection = $allSubscriptions->filter(function ($item) use ($today) {
            return $item['start_date'] <= $today && $today <= $item['end_date'];
        });

        return $filteredCollection->count() > 0;
    }   // end of HasAValidSubscription()

    public function Keywords()
    {
        return $this->belongsToMany(Keywords::class)->get();
    }   // Keywords many-to-many
    
    public function KeywordPaired()
    {
        return $this->belongsToMany(Keywords::class);
    }   // Keywords many-to-many

    public function Notifications()
    {
        return $this->hasMany('App\Models\User_Video_Notifications', 'user_id');
    }   // end of Notifications()

    public function HasUserBeenNotifiedAboutThisVideo($videoID)
    {
        $uvn = User_Video_Notifications::where("user_id", "=", $this->id)
            ->where("video_id", "=", $videoID);

        return $uvn->count() > 0;
    }   // end of HasUserBeenNotifiedAboutThisVideo()
}   // end of User class
