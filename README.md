# Laravel Ride Booking API

Simple ride booking API built with Laravel. Has passenger/driver endpoints and a basic admin panel to view rides.

## What it does

Passengers can create ride requests, approve drivers who've requested the ride, and mark rides complete. Drivers update their location, see nearby pending rides (within a radius), request rides, and mark them complete. A ride is only fully done when both passenger and driver have marked it complete.

Admin panel at `/admin/rides` shows all rides with details.

## Setup

You'll need PHP 8.2+, Composer, and a database (SQLite or MySQL works fine).

```bash
git clone <repo-url>
cd laravel-ride-booking-api
composer install
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`. For SQLite, make sure `database/database.sqlite` exists. For MySQL, set the usual DB_* vars.

```bash
php artisan migrate
php artisan db:seed   # optional - adds sample users and rides
php artisan serve
```

App runs at `http://localhost:8000`.

## API Usage

No real auth for this demo. Just pass `X-User-Id` header with a valid user ID. Passenger endpoints need a passenger user, driver endpoints need a driver user. Wrong type = 403.

After seeding you get:
- ID 1: passenger@example.com (passenger)
- ID 2: driver@example.com (driver)
- ID 3: driver2@example.com (driver)

### Passenger

**Create ride**
```
POST /api/passenger/rides
X-User-Id: 1
Body: { "pickup_latitude": 40.7128, "pickup_longitude": -74.0060, "destination_latitude": 40.7580, "destination_longitude": -73.9855 }
```

**Approve driver**
```
POST /api/passenger/rides/{ride_id}/approve-driver/{ride_driver_request_id}
X-User-Id: 1
```

**Mark complete**
```
POST /api/passenger/rides/{ride_id}/complete
X-User-Id: 1
```

### Driver

**Update location** (required before nearby rides work)
```
POST /api/driver/location
X-User-Id: 2
Body: { "latitude": 40.7128, "longitude": -74.0060 }
```

**Nearby rides**
```
GET /api/driver/rides/nearby?radius=10
X-User-Id: 2
```
Radius is in km. Default 10, max 100.

**Request ride**
```
POST /api/driver/rides/{ride_id}/request
X-User-Id: 2
```

**Mark complete**
```
POST /api/driver/rides/{ride_id}/complete
X-User-Id: 2
```

## Flow

1. Passenger creates ride
2. Driver updates location
3. Driver fetches nearby rides
4. Driver requests a ride
5. Passenger approves one driver
6. Passenger marks complete
7. Driver marks complete

Both 6 and 7 can happen in any order. Ride status becomes "completed" only when both are done.

## Tech

Laravel 11, Blade for admin, no frontend framework. Header-based user identification for the API.
