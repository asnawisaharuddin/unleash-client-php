<?php

namespace AsnawiSaharuddin\Unleash;

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

    public function __construct(array $feature)
    {
        $this->name = $feature['name'];
        $this->description = $feature['description'] ?? null;
        $this->enabled = $feature['enabled'] ?? false;
        $this->strategies = $feature['strategies'] ?? [];
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
