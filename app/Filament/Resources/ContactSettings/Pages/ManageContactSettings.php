<?php

namespace App\Filament\Resources\ContactSettings\Pages;

use App\Filament\Resources\ContactSettings\ContactSettingResource;
use App\Models\ContactSetting;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageContactSettings extends ManageRecords
{
    protected static string $resource = ContactSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn (): bool => ContactSetting::query()->doesntExist()),
        ];
    }
}
