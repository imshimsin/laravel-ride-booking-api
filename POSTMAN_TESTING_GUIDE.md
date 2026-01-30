# Testing the API with Postman

This guide walks you through testing the ride booking API in Postman. Start with database setup.

## Database Setup

```bash
php artisan migrate:fresh --seed
```

This will recreate all tables and seed sample data via RideBookingSeeder. You'll get 3 users - 1 passenger, 2 drivers.

To check user IDs:
```bash
php artisan tinker
>>> User::all(['id','name','email','type']);
```

Usually IDs 1, 2, 3. Might differ in your DB - note them down.

## Postman Setup

**Base URL:**
- XAMPP: `http://localhost/laravel-ride-booking-api/public`
- artisan serve: `http://127.0.0.1:8000`

Add these headers to every request:
- `X-User-Id` - 1 (passenger) or 2/3 (driver) - depends on the endpoint
- `Accept` - application/json
- `Content-Type` - application/json (for POST requests)

## Step by Step

### 1. Passenger - Create ride

POST `{{base}}/api/passenger/rides`  
Headers: X-User-Id: 1  
Body (raw JSON):
```json
{
  "pickup_latitude": 40.7128,
  "pickup_longitude": -74.0060,
  "destination_latitude": 40.7580,
  "destination_longitude": -73.9855
}
```

Note the ride.id from the response - you'll need it for later steps.

### 2. Driver - Update location

POST `{{base}}/api/driver/location`  
Headers: X-User-Id: 2  
Body:
```json
{
  "latitude": 40.7128,
  "longitude": -74.0060
}
```

Driver must update location first, otherwise nearby rides will be empty.

### 3. Driver - Fetch nearby rides

GET `{{base}}/api/driver/rides/nearby?radius=10`  
Headers: X-User-Id: 2  

Radius is in km. Default 10, max 100. Distance between driver's location and ride pickup must be within radius - only then the ride shows up.

### 4. Driver - Request ride

POST `{{base}}/api/driver/rides/4/request`  
(Replace 4 with your ride id)  
Headers: X-User-Id: 2  

Response will have ride.driver_requests with an id - that's the ride_driver_request_id. You need it for the approve step.

### 5. Passenger - Approve driver

POST `{{base}}/api/passenger/rides/4/approve-driver/5`  
(4 = ride_id, 5 = ride_driver_request_id - from step 4 response)  
Headers: X-User-Id: 1  

Wrong ride_driver_request_id gives 422 "Invalid ride driver request". The ID must belong to the ride in the URL.

### 6. Passenger - Mark complete

POST `{{base}}/api/passenger/rides/4/complete`  
Headers: X-User-Id: 1  

### 7. Driver - Mark complete

POST `{{base}}/api/driver/rides/4/complete`  
Headers: X-User-Id: 2  

Steps 6 and 7 can be in any order. Ride becomes completed when both are done.

## Quick test with seeded data

After seeding, Ride 3 is pending with 2 drivers who've requested. To test approve directly:

Check `ride_driver_requests` table for ride_id=3 - IDs will be 2 and 3.

POST `{{base}}/api/passenger/rides/3/approve-driver/2` (X-User-Id: 1)

Then run complete steps 6 and 7.

## Common errors

| Error | Cause | Fix |
|-------|-------|-----|
| 401 User not found | Wrong X-User-Id or user doesn't exist | Run db:seed, use correct ID |
| 403 Forbidden | Passenger ID on driver endpoint or vice versa | Use correct role for the endpoint |
| 422 Invalid ride driver request | ride_driver_request_id doesn't belong to that ride | Use ID from ride's driver_requests |
| 422 Ride already has driver | Ride already accepted | Create new ride |
| Please update your location first | Driver has no location | POST /api/driver/location first |
| Nearby rides empty | Driver too far from ride pickup | Set driver location near pickup or increase radius |

## Postman Environment (optional)

Create an environment and add variables:
- base_url
- passenger_id = 1
- driver_id = 2
- ride_id = 4

URL: `{{base_url}}/api/...`  
Header: `X-User-Id: {{passenger_id}}`

## Quick commands

```bash
php artisan migrate:fresh --seed
php artisan serve
```
