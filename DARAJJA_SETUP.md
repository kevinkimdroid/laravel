# Safaricom Daraja API Integration Setup

This application is integrated with Safaricom M-Pesa Daraja API for STK Push payments.

## Setup Instructions

### 1. Get Daraja API Credentials

1. Register at [Safaricom Developer Portal](https://developer.safaricom.co.ke/)
2. Create an app to get your credentials:
   - Consumer Key
   - Consumer Secret
   - Short Code (PayBill or Till Number)
   - Passkey

### 2. Configure Environment Variables

Add the following to your `.env` file:

```env
# Daraja API Configuration
DARAJJA_ENVIRONMENT=sandbox
DARAJJA_CONSUMER_KEY=your_consumer_key_here
DARAJJA_CONSUMER_SECRET=your_consumer_secret_here
DARAJJA_SHORT_CODE=your_short_code_here
DARAJJA_PASSKEY=your_passkey_here
DARAJJA_CALLBACK_URL=https://yourdomain.com/api/mpesa/callback
```

**Note:** For production, change `DARAJJA_ENVIRONMENT=production`

### 3. Configure Callback URL

The callback URL must be publicly accessible. For local development, you can use:
- [ngrok](https://ngrok.com/) to expose your local server
- Or use a staging/production server

Update `DARAJJA_CALLBACK_URL` in your `.env` file with your public URL.

### 4. Test the Integration

1. Use sandbox credentials for testing
2. Test with Safaricom test phone numbers (provided in Daraja dashboard)
3. Once working, switch to production credentials

## How It Works

1. Member clicks "Pay Now" and enters payment details
2. System initiates STK Push via Daraja API
3. Member receives M-Pesa prompt on their phone
4. Member enters M-Pesa PIN
5. Safaricom sends callback to `/api/mpesa/callback`
6. System automatically verifies and records the payment
7. Contribution is created automatically

## Important Notes

- The callback URL must be HTTPS in production
- Ensure your server can receive POST requests from Safaricom
- Check logs in `storage/logs/laravel.log` for debugging
- Payment requests are automatically approved when callback confirms payment

