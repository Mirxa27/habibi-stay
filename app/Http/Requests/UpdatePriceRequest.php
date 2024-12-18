<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePriceRequest extends FormRequest
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
            'base_price' => 'sometimes|required|numeric|min:0',
            'seasonal_prices' => 'sometimes|required|array',
            'seasonal_prices.*.start_date' => 'required_with:seasonal_prices|date',
            'seasonal_prices.*.end_date' => 'required_with:seasonal_prices|date|after:seasonal_prices.*.start_date',
            'seasonal_prices.*.price' => 'required_with:seasonal_prices|numeric|min:0',
            'special_prices' => 'sometimes|required|array',
            'special_prices.*.date' => 'required_with:special_prices|date',
            'special_prices.*.price' => 'required_with:special_prices|numeric|min:0',
            'cleaning_fee' => 'sometimes|numeric|min:0',
            'service_fee' => 'sometimes|numeric|min:0',
            'tax_rate' => 'sometimes|numeric|between:0,100'
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
            'seasonal_prices.*.start_date.required_with' => 'Each seasonal price must have a start date',
            'seasonal_prices.*.end_date.required_with' => 'Each seasonal price must have an end date',
            'seasonal_prices.*.end_date.after' => 'End date must be after start date',
            'seasonal_prices.*.price.required_with' => 'Each seasonal price must have a price',
            'special_prices.*.date.required_with' => 'Each special price must have a date',
            'special_prices.*.price.required_with' => 'Each special price must have a price'
        ];
    }
} 