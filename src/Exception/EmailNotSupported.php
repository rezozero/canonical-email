<?php
declare(strict_types=1);

namespace RZ\CanonicalEmail\Exception;

class EmailNotSupported extends \InvalidArgumentException
{
    public static function fromEmailAddressAndStrategy(string $emailAddress, string $strategyClass)
    {
        return new static(sprintf('Email %s is not supported by %s strategy.', $emailAddress, $strategyClass));
    }
}
