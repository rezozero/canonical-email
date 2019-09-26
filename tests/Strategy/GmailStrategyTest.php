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
            ['Te.st@googlemail.com', true],
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
     * @dataProvider supportsEmailAddressNoMxProvider
     */
    public function testSupportsEmailAddressNoMx(string $email, bool $supported)
    {
        $this->assertEquals($supported, (new GmailStrategy(false))->supportsEmailAddress($email));
    }

    public function supportsEmailAddressNoMxProvider(): array
    {
        return [
            ['test@gmail.com', true],
            ['te.st@gmail.com', true],
            ['Te.st@gmail.com', true],
            ['Te.st@googlemail.com', true],
            ['test+test@gmail.com', true],
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
