<?php

namespace App\Http\Requests;

use App\Traits\TraitDecim;
use Illuminate\Foundation\Http\FormRequest;

class OrderUserPostRequest extends FormRequest
{
    use TraitDecim;

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id', // El order_id debe existir
            'user_id' => 'required|exists:users,id', // El user_id debe existir
            'user_name' => 'required|string|min:3|max:255', // El nombre del usuario
            'amount_money' => 'nullable|numeric|min:0', // Amount money es opcional al crear
        ];
    }

    public function authorize(): bool
    {
        return true; // Si no tienes lógica de autorización, puede devolver true.
    }
}
