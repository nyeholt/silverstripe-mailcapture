<?php

/**
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class MailCaptureAdmin extends ModelAdmin {
	public static $url_segment = 'mailcapture';
	public static $menu_title = 'Email Logs';

	public static $managed_models = array(
		'MassMailSend',
		'CapturedEmail',
	);
	
	public function init() {
		parent::init();
		$this->showImportForm = false;
	}
}