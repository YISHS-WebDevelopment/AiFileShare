<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use Traits\GetSize;
    protected $guarded = [];
    public $timestamps = false;

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function folder() {
        return $this->hasOne(Folder::class, 'id', 'folder_id');
    }
}
