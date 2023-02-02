<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
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

    // public function scopeSearchUser($query, $keyword)
    // {
    //     return $query->where('username', 'like', "%$keyword%")->orWhere('email', 'like', "%$keyword%");
    // }

    public function profile()
    {
        return $this->hasOne(Profile::class)->select("user_id", "first_name", "last_name", "username", "image", "birth_date", "phone_number");
    }

    public function following()
    {
        return $this->hasMany(Following::class, "user_id", "id");
    }

    public function followers()
    {
        return $this->hasMany(Following::class, "user_account", "id");
    }
}
