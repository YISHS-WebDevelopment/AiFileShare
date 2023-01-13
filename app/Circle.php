<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Circle extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function folders(){
        return $this->belongsTo(Folder::class);
    }
}
