<?php

namespace Symbiote\MailCapture\Email;

use SilverStripe\Control\Email\Email;
use Symfony\Component\Mailer\Mailer;

use SilverStripe\Control\Email\SwiftMailer;
use SilverStripe\Core\Extension;
use Symbiote\MailCapture\Model\CapturedEmail;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mime\Rawowner;

/**
 * A mailer that can be used to capture emails instead of sending them out
 *
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class CaptureMailer extends Extension
{
    public function setRecordEmails(bool $bool)
    {
        $this->recordEmails = $bool;
    }

    public function setSendMailOutbound(bool $bool)
    {
        $this->sendMailOutbound = $bool;
    }

    /**
     * Do we capture emails in the system?
     *
     * @var boolean
     */
    protected $recordEmails = true;

    /**
     * do we send out emails for real?
     * @var bool
     */
    protected $sendMailOutbound = true;


    protected $send;

    public function setMassMailSend($item)
    {
        $this->send = $item;
    }

    /**
     * Undocumented function
     *
     * @param ViewableData $data
     * @return void
     */
    public function updateGetData($data): void
    {
        if ($this->recordEmails) {
            $owner = $this->getOwner();
            $mail = CapturedEmail::record_email($owner);
        }
    }
}
