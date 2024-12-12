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
                    ["id" => 103, "quantity" => 4],
                    ["id" => 104, "quantity" => 3],
                    ["id" => 104, "total_quantity" => 3],
                ]
            ]
        ];

        // Reglas de validación
        $rules = [
            '*.Nro' => 'required|integer',
            '*.date' => 'required|date',
            '*.products' => 'required|array|min:1',
            '*.products.*.id' => 'required|integer', // Validación para ID de producto
            '*.products.*.quantity' => 'required|integer|min:1', // Validación para cantidad
            '*.products.*.total_quantity' => 'required|integer|min:1', // Validación para cantidad
        ];

        // Generar mensajes personalizados de manera dinámica
        $customMessages = $this->generateCustomMessages($rules);

        // $validator = Validator::make($data, $rules, $customMessages); // Validación con mensajes del metodo: 
        $validator = Validator::make($data, $rules); //validacion con mensajes por defecto de laravel

        // Verificar si la validación pasó o falló
        if ($validator->fails()) {
            // Si falla, mostrar los errores
            $errors = $validator->errors();
            dd($errors->toArray());
        } else {
            // Si pasa la validación
            dd('Los datos son válidos');
        }
    }

    /**
     * Genera los mensajes personalizados de manera dinámica.
     *
     * @param array $rules
     * @return array
     */
    private function generateCustomMessages(array $rules)
    {
        $messages = [];

        foreach ($rules as $field => $rule) {
            // Si el campo es anidado, generamos los mensajes correspondientes
            if (strpos($field, '*') !== false) {
                // Para los campos anidados como 'products.*.id'
                $messages["$field.required"] = "El campo {$field} es obligatorio.";
                $messages["$field.integer"] = "El campo {$field} debe ser un número entero.";
                $messages["$field.min"] = "El campo quantity debe ser al menos 1."; //mensaje modificado a mano
            } else {
                // Para los campos no anidados como 'Nro', 'date', etc.
                $messages["$field.required"] = "El campo {$field} es obligatorio.";
                $messages["$field.integer"] = "El campo {$field} debe ser un número entero.";
                $messages["$field.min"] = "El campo {$field} debe ser al menos 1.";
            }
        }

        return $messages;
    }
}
