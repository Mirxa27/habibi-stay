<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PropertySeeder extends Seeder
{
    public function run()
    {
        $luxuryProperties = [
            [
                'title' => 'Luxury Beachfront Villa',
                'description' => 'Experience ultimate luxury in this stunning beachfront villa. Wake up to panoramic ocean views, enjoy private beach access, and indulge in world-class amenities including an infinity pool, private chef, and personal butler service.',
                'type' => 'villa',
                'price' => 1200,
                'bedrooms' => 5,
                'bathrooms' => 6,
                'capacity' => 10,
                'amenities' => json_encode([
                    'Private Pool', 'Beach Access', 'Butler Service', 'Chef Service',
                    'Home Theater', 'Gym', 'Spa', 'Wine Cellar', 'Helicopter Pad'
                ]),
                'location' => 'Dubai Marina',
                'latitude' => 25.0819,
                'longitude' => 55.1367,
            ],
            [
                'title' => 'Palm Jumeirah Penthouse',
                'description' => 'Spectacular penthouse offering breathtaking views of the Arabian Gulf and Dubai skyline. Features include a private rooftop pool, smart home technology, and exclusive access to five-star hotel amenities.',
                'type' => 'penthouse',
                'price' => 900,
                'bedrooms' => 4,
                'bathrooms' => 4.5,
                'capacity' => 8,
                'amenities' => json_encode([
                    'Rooftop Pool', 'Smart Home', 'Concierge', 'Private Elevator',
                    'Beach Club Access', 'Parking', 'Security', 'Housekeeping'
                ]),
                'location' => 'Palm Jumeirah',
                'latitude' => 25.1124,
                'longitude' => 55.1390,
            ],
            [
                'title' => 'Desert Oasis Resort Villa',
                'description' => 'Immerse yourself in luxury desert living. This stunning villa combines traditional Arabian architecture with modern luxury, featuring a private pool, desert views, and access to world-class resort amenities.',
                'type' => 'villa',
                'price' => 800,
                'bedrooms' => 3,
                'bathrooms' => 3.5,
                'capacity' => 6,
                'amenities' => json_encode([
                    'Private Pool', 'Desert Views', 'Resort Access', 'BBQ Area',
                    'Garden', 'Parking', 'Security', '24/7 Support'
                ]),
                'location' => 'Arabian Ranches',
                'latitude' => 25.0511,
                'longitude' => 55.2864,
            ],
        ];

        $user = User::firstOrCreate(
            ['email' => 'host@example.com'],
            [
                'name' => 'Luxury Host',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        foreach ($luxuryProperties as $propertyData) {
            $property = Property::create(array_merge($propertyData, [
                'user_id' => $user->id,
                'slug' => Str::slug($propertyData['title']),
                'status' => 'active',
                'featured' => true,
                'verified' => true,
                'images' => json_encode([
                    'https://source.unsplash.com/1600x900/?luxury,villa',
                    'https://source.unsplash.com/1600x900/?mansion,pool',
                    'https://source.unsplash.com/1600x900/?penthouse,view',
                    'https://source.unsplash.com/1600x900/?luxury,interior'
                ]),
                'rules' => json_encode([
                    'No smoking indoors',
                    'No pets allowed',
                    'No parties or events',
                    'Check-in time is 2PM - 8PM',
                    'Check out by 11AM'
                ]),
                'cancellation_policy' => 'Flexible - Full refund 7 days prior to check-in',
                'min_stay' => 2,
                'max_stay' => 30,
            ]));
        }
    }
}
