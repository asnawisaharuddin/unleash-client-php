<?php

namespace AsnawiSaharuddin\Unleash\Strategies;

use AsnawiSaharuddin\Unleash\Context;

interface StrategyContract
{
    public function __construct(array $parameters);

    public function getParameters(): array;

    public function isEnabled(Context $context): bool;
}
