<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RZ\CanonicalEmail\EmailCanonizer;
use RZ\CanonicalEmail\Strategy\GmailStrategy;
use RZ\CanonicalEmail\Strategy\GSuiteStrategy;
use RZ\CanonicalEmail\Strategy\OutlookStrategy;

class EmailCanonizerTest extends TestCase
{
    /**
     * @dataProvider getCanonicalEmailAddressProvider
     */
    public function testGetCanonicalEmailAddress(string $email, string $canonicalEmail)
    {
        $canonizer = new EmailCanonizer([
            new GmailStrategy(),
            new GSuiteStrategy(),
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
            ['Te.st@outlook.com', 'te.st@outlook.com'],
            ['test+test+test@outlook.com', 'test@outlook.com'],
            ['Te.st+spam@outlook.com', 'te.st@outlook.com'],
            ['test+te.st@rezo-zero.com', 'test@rezo-zero.com'],
            ['test+te.st@Test.test', 'test+te.st@test.test'],
            ['SuperTest@test.test', 'SuperTest@test.test'],
        ];
    }

    /**
     * @dataProvider getNonGmailAddressProvider
     */
    public function testThrowsInvalidEmail(string $emailAddress)
    {
        $this->expectException(\RZ\CanonicalEmail\Exception\InvalidEmailException::class);
        $canonizer = new EmailCanonizer([
            new GmailStrategy(),
            new GSuiteStrategy(),
            new OutlookStrategy()
        ]);
        $canonizer->getCanonicalEmailAddress($emailAddress);
    }

    public function getNonGmailAddressProvider()
    {
        return [
            ['test+te.st.test.test'],
            ['im not an email']
        ];
    }
}
