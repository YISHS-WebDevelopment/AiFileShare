<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];
    protected $keyType = 'string';
    public $timestamps = false;

    public function circle()
    {
        return $this->belongsTo(Circle::class);
    }
    public function posts(){
        return $this->hasMany(Board::class);
    }

}
