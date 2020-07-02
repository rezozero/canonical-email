# canonical-email
Simple PHP library to canonize email addresses from gmail.com, outlook.com or other providers that allow several forms of email.

[![Build Status](https://travis-ci.org/rezozero/canonical-email.svg?branch=master)](https://travis-ci.org/rezozero/canonical-email)

**Be careful: do not store canonical email as primary email for login or sending emails!**    
Your users may not be able to login again to your site if they used a specific email syntax which differs from canonical. Only store canonical emails in order to test against duplicates and prevent new users from creating multiple accounts with same email using variants.

Always store `email` and `canonical_email` in your databases.

## Strategies

- `LowercaseDomainStrategy`: for every emails, domain is case insensitive, so it should be lowercased.
- `GmailStrategy`: for `@gmail.com` addresses or whatever domain which MX servers are from Google GSuite (if `$checkMxRecords` is `true`). This will remove any dots, and any character after `+` sign. Then all email parts will be lowercased. When MX are checked, your app will use PHP `getmxrr` function.
- `OutlookStrategy`: for `@outlook.com` addresses. This will remove any character after `+` sign.

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
