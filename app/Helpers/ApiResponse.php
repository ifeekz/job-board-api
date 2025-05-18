<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success($data = null, string $message = '', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'statusCode' => $statusCode,
            'success' => true,
            'message' => $message,
            'data' => $data ?? (object) [],
        ], $statusCode);
    }

    public static function error(string $message = '', $errors = null, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'statusCode' => $statusCode,
            'success' => false,
            'message' => $message,
            'data' => $errors ?? (object) [],
        ], $statusCode);
    }
}
