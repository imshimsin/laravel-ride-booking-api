<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use Illuminate\View\View;

class RideController extends Controller
{
    public function index(): View
    {
        $rides = Ride::with(['passenger', 'driver', 'driverRequests.driver'])
            ->latest()
            ->paginate(15);

        return view('admin.rides.index', compact('rides'));
    }

    public function show(Ride $ride): View
    {
        $ride->load(['passenger', 'driver', 'driverRequests.driver']);

        return view('admin.rides.show', compact('ride'));
    }
}
