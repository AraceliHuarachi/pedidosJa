<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExampleOrderRequest;
use App\traits\SimplifyValidationErrors;
use Illuminate\Support\Facades\Validator;

class ExampleOrderController extends Controller
{
    //usamos el trait (para el metodo sin FormRequest)
    use SimplifyValidationErrors;

    public function withFormRequest(ExampleOrderRequest $request)
    {
        // En este punto, los datos ya están validados gracias al FormRequest,
        // Si la validación es exitosa, podemos acceder a los datos validados
        $validatedData = $request->validated();

        // Si la validación pasa, podemos continuar con la lógica de negocio
        return response()->json([
            'message' => 'Validate Data',
            'data' => $validatedData
        ], 200);
    }

    public function withoutFormRequest()
    {
        // Sample data for validation
        $data = [
            'day_date' => null, // Error: cannot be null
            'orders' => [
                [
                    "Order_Nro" => 1,
                    "date" => "2024-12-11",
                    "products" => [
                        ["order_product_id" => 101, "quantity" => 2],
                        ["order_product_id" => 999, "quantity" => -1], // Error: negative quantity
                    ]
                ],
                [
                    "Order_Nro" => null, // Error: Order_Nro is required
                    "date" => "2024-12-10",
                    "products" => [
                        ["order_product_id" => null, "quantity" => 4],
                        ["order_product_id" => 104, "quantity" => 0.3], //rompe dos reglas
                    ]
                ]
            ]
        ];

        // Reglas de validación
        $rules = [
            'day_date' => 'required',
            'orders' => 'required|array',
            'orders.*.Order_Nro' => 'required|integer',
            'orders.*.date' => 'required|date',
            'orders.*.products' => 'required|array|min:1',
            'orders.*.products.*.order_product_id' => 'required|integer', // Validación para ID de producto
            'orders.*.products.*.quantity' => 'required|integer|min:1|multiple_of_five', // Validación para cantidad
        ];

        // Make custom Validation rule
        Validator::extend('multiple_of_five', function ($attribute, $value, $parameters, $validator) {
            return $value % 5 === 0;
        });

        // Custom messages with :attribute to reference field name dinamically
        $customMessages = [
            'day_date.required' => 'The :attribute field is required and cannot be empty.',
            'orders.*.products.*.quantity.integer' => 'The :attribute must be a valid interger, not a decimal.',
            'orders.*.products.*.quantity.multiple_of_five' => 'The :attribute must be a multiple of five.'
        ];

        // Crear una instancia de Validator
        $validator = Validator::make($data, $rules, $customMessages);

        // Si la validacion falla
        if ($validator->fails()) {
            // Usar el metodo del trait para validaciones sueltas
            return $this->forLooseValidations($validator);
        }

        // Si la validacion pasa, continuar con la logica
        return response()->json([
            'message' => 'Validation successful',
            'data' => $data,
        ], 200);
    }
}
