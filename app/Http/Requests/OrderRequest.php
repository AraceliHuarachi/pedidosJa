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
            'description' => 'required|string', //la descripción es obligatoria
            'user_id' => 'required|array', // Debe ser un arreglo de IDs
            'user_id.*' => 'exists:users,id', // Validar que cada user_id exista en la tabla users
            'amount_money' => 'required|array', // Debe ser un arreglo de montos
            'amount_money.*' => 'numeric', // Cada monto debe ser numérico
            'order_date' => 'required|date', // Validar que sea una fecha
            'products' => 'required|array', // Debe ser un arreglo de productos
            'products.*.product_id' => 'required|exists:products,id', // Validar que cada product_id exista en la tabla products
            'products.*.quantity' => 'required|integer|min:1', // Validar que la cantidad sea un número entero mayor a 0
            'products.*.final_price' => 'required|numeric', // Validar que el precio final sea numérico
        ];
    }
}
