@extends('admin.layouts.app')

@section('title', 'Ride #' . $ride->id)

@section('content')
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('admin.rides.index') }}" class="btn btn-ghost">← Back to Rides</a>
    </div>

    <div class="detail-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
        <div class="detail-section">
            <h3>Ride Information</h3>
            <div class="detail-row">
                <span class="detail-label">ID</span>
                <span class="detail-value">{{ $ride->id }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span><span class="badge badge-{{ $ride->status }}">{{ ucfirst($ride->status) }}</span></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Created</span>
                <span class="detail-value">{{ $ride->created_at->format('M d, Y H:i:s') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Updated</span>
                <span class="detail-value">{{ $ride->updated_at->format('M d, Y H:i:s') }}</span>
            </div>
            @if($ride->passenger_completed_at)
            <div class="detail-row">
                <span class="detail-label">Passenger Completed</span>
                <span class="detail-value">{{ $ride->passenger_completed_at->format('M d, Y H:i:s') }}</span>
            </div>
            @endif
            @if($ride->driver_completed_at)
            <div class="detail-row">
                <span class="detail-label">Driver Completed</span>
                <span class="detail-value">{{ $ride->driver_completed_at->format('M d, Y H:i:s') }}</span>
            </div>
            @endif
        </div>

        <div class="detail-section">
            <h3>Passenger</h3>
            <div class="detail-row">
                <span class="detail-label">Name</span>
                <span class="detail-value">{{ $ride->passenger->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email</span>
                <span class="detail-value">{{ $ride->passenger->email }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">ID</span>
                <span class="detail-value">{{ $ride->passenger->id }}</span>
            </div>
        </div>

        <div class="detail-section">
            <h3>Driver</h3>
            @if($ride->driver)
                <div class="detail-row">
                    <span class="detail-label">Name</span>
                    <span class="detail-value">{{ $ride->driver->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email</span>
                    <span class="detail-value">{{ $ride->driver->email }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">ID</span>
                    <span class="detail-value">{{ $ride->driver->id }}</span>
                </div>
            @else
                <div class="detail-row">
                    <span class="detail-label">—</span>
                    <span class="detail-value" style="color:#64748b">No driver assigned</span>
                </div>
            @endif
        </div>

        <div class="detail-section">
            <h3>Pickup Coordinates</h3>
            <div class="detail-row">
                <span class="detail-label">Latitude</span>
                <span class="detail-value">{{ number_format($ride->pickup_latitude, 8) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Longitude</span>
                <span class="detail-value">{{ number_format($ride->pickup_longitude, 8) }}</span>
            </div>
        </div>

        <div class="detail-section">
            <h3>Destination Coordinates</h3>
            <div class="detail-row">
                <span class="detail-label">Latitude</span>
                <span class="detail-value">{{ number_format($ride->destination_latitude, 8) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Longitude</span>
                <span class="detail-value">{{ number_format($ride->destination_longitude, 8) }}</span>
            </div>
        </div>

        @if($ride->driverRequests->isNotEmpty())
        <div class="detail-section" style="grid-column: 1 / -1;">
            <h3>Driver Requests</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Driver</th>
                        <th>Status</th>
                        <th>Requested At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ride->driverRequests as $request)
                        <tr>
                            <td>{{ $request->driver->name }} ({{ $request->driver->email }})</td>
                            <td><span class="badge badge-{{ $request->status }}">{{ ucfirst($request->status) }}</span></td>
                            <td>{{ $request->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
@endsection
