<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // For logging

class FcmTokenController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'device_name' => 'nullable|string|max:255',
            'platform' => 'nullable|string|max:50', // e.g., 'android', 'ios'
        ]);

        try {
            $user = Auth::user(); // Assumes user is authenticated

            if (!$user) {
                return response()->json(['message' => 'User not authenticated.'], 401);
            }

            $user->fcmTokens()->updateOrCreate(
                ['token' => $request->token], // Find by token to avoid duplicates for the same user
                [
                    'device_name' => $request->device_name,
                    'platform' => $request->platform ?? $this->deducePlatformFromUserAgent($request)
                ]
            );

            return response()->json(['message' => 'FCM token registered successfully.']);

        } catch (\Exception $e) {
            Log::error('FCM Token Registration Failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'token' => $request->token,
                'exception' => $e
            ]);
            return response()->json(['message' => 'Failed to register FCM token.', 'error' => $e->getMessage()], 500);
        }
    }

    private function deducePlatformFromUserAgent(Request $request)
    {
        $userAgent = strtolower($request->userAgent() ?? '');
        if (str_contains($userAgent, 'android')) {
            return 'android';
        }
        // Add more platform checks if needed (e.g., 'ios')
        return 'unknown';
    }

    public function unregister(Request $request)
    {
        $request->validate(['token' => 'required|string']);
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        $deleted = $user->fcmTokens()->where('token', $request->token)->delete();

        if ($deleted) {
            return response()->json(['message' => 'FCM token unregistered successfully.']);
        }
        return response()->json(['message' => 'FCM token not found or already unregistered.'], 404);
    }

    
}