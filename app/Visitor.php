<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{

    protected $table = 'visitors';

    protected $fillable = ['name', 'artist', 'path'];

}
