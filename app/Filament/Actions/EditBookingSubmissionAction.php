<?php

namespace App\Filament\Actions;

use App\Models\BookingSubmission;
use App\Services\BookingManager;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\EditAction;

class EditBookingSubmissionAction extends EditAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->using(fn (BookingSubmission $record, array $data, HasActions $livewire): BookingSubmission => app(BookingManager::class)->save(
            $record,
            $data,
            $livewire->{$livewire->getMountedActionSchemaName()}->getStatePath(),
        ));
    }
}
