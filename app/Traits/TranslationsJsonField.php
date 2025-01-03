<?php

namespace App\Traits;

// Trait that automatically handles record translations based on the configured language.
trait TranslationsJsonField
{
    public function getTranslatedNameAttribute(): string
    {
        $locale = config('app.locale'); // Language configured in APP_LOCALE

        // Use translation or original name
        return isset($this->translations[$locale]['name'])
            ? $this->translations[$locale]['name']
            : $this->attributes['name'];
    }
}
