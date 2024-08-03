# Booking System API

This project provides a backend structure for a booking system for different classes. It is built with Laravel and despite using Eloquet for some actions, I try to use pure sql queries.
The API includes endpoints for viewing available classes, booking a class, and canceling a booking.

## Table of Contents
- [Dependencies](#dependencies)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [API Endpoints](#api-endpoints)
  - [List Available Classes](#list-available-classes)
  - [Book a Class](#book-a-class)
  - [Cancel a Booking](#cancel-a-booking)
- [Running Tests](#running-tests)
- [Contributing](#contributing)
- [License](#license)

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
## API Endpoints

### List Available Classes

- **URL:** `/api/classes`
- **Method:** `GET`
- **Response:**
  ```json
  [
      {
          "id": 1,
          "name": "Math Classroom",
          "start_time": "09:00:00",
          "end_time": "19:00:00",
          "duration": 2,
          "capacity": 10,
          "days": ["Monday", "Tuesday", "Wednesday"],
          "current_availability": 10
      },
      ...
  ]
