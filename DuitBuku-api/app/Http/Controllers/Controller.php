<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    protected function ok($data = null, string $message = 'Request successful.', int $status = 200): JsonResponse
    {
        return response()->json([
            'Success' => true,
            'Message' => $message,
            'Data'    => $data,
        ], $status);
    }

    protected function fail(string $message, int $status = 400, ?string $error = null): JsonResponse
    {
        $body = ['Success' => false, 'Message' => $message];

        if ($error !== null) {
            $body['Error'] = $error;
        }

        return response()->json($body, $status);
    }
}
