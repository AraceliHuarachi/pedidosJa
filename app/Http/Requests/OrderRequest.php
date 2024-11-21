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
            'reason' => 'required|string|max:150',
            'delivery_user_id' => 'required|exists:users,id',
            'order_date' => 'required|date|after_or_equal:today',
            'state' => 'sometimes|in:draft,in_process,completed',
        ];
    }
}
