<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Property;

class StorePropertyRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'price_per_night' => 'required|numeric|min:0',
            'amenities' => 'required|array',
            'amenities.*' => 'string',
            'rules' => 'nullable|array',
            'rules.*' => 'string',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            
            // Saudi-specific fields
            'property_type' => 'required|string|in:' . implode(',', array_keys(Property::PROPERTY_TYPES)),
            'is_segregated' => 'boolean',
            'has_driver_room' => 'boolean',
            'has_maid_room' => 'boolean',
            'has_majlis' => 'boolean',
            'has_prayer_room' => 'boolean',
            'distance_to_haram' => 'nullable|numeric|min:0',
            'distance_to_metro' => 'nullable|numeric|min:0',
            'nearby_landmarks' => 'nullable|array',
            'nearby_landmarks.*' => 'string',
            'district' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'saudi_amenities' => 'nullable|array',
            'saudi_amenities.*' => 'string|in:' . implode(',', array_keys(Property::SAUDI_AMENITIES)),
            'has_family_section' => 'boolean',
            'has_bachelor_section' => 'boolean',
            'is_halal_certified' => 'boolean',
            'provides_prayer_essentials' => 'boolean',
            'available_languages' => 'nullable|array',
            'available_languages.*' => 'string|in:arabic,english,urdu,hindi,bengali,tagalog,indonesian',
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
            'property_type.in' => 'The selected property type is invalid. Please choose from the available options.',
            'saudi_amenities.*.in' => 'One or more selected amenities are invalid.',
            'available_languages.*.in' => 'One or more selected languages are invalid.',
            'distance_to_haram.min' => 'Distance to Haram must be a positive number.',
            'distance_to_metro.min' => 'Distance to metro must be a positive number.',
        ];
    }
} 