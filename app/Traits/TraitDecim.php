<?php

namespace App\Traits;

trait TraitDecim
{
    /**
     * Convierte las comas a puntos en los valores decimales.
     *
     * @param  string  $value
     * @return string
     */
    public function convertComasToPoints($value)
    {
        return str_replace(',', '.', $value);
    }

    /**
     * Convierte los valores de los campos especificados a formato con punto.
     *
     * @param  array  $fields
     * @return void
     */
    public function convertFieldsToDecimal(array $fields)
    {
        foreach ($fields as $field) {
            if ($this->$field) {
                $this->$field = $this->convertComasToPoints($this->$field);
            }
        }
    }

    /**
     * Método que puede ser sobrescrito en la clase para la validación o preparación.
     *
     * @return void
     */
    public function prepareForValidation()
    {
        $this->convertFieldsToDecimal([
            'reference_price',
            'final_price',
            'amount_money',
        ]);
    }
}
