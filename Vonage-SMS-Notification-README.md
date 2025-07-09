# Vonage SMS Notification Integration for Appointment Booking

This guide explains how to integrate Vonage (formerly Nexmo) SMS notifications into your PHP-based appointment booking system. Users will receive an SMS confirmation when they book an appointment.

---

## Prerequisites
- PHP 7.x or higher
- Composer (for dependency management)
- A Vonage account ([Sign up here](https://dashboard.nexmo.com/sign-up))
- Access to your project source code and database

---

## Step-by-Step Setup

### 1. Register and Get Vonage Credentials
- Sign up at [Vonage](https://dashboard.nexmo.com/sign-up).
- In the Vonage dashboard, obtain your **API Key** and **API Secret**.
- (Optional) Buy a Vonage virtual number if you want to use a custom sender.

### 2. Store Credentials Securely
- Add your Vonage API Key, API Secret, and (optionally) virtual number to a secure config file or environment variables.
- **Do not commit sensitive credentials to version control.**

### 3. Ensure User Phone Numbers Are Available
- Confirm your `user` or `users` table has a valid phone number column (e.g., `contact_num`).
- Update registration/profile forms to collect and validate phone numbers if needed.

### 4. Install Vonage PHP SDK
Run this command in your project root:
```bash
composer require vonage/client
```

### 5. Fetch User Phone Number on Appointment Booking
- In your appointment booking logic (e.g., `public/admin/set_appointment.php`), fetch the user's phone number from the database after booking.

### 6. Integrate Vonage SMS Sending Logic
Add the following code after booking the appointment:
```php
require_once '../../vendor/autoload.php';
require_once '../../config/config.php';
use Vonage\Client;
use Vonage\Client\Credentials\Basic;

$basic  = new Basic(VONAGE_API_KEY, VONAGE_API_SECRET);
$client = new Client($basic);

$response = $client->sms()->send(
    new \Vonage\SMS\Message\SMS($userPhoneNumber, VONAGE_VIRTUAL_NUMBER, "Your appointment is set for $preferred_date at $time_slot. Complaint: $complaint.")
);
```
- Replace `VONAGE_API_KEY`, `VONAGE_API_SECRET`, `VONAGE_VIRTUAL_NUMBER`, and variables as needed.

### 7. Add Error Handling
- Handle and log any errors from the Vonage SDK response.

### 8. Test the Integration
- Book an appointment as a user.
- Confirm the SMS is received with the correct details.
- Check the Vonage dashboard and your application logs for errors.

---

## Troubleshooting
- Ensure your API Key, API Secret, and (if used) virtual number are correct.
- Make sure your sender ID is approved for your region.
- Check phone number formatting (E.164, e.g., +639XXXXXXXXX).
- Review Vonage API documentation for more details: [Vonage SMS API Docs](https://developer.vonage.com/messaging/sms/overview)

---

## Support
For further help, visit the [Vonage Support Center](https://help.nexmo.com/) or consult their documentation. 