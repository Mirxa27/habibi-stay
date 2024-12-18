@props(['property'])

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-4">Book this property</h5>
        
        <form action="{{ route('bookings.store') }}" method="POST" id="booking-form">
            @csrf
            <input type="hidden" name="property_id" value="{{ $property->id }}">
            <input type="hidden" name="total_nights" id="total_nights">
            <input type="hidden" name="total_price" id="total_price">
            
            <!-- Dates -->
            <div class="mb-4">
                <label class="form-label">Select Dates</label>
                <x-availability-calendar 
                    :property="$property"
                    :blocked-dates="$property->blocked_dates"
                    :bookings="$property->bookings"
                    :min-nights="$property->min_nights"
                />
                <input type="hidden" name="check_in" id="check_in" required>
                <input type="hidden" name="check_out" id="check_out" required>
            </div>

            <!-- Guests -->
            <div class="mb-4">
                <label for="guests" class="form-label">Number of Guests</label>
                <select name="guests" id="guests" class="form-select" required>
                    @for($i = 1; $i <= $property->max_guests; $i++)
                        <option value="{{ $i }}">{{ $i }} {{ Str::plural('guest', $i) }}</option>
                    @endfor
                </select>
            </div>

            <!-- Price Breakdown -->
            <div class="price-breakdown mb-4 d-none" id="price-breakdown">
                <h6 class="mb-3">Price Details</h6>
                <div class="list-group">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Base Price × <span id="nights-count">0</span> nights</span>
                        <span>SAR <span id="base-price-total">0</span></span>
                    </div>
                    @if($property->cleaning_fee > 0)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Cleaning Fee</span>
                            <span>SAR {{ number_format($property->cleaning_fee, 2) }}</span>
                        </div>
                    @endif
                    @if($property->security_deposit > 0)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Security Deposit (Refundable)</span>
                            <span>SAR {{ number_format($property->security_deposit, 2) }}</span>
                        </div>
                    @endif
                    <div class="list-group-item d-flex justify-content-between align-items-center" id="discount-row" style="display: none;">
                        <span>Discount</span>
                        <span class="text-success">-SAR <span id="discount-amount">0</span></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                        <span>Total</span>
                        <span>SAR <span id="total-price-display">0</span></span>
                    </div>
                </div>
            </div>

            <!-- Special Requests -->
            <div class="mb-4">
                <label for="special_requests" class="form-label">Special Requests</label>
                <textarea name="special_requests" id="special_requests" class="form-control" rows="3" 
                          placeholder="Any special requests or requirements?"></textarea>
            </div>

            <!-- Terms and Conditions -->
            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">terms and conditions</a>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100" id="book-now-btn" disabled>
                @if($property->instant_booking)
                    <i class="bi bi-lightning-fill me-1"></i> Instant Book
                @else
                    Request to Book
                @endif
            </button>

            <p class="text-muted small text-center mt-3">
                @if($property->instant_booking)
                    Your credit card will be charged immediately
                @else
                    Your credit card won't be charged until the host accepts
                @endif
            </p>
        </form>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Booking Policy</h6>
                <ul class="mb-4">
                    <li>Check-in time: {{ $property->check_in_time }}</li>
                    <li>Check-out time: {{ $property->check_out_time }}</li>
                    <li>Minimum stay: {{ $property->min_nights }} nights</li>
                    @if($property->max_nights)
                        <li>Maximum stay: {{ $property->max_nights }} nights</li>
                    @endif
                </ul>

                <h6>Cancellation Policy</h6>
                <p class="mb-4">{{ $property->cancellation_policy }}</p>

                <h6>House Rules</h6>
                <ul class="mb-0">
                    @foreach($property->house_rules as $rule)
                        <li>{{ $rule }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('booking-form');
    const termsCheckbox = document.getElementById('terms');
    const bookButton = document.getElementById('book-now-btn');
    const priceBreakdown = document.getElementById('price-breakdown');
    const basePrice = {{ $property->base_price }};
    const cleaningFee = {{ $property->cleaning_fee }};
    const securityDeposit = {{ $property->security_deposit }};
    const weeklyDiscount = {{ $property->weekly_discount ?? 0 }};
    const monthlyDiscount = {{ $property->monthly_discount ?? 0 }};

    // Enable/disable book button based on terms acceptance
    termsCheckbox.addEventListener('change', function() {
        bookButton.disabled = !this.checked;
    });

    // Show price breakdown when dates are selected
    function updatePriceBreakdown(nights) {
        if (nights > 0) {
            priceBreakdown.classList.remove('d-none');
            
            const basePriceTotal = basePrice * nights;
            let discount = 0;
            let discountPercentage = 0;

            // Apply discounts
            if (nights >= 30 && monthlyDiscount > 0) {
                discountPercentage = monthlyDiscount;
            } else if (nights >= 7 && weeklyDiscount > 0) {
                discountPercentage = weeklyDiscount;
            }

            if (discountPercentage > 0) {
                discount = (basePriceTotal * discountPercentage) / 100;
                document.getElementById('discount-row').style.display = 'flex';
                document.getElementById('discount-amount').textContent = discount.toFixed(2);
            } else {
                document.getElementById('discount-row').style.display = 'none';
            }

            const total = basePriceTotal - discount + cleaningFee + securityDeposit;

            document.getElementById('nights-count').textContent = nights;
            document.getElementById('base-price-total').textContent = basePriceTotal.toFixed(2);
            document.getElementById('total-price-display').textContent = total.toFixed(2);
            document.getElementById('total_price').value = total;
            document.getElementById('total_nights').value = nights;
        } else {
            priceBreakdown.classList.add('d-none');
        }
    }

    // Handle form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!form.checkValidity()) {
            return;
        }

        const formData = new FormData(form);
        bookButton.disabled = true;
        bookButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (data.payment_url) {
                window.location.href = data.payment_url;
            } else {
                throw new Error(data.message || 'Something went wrong');
            }
        } catch (error) {
            alert(error.message);
            bookButton.disabled = false;
            bookButton.innerHTML = '{{ $property->instant_booking ? '<i class="bi bi-lightning-fill me-1"></i> Instant Book' : 'Request to Book' }}';
        }
    });
});
</script>
@endpush 