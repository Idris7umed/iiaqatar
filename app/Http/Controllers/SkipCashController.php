<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\SkipcashLog;
use App\Models\Subscription;
use App\Traits\SkipCashPaymentTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SkipCashController extends Controller
{
    use SkipCashPaymentTrait;

    /**
     * Generate payment link for course enrollment
     */
    public function generateCoursePaymentLink(Request $request, Course $course)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);

        // Generate unique transaction ID
        $transactionId = 'COURSE_'.$course->id.'_'.time();

        $requestData = [
            'Uid' => Str::uuid()->toString(),
            'KeyId' => config('skipcash.key_id'),
            'Amount' => strval($course->discount_price ?? $course->price),
            'FirstName' => $user->name,
            'LastName' => '',
            'Phone' => $user->phone ?? '',
            'Email' => $user->email,
            'TransactionId' => $transactionId,
            'Custom1' => 'course_id:'.$course->id,
            'Custom2' => 'user_id:'.$user->id,
        ];

        $responseSkipcashResponse = $this->generatePaymentLinkSkipcash($requestData);

        // Save logs
        SkipcashLog::create([
            'user_id' => $user->id,
            'logs' => 'Course Payment Link Generated: '.$responseSkipcashResponse,
        ]);

        $responseSkipcashArr = json_decode($responseSkipcashResponse, true);

        if (isset($responseSkipcashArr['resultObj']['payUrl'])) {
            return response()->json([
                'success' => true,
                'payment_url' => $responseSkipcashArr['resultObj']['payUrl'],
                'transaction_id' => $transactionId,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to generate payment link',
            'error' => $responseSkipcashArr,
        ], 400);
    }

    /**
     * Generate payment link for event registration
     */
    public function generateEventPaymentLink(Request $request, Event $event)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);

        // Generate unique transaction ID
        $transactionId = 'EVENT_'.$event->id.'_'.time();

        $requestData = [
            'Uid' => Str::uuid()->toString(),
            'KeyId' => config('skipcash.key_id'),
            'Amount' => strval($event->price),
            'FirstName' => $user->name,
            'LastName' => '',
            'Phone' => $user->phone ?? '',
            'Email' => $user->email,
            'TransactionId' => $transactionId,
            'Custom1' => 'event_id:'.$event->id,
            'Custom2' => 'user_id:'.$user->id,
        ];

        $responseSkipcashResponse = $this->generatePaymentLinkSkipcash($requestData);

        // Save logs
        SkipcashLog::create([
            'user_id' => $user->id,
            'logs' => 'Event Payment Link Generated: '.$responseSkipcashResponse,
        ]);

        $responseSkipcashArr = json_decode($responseSkipcashResponse, true);

        if (isset($responseSkipcashArr['resultObj']['payUrl'])) {
            return response()->json([
                'success' => true,
                'payment_url' => $responseSkipcashArr['resultObj']['payUrl'],
                'transaction_id' => $transactionId,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to generate payment link',
            'error' => $responseSkipcashArr,
        ], 400);
    }

    /**
     * Generate payment link for subscription
     */
    public function generateSubscriptionPaymentLink(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_type' => 'required|in:monthly,quarterly,yearly',
            'price' => 'required|numeric|min:0',
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);

        // Generate unique transaction ID
        $transactionId = 'SUBSCRIPTION_'.$request->plan_type.'_'.time();

        $requestData = [
            'Uid' => Str::uuid()->toString(),
            'KeyId' => config('skipcash.key_id'),
            'Amount' => strval($request->price),
            'FirstName' => $user->name,
            'LastName' => '',
            'Phone' => $user->phone ?? '',
            'Email' => $user->email,
            'TransactionId' => $transactionId,
            'Custom1' => 'subscription:'.$request->plan_type,
            'Custom2' => 'user_id:'.$user->id,
        ];

        $responseSkipcashResponse = $this->generatePaymentLinkSkipcash($requestData);

        // Save logs
        SkipcashLog::create([
            'user_id' => $user->id,
            'logs' => 'Subscription Payment Link Generated: '.$responseSkipcashResponse,
        ]);

        $responseSkipcashArr = json_decode($responseSkipcashResponse, true);

        if (isset($responseSkipcashArr['resultObj']['payUrl'])) {
            return response()->json([
                'success' => true,
                'payment_url' => $responseSkipcashArr['resultObj']['payUrl'],
                'transaction_id' => $transactionId,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to generate payment link',
            'error' => $responseSkipcashArr,
        ], 400);
    }

    /**
     * Handle payment return/callback
     */
    public function handlePaymentReturn(Request $request)
    {
        $payment_id = $request->get('id');

        if (! $payment_id) {
            return response()->json([
                'success' => false,
                'message' => 'Payment ID not provided',
            ], 400);
        }

        // Log the return
        SkipcashLog::create([
            'user_id' => auth()->id() ?? 0,
            'logs' => 'Payment Return: '.url()->full(),
        ]);

        // Validate payment
        $responseSkipcash = $this->validatePaymentSkipcash($payment_id);

        // Log validation response
        SkipcashLog::create([
            'user_id' => auth()->id() ?? 0,
            'logs' => 'Payment Validation: '.$responseSkipcash,
        ]);

        $responseSkipcashArr = json_decode($responseSkipcash, true);

        if (! isset($responseSkipcashArr['resultObj'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment response',
            ], 400);
        }

        $resultObj = $responseSkipcashArr['resultObj'];

        // Payment success (statusId === 2)
        if (isset($resultObj['statusId']) && $resultObj['statusId'] === 2) {
            $transactionId = $resultObj['transactionId'];

            // Update payment status based on transaction type
            $this->updatePaymentStatus($transactionId, $resultObj);

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully',
                'transaction_id' => $transactionId,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Payment verification failed',
        ], 400);
    }

    /**
     * Handle webhook from SkipCash
     */
    public function handleWebhook(Request $request)
    {
        try {
            $data = $request->all();

            // Log webhook data
            SkipcashLog::create([
                'user_id' => 0,
                'logs' => 'Webhook Received: '.json_encode($data),
            ]);

            // Process webhook data if needed
            if (isset($data['transactionId'])) {
                $this->updatePaymentStatus($data['transactionId'], $data);
            }

            return response()->json(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update payment status based on transaction type
     */
    private function updatePaymentStatus($transactionId, $paymentData)
    {
        // Parse transaction ID to determine type
        if (strpos($transactionId, 'COURSE_') === 0) {
            // Extract course_id from transaction ID
            preg_match('/COURSE_(\d+)_/', $transactionId, $matches);
            if (isset($matches[1])) {
                $courseId = $matches[1];
                $userId = $this->extractUserId($paymentData);

                if ($userId) {
                    Enrollment::where('course_id', $courseId)
                        ->where('user_id', $userId)
                        ->update([
                            'payment_status' => 'paid',
                            'payment_method' => 'skipcash',
                            'payment_id' => $paymentData['id'] ?? null,
                        ]);
                }
            }
        } elseif (strpos($transactionId, 'EVENT_') === 0) {
            // Extract event_id from transaction ID
            preg_match('/EVENT_(\d+)_/', $transactionId, $matches);
            if (isset($matches[1])) {
                $eventId = $matches[1];
                $userId = $this->extractUserId($paymentData);

                if ($userId) {
                    EventRegistration::where('event_id', $eventId)
                        ->where('user_id', $userId)
                        ->update([
                            'payment_status' => 'paid',
                            'payment_method' => 'skipcash',
                            'payment_id' => $paymentData['id'] ?? null,
                        ]);
                }
            }
        } elseif (strpos($transactionId, 'SUBSCRIPTION_') === 0) {
            $userId = $this->extractUserId($paymentData);

            if ($userId) {
                // Find the most recent pending subscription
                $subscription = Subscription::where('user_id', $userId)
                    ->where('status', 'pending')
                    ->latest()
                    ->first();

                if ($subscription) {
                    $subscription->update([
                        'status' => 'active',
                        'payment_method' => 'skipcash',
                    ]);
                }
            }
        }
    }

    /**
     * Extract user ID from payment data
     */
    private function extractUserId($paymentData)
    {
        // Try to extract from Custom2 field (check both uppercase and lowercase)
        $custom2Value = $paymentData['custom2'] ?? $paymentData['Custom2'] ?? null;
        
        if ($custom2Value) {
            preg_match('/user_id:(\d+)/', $custom2Value, $matches);
            if (isset($matches[1])) {
                return $matches[1];
            }
        }

        return null;
    }
}
