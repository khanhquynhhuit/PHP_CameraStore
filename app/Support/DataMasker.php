<?php

namespace App\Support;

class DataMasker
{
    /**
     * Replacement mask string.
     */
    public const MASK = '******';

    /**
     * Default list of exact sensitive keys.
     */
    protected static array $defaultSensitiveKeys = [
        'password',
        'password_confirmation',
        'current_password',
        'new_password',
        'old_password',
        'pwd',
        'passwd',
        'secret',
        'app_secret',
        'client_secret',
        'token',
        'access_token',
        'refresh_token',
        'api_token',
        'api_key',
        'apikey',
        'auth_token',
        'authorization',
        'auth',
        'signature',
        'private_key',
        'public_key',
        'salt',
        'card',
        'card_number',
        'card_no',
        'credit_card',
        'debit_card',
        'cvv',
        'cvc',
        'pin',
        'ssn',
        'otp',
        'bank_account',
        'account_number',
        'id_card',
        'cccd',
        'cmnd',
        'passport',
        'tax_id',
        'cookie',
        'session_id',
        'remember_token',
        'xsrf_token',
        '_token',
    ];

    /**
     * Regex pattern to detect sensitive key names dynamically.
     */
    protected static string $sensitiveKeyPattern = '/(password|secret|token|api_?key|auth|card_?number|card_?no|credit|debit|cvv|cvc|pin|ssn|otp|private_?key|salt|credential|passcode)/i';

    /**
     * Mask an entire payload (array, object, JSON string, or scalar).
     *
     * @param mixed $data
     * @param array $customSensitiveKeys
     * @return mixed
     */
    public static function mask(mixed $data, array $customSensitiveKeys = []): mixed
    {
        if (is_null($data)) {
            return null;
        }

        if (is_string($data)) {
            // Check if string is a JSON object/array
            $decoded = json_decode($data, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $masked = self::maskArray($decoded, $customSensitiveKeys);
                return json_encode($masked, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            return self::maskStringValue($data);
        }

        if (is_array($data)) {
            return self::maskArray($data, $customSensitiveKeys);
        }

        if (is_object($data)) {
            $array = json_decode(json_encode($data), true);
            return self::maskArray($array, $customSensitiveKeys);
        }

        return $data;
    }

    /**
     * Mask an associative or sequential array recursively.
     *
     * @param array $data
     * @param array $customSensitiveKeys
     * @return array
     */
    public static function maskArray(array $data, array $customSensitiveKeys = []): array
    {
        $sensitiveKeys = array_map('strtolower', array_merge(
            self::$defaultSensitiveKeys,
            config('audit.sensitive_keys', []),
            $customSensitiveKeys
        ));

        $result = [];

        foreach ($data as $key => $value) {
            $keyStr = (string) $key;
            $lowerKey = strtolower($keyStr);

            // 1. Check if key matches sensitive exact list or regex pattern
            if (in_array($lowerKey, $sensitiveKeys, true) || preg_match(self::$sensitiveKeyPattern, $keyStr)) {
                $result[$key] = self::MASK;
                continue;
            }

            // 2. If value is nested array, recurse
            if (is_array($value)) {
                $result[$key] = self::maskArray($value, $customSensitiveKeys);
                continue;
            }

            // 3. If value is string, mask any sensitive patterns inside string
            if (is_string($value)) {
                $result[$key] = self::maskStringValue($value);
                continue;
            }

            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * Mask sensitive strings (Bearer tokens, Authorization headers, Credit card regex).
     *
     * @param string $value
     * @return string
     */
    public static function maskStringValue(string $value): string
    {
        // Mask Authorization Bearer Tokens
        $value = preg_replace('/(Bearer\s+)[A-Za-z0-9\-\._~\+\/]+=*/i', '$1' . self::MASK, $value);

        // Mask Basic Auth strings
        $value = preg_replace('/(Basic\s+)[A-Za-z0-9\+\/=]+/i', '$1' . self::MASK, $value);

        // Mask credit card numbers (13 to 19 digits with optional hyphens/spaces)
        $value = preg_replace('/\b(?:\d[ -]*?){13,19}\b/', '****-****-****-****', $value);

        return $value;
    }

    /**
     * Mask HTTP headers array.
     *
     * @param array $headers
     * @return array
     */
    public static function maskHeaders(array $headers): array
    {
        $sensitiveHeaders = [
            'authorization',
            'cookie',
            'set-cookie',
            'x-xsrf-token',
            'x-csrf-token',
            'x-api-key',
            'php-auth-pw',
        ];

        $masked = [];
        foreach ($headers as $key => $values) {
            $lowerKey = strtolower((string) $key);
            if (in_array($lowerKey, $sensitiveHeaders, true) || preg_match(self::$sensitiveKeyPattern, $lowerKey)) {
                $masked[$key] = [self::MASK];
            } else {
                $masked[$key] = $values;
            }
        }

        return $masked;
    }
}
