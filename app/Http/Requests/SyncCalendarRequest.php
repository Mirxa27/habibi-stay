<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncCalendarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled in the controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'ical_url' => 'required|url|max:2048',
            'sync_from_date' => 'sometimes|required|date|after_or_equal:today',
            'sync_to_date' => 'sometimes|required|date|after:sync_from_date',
            'auto_sync' => 'sometimes|required|boolean',
            'sync_interval' => 'required_if:auto_sync,true|integer|min:15|max:1440', // in minutes
            'override_existing' => 'sometimes|required|boolean',
            'calendar_name' => 'sometimes|required|string|max:255'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'ical_url.required' => 'The iCal URL is required',
            'ical_url.url' => 'Please provide a valid URL',
            'ical_url.max' => 'The URL is too long',
            'sync_from_date.after_or_equal' => 'Sync from date must be today or in the future',
            'sync_to_date.after' => 'Sync to date must be after sync from date',
            'sync_interval.required_if' => 'Sync interval is required when auto sync is enabled',
            'sync_interval.min' => 'Sync interval must be at least 15 minutes',
            'sync_interval.max' => 'Sync interval cannot exceed 24 hours'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->ical_url) {
                try {
                    $headers = get_headers($this->ical_url);
                    if (!$headers || strpos($headers[0], '200') === false) {
                        $validator->errors()->add(
                            'ical_url',
                            'The provided iCal URL is not accessible'
                        );
                    }
                } catch (\Exception $e) {
                    $validator->errors()->add(
                        'ical_url',
                        'Could not verify the iCal URL'
                    );
                }
            }
        });
    }
} 