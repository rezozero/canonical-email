<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RZ\CanonicalEmail\EmailCanonizer;
use RZ\CanonicalEmail\Strategy\GmailStrategy;
use RZ\CanonicalEmail\Strategy\GSuiteStrategy;
use RZ\CanonicalEmail\Strategy\LowercaseDomainStrategy;
use RZ\CanonicalEmail\Strategy\OutlookStrategy;

class EmailCanonizerTest extends TestCase
{
    /**
     * @dataProvider getCanonicalEmailAddressProvider
     *
     * @param string $email
     * @param string $canonicalEmail
     */
    public function testGetCanonicalEmailAddress(string $email, string $canonicalEmail)
    {
        $canonizer = new EmailCanonizer([
            new LowercaseDomainStrategy(),
            new GmailStrategy(true),
            new GSuiteStrategy(true),
            new OutlookStrategy()
        ]);
        $this->assertEquals($canonicalEmail, $canonizer->getCanonicalEmailAddress($email));
    }

    public function getCanonicalEmailAddressProvider(): array
    {
        return [
            ['test@gmail.com', 'test@gmail.com'],
            ['te.st@gmail.com', 'test@gmail.com'],
            ['Test@Gmail.com', 'test@gmail.com'],
            ['Te.St@gmail.com', 'test@gmail.com'],
            ['Te.St@googlemail.com', 'test@gmail.com'],
            ['test+test@gMail.com', 'test@gmail.com'],
            ['test+test+cool@gMail.com', 'test@gmail.com'],
            ['Te.st@outlook.com', 'Te.st@outlook.com'],
            ['test+test+test@outlook.com', 'test@outlook.com'],
            ['Te.st+spam@outlook.com', 'Te.st@outlook.com'],
            ['test+te.st@rezo-zero.com', 'test@rezo-zero.com'],
            ['test.test@rezo-zero.com', 'test.test@rezo-zero.com'], // GSuite does not ignore dots before +
            ['test+te.st@Test.test', 'test+te.st@test.test'],
            ['SuperTest@test.test', 'SuperTest@test.test'],
        ];
    }
}
