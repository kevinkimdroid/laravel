<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use App\Models\Contribution;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MpesaCallbackController extends Controller
{
    /**
     * Handle M-Pesa STK Push callback
     */
    public function stkCallback(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('M-Pesa STK Callback received', $data);

            // Safaricom sends data in Body->stkCallback format (sandbox) or direct format (production)
            $stkCallback = $data['Body']['stkCallback'] ?? $data['stkCallback'] ?? $data;
            
            if (!$stkCallback || (!isset($stkCallback['MerchantRequestID']) && !isset($stkCallback['CheckoutRequestID']))) {
                Log::error('M-Pesa Callback: Invalid format', $data);
                return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Invalid callback format'], 400);
            }

            $merchantRequestId = $stkCallback['MerchantRequestID'] ?? null;
            $checkoutRequestId = $stkCallback['CheckoutRequestID'] ?? null;
            $resultCode = $stkCallback['ResultCode'] ?? null;
            $resultDesc = $stkCallback['ResultDesc'] ?? null;

            // Find payment request
            $paymentRequest = PaymentRequest::where('checkout_request_id', $checkoutRequestId)
                ->orWhere('merchant_request_id', $merchantRequestId)
                ->first();

            if (!$paymentRequest) {
                Log::error('M-Pesa Callback: Payment request not found', [
                    'checkout_request_id' => $checkoutRequestId,
                    'merchant_request_id' => $merchantRequestId
                ]);
                return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Payment request not found'], 404);
            }

            // Update payment request with callback data
            $updateData = [
                'result_code' => $resultCode,
                'result_desc' => $resultDesc,
                'callback_data' => json_encode($data),
            ];

            // If payment was successful (ResultCode = 0)
            if ($resultCode == 0) {
                $callbackMetadata = $stkCallback['CallbackMetadata']['Item'] ?? [];
                
                $metadata = [];
                foreach ($callbackMetadata as $item) {
                    $metadata[$item['Name']] = $item['Value'] ?? null;
                }

                $mpesaReceiptNumber = $metadata['MpesaReceiptNumber'] ?? null;
                $transactionDate = isset($metadata['TransactionDate']) 
                    ? date('Y-m-d H:i:s', strtotime($metadata['TransactionDate'])) 
                    : null;
                $amountPaid = $metadata['Amount'] ?? $paymentRequest->amount;
                $phoneNumber = $metadata['PhoneNumber'] ?? null;

                $updateData['status'] = 'approved';
                $updateData['mpesa_receipt_number'] = $mpesaReceiptNumber;
                $updateData['mpesa_code'] = $mpesaReceiptNumber; // Use receipt number as mpesa_code
                $updateData['transaction_date'] = $transactionDate;
                $updateData['amount_paid'] = $amountPaid;
                $updateData['phone_number'] = $phoneNumber;
                $updateData['approved_at'] = now();

                // Automatically create contribution and transaction
                DB::beginTransaction();
                try {
                    // Create contribution
                    $ref = 'MPESA-' . $mpesaReceiptNumber . '-' . now()->format('YmdHis');
                    
                    Contribution::create([
                        'member_id' => $paymentRequest->member_id,
                        'amount' => $amountPaid,
                        'type' => $paymentRequest->type,
                        'contribution_date' => $paymentRequest->payment_date,
                        'transaction_ref' => $ref,
                    ]);

                    // Record transaction
                    $account = Account::firstOrCreate(
                        ['slug' => 'contributions'],
                        ['name' => 'Contributions Account', 'balance' => 0]
                    );

                    Transaction::create([
                        'account_id' => $account->id,
                        'income' => $amountPaid,
                        'expense' => 0,
                        'transaction_date' => $paymentRequest->payment_date,
                    ]);

                    $account->increment('balance', $amountPaid);

                    DB::commit();

                    Log::info('M-Pesa Callback: Payment approved and contribution created', [
                        'payment_request_id' => $paymentRequest->id,
                        'mpesa_receipt_number' => $mpesaReceiptNumber
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('M-Pesa Callback: Failed to create contribution', [
                        'payment_request_id' => $paymentRequest->id,
                        'error' => $e->getMessage()
                    ]);
                }
            } else {
                // Payment failed or was cancelled
                $updateData['status'] = 'rejected';
                Log::info('M-Pesa Callback: Payment failed or cancelled', [
                    'payment_request_id' => $paymentRequest->id,
                    'result_code' => $resultCode,
                    'result_desc' => $resultDesc
                ]);
            }

            $paymentRequest->update($updateData);

            return response()->json([
                'ResultCode' => 0,
                'ResultDesc' => 'Callback processed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('M-Pesa Callback: Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'ResultCode' => 1,
                'ResultDesc' => 'Error processing callback'
            ], 500);
        }
    }
}

