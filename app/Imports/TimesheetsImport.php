<?php

namespace App\Imports;

use App\Client;
use App\Timesheet;
use Maatwebsite\Excel\Concerns\ToModel;

class TimesheetsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Timesheet([
            "lawyer_id" => "lawyer_id",
            "client_id" => Client::where("name", $row["client"])->firstOrFail()->id,
            "start_time" => $row["date"]." ".$row["start"].":00",
            "end_time" => $row["date"]." ".$row["end"].":00",
        ]);
    }
}
