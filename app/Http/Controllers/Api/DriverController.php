<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RideResource;
use App\Models\Ride;
use App\Models\RideDriverRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function updateLocation(Request $request): JsonResponse
    {
        $data = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $request->user()->update($data);

        return response()->json([
            'message' => 'Location updated successfully',
            'location' => [
                'latitude' => (float) $request->user()->latitude,
                'longitude' => (float) $request->user()->longitude,
            ],
        ]);
    }

    public function nearbyRides(Request $request): JsonResponse
    {
        $radius = $request->validate(['radius' => 'nullable|numeric|min:0.1|max:100'])['radius'] ?? 10;
        $driver = $request->user();

        if (! $driver->latitude || ! $driver->longitude) {
            return response()->json(['message' => 'Update your location first', 'rides' => []]);
        }

        $rides = Ride::pending()
            ->whereDoesntHave('driverRequests', fn ($q) => $q->where('driver_id', $driver->id))
            ->get()
            ->filter(function (Ride $ride) use ($driver, $radius) {
                return $this->distanceKm(
                    (float) $driver->latitude,
                    (float) $driver->longitude,
                    (float) $ride->pickup_latitude,
                    (float) $ride->pickup_longitude
                ) <= $radius;
            })
            ->values();

        $rides->load(['passenger', 'driver', 'driverRequests.driver']);

        return response()->json(['rides' => RideResource::collection($rides)]);
    }

    public function requestRide(Request $request, Ride $ride): JsonResponse
    {
        if ($ride->status !== 'pending') {
            return response()->json(['message' => 'Ride is no longer available'], 422);
        }

        $existing = RideDriverRequest::where('ride_id', $ride->id)
            ->where('driver_id', $request->user()->id)
            ->first();

        if ($existing) {
            $msg = $existing->status === 'pending'
                ? 'You have already requested this ride'
                : 'Your request was already ' . $existing->status;
            return response()->json(['message' => $msg], 422);
        }

        RideDriverRequest::create([
            'ride_id' => $ride->id,
            'driver_id' => $request->user()->id,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Ride request submitted. Waiting for passenger approval.',
            'ride' => new RideResource($ride->fresh()->load('driverRequests.driver')),
        ], 201);
    }

    public function markCompleted(Request $request, Ride $ride): JsonResponse
    {
        if ($ride->driver_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if ($ride->driver_completed_at) {
            return response()->json(['message' => 'Already marked completed'], 422);
        }

        $ride->update(['driver_completed_at' => now()]);
        if ($ride->passenger_completed_at) {
            $ride->update(['status' => 'completed']);
        }

        return response()->json([
            'message' => 'Ride marked as completed',
            'ride' => new RideResource($ride->fresh()),
        ]);
    }

    private function distanceKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $R = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $R * $c;
    }
}
