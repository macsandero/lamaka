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
use App\Models\LegalPage;
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

Route::get('/esperienze/{experience}', function (Experience $experience) {
    abort_unless($experience->is_active, 404);

    return view('experience-show', [
        'experience' => $experience,
        'contact' => ContactSetting::query()->where('is_active', true)->first(),
    ]);
})->name('experiences.show');

Route::get('/privacy-policy', function () {
    $page = LegalPage::query()
        ->where('slug', 'privacy-policy')
        ->where('is_active', true)
        ->firstOrFail();

    return view('legal-page', [
        'page' => $page,
        'contact' => ContactSetting::query()->where('is_active', true)->first(),
    ]);
})->name('legal.privacy');

Route::get('/cookie-policy', function () {
    $page = LegalPage::query()
        ->where('slug', 'cookie-policy')
        ->where('is_active', true)
        ->firstOrFail();

    return view('legal-page', [
        'page' => $page,
        'contact' => ContactSetting::query()->where('is_active', true)->first(),
    ]);
})->name('legal.cookie');

Route::post('/prenota', [BookingSubmissionController::class, 'store'])
    ->name('booking.store');

Route::post('/admin/manual-login', [AdminLoginController::class, 'store'])
    ->middleware('web')
    ->name('admin.manual-login');

Route::get('/admin-password/setup/{token}', [AdminPasswordSetupController::class, 'edit'])
    ->name('admin.password-setup.edit');
Route::post('/admin-password/setup/{token}', [AdminPasswordSetupController::class, 'update'])
    ->name('admin.password-setup.update');
