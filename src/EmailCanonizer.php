<?php

declare(strict_types=1);

namespace RZ\CanonicalEmail;

use RZ\CanonicalEmail\Strategy\CanonizeStrategy;

class EmailCanonizer implements CanonizeStrategy
{
    private array $strategies = [];

    /**
     * @param array $strategies
     */
    public function __construct(array $strategies)
    {
        $this->strategies = $strategies;
    }

    public function supportsEmailAddress(string $emailAddress): bool
    {
        return false !== filter_var($emailAddress, FILTER_VALIDATE_EMAIL);
    }

    public function getCanonicalEmailAddress(string $emailAddress): string
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy instanceof CanonizeStrategy && $strategy->supportsEmailAddress($emailAddress)) {
                $emailAddress = $strategy->getCanonicalEmailAddress($emailAddress);
            }
        }
        return $emailAddress;
    }
}
