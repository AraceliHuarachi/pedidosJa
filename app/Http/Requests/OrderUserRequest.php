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
            throw new \Exception("The associated OrderUser record was not found.");
        }

        //obtenemos los datos del objeto y amount_money de la solicitud.
        $orderId = $orderUser->order_id;
        $userId = $orderUser->user_id;
        $amountMoney = $this->input('amount_money');
        $state = $orderUser->order->state;

        $this->amountValidationService->validateAmountMoney($orderId, $userId, $amountMoney, $state);
    }

    private function getOrderUser(): ?OrderUser
    {
        return OrderUser::find($this->route('order_user'));
    }

    public function getAmountMoneyRules()
    {
        $orderUser = $this->getOrderUser();

        if ($orderUser && $orderUser->order->state === 'draft') {
            return ['nullable', 'numeric', 'gt:0', 'max:1000', 'regex:/^\d{1,4}(\.\d{1,2})?$/'];
        }

        return ['required', 'numeric', 'gt:0', 'max:1000', 'regex:/^\d{1,4}(\.\d{1,2})?$/'];
    }


    /**
     * Reglas de validacion que se aplican a la solicitud.
     *
     */
    public function rules(): array
    {
        $rules = [];

        // Si es un método POST (crear)
        if ($this->isMethod('post')) {
            $rules = [
                'order_id' => 'required|exists:orders,id',
                'user_id' => 'required|exists:users,id',
                'user_name' => ['required', 'string', 'min:3', 'max:20', 'regex:/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$/'],
            ];
        }
        // Si es un método PUT o PATCH (actualizar)
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules = [
                'amount_money' => $this->getAmountMoneyRules(),
            ];
        }

        // Reglas para amount_money si es un PUT/PATCH o POST
        $rules['amount_money'] = $this->getAmountMoneyRules();

        return $rules;
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
