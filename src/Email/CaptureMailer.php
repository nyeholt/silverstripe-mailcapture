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

            $mail = CapturedEmail::create();
            $mail->To = $formatEmailAddress($message->getTo());
            $mail->From = $formatEmailAddress($message->getFrom());
            $mail->ReplyTo = $message->getReplyTo();
            $mail->Subject = $message->getSubject();
            $mail->Headers = $message->getSwiftMessage()->getHeaders()->toString();

            // Ensure we can at least render template if any
            $htmlTemplate = $message->getHTMLTemplate();
            $plainTemplate = $message->getPlainTemplate();

            $plainContent = $htmlContent = '';
            // use html content with html template
            if ($htmlTemplate) {
                $htmlContent = $message->renderWith($htmlTemplate);
                $mail->Content = html_entity_decode($htmlContent);
            }
            // use plain content with plain template
            elseif ($plainTemplate) {
                $plainContent = $message->renderWith($plainTemplate);
                $mail->PlainText = $plainContent;
            }
            // default to same behaviour a prior to above implementation for templates so
            // as to not be a breaking change. We should probably decode this in the future.
            else {
                $mail->Content = $message->getBody();
            }

            if ($this->send) {
                $mail->SendID = $this->send->ID;
            }
        }

        if ($this->sendMailOutbound) {
            $mail->Success = parent::send($message);
        }
        if ($this->recordEmails) {
            $mail->write();    
        }
        return true;
    }
}
