<?php

namespace Slurp\Unleash\Strategies;

class StrategyFactory
{
    public static function make(array $strategy): ?StrategyContract
    {
        $strategyName = $strategy['name'];

        switch ($strategyName) {
            case StrategyEnum::USER_WITH_ID:
                return new UserWithIdStrategy($strategy);

            default:
                return null;
        }
    }
}
