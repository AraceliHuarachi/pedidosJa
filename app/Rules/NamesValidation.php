<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NamesValidation implements ValidationRule
{

    private string $pattern;
    private string $defaultPattern = '/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$/'; // Patrón por defecto

    public function __construct(string $pattern = null)
    {
        $this->pattern = $pattern ?? $this->defaultPattern; // Usa el patrón por defecto si no se pasa otro
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match($this->pattern, $value)) {
            $fail('The :attribute contains unsupported characters.');
        }
    }
}
