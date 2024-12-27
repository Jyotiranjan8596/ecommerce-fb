<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'transaction_charge' => 'required',
            'min_charge' => 'required',
            'max_charge' => 'required',
            'initial_letter_of_invoice' => 'required',
            'pos_code' => 'required',
            'entity_name' => 'required',
            'entity_address' => 'required',
            'entity_contact' => 'required',
            'comment' => 'required',
            'address' => 'required',
            'city' => 'required',
            'zip' => 'required',
        ];
    }
}
