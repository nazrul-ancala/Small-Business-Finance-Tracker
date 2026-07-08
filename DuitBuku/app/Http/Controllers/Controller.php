<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

abstract class Controller
{
    protected function callApi(string $method, string $endpoint, array $params = []): mixed
    {
        $base  = rtrim(config('api.url'), '/');
        $pass1 = config('api.pass1');
        $pass2 = config('api.pass2');

        $request = Http::withBasicAuth($pass1, $pass2)->timeout(10);

        $response = match (strtoupper($method)) {
            'POST'  => $request->asForm()->post("{$base}/api/{$endpoint}", $params),
            default => $request->get("{$base}/api/{$endpoint}", $params),
        };

        return $response->json();
    }
}
