<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoice extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $url_arr = explode('/',$this->url());
        $url_id = $url_arr[count($url_arr) - 1];

        $start_date = $this->get("date_range") ? explode(' - ', $this->get("date_range"))[0]. " 00:00:00" : null;
        $end_date = $this->get("date_range") ? explode(' - ', $this->get("date_range"))[1] . " 23:59:59" : null;

        $this->merge([
            'id' =>  $url_id,
            'start_date' => $start_date,
            "end_date" => $end_date,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "client_id" => "required",
            "date_range" => "required",
            "start_date" => "required|date",
            "end_date" => "required|after:start_date|unique_with:invoices,start_date,client_id,$this->id",

        ];
    }

    public function messages()
    {
        return [
            'date_range.required' => "Please select a Date Range",
            'client_id.required' => 'Please select a Client',
            'end_date.unique_with' => 'There exists an invoice for this client for the selected period, Update or Delete it!',
        ];
    }
}
