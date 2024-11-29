<?php

namespace App\Http\Requests;

use App\Services\OrderService;
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
            'd_user_name' => ['required', 'string', 'min:3', 'max:20', 'regex:/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$/'],
            'order_date' => 'required|date|after_or_equal:today',
            'state' => [
                'nullable', // Estado no es obligatorio
                'in:draft,in_process,completed,canceled', // Valores permitidos
                function ($attribute, $value, $fail) {
                    $order = $this->route('order'); // Obtener la orden desde la ruta
                    if ($value && !in_array($value, ['draft', 'in_process', 'completed', 'canceled'])) {
                        $fail("El estado {$value} no es válido.");
                    }

                    // Si el estado es enviado, validar que la transición sea válida
                    if ($value && $order) {
                        try {
                            app(OrderService::class)->changeOrderState($order, $value);
                        } catch (\Exception $e) {
                            $fail($e->getMessage());
                        }
                    }
                },
            ],
        ];
    }
}
