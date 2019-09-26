<?php

namespace RZ\CanonicalEmail\Strategy;

use RZ\CanonicalEmail\Exception\EmailNotSupported;

class OutlookStrategy implements CanonizeStrategy
{
    public function supportsEmailAddress(string $emailAddress): bool
    {
        if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            if (preg_match('#\@outlook\.com$#', $emailAddress) === 1) {
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
        // Outlook ignores dots and everything after + sign
        $emailAddress[0] = preg_replace('#(\+[^@]+)#', '', $emailAddress[0]);

        return implode('@', $emailAddress);
    }
}
