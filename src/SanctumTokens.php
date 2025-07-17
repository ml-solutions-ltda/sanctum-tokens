<?php

declare(strict_types=1);

namespace MlSolutions\SanctumTokens;

use Laravel\Nova\ResourceTool;

class SanctumTokens extends ResourceTool
{
    private array $defaultOptions = [
        'showAbilities' => true,
        'defaultAbilities' => '*',
    ];

    public function __construct()
    {
        parent::__construct();

        return $this->withMeta($this->defaultOptions);
    }

    /**
     * Get the displayable name of the resource tool.
     */
    public function name(): string
    {
        return 'Sanctum Tokens';
    }

    /**
     * This will hide the references to abilities from the UI.
     *
     * @return $this
     */
    public function hideAbilities(): static
    {
        return $this->withMeta([
            'showAbilities' => false,
        ]);
    }

    /**
     * This will hide the references to abilities from the UI.
     *
     * @return $this
     */
    public function defaultAbilities(array $abilities): static
    {
        return $this->withMeta([
            'defaultAbilities' => implode(', ', $abilities),
        ]);
    }

    /**
     * Get the component name for the resource tool.
     */
    public function component(): string
    {
        return 'SanctumTokens';
    }
}
