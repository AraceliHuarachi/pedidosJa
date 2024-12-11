<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NamesValidation implements ValidationRule
{

    private string $pattern;
    public const DEFAULT = '/^[a-zA-Z\sñÑáéíóúÁÉÍÓÚ]+$/'; // Patrón por defecto
    public const ALPHANUM = '/^[a-zA-Z0-9\sñÑáéíóúÁÉÍÓÚ]+$/'; //Patron variante, incluye numeros

    public function __construct(string $variant = 'DEFAULT')
    {
        //vincular las variantes a sus patrones correspondientes
        $patterns = [
            'DEFAULT' => self::DEFAULT,
            'ALPHANUM' => self::ALPHANUM,
        ];

        $this->pattern = $patterns[$variant] ?? self::DEFAULT;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match($this->pattern, $value)) {
            $fail('The :attribute contains unsupported characters.');
        }
    }
}
