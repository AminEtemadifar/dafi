# Dafi Laravel Project

This is a Laravel-based single-page application that converts the original HTML templates into a modern, component-based architecture with AJAX navigation.

## Features

- **Single Page Application**: All components are loaded via AJAX without page refresh
- **Component-Based Architecture**: Each template is converted to a reusable Laravel component
- **AJAX Navigation**: Smooth transitions between different sections
- **Form Handling**: Proper form validation and database storage
- **Responsive Design**: Maintains the original Persian/Farsi design and RTL layout

## Components

1. **Welcome Component** (`/`) - Landing page with hero image and steps
2. **Information Component** - User registration form (name and mobile)
3. **OTP Component** - OTP verification form
4. **Coupon Component** - Coupon code input with payment option
5. **Deliver Component** - Audio player with music delivery

## Installation

1. Clone the project
2. Install dependencies: `composer install`
3. Copy `.env.example` to `.env` and configure database
4. Generate application key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Start the server: `php artisan serve`

## Usage

- Navigate to `/` to see the main application
- Use the navigation buttons to move between components
- All navigation is handled via AJAX for smooth user experience
- Browser back/forward buttons are supported

## API Endpoints

- `POST /api/information` - Store user information
- `POST /api/verify-otp` - Verify OTP code
- `POST /api/verify-coupon` - Verify coupon code
- `GET /component/{component}` - Load component views

## Database

The application uses a `users` table with the following fields:
- `id` (primary key)
- `name` (from Laravel default)
- `fullname` (user's full name)
- `mobile` (user's mobile number)
- `email` (from Laravel default)
- `password` (from Laravel default)
- `created_at` and `updated_at` timestamps

## Assets

All original assets (images, fonts, audio) are preserved in the `public/assets` directory and properly referenced using Laravel's `asset()` helper.

## Notes

- This is a demo application with simplified validation
- OTP verification accepts any 4-digit code for demonstration
- Coupon verification accepts any non-empty string for demonstration
- In production, implement proper OTP generation and verification
- Add proper payment gateway integration for the payment functionality
