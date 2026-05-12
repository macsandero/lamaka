<?php

namespace App\Filament\Resources\Animals\Pages;

use App\Filament\Resources\Animals\AnimalResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAnimal extends EditRecord
{
    protected static string $resource = AnimalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
