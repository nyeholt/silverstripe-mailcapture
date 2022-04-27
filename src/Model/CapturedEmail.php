<?php

namespace Symbiote\MailCapture\Model;

use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;
use Symbiote\MailCapture\Model\MassMailSend;

/**
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class CapturedEmail extends DataObject {

    private static $table_name = 'CapturedEmail';

	private static $db = array(
		'To'			=> 'Varchar(128)',
		'From'			=> 'Varchar(128)',
		'Subject'		=> 'Varchar(128)',
		'Headers'		=> 'Text',
		'Content'		=> 'Text',
		'PlainText'		=> 'Text',
        'Success'       => 'Boolean',
	);

	private static $has_one = array(
		'Send'			=> MassMailSend::class,
	);

	private static $summary_fields = array(
		'Created',
		'Subject',
		'To',
		'From'
	);
	private static $searchable_fields = array(
		'Subject',
		'To',
		'From'
	);

	private static $default_sort = 'ID DESC';

    public function canView($member = null)
    {
        if (!$member || !($member instanceof Member) || is_numeric($member)) {
            $member = Member::currentUser();
        }
        if ($member && Permission::checkMember($member, array("ADMIN", "CMS_ACCESS_MailCaptureAdmin"))) {
            return true;
        }

        return parent::canView($member);
    }

    public function canEdit($member = null)
    {
        return false;
    }

    public function canDelete($member = null)
    {
        return false;
    }
}
