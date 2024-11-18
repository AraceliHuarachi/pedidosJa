<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUserRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'order_id' => 'required|exists:orders,id',
            'amount_money' => ['nullable', 'numeric', 'gt:0', 'max:1000', 'regex:/^\d{1,4}(\.\d{1,2})?$/'],

        ];
    }
}
