<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAvailabilityRequest extends FormRequest
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
            'available_dates' => 'sometimes|required|array',
            'available_dates.*' => 'date|after_or_equal:today',
            'blocked_dates' => 'sometimes|required|array',
            'blocked_dates.*' => 'date|after_or_equal:today',
            'minimum_stay' => 'sometimes|required|integer|min:1',
            'maximum_stay' => 'sometimes|required|integer|min:1|gte:minimum_stay',
            'check_in_time' => 'sometimes|required|date_format:H:i',
            'check_out_time' => 'sometimes|required|date_format:H:i',
            'advance_notice' => 'sometimes|required|integer|min:0',
            'preparation_time' => 'sometimes|required|integer|min:0',
            'instant_booking' => 'sometimes|required|boolean'
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
            'available_dates.*.date' => 'Each available date must be a valid date',
            'available_dates.*.after_or_equal' => 'Available dates must be today or in the future',
            'blocked_dates.*.date' => 'Each blocked date must be a valid date',
            'blocked_dates.*.after_or_equal' => 'Blocked dates must be today or in the future',
            'minimum_stay.min' => 'Minimum stay must be at least 1 night',
            'maximum_stay.min' => 'Maximum stay must be at least 1 night',
            'maximum_stay.gte' => 'Maximum stay must be greater than or equal to minimum stay',
            'check_in_time.date_format' => 'Check-in time must be in 24-hour format (HH:mm)',
            'check_out_time.date_format' => 'Check-out time must be in 24-hour format (HH:mm)'
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
            if ($this->has('available_dates') && $this->has('blocked_dates')) {
                $overlapping = array_intersect(
                    $this->available_dates,
                    $this->blocked_dates
                );

                if (!empty($overlapping)) {
                    $validator->errors()->add(
                        'dates',
                        'A date cannot be both available and blocked'
                    );
                }
            }
        });
    }
} 