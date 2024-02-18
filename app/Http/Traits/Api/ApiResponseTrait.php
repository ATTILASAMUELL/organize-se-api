<?php

namespace App\Http\Traits\Api;

trait ApiResponseTrait
{
    /**
     * Retorna uma resposta JSON padronizada para erros.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message = 'Try again later!', $statusCode = 500)
    {
        return response()->json([
            'status' => 'false',
            'message' => $message,
        ], $statusCode);
    }

    /**
     * Retorna uma resposta JSON padronizada para erros.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($message = 'Successfully logged out!', $statusCode = 200)
    {
        return response()->json([
            'status' => 'true',
            'message' => $message,
        ], $statusCode);
    }
}
