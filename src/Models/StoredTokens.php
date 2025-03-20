<?php

declare(strict_types=1);

namespace MetasyncSite\SanctumTokens\Models;

use Illuminate\Database\Eloquent\Model;

class StoredTokens extends Model
{
    protected $table = 'stored_tokens';

    protected $fillable = [
        'token_id',
        'encrypted_token',
        'created_by',
        'is_viewable',
        'viewable_until',
    ];
}
