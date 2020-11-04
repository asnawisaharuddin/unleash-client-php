<?php

namespace Slurp\Unleash\Strategies;

use Slurp\Unleash\Context;

class UserWithIdStrategy implements StrategyContract
{
    /** @var array */
    private $parameters;

    /** @var string */
    private $name;

    public function __construct(array $strategy)
    {
        $this->parameters = $strategy['parameters'];
        $this->name = $strategy['name'];
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function isEnabled(Context $context): bool
    {
        if (empty($this->parameters) || !isset($this->parameters['userIds'])) {
            return false;
        }

        if (empty($this->parameters['userIds'])) {
            return false;
        }

        if (is_null($context->getUserId())) {
            return false;
        }

        $userIds = explode(',', $this->parameters['userIds']);

        return in_array($context->getUserId(), $userIds);
    }
}
