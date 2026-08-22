<?php

namespace App\Filament\Concerns;

use Filament\Notifications\Notification;

trait HasSaveConfirmation
{
    protected function getSavedNotification(): ?Notification
    {
        return static::saveConfirmation();
    }

    protected function getCreatedNotification(): ?Notification
    {
        return static::saveConfirmation();
    }

    private static function saveConfirmation(): Notification
    {
        return Notification::make()
            ->success()
            ->title('Salvataggio completato')
            ->body('Le modifiche sono state salvate correttamente.')
            ->persistent();
    }
}
