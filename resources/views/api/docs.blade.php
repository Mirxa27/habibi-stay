@extends('layouts.app')

@section('title', 'API Documentation')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold mb-6">Habibi Stay API Documentation</h1>
        
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">Authentication</h2>
            <p class="mb-4">
                The API uses Bearer token authentication. Include the token in the Authorization header:
            </p>
            <pre class="bg-gray-100 p-4 rounded-lg overflow-x-auto">
                <code>Authorization: Bearer your-token-here</code>
            </pre>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">Rate Limiting</h2>
            <p class="mb-4">
                API requests are rate limited to:
            </p>
            <ul class="list-disc list-inside mb-4">
                <li>60 requests per minute for general endpoints</li>
                <li>30 requests per minute for channel manager operations</li>
                <li>20 requests per minute for messaging operations</li>
            </ul>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">API Endpoints</h2>
            
            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-3">Properties</h3>
                <div class="space-y-4">
                    <div class="border-l-4 border-blue-500 pl-4">
                        <p class="font-semibold">GET /api/properties</p>
                        <p class="text-gray-600">List all properties</p>
                    </div>
                    <div class="border-l-4 border-green-500 pl-4">
                        <p class="font-semibold">POST /api/properties</p>
                        <p class="text-gray-600">Create a new property</p>
                    </div>
                    <div class="border-l-4 border-yellow-500 pl-4">
                        <p class="font-semibold">PUT /api/properties/{id}</p>
                        <p class="text-gray-600">Update a property</p>
                    </div>
                    <div class="border-l-4 border-red-500 pl-4">
                        <p class="font-semibold">DELETE /api/properties/{id}</p>
                        <p class="text-gray-600">Delete a property</p>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-3">Bookings</h3>
                <div class="space-y-4">
                    <div class="border-l-4 border-blue-500 pl-4">
                        <p class="font-semibold">GET /api/bookings</p>
                        <p class="text-gray-600">List user's bookings</p>
                    </div>
                    <div class="border-l-4 border-green-500 pl-4">
                        <p class="font-semibold">POST /api/bookings/{property_id}</p>
                        <p class="text-gray-600">Create a new booking</p>
                    </div>
                    <div class="border-l-4 border-yellow-500 pl-4">
                        <p class="font-semibold">POST /api/bookings/{booking}/cancel</p>
                        <p class="text-gray-600">Cancel a booking</p>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-3">Reviews</h3>
                <div class="space-y-4">
                    <div class="border-l-4 border-blue-500 pl-4">
                        <p class="font-semibold">GET /api/reviews/property/{property_id}</p>
                        <p class="text-gray-600">Get property reviews</p>
                    </div>
                    <div class="border-l-4 border-green-500 pl-4">
                        <p class="font-semibold">POST /api/reviews/{booking}</p>
                        <p class="text-gray-600">Create a review</p>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-3">Channel Manager</h3>
                <div class="space-y-4">
                    <div class="border-l-4 border-blue-500 pl-4">
                        <p class="font-semibold">GET /api/channel-manager/calendar</p>
                        <p class="text-gray-600">Get calendar data</p>
                    </div>
                    <div class="border-l-4 border-yellow-500 pl-4">
                        <p class="font-semibold">PATCH /api/channel-manager/properties/{property}/price</p>
                        <p class="text-gray-600">Update property price</p>
                    </div>
                    <div class="border-l-4 border-yellow-500 pl-4">
                        <p class="font-semibold">PATCH /api/channel-manager/properties/{property}/availability</p>
                        <p class="text-gray-600">Update property availability</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">Response Format</h2>
            <p class="mb-4">
                All responses are returned in JSON format. Successful responses include:
            </p>
            <pre class="bg-gray-100 p-4 rounded-lg overflow-x-auto">
                <code>{
    "data": {
        // Resource data
    },
    "meta": {
        // Pagination information (if applicable)
    }
}</code>
            </pre>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">Error Handling</h2>
            <p class="mb-4">
                Error responses include:
            </p>
            <pre class="bg-gray-100 p-4 rounded-lg overflow-x-auto">
                <code>{
    "message": "Error message",
    "errors": {
        // Validation errors (if applicable)
    }
}</code>
            </pre>
        </div>

        <div>
            <h2 class="text-2xl font-semibold mb-4">Need Help?</h2>
            <p>
                For additional help or to report issues, please contact our support team at
                <a href="mailto:support@habibistay.com" class="text-blue-500 hover:text-blue-700">
                    support@habibistay.com
                </a>
            </p>
        </div>
    </div>
</div>
@endsection 