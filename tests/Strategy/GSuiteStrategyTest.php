<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RZ\CanonicalEmail\Exception\EmailNotSupported;
use RZ\CanonicalEmail\Strategy\GSuiteStrategy;

final class GSuiteStrategyTest extends TestCase
{
    /**
     * @dataProvider supportsEmailAddressProvider
     */
    public function testSupportsEmailAddress(string $email, bool $supported)
    {
        $this->assertEquals($supported, (new GSuiteStrategy())->supportsEmailAddress($email));
    }

    public function supportsEmailAddressProvider(): array
    {
        return [
            ['test@gmail.com', false],
            ['Te.st@googlemail.com', false],
            ['test+test@rezo-zero.com', true],
            ['test+test@rezo-zero.dev', true],
            ['test+test@thehopegallery.com', true],
            ['test+@thehopegallery.com', true],
            ['test+test@thehope.gallery', true],
            ['test@hotmail.com', false],
            ['test@google-groups.com', false],
            ['test@roadiz.io', true],
        ];
    }

    /**
     * @dataProvider getNonGSuiteAddressProvider
     */
    public function testThrowsEmailNotSupported(string $emailAddress)
    {
        $this->expectException(EmailNotSupported::class);
        (new GSuiteStrategy())->getCanonicalEmailAddress($emailAddress);
    }

    public function getNonGSuiteAddressProvider()
    {
        return [
            ['test+te.st@test.test'],
            ['test+te.st.test.test'],
            ['im not an email']
        ];
    }
}
