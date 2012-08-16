<?php

/**
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class CapturedEmail extends DataObject {
	public static $db = array(
		'To'			=> 'Varchar(128)',
		'From'			=> 'Varchar(128)',
		'Subject'		=> 'Varchar(128)',
		'Headers'		=> 'Text',
		'Content'		=> 'Text',
		'PlainText'		=> 'Text',
	);

	public static $has_one = array(
		'Send'			=> 'MassMailSend',
	);
	
	public static $summary_fields = array(
		'Created',
		'Subject',
		'To',
		'From'
	);
	public static $searchable_fields = array(
		'Subject',
		'To',
		'From'
	);
	
	public static $default_sort = 'ID DESC';
}
