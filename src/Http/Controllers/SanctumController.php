<?php

declare(strict_types=1);

namespace MetasyncSite\SanctumTokens\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Nova\Nova;
use MetasyncSite\SanctumTokens\Models\PersonalAccessToken;
use MetasyncSite\SanctumTokens\Services\TokenManager;

class SanctumController
{
    public function __construct(private readonly TokenManager $tokenManager) {}

    public function tokens($resourceName, $id)
    {
        return Nova::modelInstanceForKey($resourceName)
            ->with('tokens')
            ->find($id);
    }

    public function createToken(Request $request, $resourceName, $id)
    {
        $model = Nova::modelInstanceForKey($resourceName)->find($id);

        $abilities = collect(explode(',', $request->abilities))
            ->map(function ($item) {
                return trim($item);
            })
            ->toArray();

        $token = $model->createToken($request->name, $abilities);

        /** @var PersonalAccessToken $accessToken */
        $accessToken = $token->accessToken;
        $accessToken->description = $request->description;
        $accessToken->save();

        $this->tokenManager->createToken($accessToken->id, $token->plainTextToken);

        return $token->plainTextToken;
    }

    public function revoke(Request $request, $resourceName, $id)
    {
        $user = Nova::modelInstanceForKey($resourceName)
            ->with('tokens')
            ->find($id);

        return $user
            ->tokens()
            ->whereKey($request->token_id)
            ->delete();
    }
}
