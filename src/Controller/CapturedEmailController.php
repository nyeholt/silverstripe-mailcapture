<?php

namespace Symbiote\MailCapture\Controller;

use SilverStripe\Control\Controller;
use SilverStripe\ORM\DataList;
use SilverStripe\Security\PermissionProvider;
use Symbiote\MailCapture\Model\CapturedEmail;

/**
 * Controller for viewing a previously captured email as the client would see it
 *
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class CapturedEmailController extends Controller implements PermissionProvider {

	private static $allowed_actions = array('view' => 'CMS_ACCESS_MailCaptureAdmin');

    public function providePermissions(){
		return array(
			'CMS_ACCESS_MailCaptureAdmin' => 'View MailCapture records'
		);
	}

	public function view() {
		$id = (int) $this->getRequest()->param('ID');

		if ($id) {
			$email = CapturedEmail::get()->byID($id);
			if ($email) {
				return array('Email' => $email);
			}
		}
	}
}
