<?php

namespace App\Helpers;

class JwtHelper
{
    /**
     * Generate a JWT token
     * 
     * @param array $payload The data to encode in the token
     * @param string $secret The secret key for signing
     * @param int $expiration Expiration time in seconds (default: 24 hours)
     * @return string The JWT token
     */
    public static function encode(array $payload, string $secret, int $expiration = 86400): string
    {
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        $now = time();
        $payload['iat'] = $now;
        $payload['exp'] = $now + $expiration;

        $headerEncoded = self::base64UrlEncode(json_encode($header));
        $payloadEncoded = self::base64UrlEncode(json_encode($payload));

        $signature = hash_hmac('sha256', $headerEncoded . '.' . $payloadEncoded, $secret, true);
        $signatureEncoded = self::base64UrlEncode($signature);

        return $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
    }

    /**
     * Decode and verify a JWT token
     * 
     * @param string $token The JWT token
     * @param string $secret The secret key for verification
     * @return array|false The decoded payload or false if invalid
     */
    public static function decode(string $token, string $secret)
    {
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            return false;
        }

        list($headerEncoded, $payloadEncoded, $signatureEncoded) = $parts;

        // Verify signature
        $signature = self::base64UrlDecode($signatureEncoded);
        $expectedSignature = hash_hmac('sha256', $headerEncoded . '.' . $payloadEncoded, $secret, true);

        if (!hash_equals($signature, $expectedSignature)) {
            return false;
        }

        $payload = json_decode(self::base64UrlDecode($payloadEncoded), true);

        // Check expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false;
        }

        return $payload;
    }

    /**
     * Base64 URL encode
     */
    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Base64 URL decode
     */
    private static function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}

