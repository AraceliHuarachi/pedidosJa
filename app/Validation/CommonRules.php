<?php

namespace App\Validation;

class CommonRules
{
    //Reglas base para el campo 'nombre'
    public static function nameRuleBase(): array
    {
        return ['required', 'string', 'min:3'];
    }
}
