<?php

namespace App\Http\Controllers;

use App\Events\BookingConfirmed;
use App\Models\Booking;
use App\Models\Property;
use App\Services\MyFatoorahService;
use App\Http\Requests\StoreBookingRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    protected MyFatoorahService $myfatoorah;

    public function __construct(MyFatoorahService $myfatoorah)
    {
        $this->myfatoorah = $myfatoorah;
        $this->middleware('auth');
    }

    /**
     * Display all bookings for the authenticated user.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        try {
            $bookings = Auth::user()->bookings()
                ->with(['property', 'property.user'])
                ->latest()
                ->paginate(10);

            return view('bookings.index', compact('bookings'));
        } catch (\Exception $e) {
            Log::error('Failed to load bookings: ' . $e->getMessage());
            return view('bookings.index')
                ->with('error', 'Failed to load bookings. Please try again.');
        }
    }

    /**
     * Show the form for creating a new booking.
     *
     * @param \App\Models\Property $property
     * @return \Illuminate\View\View
     */
    public function create(Property $property): View
    {
        try {
            if (!$property->isAvailable()) {
                throw new \Exception('Property is not available for booking.');
            }
            return view('bookings.create', compact('property'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created booking in storage.
     *
     * @param \App\Http\Requests\StoreBookingRequest $request
     * @param \App\Models\Property $property
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreBookingRequest $request, Property $property): RedirectResponse
    {
        try {
            $validated = $request->validated();
            $checkIn = Carbon::parse($validated['check_in']);
            $checkOut = Carbon::parse($validated['check_out']);
            $nights = $checkIn->diffInDays($checkOut);

            if (!$property->isAvailableForDates($checkIn, $checkOut)) {
                throw new \Exception('Property is not available for the selected dates');
            }

            $priceDetails = $property->calculateTotalPrice($checkIn, $checkOut, $validated['guests']);
            $totalPrice = $priceDetails['total'];

            DB::beginTransaction();

            // Create booking record
            $booking = Booking::create([
                'property_id' => $property->id,
                'user_id' => Auth::id(),
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'guests' => $validated['guests'],
                'nights' => $nights,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_status' => 'pending',
                'check_in_code' => strtoupper(substr(md5(uniqid()), 0, 6)),
            ]);

            // Initialize payment with MyFatoorah
            $paymentData = [
                'CustomerName' => Auth::user()->name,
                'NotificationOption' => 'ALL',
                'InvoiceValue' => $totalPrice,
                'CallBackUrl' => route('bookings.callback', $booking),
                'ErrorUrl' => route('bookings.error', $booking),
                'DisplayCurrencyIso' => 'SAR',
            ];

            $paymentResponse = $this->myfatoorah->initiatePayment($paymentData);

            if (!$paymentResponse || !isset($paymentResponse['Data']['InvoiceURL'])) {
                throw new \Exception('Payment initialization failed: Invalid response from payment gateway');
            }

            $booking->update([
                'payment_id' => $paymentResponse['Data']['InvoiceId']
            ]);

            DB::commit();
            return redirect($paymentResponse['Data']['InvoiceURL']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking creation failed: ' . $e->getMessage(), [
                'property_id' => $property->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withInput()
                ->with('error', 'Booking failed: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified booking.
     *
     * @param \App\Models\Booking $booking
     * @return \Illuminate\View\View
     */
    public function show(Booking $booking): View
    {
        try {
            $this->authorize('view', $booking);
            $booking->load(['property', 'property.user', 'user']);
            return view('bookings.show', compact('booking'));
        } catch (\Exception $e) {
            Log::error('Failed to load booking: ' . $e->getMessage());
            return back()->with('error', 'Failed to load booking details.');
        }
    }

    /**
     * Cancel the specified booking.
     *
     * @param \App\Models\Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Booking $booking): RedirectResponse
    {
        $this->authorize('cancel', $booking);

        try {
            if (!in_array($booking->status, ['pending', 'confirmed'])) {
                throw new \Exception('This booking cannot be cancelled');
            }

            DB::transaction(function () use ($booking) {
                $booking->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now(),
                ]);

                // Handle refund if payment was made
                if ($booking->payment_status === 'paid') {
                    // Implement refund logic here
                }
            });

            return back()->with('success', 'Booking cancelled successfully');
        } catch (\Exception $e) {
            Log::error('Booking cancellation failed: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Failed to cancel booking: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment callback from MyFatoorah.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request, Booking $booking): RedirectResponse
    {
        try {
            $this->authorize('view', $booking);
            
            $paymentId = $request->input('paymentId');
            if (!$paymentId) {
                throw new \Exception('Payment ID not found in callback request');
            }

            $paymentStatus = $this->myfatoorah->getPaymentStatus($paymentId);

            if (!isset($paymentStatus['Data']['InvoiceStatus'])) {
                throw new \Exception('Invalid payment status response');
            }

            if ($paymentStatus['Data']['InvoiceStatus'] === 'Paid') {
                DB::transaction(function () use ($booking) {
                    $booking->update([
                        'status' => 'confirmed',
                        'payment_status' => 'paid'
                    ]);

                    event(new BookingConfirmed($booking));
                });

                return redirect()->route('bookings.show', $booking)
                    ->with('success', 'Booking confirmed successfully!');
            }

            throw new \Exception('Payment was not successful: ' . $paymentStatus['Data']['InvoiceStatus']);

        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'payment_id' => $request->input('paymentId'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment error from MyFatoorah.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function error(Request $request, Booking $booking): RedirectResponse
    {
        try {
            $this->authorize('view', $booking);
            
            $booking->update([
                'status' => 'failed',
                'payment_status' => 'failed'
            ]);

            Log::warning('Payment failed for booking', [
                'booking_id' => $booking->id,
                'error_data' => $request->all(),
            ]);

            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Payment failed. Please try again.');
        } catch (\Exception $e) {
            Log::error('Error handling payment failure: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'An error occurred while processing your payment.');
        }
    }
} 