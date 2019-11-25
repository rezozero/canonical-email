<?php
declare(strict_types=1);

namespace RZ\CanonicalEmail\Strategy;

class LowercaseDomainStrategy implements CanonizeStrategy
{
    public function supports(string $email, array $mxHosts): bool
    {
        return true;
    }

    public function getCanonicalEmailAddress(string $emailAddress): string
    {
        [$localPart, $domain] = explode('@', $emailAddress);
        /*
         * For now ONLY the domain part will be lowercased as some exotic mail servers
         * still support case sensitive mailboxes. So it might happen that
         * User@domain.tld exists along with a uSER@domain.tld
         *
         * @see https://stackoverflow.com/questions/9807909/are-email-addresses-case-sensitive
         *
         */
        $domain = strtolower($domain);
        return $localPart . '@' . $domain;
    }
}
