<?php

namespace Database\Seeders;

use App\Models\Ride;
use App\Models\RideDriverRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RideBookingSeeder extends Seeder
{
    public function run(): void
    {
        RideDriverRequest::query()->delete();
        Ride::query()->delete();
        $passenger = User::updateOrCreate(
            ['email' => 'passenger@example.com'],
            [
                'name' => 'Alice Passenger',
                'password' => Hash::make('password'),
                'type' => 'passenger',
            ]
        );

        $driver1 = User::updateOrCreate(
            ['email' => 'driver@example.com'],
            [
                'name' => 'Bob Driver',
                'password' => Hash::make('password'),
                'type' => 'driver',
                'latitude' => 40.7128,
                'longitude' => -74.0060,
            ]
        );

        $driver2 = User::updateOrCreate(
            ['email' => 'driver2@example.com'],
            [
                'name' => 'Charlie Driver',
                'password' => Hash::make('password'),
                'type' => 'driver',
                'latitude' => 40.7200,
                'longitude' => -74.0100,
            ]
        );

        $ride1 = Ride::create([
            'passenger_id' => $passenger->id,
            'driver_id' => $driver1->id,
            'pickup_latitude' => 40.7128,
            'pickup_longitude' => -74.0060,
            'destination_latitude' => 40.7580,
            'destination_longitude' => -73.9855,
            'status' => 'completed',
            'passenger_completed_at' => now(),
            'driver_completed_at' => now(),
        ]);

        $ride2 = Ride::create([
            'passenger_id' => $passenger->id,
            'driver_id' => null,
            'pickup_latitude' => 40.7300,
            'pickup_longitude' => -74.0020,
            'destination_latitude' => 40.7480,
            'destination_longitude' => -73.9850,
            'status' => 'pending',
            'passenger_completed_at' => null,
            'driver_completed_at' => null,
        ]);

        RideDriverRequest::create([
            'ride_id' => $ride2->id,
            'driver_id' => $driver2->id,
            'status' => 'approved',
        ]);

        $ride2->update(['driver_id' => $driver2->id, 'status' => 'accepted']);

        $ride3 = Ride::create([
            'passenger_id' => $passenger->id,
            'driver_id' => null,
            'pickup_latitude' => 40.7150,
            'pickup_longitude' => -74.0080,
            'destination_latitude' => 40.7550,
            'destination_longitude' => -73.9900,
            'status' => 'pending',
        ]);

        RideDriverRequest::create([
            'ride_id' => $ride3->id,
            'driver_id' => $driver1->id,
            'status' => 'pending',
        ]);

        RideDriverRequest::create([
            'ride_id' => $ride3->id,
            'driver_id' => $driver2->id,
            'status' => 'pending',
        ]);
    }
}
