<?php

namespace RZ\CanonicalEmail\Strategy;

use Assert\Assert;
use Assert\AssertionFailedException;
use RZ\CanonicalEmail\Exception\EmailNotSupported;

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

    public function supportsEmailAddress(string $emailAddress): bool
    {
        if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            // Use the GmailStrategy to check gmail.
            if (preg_match('#\@(?:gmail|googlemail)\.com$#', $emailAddress) === 1) {
                return false;
            }
            return $this->areMailExchangesFromGoogle($emailAddress);
        }
        return false;
    }

    public function getCanonicalEmailAddress(string $emailAddress): string
    {
        if (!$this->supportsEmailAddress($emailAddress)) {
            throw EmailNotSupported::fromEmailAddressAndStrategy($emailAddress, static::class);
        }
        $emailAddress = explode('@', $emailAddress);
        // GSuite ignore user letter case
        $emailAddress[0] = strtolower($emailAddress[0]);
        // GSuite ignores everything after + sign
        $emailAddress[0] = preg_replace('#(\+[^@]*)#', '', $emailAddress[0]);

        return implode('@', $emailAddress);
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
