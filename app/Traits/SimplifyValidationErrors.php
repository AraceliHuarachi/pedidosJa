<?php

namespace App\traits;

use App\Services\FieldTranslator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

trait SimplifyValidationErrors
{
    // Method to translate the name using the FieldTranslator Service
    protected function translateFieldName(string $fieldName)
    {
        $translator = app(FieldTranslator::class); // Get the service instance
        return $translator->translate($fieldName); // Translate field name
    }

    // Method to simplify error messages.
    public function simplifyErrorMessages(array $errors)
    {
        $simplified = [];

        foreach ($errors as $key => $messages) {
            // Extract field name after last point.
            if (preg_match('/\.([^.]+)$/', $key, $matches)) {
                $fieldName = $matches[1];
            } else {
                $fieldName = $key;
            }

            // Replace underscores with spaces to improve readability.
            $readableFieldName = str_replace('_', ' ', $fieldName);

            // Translate the simplified field name if translations exists
            $translateFieldName = $this->translateFieldName($readableFieldName);

            // Replace the full field name with the simplified name
            foreach ($messages as $message) {
                // If an entry already exists for this field, add the following message to an array.
                if (isset($simplified[$key])) {
                    $simplified[$key][] = str_replace($key, $translateFieldName, $message);
                } else {
                    $simplified[$key] = [str_replace($key, $translateFieldName, $message)];
                }
            }
        }

        return $simplified;
    }
    // Method to simplify error messages within a FormRequest.
    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->getMessages(); // Get the original errors
        $simplifiedErrors = $this->simplifyErrorMessages($errors); // simplifies error messages

        throw new HttpResponseException(
            response()->json($simplifiedErrors, 422) // Return modified messages
        );
    }
    // Method to simplify errors when using Validator directly
    public function forLooseValidations(Validator $validator)
    {
        $errors = $validator->errors()->getMessages();
        $simplifiedErrors = $this->simplifyErrorMessages($errors);

        return response()->json($simplifiedErrors, 422);
    }
}
