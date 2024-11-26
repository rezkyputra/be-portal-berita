<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Carbon\Carbon;
use App\Models\otpCode;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public static function boot()
    {
        parent::boot();

        static::created(function($model){
            $model->generateOtpCodeData();
        });
    }


    public function generateOtpCodeData()
    {
        $randomNumber = mt_rand(100000, 999999);

        $now = Carbon::now();

        $otp = otpCode::updateOrCreate(
            ['user_id' => $this->id],
            [
                'otp' =>  $randomNumber, 
                'valid_until' => $now->addMinutes(5)
            ]
        );
                
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
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

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class,'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }

    public function historyComment()
    {
        return $this->belongsToMany(News::class,'comments','user_id', 'news_id');
    }

    public function otpcode()
    {
        return $this->hasOne(otpCode::class, 'user_id');
    }
}
