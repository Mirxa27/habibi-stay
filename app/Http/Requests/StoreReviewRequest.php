<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Authorization is handled in the controller
    }

    public function rules()
    {
        return [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:10', 'max:1000'],
            'cleanliness_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'communication_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'check_in_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'accuracy_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'location_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'value_rating' => ['required', 'integer', 'min:1', 'max:5'],
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => 'Please provide an overall rating.',
            'rating.min' => 'Rating must be at least 1 star.',
            'rating.max' => 'Rating cannot exceed 5 stars.',
            'comment.required' => 'Please provide a review comment.',
            'comment.min' => 'Review comment must be at least 10 characters.',
            'comment.max' => 'Review comment cannot exceed 1000 characters.',
            'cleanliness_rating.required' => 'Please rate the cleanliness.',
            'communication_rating.required' => 'Please rate the communication.',
            'check_in_rating.required' => 'Please rate the check-in experience.',
            'accuracy_rating.required' => 'Please rate the property accuracy.',
            'location_rating.required' => 'Please rate the location.',
            'value_rating.required' => 'Please rate the value for money.',
        ];
    }
} 