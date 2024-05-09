import React from 'react';
import { Link } from 'react-router-dom';
import { useQuery, getUserListings } from 'wasp/client/operations';

const HomePage = () => {
  const { data: userlistings, isLoading, error } = useQuery(getUserListings);

  if (isLoading) return 'Loading...';
  if (error) return 'Error: ' + error;

  return (
    <div className='p-4'>
      <h1 className='text-3xl font-bold mb-4'>Welcome to HabibiStay</h1>
      <p className='text-lg mb-4'>Find your perfect stay in Saudi Arabia</p>
      <nav className='flex gap-x-4'>
        <Link to='/listings' className='text-blue-500 hover:underline'>View Listings</Link>
        <Link to='/dashboard' className='text-blue-500 hover:underline'>Dashboard</Link>
      </nav>
      <div className='mt-4'>
        {userlistings.map((listing) => (
          <div key={listing.id} className='bg-white p-2 mb-2 rounded shadow-md'>
            <div>{listing.address}</div>
            <div>Price: ${listing.price} per night</div>
            <div>Available from: {new Date(listing.availableFrom).toLocaleDateString()}</div>
            <div>Available until: {new Date(listing.availableUntil).toLocaleDateString()}</div>
            <div>Max guests: {listing.maxGuests}</div>
          </div>
        ))}
      </div>
    </div>
  );
}

export default HomePage;