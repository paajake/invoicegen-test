<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreLawyer extends FormRequest
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
        $this->merge([
            'id' =>  $this->segment(2), //get ID of lawyer being updated
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
            "first_name" => "required",
            "last_name" => "required",
            'image' => ['sometimes','image','mimes:jpeg,png,jpg,gif,svg','max:1024'],
            "rank_id" => "required",
            "addon_rate" => "nullable|numeric|min:0",
            "email" => "required|email|unique:lawyers,email,".$this->id,
            "phone" => "nullable|regex:/^[+]?\d{10,16}$/i",
        ];
    }

    public function messages()
    {
        return [
            'rank_id.required' => "Please select the lawyer's rank",
            'phone.regex' => 'Enter a valid phone number eg: +233123456789',
        ];
    }
}
