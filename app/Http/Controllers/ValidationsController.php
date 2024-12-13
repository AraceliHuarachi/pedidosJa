<?

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidationsController extends Controller
{
    public function validateOrdersData()
    {

        // Datos de ejemplo
        $data = [
            'date' => null,
            'orders' => [
                [
                    "Nro" => 1,
                    "date" => "2024-12-11",
                    "products" => [
                        ["id" => 101, "quantity" => 2],
                        ["id" => 999, "quantity" => -1], // Error: cantidad negativa
                    ]
                ],
                [
                    "Nro" => null, // Error: Nro es requerido
                    "date" => "2024-12-10",
                    "products" => [
                        ["id" => null, "quantity" => 4],
                        ["id" => 104, "quantity" => 0.3], //rompe dos reglas
                    ]
                ]
            ]
        ];

        // Reglas de validación
        $rules = [
            'date' => 'required',
            'orders' => 'required|array',
            'orders.*.Nro' => 'required|integer',
            'orders.*.date' => 'required|date',
            'orders.*.products' => 'required|array|min:1',
            'orders.*.products.*.id' => 'required|integer', // Validación para ID de producto
            'orders.*.products.*.quantity' => 'required|integer|min:1', // Validación para cantidad
        ];


        $validator = Validator::make($data, $rules);

        // Verificar si la validación pasó o falló
        if ($validator->fails()) {
            // Si falla, almacenar los errores
            $errors = $validator->errors()->toArray();

            //Simplificar los mensajes de error:
            $simplifiedErrors = $this->simplifyErrorMessages($errors);

            // dd($errors); //ver los errores sin simplificar

            dd($simplifiedErrors);
        } else {
            // Si pasa la validación
            dd('Los datos son válidos');
        }
    }

    private function simplifyErrorMessages(array $errors)
    {
        $simplified = [];

        foreach ($errors as $key => $messages) {
            // Extraer el nombre del campo después del último punto
            if (preg_match('/\.([^.]+)$/', $key, $matches)) {
                $fieldName = $matches[1];
            } else {
                $fieldName = $key;
            }

            // Reemplazar el nombre completo del campo con el nombre simplificado
            foreach ($messages as $message) {
                // Si ya existe una entrada para este campo, agrega el mensaje
                if (isset($simplified[$key])) {
                    $simplified[$key][] = str_replace($key, $fieldName, $message);
                } else {
                    $simplified[$key] = [str_replace($key, $fieldName, $message)];
                }
            }
        }

        return $simplified;
    }
}
