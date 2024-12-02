<?php

namespace App\Http\Requests;

use App\Models\OrderUser;
use App\Services\AmountValidationService;
use App\Traits\TraitDecim;
use Illuminate\Foundation\Http\FormRequest;

class OrderUserPutRequest extends FormRequest
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

        return [
            // amount_money ahora es siempre requerido
            'amount_money' => ['required', 'numeric', 'gt:0', 'max:1000', 'regex:/^\d{1,4}(\.\d{1,2})?$/'],
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
