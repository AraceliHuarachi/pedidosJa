<?php

namespace App\Http\Requests;

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
                'alpha_num',
                Rule::unique('products', 'name')->ignore($this->product),
            ],
            'reference_price' => ['required', 'numeric', 'gt:0', 'decimal:0,2', 'max_digits:7']
        ];
    }
}
