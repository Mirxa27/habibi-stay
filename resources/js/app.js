import './bootstrap';
import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import { Loader } from '@googlemaps/js-api-loader';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Initialize Flatpickr for date inputs
document.addEventListener('DOMContentLoaded', function() {
    // Date picker initialization
    const dateInputs = document.querySelectorAll('.date-picker');
    dateInputs.forEach(input => {
        flatpickr(input, {
            minDate: 'today',
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'F j, Y',
        });
    });

    // Property search form
    const searchForm = document.getElementById('property-search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const locationInput = document.getElementById('location');
            const checkIn = document.getElementById('check_in');
            const checkOut = document.getElementById('check_out');
            const guests = document.getElementById('guests');

            if (!locationInput.value || !checkIn.value || !checkOut.value || !guests.value) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    }

    // Property booking form
    const bookingForm = document.getElementById('booking-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            const checkIn = document.getElementById('check_in');
            const checkOut = document.getElementById('check_out');
            const guests = document.getElementById('guests');

            if (!checkIn.value || !checkOut.value || !guests.value) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    }

    // Initialize Google Maps
    const mapElements = document.querySelectorAll('.google-map');
    if (mapElements.length > 0) {
        const loader = new Loader({
            apiKey: process.env.GOOGLE_MAPS_API_KEY,
            version: 'weekly',
            libraries: ['places']
        });

        loader.load().then(() => {
            mapElements.forEach(element => {
                const lat = parseFloat(element.dataset.lat);
                const lng = parseFloat(element.dataset.lng);
                const map = new google.maps.Map(element, {
                    center: { lat, lng },
                    zoom: 15,
                    styles: [
                        {
                            featureType: 'poi',
                            elementType: 'labels',
                            stylers: [{ visibility: 'off' }]
                        }
                    ]
                });

                new google.maps.Marker({
                    position: { lat, lng },
                    map: map
                });
            });
        });
    }

    // Image gallery
    const galleryImages = document.querySelectorAll('.gallery-image');
    const mainImage = document.querySelector('.main-image');
    if (galleryImages.length > 0 && mainImage) {
        galleryImages.forEach(image => {
            image.addEventListener('click', function() {
                mainImage.src = this.src;
                galleryImages.forEach(img => img.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
    }

    // Price calculator
    const calculatePrice = () => {
        const checkIn = document.getElementById('check_in');
        const checkOut = document.getElementById('check_out');
        const guests = document.getElementById('guests');
        const priceDisplay = document.getElementById('total-price');
        const propertyId = priceDisplay?.dataset.propertyId;

        if (checkIn && checkOut && guests && priceDisplay && propertyId) {
            if (checkIn.value && checkOut.value && guests.value) {
                fetch(`/api/properties/${propertyId}/calculate-price`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        check_in: checkIn.value,
                        check_out: checkOut.value,
                        guests: guests.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    priceDisplay.innerHTML = `
                        <div class="space-y-2">
                            <p class="flex justify-between">
                                <span>SAR ${data.nightly_rate} × ${data.nights} nights</span>
                                <span>SAR ${data.subtotal}</span>
                            </p>
                            <p class="flex justify-between">
                                <span>Cleaning fee</span>
                                <span>SAR ${data.cleaning_fee}</span>
                            </p>
                            <p class="flex justify-between">
                                <span>Security deposit</span>
                                <span>SAR ${data.security_deposit}</span>
                            </p>
                            <div class="border-t pt-2">
                                <p class="flex justify-between font-semibold">
                                    <span>Total</span>
                                    <span>SAR ${data.total}</span>
                                </p>
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Error calculating price:', error);
                });
            }
        }
    };

    // Attach price calculator to form inputs
    const priceInputs = document.querySelectorAll('.price-calculator-input');
    priceInputs.forEach(input => {
        input.addEventListener('change', calculatePrice);
    });

    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(element => {
        tippy(element, {
            content: element.dataset.tooltip,
            placement: 'top',
        });
    });

    // Mobile menu
    const mobileMenuButton = document.querySelector('[data-mobile-menu-button]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
});

// Property filter functionality
const initializePropertyFilters = () => {
    const filterForm = document.getElementById('property-filter-form');
    if (!filterForm) return;

    const applyFilters = () => {
        const formData = new FormData(filterForm);
        const searchParams = new URLSearchParams(formData);
        
        fetch(`${window.location.pathname}?${searchParams.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('property-grid').innerHTML = html;
        })
        .catch(error => {
            console.error('Error applying filters:', error);
        });
    };

    // Attach event listeners to form inputs
    filterForm.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('change', applyFilters);
    });
};

// Initialize property filters
initializePropertyFilters();

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Initialize popovers
document.addEventListener('DOMContentLoaded', function() {
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});

// Handle flash messages auto-hide
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert:not(.alert-important)');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Handle property image gallery
document.addEventListener('DOMContentLoaded', function() {
    const galleryThumbs = document.querySelectorAll('.gallery-thumb');
    const mainImage = document.querySelector('.gallery-main-image');

    if (galleryThumbs && mainImage) {
        galleryThumbs.forEach(thumb => {
            thumb.addEventListener('click', function() {
                mainImage.src = this.dataset.image;
                galleryThumbs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }
});

// Handle booking form
document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.querySelector('#booking-form');
    if (bookingForm) {
        const checkIn = bookingForm.querySelector('#check_in');
        const checkOut = bookingForm.querySelector('#check_out');
        const guests = bookingForm.querySelector('#guests');
        const pricePerNight = parseFloat(bookingForm.dataset.price);
        const totalPrice = bookingForm.querySelector('#total_price');

        function updateTotalPrice() {
            if (checkIn.value && checkOut.value) {
                const start = new Date(checkIn.value);
                const end = new Date(checkOut.value);
                const nights = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                if (nights > 0) {
                    const total = nights * pricePerNight;
                    totalPrice.textContent = total.toFixed(2) + ' KWD';
                }
            }
        }

        checkIn.addEventListener('change', updateTotalPrice);
        checkOut.addEventListener('change', updateTotalPrice);
    }
});

// Handle property search filters
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('#search-filters');
    if (searchForm) {
        const priceRange = searchForm.querySelector('#price_range');
        const minPrice = searchForm.querySelector('#min_price');
        const maxPrice = searchForm.querySelector('#max_price');

        if (priceRange) {
            noUiSlider.create(priceRange, {
                start: [0, 1000],
                connect: true,
                range: {
                    'min': 0,
                    'max': 1000
                },
                format: {
                    to: value => Math.round(value),
                    from: value => value
                }
            });

            priceRange.noUiSlider.on('update', function(values) {
                minPrice.value = values[0];
                maxPrice.value = values[1];
            });
        }
    }
});

// Handle property manager calendar
document.addEventListener('DOMContentLoaded', function() {
    const calendar = document.querySelector('#property-calendar');
    if (calendar) {
        const events = JSON.parse(calendar.dataset.events || '[]');
        
        new FullCalendar.Calendar(calendar, {
            initialView: 'dayGridMonth',
            events: events,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            eventClick: function(info) {
                const event = info.event;
                const modal = new bootstrap.Modal(document.getElementById('event-modal'));
                document.getElementById('event-title').textContent = event.title;
                document.getElementById('event-start').textContent = event.start.toLocaleDateString();
                document.getElementById('event-end').textContent = event.end.toLocaleDateString();
                modal.show();
            }
        }).render();
    }
});

// Handle messages
document.addEventListener('DOMContentLoaded', function() {
    const messageContainer = document.querySelector('#message-container');
    if (messageContainer) {
        const messageList = messageContainer.querySelector('.message-list');
        const messageForm = messageContainer.querySelector('#message-form');
        const messageInput = messageContainer.querySelector('#message-input');

        // Scroll to bottom of message list
        function scrollToBottom() {
            messageList.scrollTop = messageList.scrollHeight;
        }

        scrollToBottom();

        // Handle message form submission
        if (messageForm) {
            messageForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(messageForm);
                try {
                    const response = await fetch(messageForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (response.ok) {
                        messageInput.value = '';
                        const data = await response.json();
                        // Append new message to list
                        const messageHtml = `
                            <div class="message-bubble sent">
                                <p class="mb-1">${data.message}</p>
                                <small class="text-white-50">Just now</small>
                            </div>
                        `;
                        messageList.insertAdjacentHTML('beforeend', messageHtml);
                        scrollToBottom();
                    }
                } catch (error) {
                    console.error('Error sending message:', error);
                }
            });
        }
    }
});

// Handle notifications
document.addEventListener('DOMContentLoaded', function() {
    const notificationsList = document.querySelector('#notifications-list');
    if (notificationsList) {
        // Mark notification as read when clicked
        notificationsList.addEventListener('click', async function(e) {
            const notificationLink = e.target.closest('[data-notification-id]');
            if (notificationLink) {
                const notificationId = notificationLink.dataset.notificationId;
                try {
                    await fetch(`/notifications/${notificationId}/mark-as-read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                }
            }
        });
    }
});
