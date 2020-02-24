<?php

namespace App\Imports;

use App\Client;
use App\Timesheet;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TimesheetsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Timesheet
     */
    public function model(array $row)
    {
        return new Timesheet([
            "lawyer_id" => $row["lawyer_id"],
            "client_id" => Client::where("name", $row["client"])->firstOrFail()->id,
            "start_time" => $row["date"]." ".$row["start"].":00",
            "end_time" => $row["date"]." ".$row["end"].":00",
        ]);
    }
}
