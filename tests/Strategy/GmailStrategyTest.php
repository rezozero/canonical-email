<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RZ\CanonicalEmail\Exception\EmailNotSupported;
use RZ\CanonicalEmail\Strategy\GmailStrategy;

final class GmailStrategyTest extends TestCase
{
    /**
     * @dataProvider supportsEmailAddressProvider
     */
    public function testSupportsEmailAddress(string $email, bool $supported)
    {
        [, $domain] = explode('@', $email);
        $this->assertEquals($supported, (new GmailStrategy())->supports($email, []));
    }

    public function supportsEmailAddressProvider(): array
    {
        return [
            ['test@gmail.com', true],
            ['te.st@gmail.com', true],
            ['Te.st@gmail.com', true],
            ['Te.st@googlemail.com', true],
            ['test+test@gmail.com', true],
            ['test+@gmail.com', true],
            ['test@hotmail.com', false],
            ['test@google-groups.com', false],
            ['test@roadiz.io', false],
        ];
    }
}
