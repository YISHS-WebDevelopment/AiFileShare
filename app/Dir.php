<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dir extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $keyType = 'string';
}
