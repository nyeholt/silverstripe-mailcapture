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
    class: Symbiote\MailCapture\Control\Email\CaptureMailer
    properties:
     # Set to FALSE to enable pass through of emails without logging
      recordEmails: TRUE
     # Set to TRUE to send emails, uses the configured SwiftMailer
      sendMailOutbound: TRUE
```
