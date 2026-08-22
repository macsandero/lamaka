<?php

namespace App\Filament\Resources\Experiences\Pages;

use App\Filament\Concerns\HasSaveConfirmation;
use App\Filament\Resources\Experiences\ExperienceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExperience extends CreateRecord
{
    use HasSaveConfirmation;

    protected static string $resource = ExperienceResource::class;
}
