<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RZ\CanonicalEmail\Exception\EmailNotSupported;
use RZ\CanonicalEmail\Strategy\OutlookStrategy;

final class OutlookStrategyTest extends TestCase
{
    /**
     * @dataProvider supportsEmailAddressProvider
     */
    public function testSupportsEmailAddress(string $email, bool $supported)
    {
        [, $domain] = explode('@', $email);
        $this->assertEquals($supported, (new OutlookStrategy())->supports($email, []));
    }

    public function supportsEmailAddressProvider(): array
    {
        return [
            ['test@outlook.com', true],
            ['te.st@outlook.com', true],
            ['Te.st@outlook.com', true],
            ['test+test@gmail.com', false],
            ['test+test@rezo-zero.com', false],
            ['test+test@rezo-zero.dev', false],
            ['test+test@google.com', false],
            ['test+test@thehopegallery.com', false],
            ['test+test@thehope.gallery', false],
            ['test@hotmail.com', false],
            ['test@google-groups.com', false],
            ['test@roadiz.io', false],
        ];
    }
}
