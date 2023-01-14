<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function childFolders($folder)
    {
        if (!is_null($folder)) {
            $find = Folder::where('url', $folder)->first();
            $child = Folder::where('folder_id', $find['id'])->first();
            $child_arr = [];
            $path = null;
            $cnt = 0;
            while (true) {
                if ($cnt === 0) {
                    $c = Folder::where('folder_id', $find['id'])->first();
                    if (!is_null($c)) {
                        array_push($child_arr, $c);
                    }
                }
                if (is_null($c)) {
                    break;
                } else {
                    $c = Folder::where('folder_id', $c['id'])->first();
                    if (!is_null($c)) {
                        array_push($child_arr, $c);
                    }
                }
                $cnt++;
            }
            if (!empty($child_arr)) {
                return $child_arr;
            } else {
                return null;
            }
        }
    }

    public function getFileSize($byte)
    {
        $kb = $byte / 1024;
        $mb = $kb / 1024;
        $gb = $mb / 1024;

        if ($mb >= 1) $result = round($mb, 1) . 'MB';
        else if ($gb >= 1) $result = round($gb, 1) . 'GB';
        else $result = round($kb, 1) . 'KB';

        return $result;
    }

}
