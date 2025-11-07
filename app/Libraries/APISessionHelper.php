<?php

namespace App\Libraries;

class APISessionHelper
{
    /**
     * Generate simple session token for mobile app
     * Format: base64(user_id:timestamp:random_string)
     */
    public static function generateSessionToken($userId)
    {
        $timestamp = time();
        $randomString = bin2hex(random_bytes(8)); // 16 character random string
        $sessionData = $userId . ':' . $timestamp . ':' . $randomString;
        return base64_encode($sessionData);
    }

    /**
     * Extract user ID from session token
     */
    public static function extractUserIdFromSessionToken($token)
    {
        try {
            if (empty($token)) {
                return null;
            }

            // Remove "Bearer " prefix if present
            $token = str_replace('Bearer ', '', $token);

            $decoded = base64_decode($token);
            if ($decoded && strpos($decoded, ':') !== false) {
                $parts = explode(':', $decoded);
                if (count($parts) >= 3) {
                    $userId = (int)$parts[0];
                    $timestamp = (int)$parts[1];
                    $randomString = $parts[2];

                    // Validate session is not too old (7 days)
                    if (abs(time() - $timestamp) < 7 * 24 * 3600) {
                        return $userId;
                    }
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Session token extraction error: ' . $e->getMessage());
            return null;
        }

        return null;
    }

    /**
     * Validate session from request
     */
    public static function validateSession($request)
    {
        $token = $request->getHeaderLine('Authorization');
        if (empty($token)) {
            // Also check if token is in POST data for mobile compatibility
            $token = $request->getPost('session_token');
        }

        if (empty($token)) {
            return null;
        }

        return self::extractUserIdFromSessionToken($token);
    }

    /**
     * Get session info from token
     */
    public static function getSessionInfo($token)
    {
        try {
            $token = str_replace('Bearer ', '', $token);
            $decoded = base64_decode($token);
            if ($decoded && strpos($decoded, ':') !== false) {
                $parts = explode(':', $decoded);
                if (count($parts) >= 3) {
                    return [
                        'user_id' => (int)$parts[0],
                        'timestamp' => (int)$parts[1],
                        'random_string' => $parts[2],
                        'created_at' => date('Y-m-d H:i:s', $parts[1]),
                        'is_expired' => abs(time() - $parts[1]) >= 7 * 24 * 3600
                    ];
                }
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }
}