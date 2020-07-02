<?php
declare(strict_types=1);

namespace RZ\CanonicalEmail\Exception;

use Throwable;

class EmailNotSupported extends \InvalidArgumentException
{
    final public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param string $emailAddress
     * @param string $strategyClass
     *
     * @return static
     */
    public static function fromEmailAddressAndStrategy(string $emailAddress, string $strategyClass)
    {
        return new static(sprintf('Email %s is not supported by %s strategy.', $emailAddress, $strategyClass));
    }
}
