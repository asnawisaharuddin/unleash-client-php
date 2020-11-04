<?php

namespace Slurp\Unleash\Strategies;

use Slurp\Unleash\Context;

interface StrategyContract
{
    public function __construct(array $parameters);

    public function getParameters(): array;

    public function isEnabled(Context $context): bool;
}
