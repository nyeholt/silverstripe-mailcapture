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
Injector:
  Mailer:
    class: CaptureMailer
    properties:
      outboundMailer: %$MailerObject
      # Set to false to enable pass through of emails without logging
      captureEmails: FALSE
  MailerObject:
    class: Mailer
```

# Using on Silverstripe 3.1

Due to API breakages between 3.1 and 3.2, you must add the following line
to your config to override the Mailer:
```php
Email::set_mailer(Injector::inst()->get('Mailer'));
```
