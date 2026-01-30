<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RideResource;
use App\Models\Ride;
use App\Models\RideDriverRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PassengerController extends Controller
{
    public function createRide(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pickup_latitude' => 'required|numeric|between:-90,90',
            'pickup_longitude' => 'required|numeric|between:-180,180',
            'destination_latitude' => 'required|numeric|between:-90,90',
            'destination_longitude' => 'required|numeric|between:-180,180',
        ]);

        $ride = Ride::create(array_merge($data, [
            'passenger_id' => $request->user()->id,
            'status' => 'pending',
        ]));

        return response()->json([
            'message' => 'Ride request created successfully',
            'ride' => new RideResource($ride),
        ], 201);
    }

    public function approveDriver(Request $request, Ride $ride, RideDriverRequest $rideDriverRequest): JsonResponse
    {
        $user = $request->user();
        if ($ride->passenger_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if ($ride->status !== 'pending') {
            return response()->json(['message' => 'Ride already has a driver assigned'], 422);
        }
        if ($rideDriverRequest->ride_id !== $ride->id) {
            return response()->json(['message' => 'Invalid ride driver request'], 422);
        }
        if ($rideDriverRequest->status !== 'pending') {
            return response()->json(['message' => 'Driver request already processed'], 422);
        }

        $rideDriverRequest->update(['status' => 'approved']);
        $ride->update([
            'driver_id' => $rideDriverRequest->driver_id,
            'status' => 'accepted',
        ]);

        RideDriverRequest::where('ride_id', $ride->id)
            ->where('id', '!=', $rideDriverRequest->id)
            ->update(['status' => 'rejected']);

        return response()->json([
            'message' => 'Driver approved successfully',
            'ride' => new RideResource($ride->fresh()),
        ]);
    }

    public function markCompleted(Request $request, Ride $ride): JsonResponse
    {
        $user = $request->user();
        if ($ride->passenger_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if ($ride->status === 'pending') {
            return response()->json(['message' => 'Ride has no driver assigned yet'], 422);
        }
        if ($ride->passenger_completed_at) {
            return response()->json(['message' => 'Already marked completed'], 422);
        }

        $ride->update(['passenger_completed_at' => now()]);
        if ($ride->driver_completed_at) {
            $ride->update(['status' => 'completed']);
        }

        return response()->json([
            'message' => 'Ride marked as completed',
            'ride' => new RideResource($ride->fresh()),
        ]);
    }
}
