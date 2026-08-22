<?php

namespace App\Filament\Resources\LegalPages\Pages;

use App\Filament\Concerns\HasSaveConfirmation;
use App\Filament\Resources\LegalPages\LegalPageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLegalPage extends EditRecord
{
    use HasSaveConfirmation;

    protected static string $resource = LegalPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(false),
        ];
    }
}
