<?php

namespace App\Filament\Resources\BookingFormFields\Pages;

use App\Filament\Concerns\HasSaveConfirmation;
use App\Filament\Resources\BookingFormFields\BookingFormFieldResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBookingFormField extends CreateRecord
{
    use HasSaveConfirmation;

    protected static string $resource = BookingFormFieldResource::class;
}
