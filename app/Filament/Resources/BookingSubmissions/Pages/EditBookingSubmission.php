<?php

namespace App\Filament\Resources\BookingSubmissions\Pages;

use App\Filament\Concerns\HasSaveConfirmation;
use App\Filament\Resources\BookingSubmissions\BookingSubmissionResource;
use App\Services\BookingManager;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditBookingSubmission extends EditRecord
{
    use HasSaveConfirmation;

    protected static string $resource = BookingSubmissionResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return app(BookingManager::class)->save($record, $data, $this->form->getStatePath());
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
