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
	public $captureEmails = true;

	/**
	 * Mailer or a sub-class of Mailer that can be used for sending the captured emails
	 *
	 * @var Object
	 */
	public $outboundMailer;

	protected $send;

	/**
	 * Legacy fields we check on in __constructor and throw an exception if they are set to a value other than null
	 *
	 * @var null
	 */
	private static $capture_emails;
	private static $outbound_send;

	public function __construct() {
		if (self::$outbound_send !== null || self::$capture_emails !== null) {
			user_error('Mailcapture no longer uses private statics for config, please remove any settings of '
				. 'private static $capture_emails and private static $outbound_send. Check the read for new yml based '
				. 'config');
		}

		parent::__construct();
	}

	public function setMassMailSend($item) {
		$this->send = $item;
	}

	/**
	 * @param string $to Email recipient
	 * @param string $from Email from
	 * @param string $subject Subject text
	 * @param string $plainContent Plain text content
	 * @param array $attachedFiles List of attached files
	 * @param array $customHeaders List of custom headers
	 * @return mixed Return false if failure, or list of arguments if success
	 */
	public function sendPlain($to, $from, $subject, $plainContent, $attachedFiles = false, $customHeaders = false) {
		if ($this->captureEmails) {
			$mail = new CapturedEmail();
			$mail->To = $to;
			$mail->From = $from;
			$mail->Subject = $subject;

			if (is_array($customHeaders)) {
				foreach ($customHeaders as $header => $val) {
					$mail->Headers .= "$header: $val \n";
				}
			}

			$mail->PlainText = $plainContent;

			if ($this->send) {
				$mail->SendID = $this->send->ID;
			}

			$mail->write();
		}

		if ($this->outboundMailer) {
			return $this->outboundMailer->sendPlain(
				$to,
				$from,
				$subject,
				$plainContent,
				$attachedFiles,
				$customHeaders
			);
		}

		return true;
	}

	/**
	 * Send a multi-part HTML email
	 * TestMailer will merely record that the email was asked to be sent, without sending anything.
	 *
	 * @param string $to Email recipient
	 * @param string $from Email from
	 * @param string $subject Subject text
	 * @param string $htmlContent HTML Content
	 * @param array $attachedFiles List of attachments
	 * @param array $customHeaders User specified headers
	 * @param string $plainContent Plain text content. If omitted, will be generated from $htmlContent
	 * @return mixed Return false if failure, or list of arguments if success
	 */
	public function sendHTML(
		$to,
		$from,
		$subject,
		$htmlContent,
		$attachedFiles = false,
		$customHeaders = false,
		$plainContent = false,
		$inlineImages = false
	) {
		if ($this->captureEmails) {
			$mail = new CapturedEmail();
			$mail->To = $to;
			$mail->From = $from;
			$mail->Subject = $subject;

			if (is_array($customHeaders)) {
				foreach ($customHeaders as $header => $val) {
					$mail->Headers .= "$header: $val \n";
				}
			}

			$mail->Content = $htmlContent;
			$mail->PlainText = $plainContent;

			if ($this->send) {
				$mail->SendID = $this->send->ID;
			}

			$mail->write();
		}

		if ($this->outboundMailer) {
			return $this->outboundMailer->sendHTML(
				$to,
				$from,
				$subject,
				$htmlContent,
				$attachedFiles,
				$customHeaders,
				$plainContent,
				$inlineImages
			);
		}

		return true;
	}
}
