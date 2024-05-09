import React from 'react';
import { Link } from 'react-router-dom';
import { useQuery, getUserListings } from 'wasp/client/operations';

const DashboardPage = () => {
  const { data: userListings, isLoading, error } = useQuery(getUserListings);

  if (isLoading) return 'Loading...';
  if (error) return 'Error: ' + error;

  return (
    <div className='p-4'>
      {userListings.map((listing) => (
        <div key={listing.id} className='flex items-center justify-between bg-gray-100 p-4 mb-4 rounded-lg'>
          <div>{listing.address}</div>
          <div>Price: ${listing.price}</div>
          <div>{listing.availableFrom} - {listing.availableUntil}</div>
          <div>Max Guests: {listing.maxGuests}</div>
        </div>
      ))}
    </div>
  );
}

export default DashboardPage;