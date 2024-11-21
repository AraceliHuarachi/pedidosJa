<?php

namespace App\Http\Requests;

use App\Traits\traitDecim;
use Illuminate\Foundation\Http\FormRequest;

class OrderUserProductRequest extends FormRequest
{
    use TraitDecim;
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
            'order_user_id' => $this->isMethod('post') ? 'required|exists:order_users,id' : 'sometimes|exists:order_users,id',
            'product_id' => $this->isMethod('post') ? 'required|exists:products,id' : 'sometimes|exists:products,id',
            'product_name' => ['required', 'string', 'min:3', 'max:30', 'regex:/^[a-zA-Z0-9\sñÑáéíóúÁÉÍÓÚ]+$/'],
            'quantity' => 'required|numeric|min:1|max:100',
            'description' => 'nullable|string|max:255',
            'final_price' => ['required', 'numeric', 'gt:0', 'max:1000', 'regex:/^\d{1,4}(\.\d{1,2})?$/'],
        ];
    }
}
