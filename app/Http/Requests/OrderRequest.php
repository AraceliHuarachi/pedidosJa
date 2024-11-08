<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'order' => 'required|array',
            'order.description' => 'required|string|max:150',
            'order.delivery_user_id' => 'required|exists:users,id',
            'order.order_date' => 'required|date',
            'order.order_users' => 'required|array',
            'order.order_users.*.user_id' => 'required|exists:users,id',
            'order.order_users.*.amount_money' => ['required', 'numeric', 'gt:0', 'max:1000', 'regex:/^\d{1,4}(\.\d{1,2})?$/'],
            'order.order_users.*.products' => 'required|array',
            'order.order_users.*.products.*.product_id' => 'required|exists:products,id',
            'order.order_users.*.products.*.quantity' => 'required|integer|min:1|max:100',
            'order.order_users.*.products.*.description' => 'nullable|string|max:150',
            'order.order_users.*.products.*.final_price' => ['required', 'numeric', 'gt:0', 'max:1000', 'regex:/^\d{1,4}(\.\d{1,2})?$/'],
        ];
    }
}
