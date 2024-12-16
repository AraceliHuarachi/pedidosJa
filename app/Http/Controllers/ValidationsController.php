<?

namespace App\Http\Controllers;

use App\traits\SimplifyValidationErrors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidationsController extends Controller
{
    use SimplifyValidationErrors;

    public function validateOrdersData(Request $request)
    {

        // Datos de ejemplo
        $data = [
            'day_date' => null,
            'orders' => [
                [
                    "Order_Nro" => 1,
                    "date" => "2024-12-11",
                    "products" => [
                        ["order_product_id" => 101, "quantity" => 2],
                        ["order_product_id" => 999, "quantity" => -1], // Error: cantidad negativa
                    ]
                ],
                [
                    "Order_Nro" => null, // Error: Order_Nro es requerido
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
            'orders.*.products.*.quantity' => 'required|integer|min:1', // Validación para cantidad
        ];

        // validacion 
        $validator = Validator::make($data, $rules);

        // Verificar si la validación pasó o falló
        if ($validator->fails()) {
            // Si falla, almacenar los errores
            $errors = $validator->errors()->toArray();

            //Simplificar los mensajes de error:
            $simplifiedErrors = $this->simplifyErrorMessages($errors);

            return response()->json(['errors' => $simplifiedErrors], 400);

            // dd($errors); //ver los errores sin simplificar
            // dd($simplifiedErrors);
        }
        // Si pasa la validación
        // dd('Los datos son válidos');
        return response()->json(['message' => 'Datos válidos']);
    }
}
