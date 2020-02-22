<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimesheet extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "lawyer_id" => "required",
            "client_id" => "required",
            "start_time" => "required|date",
            "end_time" => "required|date|after:start_time",
        ];
    }

    public function messages()
    {
        return [
            'lawyer_id.required' => "Please select a Lawyer",
            'client_id.required' => 'Please select a Client',
        ];
    }
}
