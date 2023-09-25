# SilverStripe Mail Capture module

[![Build Status](https://travis-ci.org/nyeholt/silverstripe-mailcapture.svg?branch=master)](https://travis-ci.org/nyeholt/silverstripe-mailcapture)

A module for capturing outbound emails in data objects that can then be viewed
by users in the CMS directly.

Useful for sites that are in development (or on test/staging servers) where
users need to be able to use functionality that sends emails, but it is not
desirable to have these emails actually go out to the recipients.

This module defaults to not sending emails, but supports sending via an out
bound mailer, this is configured via yml, e.g:

```yml
---
Name: mymailcapture
after: mailcapture
---
SilverStripe\Core\Injector\Injector:
  SilverStripe\Control\Email\Mailer:
    class: Sunnysideup\MailCapture\Control\Email\CaptureMailer
    properties:
     # Set to FALSE to enable pass through of emails without logging
      recordEmails: TRUE
     # Set to TRUE to send emails, uses the configured SwiftMailer
      sendMailOutbound: TRUE
```

## Composer Install

**[SilverStripe 3.X](https://github.com/Sunnysideup/silverstripe-mailcapture/tree/2)**
```
composer require Sunnysideup/silverstripe-mailcapture:~2.0
```

**[SilverStripe 4.X](https://github.com/Sunnysideup/silverstripe-mailcapture/tree/master)**
```
composer require Sunnysideup/silverstripe-mailcapture:~3.0
```

## Requirements

* PHP 7.0+
* SilverStripe 4.0+

## Breaking Changes

When upgrading from SilverStripe 3.x to SilverStripe 4.x, the yml file has changed syntax and function.
As an example this yml configure would record a copy of each email and use the default Mailer object to send emails
over SMTP:

```yml
---
Name: mymailcapture
after: mailcapture
---
Injector:
  Mailer:
    class: CaptureMailer
    properties:
      outboundMailer: %$MailerObject
      # Set to false to enable pass through of emails without logging
      captureEmails: TRUE
  MailerObject:
    class: Mailer
```

The same functionality in the new syntax looks like this:

```yml
---
Name: mymailcapture
after: mailcapture
---
SilverStripe\Core\Injector\Injector:
  SilverStripe\Control\Email\Mailer:
    class: Sunnysideup\MailCapture\Control\Email\CaptureMailer
    properties:
     # Set to FALSE to enable pass through of emails without logging
      recordEmails: TRUE
     # Set to TRUE to send emails, uses the configured SwiftMailer
      sendMailOutbound: TRUE
```

`captureEmails` is now `recordEmails` this removes the ambiguous capture word making the intent of the setting clearer.
`outboundMailer` is no longer used as the CaptureMailer classes instead uses the SwiftMailer transport that is
configured for the site. To enable sending of emails externally we set `sendMailOutbound`.
