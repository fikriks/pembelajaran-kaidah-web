<?php

namespace App\Libraries;

class APIHelper
{
    /**
     * Extract user ID from Base64 encoded token
     * Token format: user_id:timestamp
     */
    public static function extractUserIdFromToken($token)
    {
        try {
            if (empty($token)) {
                return null;
            }

            // Remove "Bearer " prefix if present
            $token = str_replace('Bearer ', '', $token);

            $decoded = base64_decode($token);
            if ($decoded && strpos($decoded, ':') !== false) {
                list($userId, $timestamp) = explode(':', $decoded);

                // Validate that user_id is a positive integer
                $userId = (int)$userId;
                if ($userId <= 0) {
                    return null;
                }

                // Check if token is not too old (24 hours)
                $currentTime = time();
                $tokenTime = (int)$timestamp;

                if (abs($currentTime - $tokenTime) < 86400) {
                    return $userId;
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Token extraction error: ' . $e->getMessage());
            return null;
        }

        return null;
    }

    /**
     * Validate authorization header and extract user ID
     */
    public static function validateAuthHeader($request)
    {
        $token = $request->getHeaderLine('Authorization');
        if (empty($token)) {
            return null;
        }

        return self::extractUserIdFromToken($token);
    }

    /**
     * Generate API response format
     */
    public static function formatResponse($status, $message, $data = null, $httpCode = 200)
    {
        $response = [
            'status' => $status,
            'message' => $message
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return [
            'data' => $response,
            'httpCode' => $httpCode
        ];
    }

    /**
     * Format timestamp for API response
     */
    public static function formatTimestamp($timestamp)
    {
        if (empty($timestamp)) {
            return null;
        }

        return date('Y-m-d H:i:s', strtotime($timestamp));
    }
}