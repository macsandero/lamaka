<?php

namespace App\Http\Controllers;

use App\Models\BookingFormField;
use App\Models\BookingFormSetting;
use App\Models\BookingSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BookingSubmissionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if (filled($request->input('website'))) {
            return back()->with('booking_success', 'Richiesta inviata.');
        }

        $fields = BookingFormField::query()->published()->ordered()->get();

        $rules = [];
        $attributes = [];

        foreach ($fields as $field) {
            $fieldRules = $field->is_required ? ['required'] : ['nullable'];

            $fieldRules[] = match ($field->type) {
                'email' => 'email',
                'number' => 'numeric',
                'date' => 'date',
                'checkbox' => 'accepted',
                'select' => Rule::in($field->optionsList()),
                default => 'string',
            };

            if (in_array($field->type, ['text', 'tel', 'textarea'], true)) {
                $fieldRules[] = 'max:2000';
            }

            $rules["fields.{$field->key}"] = $fieldRules;
            $attributes["fields.{$field->key}"] = strtolower($field->label);
        }

        $validated = $request->validate($rules, [], $attributes);
        $input = Arr::get($validated, 'fields', []);
        $data = [];

        foreach ($fields as $field) {
            $value = $input[$field->key] ?? null;

            if ($field->type === 'checkbox') {
                $value = $request->boolean("fields.{$field->key}");
            }

            $data[$field->key] = [
                'label' => $field->label,
                'value' => $value,
                'type' => $field->type,
            ];
        }

        BookingSubmission::query()->create([
            'reference' => 'LAMAKA-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
            'data' => $data,
            'status' => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        $successMessage = BookingFormSetting::query()
            ->where('is_active', true)
            ->value('success_message') ?: 'Richiesta inviata. Ti risponderemo al più presto.';

        return back()
            ->withInput([])
            ->with('booking_success', $successMessage);
    }
}
