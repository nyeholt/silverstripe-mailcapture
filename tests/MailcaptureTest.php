<?php

namespace Symbiote\MailCapture\tests;

use SilverStripe\Control\Email\Email;
use SilverStripe\Control\Email\Mailer;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;
use Symbiote\MailCapture\Email\CaptureMailer;
use Symbiote\MailCapture\Model\CapturedEmail;

class MailcaptureTest extends SapphireTest
{
    protected $usesDatabase = true;
    protected $originalMailer = '';

    // public function setUp()
    // {
    //     parent::setUp();
    //     $this->originalMailer = Mailer::class;
    //     Injector::inst()->registerService(CaptureMailer::class, 'Mailer');
    // }
    //
    // public function tearDown()
    // {
    //     Injector::inst()->registerService($this->originalMailer, 'Mailer');
    //     parent::tearDown();
    // }

    public function testCaptureMail()
    {
        $mailer = Mailer::class;
        $this->assertTrue($mailer instanceof CaptureMailer);

        $capturedCount = CapturedEmail::get()->count();

        $from = "test@test.com";
        $to = "test@test.com";
        $subject = "Test Capture Mail";
        $body = "Test body.";

        $email = Email::create($from , $to, $subject, $body);
        $email->send();

        // Should capture 1 email
        $this->assertTrue(CapturedEmail::get()->count() == $capturedCount + 1);
    }
}
