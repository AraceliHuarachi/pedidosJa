<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUserProductRequest extends FormRequest
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
            'order_users_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required|numeric|min:1|max:100',
            'description' => 'nullable|string|max:150',
            'final_price' => ['required', 'numeric', 'gt:0', 'decimal:0,2', 'max:7']
        ];
    }
}
