<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExampleOrderRequest;
use App\traits\SimplifyValidationErrors;
use Illuminate\Support\Facades\Validator;

class ExampleOrderController extends Controller
{
    // Using the trait (for the method without FormRequest)
    use SimplifyValidationErrors;

    public function withFormRequest(ExampleOrderRequest $request)
    {
        // At this point, the data is already validated thanks to the FormRequest,
        // If the validation is successful, we can access the validated data
        $validatedData = $request->validated();

        // If the validation passes, we can continue with the business logic
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
                        ["order_product_id" => 104, "quantity" => 0.3], // Break two rules
                    ]
                ]
            ]
        ];

        // Validation rules
        $rules = [
            'day_date' => 'required',
            'orders' => 'required|array',
            'orders.*.Order_Nro' => 'required|integer',
            'orders.*.date' => 'required|date',
            'orders.*.products' => 'required|array|min:1',
            'orders.*.products.*.order_product_id' => 'required|integer',
            'orders.*.products.*.quantity' => 'required|integer|min:1|multiple_of_five',
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

        // Create a Validator instance
        $validator = Validator::make($data, $rules, $customMessages);

        // If validation fails
        if ($validator->fails()) {
            // Use the trait method for loose validations
            // return $this->forLooseValidations($validator);
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // If the validation passes, continue with the logic
        return response()->json([
            'message' => 'Validation successful',
            'data' => $data,
        ], 200);
    }
}
