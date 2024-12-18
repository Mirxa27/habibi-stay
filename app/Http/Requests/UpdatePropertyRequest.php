<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('property'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'country' => ['required', 'string'],
            'zip_code' => ['required', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'bedrooms' => ['required', 'integer', 'min:0'],
            'bathrooms' => ['required', 'integer', 'min:0'],
            'max_guests' => ['required', 'integer', 'min:1'],
            'amenities' => ['required', 'array'],
            'amenities.*' => ['string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'cleaning_fee' => ['required', 'numeric', 'min:0'],
            'security_deposit' => ['required', 'numeric', 'min:0'],
            'min_nights' => ['required', 'integer', 'min:1'],
            'max_nights' => ['required', 'integer', 'min:1', 'gte:min_nights'],
            'advance_notice' => ['required', 'integer', 'min:0'],
            'instant_booking' => ['boolean'],
            'is_halal_friendly' => ['boolean'],
            'is_family_friendly' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'images.*.max' => 'Each image must not exceed 2MB.',
            'images.*.mimes' => 'Images must be in JPEG, PNG, or JPG format.',
            'max_nights.gte' => 'Maximum nights must be greater than or equal to minimum nights.',
        ];
    }
} 