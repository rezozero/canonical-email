<?php

namespace RZ\CanonicalEmail\Strategy;

use Assert\Assert;
use Assert\AssertionFailedException;

/**
 * Class GSuiteStrategy
 *
 * @package RZ\CanonicalEmail\Strategy
 * @see https://support.google.com/mail/answer/10313?hl=fr
 */
class GSuiteStrategy implements CanonizeStrategy
{
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

    public function supports(string $email, array $mxHosts): bool
    {
        // Use the GmailStrategy to check gmail.
        if (preg_match('/@(?:gmail|googlemail)\.com$/i', $email) === 1) {
            return false;
        }

        try {
            Assert::that($mxHosts)->minCount(1);
            Assert::thatAll($mxHosts)->inArray(static::$googleMxHosts);
            return true;
        } catch (AssertionFailedException $e) {
            return false;
        }
    }

    public function getCanonicalEmailAddress(string $emailAddress): string
    {
        // GSuite ignore user letter case
        $emailAddress = strtolower($emailAddress);
        [$localPart, $domain] = explode('@', $emailAddress);

        // GSuite ignores everything after + sign
        $localPart = preg_replace('/\+.*$/', '', $localPart);

        return $localPart . '@' . $domain;
    }
}
