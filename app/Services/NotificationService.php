<?php


namespace App\Services;

use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification; // Alias
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Laravel\Firebase\Facades\Firebase; // Use the Facade
use Illuminate\Support\Facades\Log;
use App\Events\MessageSent;

class NotificationService
{
    protected $messaging;

    public function __construct()
    {
        $this->messaging = Firebase::messaging();
    }

    public function sendNotificationToUser(User $user, string $title, string $body, ?array $data = [])
    {
        return true;
        // event(new MessageSent($user->id, $data ));

        // $tokens = $user->fcmTokens()->whereNotNull('token')->pluck('token')->filter()->unique()->toArray();

        // if (empty($tokens)) {
        //     Log::info("No FCM tokens found for user ID: {$user->id}");
        //     return false;
        // }

        // // Basic notification payload (for system tray when app is in background)
        // $notificationPayload = FirebaseNotification::create($title, $body);

        // // Construct the message
        // $message = CloudMessage::new()
        //     ->withNotification($notificationPayload) // For background display & foreground `notification` object
        //     ->withData($data ?: []); // Custom key-value pairs for in-app handling

        // // Optional: Platform-specific configurations
        // // $message = $message->withAndroidConfig(AndroidConfig::new()->...);
        // // $message = $message->withApnsConfig(ApnsConfig::new()->...);

        // try {
        //     if (count($tokens) === 1) {
        //         $report = $this->messaging->send(CloudMessage::withTarget('token', $tokens[0])
        //             ->withNotification($notificationPayload)
        //             ->withData($data ?: [])
        //         );
        //         Log::info('Single FCM message sent.', ['report' => $report, 'user_id' => $user->id, 'token' => $tokens[0]]);
        //     } else {
        //         $report = $this->messaging->sendMulticast($message, $tokens);
        //         Log::info('Multicast FCM message report.', [
        //             'user_id' => $user->id,
        //             'successful_sends' => $report->successes()->count(),
        //             'failed_sends' => $report->failures()->count()
        //         ]);
        //     }

        //     // Handle invalid tokens based on the report
        //     // if ($report->hasFailures()) {
        //     //     $this->handleFailedTokens($report, $user);
        //     // }
        //     return true;

        // } catch (\Kreait\Firebase\Exception\MessagingException $e) {
        //     Log::error('FCM Sending Error (MessagingException): ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        // } catch (\Exception $e) {
        //     Log::error('Generic FCM Sending Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        // }
        // return false;
    }

    protected function handleFailedTokens($report, User $user)
    {
        $failedTokens = [];
        foreach ($report->getItems() as $item) {
            if (!$item->isSuccess() && $item->target() && $item->target()->type() === 'token') {
                $tokenValue = $item->target()->value();
                // You can get more error details from $item->error()
                Log::warning('FCM send failed for token: ' . $tokenValue, [
                    'error' => $item->error() ? $item->error()->getMessage() : 'Unknown error',
                    'status_code' => $item->error() && method_exists($item->error(), 'getCode') ? $item->error()->getCode() : null,
                ]);

                // Common error codes indicating an invalid/unregistered token
                $errorReason = $item->error() ? strtoupper(optional($item->error()->getReason())->_toString()) : null;
                if (in_array($errorReason, ['UNREGISTERED', 'INVALID_ARGUMENT', 'NOT_FOUND'])) {
                     $failedTokens[] = $tokenValue;
                }
            }
        }

        if (!empty($failedTokens)) {
            Log::info('Deleting invalid FCM tokens: ', $failedTokens);
            $user->fcmTokens()->whereIn('token', $failedTokens)->delete();
        }
    }
}
