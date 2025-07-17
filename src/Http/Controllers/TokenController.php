<?php

declare(strict_types=1);

namespace MlSolutions\SanctumTokens\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MlSolutions\SanctumTokens\Services\TokenManager;

class TokenController extends Controller
{
    protected TokenManager $tokenManager;

    public function __construct(TokenManager $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    public function reveal(Request $request, string|int $id): JsonResponse
    {
        try {
            $token = $this->tokenManager->retrieveToken($id);

            return response()->json([
                'token' => $token,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 404);
        }
    }
}
