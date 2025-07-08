<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesRequest extends FormRequest
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
            'client_id' => 'required|uuid',
            'entry_no' => 'required|integer',
            'sales_sale_return_no' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'transaction_type' => 'required|string|in:sale,sale return',
            //'customer_id' => 'required|integer',
            'mode_of_transaction' => 'required|string|in:cash,credit,bank transfer',
            'gross_amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'net_amount' => 'nullable|numeric',
            'vat_amount' => 'nullable|numeric',
            'grand_amount' => 'nullable|numeric',
        ];
    }
}
