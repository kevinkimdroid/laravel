# Troubleshooting Daraja API Authentication Issues

## Error: "Failed to authenticate with M-Pesa API"

This error occurs when the system cannot obtain an OAuth access token from Safaricom's Daraja API.

### Step 1: Check Your .env File

Ensure all required credentials are set in your `.env` file:

```env
DARAJJA_ENVIRONMENT=sandbox
DARAJJA_CONSUMER_KEY=your_consumer_key_here
DARAJJA_CONSUMER_SECRET=your_consumer_secret_here
DARAJJA_SHORT_CODE=your_short_code_here
DARAJJA_PASSKEY=your_passkey_here
DARAJJA_CALLBACK_URL=https://yourdomain.com/api/mpesa/callback
```

**Important:** 
- Remove any quotes around the values
- No spaces before or after the `=` sign
- Make sure there are no typos in the variable names

### Step 2: Clear Configuration Cache

After updating `.env`, run:

```bash
php artisan config:clear
php artisan cache:clear
```

### Step 3: Use the Diagnostic Tool

1. Log in as an admin
2. Go to: `/admin/daraja/test`
3. Review the test results and follow the recommendations

### Step 4: Verify Your Credentials

1. Go to [Safaricom Developer Portal](https://developer.safaricom.co.ke/)
2. Log in to your account
3. Check your app credentials:
   - **Consumer Key** - Should start with letters/numbers
   - **Consumer Secret** - Long alphanumeric string
   - **Short Code** - Your PayBill or Till number
   - **Passkey** - Long string provided by Safaricom

### Step 5: Check Environment Settings

**For Sandbox (Testing):**
- Use sandbox credentials from the developer portal
- Set `DARAJJA_ENVIRONMENT=sandbox`
- Base URL: `https://sandbox.safaricom.co.ke`

**For Production:**
- Use production credentials (requires approval from Safaricom)
- Set `DARAJJA_ENVIRONMENT=production`
- Base URL: `https://api.safaricom.co.ke`

### Step 6: Check Logs

Review the application logs for detailed error messages:

```bash
tail -f storage/logs/laravel.log
```

Look for entries containing "Daraja" to see the exact error.

### Common Issues and Solutions

#### Issue 1: "Invalid credentials"
- **Solution:** Double-check your Consumer Key and Consumer Secret
- Make sure you're using the correct credentials for your environment (sandbox vs production)

#### Issue 2: "Network timeout"
- **Solution:** Check your server's internet connection
- Ensure your firewall allows outbound HTTPS requests to `sandbox.safaricom.co.ke` or `api.safaricom.co.ke`

#### Issue 3: "Missing credentials"
- **Solution:** Verify all environment variables are set in `.env`
- Run `php artisan config:clear` after updating `.env`

#### Issue 4: "Wrong environment"
- **Solution:** Ensure `DARAJJA_ENVIRONMENT` matches your credentials
- Sandbox credentials only work with `sandbox` environment
- Production credentials only work with `production` environment

### Step 7: Test with cURL (Advanced)

If the diagnostic tool doesn't help, test the API directly:

```bash
curl -X GET "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials" \
  -H "Authorization: Basic BASE64_ENCODED_CREDENTIALS"
```

Replace `BASE64_ENCODED_CREDENTIALS` with:
```bash
echo -n "YOUR_CONSUMER_KEY:YOUR_CONSUMER_SECRET" | base64
```

### Still Having Issues?

1. Check the [Safaricom Daraja API Documentation](https://developer.safaricom.co.ke/docs)
2. Contact Safaricom Developer Support
3. Review the application logs for specific error messages
4. Ensure your server has SSL/TLS properly configured (for production)

### Quick Checklist

- [ ] All credentials are set in `.env`
- [ ] Ran `php artisan config:clear`
- [ ] Credentials match the environment (sandbox/production)
- [ ] Server can make outbound HTTPS requests
- [ ] Checked logs for specific errors
- [ ] Used the diagnostic tool at `/admin/daraja/test`

