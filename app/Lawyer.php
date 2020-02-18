<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lawyer extends Model
{
    protected $guarded = ["id"];

    public function title()
    {
        return $this->belongsTo(Title::class);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }
}
