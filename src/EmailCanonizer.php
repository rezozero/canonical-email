<?php
declare(strict_types=1);

namespace RZ\CanonicalEmail;

use RZ\CanonicalEmail\Exception\InvalidEmailException;
use RZ\CanonicalEmail\Strategy\CanonizeStrategy;

class EmailCanonizer
{
    /**
     * @var array
     */
    private $strategies = [];

    /**
     * EmailCanonizer constructor.
     *
     * @param array $strategies
     */
    public function __construct(array $strategies)
    {
        $this->strategies = $strategies;
    }

    public function getCanonicalEmailAddress(string $emailAddress): string
    {
        if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException("Invalid email address");
        }
        [$local_part, $domain] = explode('@', $emailAddress);
        $domain = strtolower($domain);
        getmxrr($domain, $mxHosts);

        $emailAddress = $local_part . '@' . $domain;

        foreach ($this->strategies as $strategy) {
            if ($strategy instanceof CanonizeStrategy && $strategy->supports($emailAddress, $mxHosts)) {
                $emailAddress = $strategy->getCanonicalEmailAddress($emailAddress);
            }
        }
        return $emailAddress;
    }
}
