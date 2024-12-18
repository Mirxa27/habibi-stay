<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $property = $this->route('property');
        $minDate = Carbon::today()->addDays($property->advance_notice ?? 0);

        return [
            'check_in' => [
                'required',
                'date',
                'after_or_equal:' . $minDate->format('Y-m-d'),
            ],
            'check_out' => [
                'required',
                'date',
                'after:check_in',
            ],
            'guests' => [
                'required',
                'integer',
                'min:1',
                'max:' . $property->max_guests,
            ],
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
            $property = $this->route('property');
            $checkIn = Carbon::parse($this->check_in);
            $checkOut = Carbon::parse($this->check_out);
            $nights = $checkIn->diffInDays($checkOut);

            // Validate minimum and maximum nights
            if ($nights < $property->min_nights) {
                $validator->errors()->add(
                    'check_out',
                    "Minimum stay is {$property->min_nights} nights."
                );
            }

            if ($property->max_nights && $nights > $property->max_nights) {
                $validator->errors()->add(
                    'check_out',
                    "Maximum stay is {$property->max_nights} nights."
                );
            }

            // Validate if dates are not blocked
            if (!empty($property->blocked_dates)) {
                $requestedDates = collect(getDatesFromRange($checkIn, $checkOut));
                $blockedDates = collect($property->blocked_dates);
                
                if ($blockedDates->intersect($requestedDates)->isNotEmpty()) {
                    $validator->errors()->add(
                        'check_in',
                        'Some of the selected dates are not available.'
                    );
                }
            }

            // Validate if there are no overlapping bookings
            $existingBooking = $property->bookings()
                ->where(function ($query) use ($checkIn, $checkOut) {
                    $query->whereBetween('check_in', [$checkIn, $checkOut])
                        ->orWhereBetween('check_out', [$checkIn, $checkOut])
                        ->orWhere(function ($q) use ($checkIn, $checkOut) {
                            $q->where('check_in', '<=', $checkIn)
                                ->where('check_out', '>=', $checkOut);
                        });
                })
                ->exists();

            if ($existingBooking) {
                $validator->errors()->add(
                    'check_in',
                    'The property is already booked for some of the selected dates.'
                );
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'check_in.after_or_equal' => 'Check-in date must be after the minimum advance notice period.',
            'check_out.after' => 'Check-out date must be after check-in date.',
            'guests.max' => 'The number of guests cannot exceed the property\'s maximum capacity.',
        ];
    }
} 