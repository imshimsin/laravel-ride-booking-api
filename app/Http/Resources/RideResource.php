<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RideResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $ride = $this->resource;
        $ride->loadMissing(['passenger', 'driver', 'driverRequests.driver']);

        return [
            'id' => $ride->id,
            'passenger' => $this->userArray($ride->passenger),
            'driver' => $this->userArray($ride->driver),
            'pickup' => [
                'latitude' => (float) $ride->pickup_latitude,
                'longitude' => (float) $ride->pickup_longitude,
            ],
            'destination' => [
                'latitude' => (float) $ride->destination_latitude,
                'longitude' => (float) $ride->destination_longitude,
            ],
            'status' => $ride->status,
            'passenger_completed_at' => $ride->passenger_completed_at?->toIso8601String(),
            'driver_completed_at' => $ride->driver_completed_at?->toIso8601String(),
            'driver_requests' => $ride->driverRequests->map(fn ($req) => [
                'id' => $req->id,
                'driver' => $this->userArray($req->driver),
                'status' => $req->status,
            ])->values()->all(),
            'created_at' => $ride->created_at->toIso8601String(),
            'updated_at' => $ride->updated_at->toIso8601String(),
        ];
    }

    private function userArray($user): ?array
    {
        if (! $user) {
            return null;
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email ?? null,
        ];
    }
}
