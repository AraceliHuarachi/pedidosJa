<?php

namespace App\Http\Requests;

use App\Traits\TraitDecim;
use Illuminate\Foundation\Http\FormRequest;

class OrderUserRequest extends FormRequest
{
    use TraitDecim;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function getAmountMoneyRules()
    {
        $order = $this->route('order_id')
            ? \App\Models\Order::find($this->route('order_id'))
            : null;
        $isDraft = $order && $order->status === 'draft';

        return array_merge(
            $isDraft ? ['nullable'] : ['required'],
            ['numeric', 'gt:0', 'max:1000', 'regex:/^\d{1,4}(\.\d{1,2})?$/']
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'user_id' => 'required|exists:users,id',
            'user_name' => ['required', 'string', 'min:3', 'max:20', 'regex:/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$/'],
            'amount_money' => $this->getAmountMoneyRules(),
        ];
    }
}
