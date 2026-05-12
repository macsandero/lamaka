<?php

use App\Http\Controllers\AdminPasswordSetupController;
use App\Models\Animal;
use App\Models\ContactSetting;
use App\Models\Experience;
use App\Models\HomepageContent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'homepage' => HomepageContent::query()->first(),
        'experiences' => Experience::query()->published()->ordered()->get(),
        'animals' => Animal::query()->published()->ordered()->get(),
        'contact' => ContactSetting::query()->where('is_active', true)->first(),
    ]);
});

Route::get('/admin/setup-password/{token}', [AdminPasswordSetupController::class, 'edit'])
    ->name('admin.password-setup.edit');
Route::post('/admin/setup-password/{token}', [AdminPasswordSetupController::class, 'update'])
    ->name('admin.password-setup.update');
