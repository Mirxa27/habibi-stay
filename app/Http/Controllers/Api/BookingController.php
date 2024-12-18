<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use App\Http\Requests\StoreBookingRequest;
use App\Events\BookingConfirmed;
use App\Services\MyFatoorahService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected $myFatoorahService;

    public function __construct(MyFatoorahService $myFatoorahService)
    {
        $this->myFatoorahService = $myFatoorahService;
    }

    /**
     * Display all bookings for the authenticated user via API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $bookings = Booking::where('user_id', $request->user()->id)
            ->with(['property', 'property.user'])
            ->latest()
            ->paginate(10);

        return response()->json($bookings);
    }

    /**
     * Store a newly created booking.
     */
    public function store(StoreBookingRequest $request, Property $property): JsonResponse
    {
        $validated = $request->validated();
        $booking = new Booking($validated);
        $booking->user_id = $request->user()->id;
        $booking->property_id = $property->id;
        $booking->status = 'pending';
        $booking->total_price = $property->calculateTotalPrice(
            $validated['check_in'],
            $validated['check_out'],
            $validated['guests']
        );
        $booking->save();

        // Create payment through MyFatoorah
        $paymentData = $this->myFatoorahService->createPayment([
            'amount' => $booking->total_price,
            'currency' => 'SAR',
            'description' => "Booking for {$property->name}",
            'callback_url' => route('api.bookings.callback', $booking->id),
            'error_url' => route('api.bookings.error', $booking->id)
        ]);

        $booking->payment_id = $paymentData['payment_id'];
        $booking->payment_url = $paymentData['payment_url'];
        $booking->save();

        return response()->json([
            'booking' => $booking,
            'payment_url' => $booking->payment_url
        ], 201);
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking): JsonResponse
    {
        $this->authorize('view', $booking);
        
        $booking->load(['property', 'property.user', 'user']);
        return response()->json($booking);
    }

    /**
     * Cancel the specified booking.
     */
    public function cancel(Booking $booking): JsonResponse
    {
        $this->authorize('update', $booking);

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json([
                'message' => 'This booking cannot be cancelled'
            ], 422);
        }

        // Process refund through MyFatoorah if payment was made
        if ($booking->payment_id && $booking->status === 'confirmed') {
            $refundData = $this->myFatoorahService->refundPayment([
                'payment_id' => $booking->payment_id,
                'amount' => $booking->calculateRefundAmount()
            ]);
            
            $booking->refund_id = $refundData['refund_id'];
        }

        $booking->status = 'cancelled';
        $booking->cancelled_at = now();
        $booking->save();

        return response()->json($booking);
    }

    /**
     * Handle payment callback.
     */
    public function callback(Request $request, Booking $booking): JsonResponse
    {
        $paymentStatus = $this->myFatoorahService->getPaymentStatus($request->payment_id);

        if ($paymentStatus['status'] === 'success') {
            $booking->status = 'confirmed';
            $booking->confirmed_at = now();
            $booking->save();

            event(new BookingConfirmed($booking));

            return response()->json([
                'message' => 'Payment successful',
                'booking' => $booking
            ]);
        }

        return response()->json([
            'message' => 'Payment failed',
            'error' => $paymentStatus['error']
        ], 422);
    }

    /**
     * Handle payment error.
     */
    public function error(Request $request, Booking $booking): JsonResponse
    {
        $booking->status = 'failed';
        $booking->save();

        return response()->json([
            'message' => 'Payment failed',
            'error' => $request->error
        ], 422);
    }
} 