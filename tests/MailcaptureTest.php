<?php

class MailcaptureTest extends SapphireTest
{
    protected $usesDatabase = true;

    public function setUp() 
    {
        parent::setUp();

        // parent::setUp() overrides with TestMailer, so lets put the
        // original back.
        
        Injector::inst()->registerService($this->originalMailer, 'Mailer');
    }

    public function testCaptureMail()
    {
        $mailer = Email::mailer();
        $this->assertTrue($mailer instanceof CaptureMailer);

        $capturedCount = CapturedEmail::get()->count();

        $from = "test@test.com";
        $to = "test@test.com";
        $subject = "Test Capture Mail";
        $body = "Test body.";

        $email = new Email($from , $to, $subject, $body);
        $email->send();

        // Should capture 1 email
        $this->assertTrue(CapturedEmail::get()->count() == $capturedCount + 1);
    }
}