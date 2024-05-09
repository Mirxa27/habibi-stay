import React, { useState } from 'react';
import { useQuery, useAction, getListing, makeBooking } from 'wasp/client/operations';
import { Link } from 'react-router-dom';

const ListingPage = () => {
    const [guests, setGuests] = useState(1);
    const { data: listing, isLoading, error } = useQuery(getListing, { id: listingId });
    const makeBookingFn = useAction(makeBooking);

    if (isLoading) return 'Loading...';
    if (error) return 'Error: ' + error;

    const handleBookListing = () => {
        makeBookingFn({ listingId: listing.id, dateFrom: new Date(), dateUntil: new Date(), guests });
    };

    return (
        <div className='p-4'>
            <h2 className='text-2xl font-bold'>{listing.address}</h2>
            <p>Price: ${listing.price}/night</p>
            <p>Max Guests: {listing.maxGuests}</p>
            <input type='number' value={guests} onChange={(e) => setGuests(parseInt(e.target.value))} />
            <button className='bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded' onClick={handleBookListing}>
                Book Now
            </button>
        </div>
    );
}

export default ListingPage;