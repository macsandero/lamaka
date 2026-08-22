<?php

namespace App\Filament\Resources\Animals\Pages;

use App\Filament\Concerns\HasSaveConfirmation;
use App\Filament\Resources\Animals\AnimalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAnimal extends CreateRecord
{
    use HasSaveConfirmation;

    protected static string $resource = AnimalResource::class;
}
