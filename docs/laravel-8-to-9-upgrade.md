# Laravel 8 → Laravel 9 Upgrade Documentation

## Project Upgrade Summary

This document records all packages removed and updated during the
upgrade from Laravel 8 to Laravel 9.

Framework upgraded to:

-   Laravel 9.x

------------------------------------------------------------------------

# Packages Removed

The following packages were removed because they were either deprecated,
incompatible with Laravel 9, or no longer required.

## 1. pragmarx/tracker

Reason: Caused dependency conflicts with illuminate/support and was not
compatible with Laravel 9.

------------------------------------------------------------------------

## 2. fideloper/proxy

Reason: No longer required in Laravel 9. Laravel now includes proxy
handling internally.

------------------------------------------------------------------------

# Packages Updated

The following packages were upgraded to versions compatible with Laravel
9.

## 1. laravel/framework

Updated from: "\^8.0"

To: "\^9.0"

------------------------------------------------------------------------

## 2. laravel/passport

Updated from: "\^10.4"

To: "\^11.0"

Important Change: Removed the following line from AuthServiceProvider:

Passport::routes();

In Passport v11+, routes are automatically registered.

------------------------------------------------------------------------

## 3. laravel/jetstream

Updated from: "\^2.3"

To: "\^3.0"

------------------------------------------------------------------------

## 4. barryvdh/laravel-dompdf

Updated from: "\^0.9.0"

To: "\^2.0"

Older version required illuminate/support \^8 which conflicts with
Laravel 9.

------------------------------------------------------------------------

## 5. laravelcollective/html

Changed from: "\^9.0"

To: "\^6.4"

Note: This package is officially abandoned. Consider migrating to a
maintained alternative in the future.

------------------------------------------------------------------------

## 6. spatie/laravel-permission

Updated from: "\^4.2"

To: "\^5.0"

Required for Laravel 9 compatibility.

------------------------------------------------------------------------

## 7. nunomaduro/collision (dev)

Updated to: "\^6.1"

Required for Laravel 9 console compatibility.

------------------------------------------------------------------------

# Structural Changes

## Database Folder Structure Updated

Moved:

app/database/

To:

database/ ├── migrations/ ├── seeders/ ├── factories/

------------------------------------------------------------------------

## Composer Autoload Cleaned

Removed custom database PSR-4 entries.

Kept only:

"psr-4": { "App\\": "app/" }

------------------------------------------------------------------------

# Cleanup Commands Used

rm composer.lock rm -rf vendor composer update -W php artisan
optimize:clear composer dump-autoload

------------------------------------------------------------------------

# Final Status

Laravel 9 successfully installed and project upgraded.
