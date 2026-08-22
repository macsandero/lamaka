<?php

namespace App\Filament\Resources\BookingFormFields\Pages;

use App\Filament\Concerns\HasSaveConfirmation;
use App\Filament\Resources\BookingFormFields\BookingFormFieldResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBookingFormField extends EditRecord
{
    use HasSaveConfirmation;

    protected static string $resource = BookingFormFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
