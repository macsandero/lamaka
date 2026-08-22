<?php

namespace App\Providers;

use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $confirmation = fn (): Notification => Notification::make()
            ->success()
            ->title('Salvataggio completato')
            ->body('Le modifiche sono state salvate correttamente.')
            ->persistent();

        CreateAction::configureUsing(
            fn (CreateAction $action): CreateAction => $action->successNotification($confirmation),
        );

        EditAction::configureUsing(
            fn (EditAction $action): EditAction => $action->successNotification($confirmation),
        );
    }
}
