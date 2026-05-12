<?php

namespace App\Filament\Resources\BookingFormSettings\Pages;

use App\Filament\Resources\BookingFormSettings\BookingFormSettingResource;
use App\Models\BookingFormSetting;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageBookingFormSettings extends ManageRecords
{
    protected static string $resource = BookingFormSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->visible(fn (): bool => BookingFormSetting::query()->doesntExist()),
        ];
    }
}
