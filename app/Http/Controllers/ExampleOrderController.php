<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExampleOrderRequest;

class ExampleOrderController extends Controller
{
    public function store(ExampleOrderRequest $request)
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
}
