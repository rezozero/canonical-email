<?php
declare(strict_types=1);

namespace RZ\CanonicalEmail\Strategy;

interface CanonizeStrategy
{
    public function supports(string $email, array $mxHosts): bool;

    public function getCanonicalEmailAddress(string $emailAddress): string;
}
