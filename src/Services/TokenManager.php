<?php

declare(strict_types=1);

namespace MlSolutions\SanctumTokens\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use MlSolutions\SanctumTokens\Exception\MlSolutionsSanctumTokenException;
use MlSolutions\SanctumTokens\Models\StoredTokens;

class TokenManager
{
    public function createToken(string|int $tokenId, string $plainTextToken): string
    {
        $currentUser = auth(config('nova.guard'))->id();

        StoredTokens::query()->create([
            'token_id' => $tokenId,
            'encrypted_token' => Crypt::encryptString($plainTextToken),
            'created_by' => $currentUser,
            'is_viewable' => true,
            'viewable_until' => now()->addDays(config('sanctum-tokens.viewable_days', 7)),
        ]);

        if (config('app.debug')) {
            Log::info('Token created', [
                'token_id' => $tokenId,
                'admin_id' => $currentUser,
            ]);
        }

        return $plainTextToken;
    }

    /**
     * @throws MlSolutionsSanctumTokenException
     */
    public function retrieveToken(string|int $tokenId): string
    {
        $storedToken = StoredTokens::query()->where('token_id', $tokenId)
            ->first();

        if (! $storedToken) {
            throw new MlSolutionsSanctumTokenException('Token not found');
        }

        if (! $storedToken->is_viewable) {
            throw new MlSolutionsSanctumTokenException('Token is no longer viewable');
        }

        if ($storedToken->viewable_until && now()->gt($storedToken->viewable_until)) {
            throw new MlSolutionsSanctumTokenException('Token view period has expired');
        }

        if (config('app.debug')) {
            Log::warning('Token viewed', [
                'token_id' => $tokenId,
                'admin_id' => auth(config('nova.guard'))->id(),
                'ip' => request()->ip(),
            ]);
        }

        return Crypt::decryptString($storedToken->encrypted_token);
    }
}
