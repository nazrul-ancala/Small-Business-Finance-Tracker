<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class BasicTokenAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        if (! $authHeader || ! preg_match('/Basic\s+(.*)$/i', $authHeader, $matches)) {
            return response()->json(['Success' => false, 'Message' => 'Missing Authorization header.'], 401);
        }

        $decoded  = base64_decode($matches[1]);
        $parts    = explode(':', $decoded, 2);
        $username = $parts[0] ?? '';
        $password = $parts[1] ?? '';

        if ($username !== config('api.pass1') || $password !== config('api.pass2')) {
            Log::warning('Unauthorized API attempt', ['ip' => $request->ip()]);
            return response()->json(['Success' => false, 'Message' => 'Invalid credentials.'], 401);
        }

        return $next($request);
    }
}