<?php

namespace App\Http\Requests;

use App\Services\AmountValidationService;
use App\Traits\TraitDecim;
use Illuminate\Foundation\Http\FormRequest;

class OrderUserRequest extends FormRequest
{
    use TraitDecim;

    protected AmountValidationService $amountValidationService;

    public function __construct(AmountValidationService $amountValidationService)
    {
        $this->amountValidationService = $amountValidationService;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function validateAmountMoney(): void
    {
        //obtenemos los datos de la solicitud
        $orderId = $this->input('order_id');
        $userId = $this->input('user_id');
        $amountMoney = $this->input('amount_money');
       // $state = $this->input('state');
       //$state ="in_process";

       $order = \App\Models\Order::find($orderId);

       $state = $order['state'];

       $this->amountValidationService->validateAmountMoney($orderId, $userId, $amountMoney, $state);
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
     * Reglas de validacion que se aplican a la solicitud.
     *
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

    /**
     * preparamos la validacion antes de ejecutar. 
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->validateAmountMoney();
    }
}
