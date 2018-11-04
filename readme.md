# Disposable Email Checker

[![Build Status](https://travis-ci.org/vboctor/disposable_email_checker.png?branch=master)](https://travis-ci.org/vboctor/disposable_email_checker)

## What is it?

Disposable Email Checker is a library that allows applications to check for
users signup with [[]disposable email addresses|https://en.wikipedia.org/wiki/Disposable_email_address]].  This is very important for
services that provide some trial period based on the email address.  Hence,
if users use disposable addresses, then they can potentially bypass the time
limit.  Other applications may use it to make sure that they have valid emails
that they can use for future correspondence.

The library currently contains > 1,900 domains and growing.

## Goals

This project started with offering a php library, but in v2, it has been refactored
to separate the domain lists from the code.  This enables the following scenarios:

- Providing implementations in multiple languages that consumes such data files.
- Enable apps to easily embed these data files and consume them directly.
- Simplify the process of updating the list to add more domains.

## How Disposable Addresses are detected?

This library has a list of rules that are used to determine whether an address 
is disposable or not.  The library does not connect to the Internet to determine 
the kind or the validity of the address.  The library may be enhanced in the 
future to provide applications with ways of explicitly requiring online checks.

## Contributing

This library is available as open source with MIT license, so you can use it
in both open source and commercial applications.  The best ways to contribute
back to this library are:

1. Report service providers that the library should detect but it doesn't.  See section below for validating disposeable domains.
2. Report bugs and feature request in the bug tracker.
3. Provide ports for the library in languages other than PHP.

To report bugs and feature requests use the associated github bug tracker.

## Validating Disposable Domains

Before submitting a PR with new disposable email domains, please validate
that such domain are really disposable via the [Kickbox](kickbox.com) service.

In the following URL replace `{domain}` with your domain (e.g. `example.com`) and make sure
it returns `disposable: true`.

```
https://open.kickbox.com/v1/disposable/{domain}
```

## Running Unit Tests

Here is how to run the unit tests:

```
composer install
vendor/phpunit/phpunit/phpunit php/tests/disposable_email_checker_tests.php
```

## Versioning Scheme

The versioning for this library is formatted as follows "1.2.3".

   (1) This is the major version which will change when there are major changes 
       or re-implementation.
       
   (2) This is the minor version which will change when the APIs changes.
       Most of the time these won't be breaking changes, but sometimes they
       may be.
       
   (3) This is the data version which is the only one changed in releases that
       just update the rules that are used to determine that an email address
       is disposable.

## Credits

- Contributors of Domains
- Merged domains from [[ivolo/disposable-email-domains|https://github.com/ivolo/disposable-email-domains/]]
- Merged domains from [[aaronbassett/DisposableEmailChecker:https://github.com/aaronbassett/DisposableEmailChecker]]
