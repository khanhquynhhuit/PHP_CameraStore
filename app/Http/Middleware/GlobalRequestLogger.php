<?php

namespace App\Http\Middleware;

use App\Support\DataMasker;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class GlobalRequestLogger
{
    /**
     * Handle an incoming request and log metrics with masked sensitive data.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if audit logging is globally enabled
        if (!config('audit.enabled', true)) {
            return $next($request);
        }

        // Check if path is in excluded paths
        if ($this->shouldExclude($request)) {
            return $next($request);
        }

        // Attach unique Request ID for distributed tracing
        $requestId = (string) Str::uuid();
        $request->attributes->set('request_id', $requestId);

        $startTime = microtime(true);

        // Process request through next middlewares and controller
        /** @var Response $response */
        $response = $next($request);

        $durationMs = round((microtime(true) - $startTime) * 1000, 2);
        $peakMemoryMb = round(memory_get_peak_usage(true) / 1024 / 1024, 2);

        // Write structured audit log
        $this->logActivity($request, $response, $requestId, $durationMs, $peakMemoryMb);

        // Attach trace headers to response
        $response->headers->set('X-Request-ID', $requestId);
        $response->headers->set('X-Response-Time', $durationMs . 'ms');

        return $response;
    }

    /**
     * Determine if the request matches any excluded URI pattern.
     *
     * @param Request $request
     * @return bool
     */
    protected function shouldExclude(Request $request): bool
    {
        $excludedPaths = config('audit.excluded_paths', []);

        foreach ($excludedPaths as $pattern) {
            if ($request->is($pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log structured activity details with masked sensitive data.
     *
     * @param Request $request
     * @param Response $response
     * @param string $requestId
     * @param float $durationMs
     * @param float $peakMemoryMb
     * @return void
     */
    protected function logActivity(
        Request $request,
        Response $response,
        string $requestId,
        float $durationMs,
        float $peakMemoryMb
    ): void {
        $user = $request->user();
        $statusCode = $response->getStatusCode();
        $method = $request->method();
        $path = $request->path();

        // Build masked context
        $context = [
            'request_id'   => $requestId,
            'method'       => $method,
            'url'          => $request->fullUrl(),
            'path'         => '/' . ltrim($path, '/'),
            'route'        => $request->route()?->getName(),
            'action'       => $request->route()?->getActionName(),
            'status'       => $statusCode,
            'duration'     => $durationMs . ' ms',
            'memory_peak'  => $peakMemoryMb . ' MB',
            'ip'           => $request->ip(),
            'user_agent'   => $request->userAgent(),
            'user'         => $user ? [
                'id'    => $user->id,
                'email' => $user->email,
                'role'  => $user->role ?? null,
            ] : 'Guest',
        ];

        // 1. Log masked headers if enabled
        if (config('audit.log_headers', true)) {
            $context['headers'] = DataMasker::maskHeaders($request->headers->all());
        }

        // 2. Log masked query & payload if enabled
        if (config('audit.log_request_payload', true)) {
            $context['payload'] = DataMasker::mask($request->all());
        }

        // 3. Log masked response if JSON and configured
        if (config('audit.log_response_payload', false)) {
            $content = $response->getContent();
            $decoded = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $context['response_payload'] = DataMasker::mask($decoded);
            }
        }

        $logChannel = config('audit.channel', 'daily');
        $logMessage = sprintf(
            '[AOP-AUDIT] %s /%s -> %d (%s ms) [%s]',
            $method,
            ltrim($path, '/'),
            $statusCode,
            $durationMs,
            $user ? "User: {$user->id}" : 'Guest'
        );

        $logger = $logChannel ? Log::channel($logChannel) : Log::getFacadeRoot();

        if ($statusCode >= 500) {
            $logger->error($logMessage, $context);
        } elseif ($statusCode >= 400) {
            $logger->warning($logMessage, $context);
        } else {
            $logger->info($logMessage, $context);
        }
    }
}
