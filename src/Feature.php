<?php

namespace Slurp\Unleash;

class Feature
{
    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var bool */
    private $enabled;

    /** @var array */
    private $strategies;

    public function __construct(array $strategy)
    {
        $this->name = $strategy['name'];
        $this->description = $strategy['description'] ?? null;
        $this->enabled = $strategy['enabled'] ?? false;
        $this->strategies = $strategy['strategies'] ?? [];
    }

    public function isEnabled(): bool
    {
        return $this->enabled === true;
    }

    public function getStrategies(): array
    {
        return $this->strategies;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
