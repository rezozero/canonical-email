<?php

declare(strict_types=1);

namespace RZ\CanonicalEmail\Strategy;

use RZ\CanonicalEmail\Exception\EmailNotSupported;

/**
 * @see https://support.google.com/mail/answer/10313?hl=fr
 */
class GmailStrategy implements CanonizeStrategy
{
    public function supportsEmailAddress(string $emailAddress): bool
    {
        if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            if (preg_match('#\@(?:gmail|googlemail)\.com$#', $emailAddress) === 1) {
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
        // Force using gmail.com domain
        if ($emailAddress[1] === 'googlemail.com') {
            $emailAddress[1] = 'gmail.com';
        }
        // Gmail ignore user letter case
        $emailAddress[0] = strtolower($emailAddress[0]);
        // Gmail ignores dots and everything after + sign
        $emailAddress[0] = preg_replace('#(\+[^@]*)|\.#', '', $emailAddress[0]);

        return implode('@', $emailAddress);
    }
}
