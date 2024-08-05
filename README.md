# Booking System API

This project provides a backend structure for a booking system for different classes. It is built with Laravel and despite using Eloquet for some actions, I try to use pure sql queries.
The API includes endpoints for viewing available classes, booking a class, and canceling a booking.

## Table of Contents
- [Dependencies](#dependencies)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [API Endpoints](#api-endpoints)
  - [GET CLASSROMS](#get-classroms)
  - [POST BOOK](#post-book)
  - [GET USER'S BOOKINGS](#get-users-bookings)
  - [DELETE BOOKING](#delete-booking)
- [Running Tests](#running-tests)
- [Postman Collection](#postman-collection)

## Dependencies
- **Docker:** [`https://docs.docker.com/engine/install/`](https://docs.docker.com/engine/install/)
- **Docker Compose:** [`https://docs.docker.com/compose/install/`](https://docs.docker.com/compose/install/)


## Installation

### Using Docker

1. Clone the repository:
    ```sh
    git clone git@github.com:gordomo/clearit-booking.git
    cd clearit-booking
    ```

2. Build and run the Docker containers:
    ```sh
    docker compose up -d --build
    ```

3. Install PHP dependencies:
    ```sh
    docker compose exec app composer install
    ```

## Configuration

1. Copy the `.env.example` file to `.env`:
    ```sh
    cp clearit-booking/.env.example clearit-booking/.env
    ```

2. Generate the application key:
    ```sh
    docker compose exec app php artisan key:generate
    ```

3. Update the `.env` file with your database configuration:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=booking_system
    DB_USERNAME=user
    DB_PASSWORD=secret
    ```

## Database Setup

1. Run the migrations:
    ```sh
    docker compose exec app php artisan migrate
    ```
## Troubleshooting
1. fix common permisions error
    ```sh
    chmod -R 755 clearit-booking/storage/logs
    chmod -R 777 clearit-booking/storage/framework/cache
    ```
    
## Running Tests
```sh
    docker compose exec app php artisan test
```

## API Endpoints

### GET CLASSROMS

- **URL:** `http://localhost:8080/api/classes`
- **Method:** `GET`
- **Response:**
  ```json
  [
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Math Classroom",
            "days": "[\"Monday\",\"Tuesday\",\"Wednesday\"]",
            "start_time": "09:00:00",
            "end_time": "19:00:00",
            "capacity": 10,
            "duration": 2,
            "created_at": "2024-08-03 22:08:51",
            "updated_at": "2024-08-03 22:08:51"
        },
      ...
  ]
  ```
### POST BOOK

- **URL:** `http://localhost:8080/api/book`
- **Method:** `POST`
- **Parameters:** 
    ```json
    {
        "classroom_id": 1,
        "user": "user@example.com",
        "start_time": "2024-08-10 09:00:00"
    }
    ```
- **Response:**
  ```json
  [
    {
        "success": true,
        "data": {
            "bookingId": {
                "id": 1
            }
        },
        "message": "Booking created successfully",
        "code": 201
    }
  ]

### GET USER'S BOOKINGS

- **URL:** `http://localhost:8080/api/classes/user`
- **Method:** `GET`
- **Parameters:** 
    ```json
    {
        "user": "user@example.com",
    }
    ```
- **Response:**
  ```json
  [
    {
        "success": true,
        "data": [
            {
                "id": 1,
                "classroom_id": 1,
                "user": "morimartin@gmail.com",
                "start_time": "2024-08-14 11:00:00",
                "end_time": "2024-08-14 13:00:00",
                "created_at": "2024-08-03 22:24:33",
                "updated_at": "2024-08-03 22:24:33"
            }
        ],
        "message": "",
        "code": 200
    }
  ]

### DELETE BOOKING

- **URL:** `http://localhost:8080/api/cancel/{bookingId}`
- **Method:** `DELETE`
- **Parameters:** 
    ```json
    {
        "user": "user@example.com",
    }
    ```
- **Response:**
  ```json
  [
    {
        "success": true,
        "data": [],
        "message": "Booking canceled successfully",
        "code": 200
    }
  ]  

## Models

### Classroom
- `id`: Primary key
- `name`: String
- `start_time`: Time
- `end_time`: Time
- `duration`: Integer
- `capacity`: Integer
- `days`: JSON

### Booking
- `id`: Primary key
- `classroom_id`: Foreign key referencing Classroom
- `user`: String
- `start_time`: Datetime
- `end_time`: Datetime
- `created_at`: Datetime
- `updated_at`: Datetime

## Relationships
- A `Classroom` can have many `Bookings`.
- A `Booking` belongs to a `Classroom`.


## Database ER Diagram

### Classroom
| Field      | Type    | Description                   |
|------------|---------|-------------------------------|
| id (PK)    | int     | Primary key                   |
| name       | string  | Name of the classroom         |
| start_time | time    | Start time of the classroom   |
| end_time   | time    | End time of the classroom     |
| duration   | int     | Duration of each session      |
| capacity   | int     | Capacity of the classroom     |
| days       | json    | Days the classroom is active  |

### Booking
| Field          | Type     | Description                           |
|----------------|----------|---------------------------------------|
| id (PK)        | int      | Primary key                           |
| classroom_id (FK) | int   | Foreign key referencing Classroom     |
| user           | string   | User who booked the classroom         |
| start_time     | datetime | Start time of the booking             |
| end_time       | datetime | End time of the booking               |
| created_at     | datetime | Timestamp when the booking was created|
| updated_at     | datetime | Timestamp when the booking was updated|

### Relationships
- A `Classroom` can have many `Bookings`.
- A `Booking` belongs to a `Classroom`.

### Visual Representation
```plaintext
+------------------+              +------------------+
|    Classroom     |              |     Booking      |
+------------------+              +------------------+
| id (PK)          |<-------------| classroom_id (FK)|
| name             |              | id (PK)          |
| start_time       |              | user             |
| end_time         |              | start_time       |
| duration         |              | end_time         |
| capacity         |              +------------------+
| days             |              
+------------------+              
```

## Postman collection

A postman collection has been added to this repository (api.postman_collection.json)<b>
Import on postman in order to use the API.



