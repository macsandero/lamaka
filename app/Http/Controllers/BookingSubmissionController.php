<?php

namespace App\Http\Controllers;

use App\Mail\BookingSubmissionReceived;
use App\Models\BookingFormField;
use App\Models\BookingFormSetting;
use App\Models\BookingSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class BookingSubmissionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if (filled($request->input('lamaka_confirm_url'))) {
            Log::info('Booking submission blocked by honeypot.', [
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
            ]);

            return redirect('/#prenota')->with('booking_success', "Richiesta inviata correttamente. Sarai contattato al più presto per concordare l'orario dell'attività");
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
                'datetime' => 'date',
                'checkbox' => 'accepted',
                'select' => Rule::in($field->optionsList()),
                default => 'string',
            };

            if (in_array($field->type, ['date', 'datetime'], true)) {
                $fieldRules[] = 'after_or_equal:'.now()->addDay()->startOfDay()->toDateTimeString();
            }

            if (in_array($field->type, ['text', 'tel', 'textarea'], true)) {
                $fieldRules[] = 'max:2000';
            }

            $rules["fields.{$field->key}"] = $fieldRules;
            $attributes["fields.{$field->key}"] = strtolower($field->label);
        }

        $validator = Validator::make($request->all(), $rules, [], $attributes);

        if ($validator->fails()) {
            return redirect('/#prenota')
                ->withErrors($validator)
                ->withInput();
        }

        $validated = $validator->validated();
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

        $submission = BookingSubmission::query()->create([
            'reference' => 'LAMAKA-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
            'data' => $data,
            'status' => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        try {
            Mail::to($this->bookingRecipients())->send(new BookingSubmissionReceived($submission));
        } catch (Throwable $exception) {
            Log::error('Unable to send booking submission email.', [
                'booking_submission_id' => $submission->getKey(),
                'exception' => $exception,
            ]);
        }

        $successMessage = BookingFormSetting::query()
            ->where('is_active', true)
            ->value('success_message') ?: "Richiesta inviata correttamente. Sarai contattato al più presto per concordare l'orario dell'attività";

        return redirect('/#prenota')
            ->withInput([])
            ->with('booking_success', $successMessage);
    }

    /**
     * @return array<int, string>
     */
    private function bookingRecipients(): array
    {
        return collect(explode(',', (string) config('mail.booking_to')))
            ->map(fn (string $email): string => trim($email))
            ->filter(fn (string $email): bool => filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
            ->values()
            ->all();
    }
}
