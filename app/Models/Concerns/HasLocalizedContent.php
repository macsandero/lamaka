<?php

namespace App\Models\Concerns;

trait HasLocalizedContent
{
    public function getAttribute($key): mixed
    {
        if (is_string($key) && $key !== 'translations' && app()->getLocale() === 'en' && ! request()->is('admin*', 'agenda*')) {
            $translated = data_get(parent::getAttribute('translations'), "en.{$key}");

            if (filled($translated)) {
                return $translated;
            }
        }

        return parent::getAttribute($key);
    }

    public function localized(string $field, mixed $fallback = null): mixed
    {
        if (app()->getLocale() === 'en') {
            $translated = data_get($this->translations, "en.{$field}");

            if (filled($translated)) {
                return $translated;
            }
        }

        return $this->getAttribute($field) ?? $fallback;
    }

    protected function localizedContentCasts(): array
    {
        return ['translations' => 'array'];
    }
}
