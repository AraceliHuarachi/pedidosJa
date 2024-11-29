<?php

namespace App\Http\Requests;

use App\Models\OrderUser;
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
        $orderUser = $this->getOrderUser();

        if (!$orderUser) {
            throw new \Exception("No se encontró el registro de OrderUser asociado.");
        }

        //obtenemos los datos del objeto y amount_money de la solicitud.
        $orderId = $orderUser->order_id;
        $userId = $orderUser->user_id;
        $amountMoney = $this->input('amount_money');

        $order = \App\Models\Order::find($orderId);

        $state = $order->state;

        $this->amountValidationService->validateAmountMoney($orderId, $userId, $amountMoney, $state);
    }

    public function getAmountMoneyRules()
    {
        $order = $this->input('order_id')
            ? \App\Models\Order::find($this->input('order_id'))
            : null;
        $isDraft = $order && $order->state === 'draft';

        $rule = array_merge(
            $isDraft ? ['nullable'] : ['required'],
            ['numeric', 'gt:0', 'max:1000', 'regex:/^\d{1,4}(\.\d{1,2})?$/']
        );

        return $rule;
    }

    private function getOrderUser(): ?OrderUser
    {
        return $this->route('order_user') ?? $this->orderUser ?? null;
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
