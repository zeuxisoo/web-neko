<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    #[\Override]
    protected function invalidJson($request, ValidationException $exception): JsonResponse {
        return response()->json([
            'ok' => false,
            'message' => $exception->getMessage(),
            'errors' => $exception->errors(),
        ], $exception->status);
    }

    #[\Override]
    protected function unauthenticated($request, AuthenticationException $exception): Response {
        if ($this->shouldReturnJson($request, $exception)) {
            return response()->json([
                'ok' => false,
                'message' => $exception->getMessage(),
            ], 401);
        }

        return redirect()->guest($exception->redirectTo($request) ?? route('login'));
    }

    #[\Override]
    protected function convertExceptionToArray(\Throwable $e): array {
        return array_merge(
            [
                'ok' => false,
            ],
            parent::convertExceptionToArray($e)
        );
    }
}
