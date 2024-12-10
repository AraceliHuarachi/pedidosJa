<?php

namespace App\Http\Requests;

use App\Rules\NamesValidation;
use App\Traits\TraitDecim;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="ProductRequest",
 *     type="object",
 *     required={"name", "reference_price"},
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="reference_price", type="number", format="float")
 * )
 */
class ProductRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'min:3',
                'max:30',
                new NamesValidation('/^[a-zA-Z0-9\sñÑáéíóúÁÉÍÓÚ]+$/'), //usando un patron personalizado
                Rule::unique('products', 'name')->ignore($this->product),
            ],
            'reference_price' => [
                'required',
                'numeric',
                'gt:0',
                'max:1000',
                'regex:/^\d{1,4}(\.\d{1,2})?$/'
            ]
        ];
    }
}
