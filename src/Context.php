<?php

namespace AsnawiSaharuddin\Unleash;

class Context
{
    private $userId;
    private $sessionId;
    private $remoteAddress;
    private $properties;

    public function __construct(array $context)
    {
        if (isset($context['userId'])) {
            $this->userId = $context['userId'];
        }
    }

    public function getUserId(): ?string
    {
        return $this->userId ?? null;
    }
}
