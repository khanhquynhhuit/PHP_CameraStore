<?php

namespace App\Http\Middleware;

use App\Exceptions\GlobalExceptionHandler;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class GlobalExceptionFilter
{
    /**
     * Handle an incoming request through a try-catch filter.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (Throwable $e) {
            $jsonResponse = GlobalExceptionHandler::render($e, $request);

            if ($jsonResponse !== null) {
                return $jsonResponse;
            }

            throw $e;
        }
    }
}
