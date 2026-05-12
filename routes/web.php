<?php

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
