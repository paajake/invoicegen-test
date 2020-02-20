<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    protected $fillable = ["name", "rate"];

//    public function lawyers()
//    {
//        return $this->hasMany(Lawyer::class);
//    }
}
