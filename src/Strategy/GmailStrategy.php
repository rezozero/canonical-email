<?php

namespace RZ\CanonicalEmail\Strategy;

/**
 * Class GmailStrategy
 *
 * @package RZ\CanonicalEmail\Strategy
 * @see https://support.google.com/mail/answer/10313?hl=fr
 */
class GmailStrategy implements CanonizeStrategy
{
    public function supports(string $email, array $mxHosts): bool
    {
        return preg_match('/@(?:gmail|googlemail)\.com$/i', $email) === 1;
    }

    public function getCanonicalEmailAddress(string $emailAddress): string
    {
        // Gmail ignore user letter case
        $emailAddress = strtolower($emailAddress);
        [$localPart, $domain] = explode('@', $emailAddress);

        // Force using gmail.com domain
        if ($domain === 'googlemail.com') {
            $domain = 'gmail.com';
        }
        // Gmail ignores dots and everything after + sign
        $localPart = preg_replace('/\+.*$|\./', '', $localPart);

        return $localPart . '@' . $domain;
    }
}
