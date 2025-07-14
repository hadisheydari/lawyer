<?php

namespace App\Support\Traits;

use App\Services\Api\ApiResponseInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait HandlesValidationErrors
{
    /**
     * Handle ValidationException and return appropriate response.
     *
     * @param ValidationException $exception
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function handleValidationException(ValidationException $exception, Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return app(ApiResponseInterface::class)->send(
                false,
                trans('validation.failed'),
                422,
                null,
                $exception->errors()
            );
        }

        return redirect()->back()
            ->withErrors($exception->errors())
            ->withInput();
    }
}
