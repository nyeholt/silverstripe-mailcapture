<?php

/**
 * Controller for viewing a previously captured email as the client would see it
 *
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class CapturedEmailController extends Controller {
	
	public function view() {
		$id = (int) $this->getRequest()->param('ID');
		
		if ($id) {
			$email = DataObject::get_by_id('CapturedEmail', $id);
			if ($email) {
				return array('Email' => $email);
				return $this->customise()->renderWith('CapturedEmailController_view');
			}
		}
	}
}
