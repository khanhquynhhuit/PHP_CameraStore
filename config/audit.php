<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AOP Global Request & Activity Logging Enabled
    |--------------------------------------------------------------------------
    |
    | When enabled, all incoming HTTP requests and responses will be
    | intercepted and logged with execution metrics and masked payloads.
    |
    */

    'enabled' => env('AUDIT_LOG_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Log Channel
    |--------------------------------------------------------------------------
    |
    | The logging channel to record activity logs. Set to null to use
    | the default application log channel.
    |
    */

    'channel' => env('AUDIT_LOG_CHANNEL', 'daily'),

    /*
    |--------------------------------------------------------------------------
    | Excluded Paths / Routes from Logging
    |--------------------------------------------------------------------------
    |
    | URIs that should not be logged (e.g. health checks, assets, telemetry).
    | Supports wildcard matching with `*`.
    |
    */

    'excluded_paths' => [
        'up',
        '_debugbar/*',
        'livewire/*',
        'telescope/*',
        'horizon/*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Include Headers & Payload in Logs
    |--------------------------------------------------------------------------
    |
    | Toggle detailed information logged for each request and response.
    | Sensitive data will ALWAYS be masked with `******`.
    |
    */

    'log_headers' => env('AUDIT_LOG_HEADERS', true),
    'log_request_payload' => env('AUDIT_LOG_REQUEST_PAYLOAD', true),
    'log_response_payload' => env('AUDIT_LOG_RESPONSE_PAYLOAD', false), // Only true if needed for API responses

    /*
    |--------------------------------------------------------------------------
    | Global Sensitive Keys (Always Masked)
    |--------------------------------------------------------------------------
    |
    | Any field matching these keys or patterns will be automatically
    | replaced with `******` before writing to log files.
    |
    */

    'sensitive_keys' => [
        'password',
        'password_confirmation',
        'current_password',
        'new_password',
        'old_password',
        'secret',
        'app_secret',
        'client_secret',
        'token',
        'access_token',
        'refresh_token',
        'api_token',
        'api_key',
        'credit_card',
        'card_number',
        'cvv',
        'cvc',
        'pin',
        'ssn',
        'otp',
        'bank_account',
        'private_key',
        'salt',
    ],

];
