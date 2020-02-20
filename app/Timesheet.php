<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $guarded = ['id'];

    protected $dates = [
        'start_time',
        'end_time',
    ];
}
