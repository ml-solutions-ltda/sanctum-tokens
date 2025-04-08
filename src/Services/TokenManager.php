<?php

declare(strict_types=1);

namespace MetasyncSite\SanctumTokens\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use MetasyncSite\SanctumTokens\Exception\MetasyncSiteSanctumTokenException;
use MetasyncSite\SanctumTokens\Models\StoredTokens;

class TokenManager
{
    public function createToken(int $tokenId, string $plainTextToken): string
    {
        StoredTokens::query()->create([
            'token_id' => $tokenId,
            'encrypted_token' => Crypt::encryptString($plainTextToken),
            'created_by' => auth()->id(),
            'is_viewable' => true,
            'viewable_until' => now()->addDays(config('sanctum-tokens.viewable_days', 7)),
        ]);

        if (config('app.debug')) {
            Log::info('Token created', [
                'token_id' => $tokenId,
                'admin_id' => auth()->id(),
            ]);
        }

        return $plainTextToken;
    }

    /**
     * @throws MetasyncSiteSanctumTokenException
     */
    public function retrieveToken(int $tokenId): string
    {
        $storedToken = StoredTokens::query()->where('token_id', $tokenId)
            ->first();

        if (! $storedToken) {
            throw new MetasyncSiteSanctumTokenException('Token not found');
        }

        if (! $storedToken->is_viewable) {
            throw new MetasyncSiteSanctumTokenException('Token is no longer viewable');
        }

        if ($storedToken->viewable_until && now()->gt($storedToken->viewable_until)) {
            throw new MetasyncSiteSanctumTokenException('Token view period has expired');
        }

        if (config('app.debug')) {
            Log::warning('Token viewed', [
                'token_id' => $tokenId,
                'admin_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);
        }

        return Crypt::decryptString($storedToken->encrypted_token);
    }
}
