<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');

        $response = $next($request);

        if ($response instanceof JsonResponse) {
            return $response;
        }

        $statusCode = $response->getStatusCode();
        $content = $response->getContent();

        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            if ($statusCode >= 400) {
                // For error responses
                $data = [
                    'error' => 'An error occurred',
                    'status' => $statusCode
                ];
            } else {
                // For successful responses
                $data = [
                    'data' => 'Success',
                    'status' => $statusCode
                ];
            }
        }

        $headers = $response->headers->all();
        unset($headers['content-type']);

        return response()->json($data, $statusCode, $headers);
    }
}
