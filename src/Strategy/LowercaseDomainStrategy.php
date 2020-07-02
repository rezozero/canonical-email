<?php
declare(strict_types=1);

namespace RZ\CanonicalEmail\Strategy;

use RZ\CanonicalEmail\Exception\EmailNotSupported;

class LowercaseDomainStrategy implements CanonizeStrategy
{
    public function supportsEmailAddress(string $emailAddress): bool
    {
        if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    public function getCanonicalEmailAddress(string $emailAddress): string
    {
        if (!$this->supportsEmailAddress($emailAddress)) {
            throw EmailNotSupported::fromEmailAddressAndStrategy($emailAddress, static::class);
        }
        $emailAddress = explode('@', $emailAddress);
        /*
         * For now ONLY the domain part will be lowercased as some exotic mail servers
         * still support case sensitive mailboxes. So it might happen that
         * User@domain.tld exists along with a uSER@domain.tld
         *
         * @see https://stackoverflow.com/questions/9807909/are-email-addresses-case-sensitive
         *
         */
        $emailAddress[1] = strtolower($emailAddress[1]);
        return implode('@', $emailAddress);
    }
}
