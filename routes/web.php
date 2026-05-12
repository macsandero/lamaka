<?php

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminPasswordSetupController;
use App\Http\Controllers\BookingSubmissionController;
use App\Models\Animal;
use App\Models\BookingFormField;
use App\Models\BookingFormSetting;
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
        'bookingSettings' => BookingFormSetting::query()->where('is_active', true)->first(),
        'bookingFields' => BookingFormField::query()->published()->ordered()->get(),
    ]);
});

Route::post('/prenota', [BookingSubmissionController::class, 'store'])
    ->name('booking.store');

Route::post('/admin/manual-login', [AdminLoginController::class, 'store'])
    ->middleware('web')
    ->name('admin.manual-login');

Route::get('/admin-password/setup/{token}', [AdminPasswordSetupController::class, 'edit'])
    ->name('admin.password-setup.edit');
Route::post('/admin-password/setup/{token}', [AdminPasswordSetupController::class, 'update'])
    ->name('admin.password-setup.update');
