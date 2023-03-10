<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    const UPDATED_AT = null;

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function current_image() {
        return $this->hasMany(Boards_image::class, 'board_id', 'id');
    }
    public function current_like_user() {
        return $this->hasMany(Boards_like::class, 'board_id', 'id')->where('user_id', auth()->user()->id);
    }
    public function current_view_user() {
        return $this->hasMany(Boards_view::class, 'board_id', 'id')->where('user_id', auth()->user()->id);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
