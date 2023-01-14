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

    public function folder() {
        return $this->hasOne(Folder::class, 'id', 'folder_id');
    }

    public function getFileSize($byte) {
        $kb = $byte / 1024;
        $mb = $kb / 1024;
        $gb = $mb / 1024;

        if($mb >= 1) $result = round($mb,1).'MB';
        else if ($gb >= 1) $result = round($gb,1).'GB';
        else $result = round($kb,1).'KB';

        return $result;
    }
}
