<?php

namespace Symbiote\MailCapture\Control\Email;

use SilverStripe\Control\Email\SwiftMailer;
use Symbiote\MailCapture\Model\CapturedEmail;

/**
 * A mailer that can be used to capture emails instead of sending them out
 *
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class CaptureMailer extends SwiftMailer {

	/**
	 * Do we capture emails in the system?
	 *
	 * @var boolean
	 */
	public $recordEmails = true;

	/**
	 * Mailer or a sub-class of Mailer that can be used for sending the captured emails
	 *
	 * @var Object
	 */
	public $outboundMailer;

	protected $send;

	public function setMassMailSend($item) {
		$this->send = $item;
	}

    /**
     * @param \SilverStripe\Control\Email\Email $message
     * @return bool Whether the sending was "successful" or not
     */
    public function send($message)
    {
        if ($this->recordEmails) {

            $formatEmailAddress = function (array $emails): string
            {
                $return = '';
                foreach ($emails as $address => $title) {
                    $return .= "$address";
                    if ($title) {
                        $return .= " <$title>";
                    }
                    $return .= ", ";
                }
                return $return;
            };

			$mail = new CapturedEmail();
			$mail->To = $formatEmailAddress($message->getTo());
			$mail->From = $formatEmailAddress($message->getFrom());
			$mail->ReplyTo = $message->getReplyTo();
			$mail->Subject = $message->getSubject();
            $mail->Headers = $message->getSwiftMessage()->getHeaders()->toString();

			$mail->Content = $message->getBody();

			if ($this->send) {
				$mail->SendID = $this->send->ID;
			}

			$mail->write();
		}

        if ($this->sendMailOutbound) {
			return parent::send($message);
		}

        return true;
    }
}
