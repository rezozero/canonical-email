# canonical-email
Simple PHP library to canonize email addresses from gmail.com, outlook.com or other providers that allow several forms of email.

**Be careful: do not store canonical email as primary email for login or sending emails!**    
Your users may not be able to login again to your site if they used a specific email syntax which differs from canonical. Only store canonical emails in order to test against duplicates and prevent new users from creating multiple accounts with same email using variants.

Always store `email` and `canonical_email` in your databases.

## Strategies

- `LowercaseDomainStrategy`: for every emails, domain is case insensitive, so it should be lowercased.
- `GmailStrategy`: for `@gmail.com` addresses. This will remove any dots, and any characters after `+` sign.
- `GSuiteStrategy`: for domains GSuite MX hosts. This will remove any characters after, and including, `+` sign.
- `OutlookStrategy`: for `@outlook.com` addresses. This will remove any characters after, and including, `+` sign.

## Usage

```bash
composer require rezozero/canonical-email
```

```php
use RZ\CanonicalEmail\EmailCanonizer;
use RZ\CanonicalEmail\Strategy\GmailStrategy;
use RZ\CanonicalEmail\Strategy\GSuiteStrategy;
use RZ\CanonicalEmail\Strategy\LowercaseStrategy;
use RZ\CanonicalEmail\Strategy\OutlookStrategy;

$canonizer = new EmailCanonizer([
    new LowercaseDomainStrategy(),
    new GmailStrategy(),
    new GSuiteStrategy(),
    new OutlookStrategy()
]);

$canonizer->getCanonicalEmailAddress($email);
```

## Running tests

```bash
composer install

make test
```
