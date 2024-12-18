@props(['property', 'blockedDates' => [], 'bookings' => [], 'minNights' => 1])

<div class="calendar-wrapper">
    <div class="card shadow-sm">
        <div class="card-body">
            <div id="availability-calendar"></div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
.calendar-wrapper .flatpickr-calendar {
    box-shadow: none;
    width: 100%;
}
.calendar-wrapper .flatpickr-day.blocked {
    background-color: #ffebee;
    border-color: #ffcdd2;
    color: #b71c1c;
}
.calendar-wrapper .flatpickr-day.booked {
    background-color: #ffe0b2;
    border-color: #ffcc80;
    color: #e65100;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const blockedDates = @json($blockedDates);
    const bookings = @json($bookings);
    const minNights = {{ $minNights }};
    
    const calendar = flatpickr("#availability-calendar", {
        inline: true,
        mode: "range",
        minDate: "today",
        dateFormat: "Y-m-d",
        disable: [
            function(date) {
                // Check if date is in blocked dates
                const dateString = date.toISOString().split('T')[0];
                return blockedDates.includes(dateString);
            },
            {
                from: "today",
                to: new Date().fp_incr({{ $property->advance_notice - 1 }})
            }
        ],
        onDayCreate: function(dObj, dStr, fp, dayElem) {
            const dateString = dayElem.dateObj.toISOString().split('T')[0];
            
            // Mark blocked dates
            if (blockedDates.includes(dateString)) {
                dayElem.classList.add('blocked');
            }
            
            // Mark booked dates
            bookings.forEach(booking => {
                if (dateString >= booking.check_in && dateString <= booking.check_out) {
                    dayElem.classList.add('booked');
                }
            });
        },
        onChange: function(selectedDates) {
            if (selectedDates.length === 2) {
                const nights = Math.round((selectedDates[1] - selectedDates[0]) / (1000 * 60 * 60 * 24));
                if (nights < minNights) {
                    this.clear();
                    alert(`Minimum stay is ${minNights} nights`);
                }
            }
        }
    });

    // Update hidden inputs for form submission
    calendar.config.onChange.push(function(selectedDates) {
        if (selectedDates.length === 2) {
            document.getElementById('check_in').value = selectedDates[0].toISOString().split('T')[0];
            document.getElementById('check_out').value = selectedDates[1].toISOString().split('T')[0];
            
            // Calculate total nights
            const nights = Math.round((selectedDates[1] - selectedDates[0]) / (1000 * 60 * 60 * 24));
            document.getElementById('total_nights').value = nights;
            
            // Trigger price calculation
            calculateTotalPrice(nights);
        }
    });
});

function calculateTotalPrice(nights) {
    const basePrice = {{ $property->base_price }};
    const cleaningFee = {{ $property->cleaning_fee }};
    const weeklyDiscount = {{ $property->weekly_discount ?? 0 }};
    const monthlyDiscount = {{ $property->monthly_discount ?? 0 }};
    
    let totalPrice = basePrice * nights + cleaningFee;
    
    // Apply discounts
    if (nights >= 30 && monthlyDiscount > 0) {
        totalPrice = totalPrice * (1 - monthlyDiscount / 100);
    } else if (nights >= 7 && weeklyDiscount > 0) {
        totalPrice = totalPrice * (1 - weeklyDiscount / 100);
    }
    
    document.getElementById('total_price').value = totalPrice.toFixed(2);
    document.getElementById('price_display').textContent = totalPrice.toFixed(2);
}
</script>
@endpush 