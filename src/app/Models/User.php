<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'name',
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

    // profile 1対１
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    // item 1対多
    public function item()
    {
        return $this->hasMany(Item::class);
    }

    // favorite 1対多
    public function favorite()
    {
        return $this->hasMany(Favorite::class);
    }

    // comment 1対多
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    // order 1対多
    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
