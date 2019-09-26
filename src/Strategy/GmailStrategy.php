<?php

namespace RZ\CanonicalEmail\Strategy;

use Assert\Assert;
use Assert\AssertionFailedException;
use RZ\CanonicalEmail\Exception\EmailNotSupported;

class GmailStrategy implements CanonizeStrategy
{
    /**
     * @var bool
     */
    private $checkMxRecords = false;

    /**
     * @var array
     */
    private static $googleMxHosts = [
        'aspmx.l.google.com',
        'aspmx2.googlemail.com',
        'aspmx3.googlemail.com',
        'alt1.aspmx.l.google.com',
        'alt2.aspmx.l.google.com',
        'alt3.aspmx.l.google.com',
        'alt4.aspmx.l.google.com'
    ];

    /**
     * GmailStrategy constructor.
     *
     * @param bool $checkMxRecords
     */
    public function __construct(bool $checkMxRecords = false)
    {
        $this->checkMxRecords = $checkMxRecords;
    }

    public function supportsEmailAddress(string $emailAddress): bool
    {
        if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            if (preg_match('#\@gmail\.com$#', $emailAddress) === 1) {
                return true;
            }
            if ($this->isCheckingMxRecords() && $this->areMailExchangesFromGoogle($emailAddress)) {
                return true;
            }
        }
        return false;
    }

    public function getCanonicalEmailAddress(string $emailAddress): string
    {
        if (!$this->supportsEmailAddress($emailAddress)) {
            throw EmailNotSupported::fromEmailAddressAndStrategy($emailAddress, static::class);
        }
        $emailAddress = explode('@', $emailAddress);
        // Gmail ignore user letter case
        $emailAddress[0] = strtolower($emailAddress[0]);
        // Gmail ignores dots and everything after + sign
        $emailAddress[0] = preg_replace('#(\+[^@]+)|\.#', '', $emailAddress[0]);

        return implode('@', $emailAddress);
    }

    /**
     * @return bool
     */
    public function isCheckingMxRecords(): bool
    {
        return $this->checkMxRecords;
    }

    private function areMailExchangesFromGoogle(string $emailAddress): bool
    {
        try {
            $emailAddress = explode('@', $emailAddress);
            $domain = $emailAddress[1];
            if (getmxrr($domain, $mxHosts) === true) {
                Assert::that($mxHosts)->minCount(1);
                Assert::thatAll($mxHosts)->inArray(static::$googleMxHosts);
                return true;
            }
            return false;
        } catch (AssertionFailedException $e) {
            return false;
        }
    }
}
