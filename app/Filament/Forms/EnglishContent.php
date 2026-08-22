<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class EnglishContent
{
    public static function section(array $fields): Section
    {
        return Section::make('Contenuti in inglese 🇬🇧')
            ->description('Questi testi vengono mostrati quando il visitatore seleziona English. Se un campo resta vuoto viene usato il testo italiano.')
            ->schema(collect($fields)->map(function (array $field) {
                [$name, $label, $type] = [...$field, 'text'];
                $component = match ($type) {
                    'rich' => RichEditor::make("translations.en.{$name}"),
                    'textarea' => Textarea::make("translations.en.{$name}")->rows(4),
                    default => TextInput::make("translations.en.{$name}")->maxLength(255),
                };

                return $component->label("{$label} (EN)")->columnSpan(in_array($type, ['rich', 'textarea'], true) ? 'full' : 1);
            })->all())
            ->columns(2)
            ->collapsible();
    }
}
