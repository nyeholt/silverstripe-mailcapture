<?php

/**
 * A mailer that can be used to capture emails instead of sending them out
 *
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class CaptureMailer extends Mailer {

	/**
	 * Do we capture emails in the system?
	 *
	 * @var type
	 */
	public static $capture_emails = true;

	/**
	 * Do we use the 'parent' send functionality to actually send emails out of the system?
	 *
	 * @var type
	 */
	public static $outbound_send = false;

	protected $send;

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
	function sendPlain($to, $from, $subject, $plainContent, $attachedFiles = false, $customHeaders = false) {
		if (self::$capture_emails) {
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

		if (self::$outbound_send) {
			return parent::sendPlain($to, $from, $subject, $plainContent, $attachedFiles, $customHeaders);
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
	function sendHTML(
		$to,
		$from,
		$subject,
		$htmlContent,
		$attachedFiles = false,
		$customHeaders = false,
		$plainContent = false,
		$inlineImages = false
	) {
		if (self::$capture_emails) {
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

		if (self::$outbound_send) {
			return parent::sendHTML(
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
