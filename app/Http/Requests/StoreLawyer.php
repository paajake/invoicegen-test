<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            "email" => "required|email|unique:lawyers,email,". ( explode('/',$this->url())[4] ?? 0 ), //fetch ID of Lawyer being updated
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
