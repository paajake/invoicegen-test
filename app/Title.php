<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    protected $fillable = ['title'];

//    public function lawyers()
//    {
//        return $this->hasMany(Lawyer::class);
//    }
}
