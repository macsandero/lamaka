<?php

namespace App\Filament\Resources\AboutContents\Pages;

use App\Filament\Resources\AboutContents\AboutContentResource;
use App\Models\HomepageContent;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAboutContents extends ManageRecords
{
    protected static string $resource = AboutContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn (): bool => HomepageContent::query()->doesntExist()),
        ];
    }
}
