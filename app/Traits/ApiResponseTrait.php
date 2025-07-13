<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    protected function respond(
        bool $success,
        string|array|null $message = null,
        int $statusCode = 200,
        mixed $data = null,
        array $errors = []
    ): JsonResponse {
        return response()->json([
            'success' => $success,
            'statusCode' => $statusCode,
            'message' => is_array($message) ? $message : ($message ? [$message] : []),
            'data' => $data,
            'errors' => $errors,
        ], $statusCode);
    }

    protected function success(
        string|array|null $message = null,
        int $statusCode = 200,
        mixed $data = null,
        array $errors = []
    ): JsonResponse {
        return $this->respond(true, $message, $statusCode, $data, $errors);
    }

    protected function error(
        string|array|null $message = null,
        int $statusCode = 400,
        mixed $data = null,
        array $errors = []
    ): JsonResponse {
        return $this->respond(false, $message, $statusCode, $data, $errors);
    }

    protected function validation(
        string|array|null $message = 'Validation failed',
        int $statusCode = 422,
        mixed $data = null,
        array $errors = []
    ): JsonResponse {
        return $this->respond(false, $message, $statusCode, $data, $errors);
    }

    protected function noContent(
        string|array|null $message = null,
        int $statusCode = 204,
        mixed $data = null,
        array $errors = []
    ): JsonResponse {
        return response()->json(null, $statusCode);
    }
}
