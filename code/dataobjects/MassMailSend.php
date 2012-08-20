<?php

/**
 * A way of grouping many emails under a single banner
 *
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class MassMailSend extends DataObject {
	public static $db = array(
		'Title'		=> 'Varchar(128)',
	);
	
	public static $has_many = array(
		'Emails'		=> 'CapturedEmail',
	);
	
	public static $searchable_fields = array(
		'Title'
	);
	
	public static $default_sort = 'ID DESC';
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();

		
		return $fields;
	}
}