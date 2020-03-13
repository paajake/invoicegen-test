<?php

namespace App;

use App\Http\Requests\StoreInvoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;

class Invoice extends Model
{
    protected $fillable = ["start_date", "end_date", "client_id", "invoice"];

    protected $dates = ['start_date', 'end_date'];


    /**
     * Returns an Array of Validated Attributes
     * @param StoreInvoice $request
     * @return array
     */
    public static function  preProcess(StoreInvoice $request)
    {

        $invoice_attributes = $request->validated();
        $invoice_attributes["invoice"] = time().".pdf";

        $client = Client::findOrFail($invoice_attributes["client_id"]);

        $start_date = $invoice_attributes["start_date"];
        $end_date = $invoice_attributes["end_date"];

        $billables = DB::select(
            "SELECT timesheets.lawyer_id,
                        CONCAT_WS(' ',titles.title, lawyers.first_name, lawyers.last_name) as lawyer,
                        SUM(TIMESTAMPDIFF(HOUR, timesheets.start_time, timesheets.end_time)) as hours,
                        ranks.rate AS rank_rate,
                        lawyers.addon_rate,
                        ranks.rate + (ranks.rate * lawyers.addon_rate * 0.01) as unit_rate,
                        (ranks.rate + (ranks.rate * lawyers.addon_rate * 0.01))  *
                        SUM(TIMESTAMPDIFF(HOUR, timesheets.start_time, timesheets.end_time)) as total_rate
                    FROM timesheets
                    JOIN lawyers
                        ON timesheets.lawyer_id = lawyers.id
                    JOIN titles
                        ON titles.id = lawyers.title_id
                    JOIN ranks
                        ON ranks.id = lawyers.rank_id
                    WHERE timesheets.client_id = $client->id
                        AND timesheets.start_time >= '$start_date'
                        AND timesheets.end_time <=  '$end_date'
                    GROUP BY timesheets.lawyer_id
                    ");

        $invoice_sum = 0;
        foreach ($billables as $billable)
        {
            $invoice_sum += $billable->total_rate;
        }

        $period = $invoice_attributes["date_range"];

        $invoice_pdf = PDF::loadView("invoices.invoice", compact("client", "period","invoice_sum", "billables"));
        $pdf_content = $invoice_pdf->download()->getOriginalContent();

        Storage::put("public/docs/gen/".$invoice_attributes["invoice"], $pdf_content, ["visibility" => "public"]) ;

        return $invoice_attributes;
    }
}
