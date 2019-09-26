<?php
declare(strict_types=1);

namespace RZ\CanonicalEmail\Strategy;

interface CanonizeStrategy
{
    public function supportsEmailAddress(string $emailAddress): bool;

    public function getCanonicalEmailAddress(string $emailAddress): string;
}
