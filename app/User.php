<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function setPasswordAttribute($password)
    {
        if ( !empty($password) ) {
            $this->attributes['password'] = bcrypt($password);
        }
    }
    public function courses()
    {
        return $this->belongsToMany(Course::class)->using('\App\CourseUser');
    }
    public function hasCourse($id) : bool
    {
        return (bool) $this->courses()->where('course_id',$id)->first();
    }
    public function getCourse($id)
    {
        return $this->courses()->where('course_id',$id)->first();
    }
    public function getRegisteredDate($id)
    {
        $sql = "SELECT `created_at` FROM `course_user` WHERE `user_id`='$this->id' AND course_id='$id'";
        $date = DB::select( DB::raw($sql));
        return $date;
    }
}
