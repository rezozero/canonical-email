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
        $this->assertEquals($supported, (new GmailStrategy(true))->supportsEmailAddress($email));
    }

    public function supportsEmailAddressProvider(): array
    {
        return [
            ['test@gmail.com', true],
            ['te.st@gmail.com', true],
            ['Te.st@gmail.com', true],
            ['test+test@gmail.com', true],
            ['test+test@rezo-zero.com', true],
            ['test+test@rezo-zero.dev', true],
            ['test+test@google.com', true],
            ['test+test@thehopegallery.com', true],
            ['test+test@thehope.gallery', true],
            ['test@hotmail.com', false],
            ['test@google-groups.com', false],
            ['test@roadiz.io', true],
        ];
    }

    /**
     * @dataProvider getNonGmailAddressProvider
     */
    public function testThrowsEmailNotSupported(string $emailAddress)
    {
        $this->expectException(EmailNotSupported::class);
        (new GmailStrategy(true))->getCanonicalEmailAddress($emailAddress);
    }

    public function getNonGmailAddressProvider()
    {
        return [
            ['test+te.st@test.test'],
            ['test+te.st.test.test'],
            ['im not an email']
        ];
    }
}
