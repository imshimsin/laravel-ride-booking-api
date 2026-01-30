@extends('admin.layouts.app')

@section('title', 'All Rides')

@section('content')
    <div class="card">
        <div class="card-header">All Rides</div>
        @if($rides->isEmpty())
            <div class="empty-state">No rides found.</div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Passenger</th>
                        <th>Driver</th>
                        <th>Pickup</th>
                        <th>Destination</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rides as $ride)
                        <tr>
                            <td>{{ $ride->id }}</td>
                            <td>{{ $ride->passenger->name ?? '-' }}<br><small style="color:#64748b">{{ $ride->passenger->email ?? '' }}</small></td>
                            <td>{{ $ride->driver?->name ?? '-' }}<br><small style="color:#64748b">{{ $ride->driver?->email ?? '' }}</small></td>
                            <td>{{ number_format($ride->pickup_latitude, 6) }}, {{ number_format($ride->pickup_longitude, 6) }}</td>
                            <td>{{ number_format($ride->destination_latitude, 6) }}, {{ number_format($ride->destination_longitude, 6) }}</td>
                            <td>
                                <span class="badge badge-{{ $ride->status }}">
                                    {{ ucfirst($ride->status) }}
                                </span>
                            </td>
                            <td>{{ $ride->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.rides.show', $ride) }}" class="btn btn-primary">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="padding: 1rem 1.25rem; border-top: 1px solid #334155;">
                {{ $rides->links('vendor.pagination.simple-default') }}
            </div>
        @endif
    </div>
@endsection
