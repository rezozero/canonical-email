<?php

namespace RZ\CanonicalEmail\Strategy;

use RZ\CanonicalEmail\Exception\EmailNotSupported;

class OutlookStrategy implements CanonizeStrategy
{
    public function supports(string $email, array $mxHosts): bool
    {
        return preg_match('/@outlook\.com$/i', $email) === 1;
    }

    public function getCanonicalEmailAddress(string $emailAddress): string
    {
        // GSuite ignore user letter case
        $emailAddress = strtolower($emailAddress);

        [$localPart, $domain] = explode('@', $emailAddress);
        // Outlook ignores dots and everything after + sign
        $localPart = preg_replace('/\+.*$/', '', $localPart);

        return $localPart . '@' . $domain;
    }
}
