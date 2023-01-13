<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
