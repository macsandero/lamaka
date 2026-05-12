<?php

namespace App\Filament\Resources\HomepageContents\Pages;

use App\Filament\Resources\HomepageContents\HomepageContentResource;
use App\Models\HomepageContent;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageHomepageContents extends ManageRecords
{
    protected static string $resource = HomepageContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn (): bool => HomepageContent::query()->doesntExist()),
        ];
    }
}
