# SilverStripe Mail Capture module

A module for capturing outbound emails in data objects that can then be viewed
by users in the CMS directly. 

Useful for sites that are in development (or on test/staging servers) where
users need to be able to use functionality that sends emails, but it is not
desireable to have these emails actually go out to the recipients

## Installation

* Place module in your SilverStripe root directory
* Run dev/build
* Add the following line to your mysite/\_config.php file.
  * Email::set\_mailer(new MailCaptureMailer())


