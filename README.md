# Trip Booking API

Laravel API for intercity bus trip booking. API-only — all responses are JSON.

## Features

- Manage cities, buses, and trips
- Auto-create seats when a bus is created
- List trips with available seat count
- View seat availability per trip
- Create bookings (passenger is created/found by phone)
- Confirm or cancel bookings
- Prevent double booking the same seat on a trip
- Block booking after the trip has departed

## Tech Stack

- PHP 8.3+
- Laravel 13
- MySQL
- Eloquent ORM
- Form Requests
- API Resources

## Database

| Table | Purpose |
|-------|---------|
| `cities` | Departure / destination cities |
| `buses` | Bus with plate number and seat capacity |
| `seats` | Seats belonging to a bus (`bus_id` + `seat_number` unique) |
| `trips` | Trip between two cities on a bus, with departure time and seat price |
| `passengers` | Passenger name + phone |
| `bookings` | Links trip + seat + passenger, with status |

Relationships:

```
City → Trips (from / to)
Bus → Seats, Trips
Trip → Bookings
Seat → Bookings
Passenger → Bookings
```

## Booking Status

`pending` → `confirmed` or `cancelled`  
`confirmed` → `cancelled`  
Cancelled bookings cannot change status again.

Active bookings (`pending` + `confirmed`) occupy the seat. Cancelled seats become available again.

## Business Rules

- Seat must belong to the trip’s bus
- Same seat cannot have two active bookings on the same trip
- Cannot book a trip that already departed
- Same bus cannot have two trips at the same departure time
- Booking creation uses `DB::transaction()` + `lockForUpdate()` to reduce race conditions

## Architecture (kept simple)

```
Route → Form Request → Controller → Service → Model
```

- Controllers stay thin
- Business logic lives in services (`BookingService`, `TripService`, `BusService`)
- Validation in Form Requests
- Booking status via `BookingStatus` enum + `HasBookingStatus` trait (`active()` scope)

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/cities` | List cities |
| POST | `/api/cities` | Create city |
| GET | `/api/buses` | List buses |
| POST | `/api/buses` | Create bus + seats |
| GET | `/api/trips` | List trips with available seats |
| POST | `/api/trips` | Create trip |
| GET | `/api/trips/{trip}/seats` | Seat availability for a trip |
| GET | `/api/bookings` | List bookings |
| POST | `/api/bookings` | Create booking |
| PATCH | `/api/bookings/{booking}/status` | Confirm or cancel |

### Example requests

**Create city**

```json
POST /api/cities
{ "name": "Damascus" }
```

**Create bus**

```json
POST /api/buses
{ "plate_number": "12345", "seats_count": 40 }
```

**Create trip**

```json
POST /api/trips
{
  "from_city_id": 1,
  "to_city_id": 2,
  "bus_id": 1,
  "departure_time": "2026-10-15 10:00:00",
  "seat_price": 100
}
```

**Create booking**

```json
POST /api/bookings
{
  "trip_id": 1,
  "seat_number": "5",
  "name": "Ahmad Ali",
  "phone": "0999123456"
}
```

**Update booking status**

```json
PATCH /api/bookings/1/status
{ "status": "confirmed" }
```

or `"cancelled"`.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure DB in `.env`, then:

```bash
php artisan migrate
php artisan serve
```

API base: `http://127.0.0.1:8000/api`
