# SkipCash Payment Gateway Integration

This document provides detailed information about the SkipCash payment gateway integration in IIAQATAR.

## Overview

SkipCash is a payment gateway that has been integrated into the IIAQATAR platform to support payments for:
- Course enrollments
- Event registrations
- Membership subscriptions

## Configuration

### Environment Variables

Add the following variables to your `.env` file:

```env
SKIPCASH_CLIENT_ID=your_client_id
SKIPCASH_KEY_ID=your_key_id
SKIPCASH_KEY_SECRET=your_key_secret
SKIPCASH_WEBHOOK_KEY=your_webhook_key
SKIPCASH_URL=https://skipcashtest.azurewebsites.net  # Test environment
#SKIPCASH_URL=https://api.skipcash.app  # Production environment
```

### Configuration File

The SkipCash configuration is stored in `config/skipcash.php`:

```php
return [
    'client_id' => env('SKIPCASH_CLIENT_ID', ''),
    'key_id' => env('SKIPCASH_KEY_ID', ''),
    'key_secret' => env('SKIPCASH_KEY_SECRET', ''),
    'webhook_key' => env('SKIPCASH_WEBHOOK_KEY', ''),
    'url' => env('SKIPCASH_URL', 'https://api.skipcash.app'),
];
```

## Database

### Migrations

Run the following migrations to set up the necessary tables:

```bash
php artisan migrate
```

This will create:
- `skipcash_logs` table - Stores all SkipCash transaction logs
- Add `payment_method` column to `subscriptions` table

### Models

- `SkipcashLog` - Logs all SkipCash API interactions

## API Endpoints

### Generate Payment Link for Course Enrollment

**Endpoint:** `POST /payment/skipcash/course/{course_id}`

**Authentication:** Required

**Request Body:**
```json
{
  "user_id": 1
}
```

**Response:**
```json
{
  "success": true,
  "payment_url": "https://skipcash.app/pay/xxx",
  "transaction_id": "COURSE_1_1234567890"
}
```

### Generate Payment Link for Event Registration

**Endpoint:** `POST /payment/skipcash/event/{event_id}`

**Authentication:** Required

**Request Body:**
```json
{
  "user_id": 1
}
```

**Response:**
```json
{
  "success": true,
  "payment_url": "https://skipcash.app/pay/xxx",
  "transaction_id": "EVENT_1_1234567890"
}
```

### Generate Payment Link for Subscription

**Endpoint:** `POST /payment/skipcash/subscription`

**Authentication:** Required

**Request Body:**
```json
{
  "user_id": 1,
  "plan_type": "monthly",
  "price": 100.00
}
```

**Response:**
```json
{
  "success": true,
  "payment_url": "https://skipcash.app/pay/xxx",
  "transaction_id": "SUBSCRIPTION_monthly_1234567890"
}
```

### Payment Return Callback

**Endpoint:** `GET /payment/skipcash/return?id={payment_id}`

**Authentication:** Not required

This endpoint is called by SkipCash after payment completion.

**Response:**
```json
{
  "success": true,
  "message": "Payment verified successfully",
  "transaction_id": "COURSE_1_1234567890"
}
```

### Webhook Handler

**Endpoint:** `POST /payment/skipcash/webhook`

**Authentication:** Not required

This endpoint receives webhook notifications from SkipCash.

**Response:**
```json
{
  "message": "Success"
}
```

## Usage Examples

### Course Enrollment with SkipCash

```php
// 1. Create enrollment with SkipCash payment method
POST /courses/{course}/enroll
{
  "user_id": 1,
  "payment_method": "skipcash"
}

// Response will include payment URL
{
  "message": "Enrollment successful",
  "enrollment": {...},
  "payment_required": true,
  "payment_method": "skipcash",
  "payment_url": "/payment/skipcash/course/1"
}

// 2. Generate payment link
POST /payment/skipcash/course/1
{
  "user_id": 1
}

// Response includes SkipCash payment URL
{
  "success": true,
  "payment_url": "https://skipcash.app/pay/xxx",
  "transaction_id": "COURSE_1_1234567890"
}

// 3. Redirect user to payment_url
// 4. User completes payment on SkipCash
// 5. SkipCash redirects to /payment/skipcash/return?id={payment_id}
// 6. System verifies payment and updates enrollment status
```

### Event Registration with SkipCash

```php
// 1. Create registration with SkipCash payment method
POST /events/{event}/register
{
  "user_id": 1,
  "payment_method": "skipcash"
}

// Response will include payment URL
{
  "message": "Registration successful",
  "registration": {...},
  "payment_required": true,
  "payment_method": "skipcash",
  "payment_url": "/payment/skipcash/event/1"
}

// 2. Generate payment link and proceed as above
```

## SkipCash Configuration in Portal

### Return URL
Set this URL in your SkipCash portal:
```
https://yourdomain.com/payment/skipcash/return
```

### Webhook URL
Set this URL in your SkipCash portal:
```
https://yourdomain.com/payment/skipcash/webhook
```

## Payment Flow

1. User initiates payment (course enrollment, event registration, or subscription)
2. System generates unique transaction ID
3. System calls SkipCash API to generate payment link
4. User is redirected to SkipCash payment page
5. User completes payment on SkipCash
6. SkipCash redirects to return URL with payment ID
7. System validates payment with SkipCash API
8. If payment successful (statusId === 2), system updates payment status
9. SkipCash sends webhook notification (optional, for redundancy)

## Transaction ID Format

Transaction IDs follow these formats:
- Course: `COURSE_{course_id}_{timestamp}`
- Event: `EVENT_{event_id}_{timestamp}`
- Subscription: `SUBSCRIPTION_{plan_type}_{timestamp}`

## Logging

All SkipCash interactions are logged in the `skipcash_logs` table:
- Payment link generation
- Payment return callbacks
- Payment validation
- Webhook notifications

## Testing

### Test Card Details
According to SkipCash documentation:
- Card Number: 4001919257537193
- Cardholder: John Smith
- Expiry: 12/2027
- CVV: 123

### Test Environment
Use the test URL for development:
```env
SKIPCASH_URL=https://skipcashtest.azurewebsites.net
```

## Security Considerations

1. **HMAC Signature**: All payment requests are signed using HMAC-SHA256
2. **Payment Verification**: Always verify payment status via API before marking as paid
3. **Webhook Validation**: Implement webhook signature verification using SKIPCASH_WEBHOOK_KEY
4. **Transaction ID Uniqueness**: Ensure transaction IDs are unique to prevent duplicates

## Troubleshooting

### Payment Link Generation Fails
- Check that all SkipCash credentials are correctly set in `.env`
- Verify that the SkipCash API URL is accessible
- Check the `skipcash_logs` table for error details

### Payment Not Marked as Paid
- Verify that the return URL is correctly configured in SkipCash portal
- Check that the payment status is being correctly parsed (statusId === 2)
- Review logs in `skipcash_logs` table

### Webhook Not Receiving Data
- Ensure webhook URL is correctly configured in SkipCash portal
- Verify that the endpoint is publicly accessible
- Check server logs for webhook requests

## Support

For SkipCash-specific issues, refer to:
- Documentation: https://skipcash.app/assets/doc/SkipCashIntegrationManual.pdf
- Package: https://github.com/shahzadthathal/skipcash

For application-specific issues, contact the development team.
