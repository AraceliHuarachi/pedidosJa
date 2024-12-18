<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\SimplifyValidationErrors;
use Illuminate\Contracts\Validation\Validator;

class ExampleOrderRequest extends FormRequest
{
    use SimplifyValidationErrors;

    public function authorize()
    {
        return true;  // Permitir a todos para este ejemplo
    }

    public function rules()
    {
        return [
            'day_date' => 'required',
            'orders' => 'required|array',
            'orders.*.Order_Nro' => 'required|integer',
            'orders.*.date' => 'required|date',
            'orders.*.products' => 'required|array|min:1',
            'orders.*.products.*.order_product_id' => 'required|integer',
            'orders.*.products.*.quantity' => 'required|integer|min:1|multiple_of_five',
        ];
    }

    public function messages()
    {
        return [
            'orders.*.products.*.quantity.multiple_of_five' => 'the :attribute must be a multiple of five.',
        ];
    }
}
