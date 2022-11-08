<?php

namespace Symbiote\MailCapture\Model;

use SilverStripe\Core\Convert;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;

use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\ReadonlyField;
use Symbiote\MailCapture\Model\MassMailSend;

/**
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class CapturedEmail extends DataObject
{

    private static $table_name = 'CapturedEmail';

    private static $db = array(
        'To'              => 'Varchar(128)',
        'From'            => 'Varchar(128)',
        'Subject'         => 'Varchar(128)',
        'Headers'         => 'Text',
        'Content'         => 'Text',
        'PlainText'       => 'Text',
        'Success'         => 'Boolean',
    );

    private static $has_one = array(
        'Send'            => MassMailSend::class,
    );

    private static $field_labels = [
        'Send' => 'Mass Mail Details',
        'SendID' => 'Mass Mail Details',
    ];

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

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->replaceField(
            'Content',
            LiteralField::create(
                'ContentNice',
                '
                <div style="width: 100%; padding-bottom: 2rem;">
                    <label class="form__field-label">Email Content</label>
                    <iframe
                        style="display: block; width: 500px; margin-left: auto!important; margin-right: auto!important; height: 300px; border: 1px solid #ccc;"
                        srcdoc="'.Convert::raw2att($this->makeLinksClickable($this->Content)).'">
                    </iframe>
                </div>
                '
            )
        );
        $fields->addFieldsToTab(
            'Root.Main',
            [
                ReadonlyField::create(
                    'Created',
                    'Sent'
                )
            ]
        );
        return $fields;
    }

    public function canCreate($member = null, $context = [])
    {
        return false;
    }

    private function makeLinksClickable( string $text ) : string
    {
    	$text = preg_replace(
            '#(script|about|applet|activex|chrome):#is',
            "\\1:",
            $text
        );

    	$ret = ' ' . $text;

    	// Replace Links with http://
    	$ret = preg_replace(
            "#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is",
            "\\1<a href=\"\\2\" target=\"_blank\" rel=\"nofollow\">\\2</a>",
            $ret
        );

    	// Replace Links without http://
    	$ret = preg_replace(
            "#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is",
            "\\1<a href=\"http://\\2\" target=\"_blank\" rel=\"nofollow\">\\2</a>",
            $ret
        );

    	// Replace Email Addresses
    	$ret = preg_replace(
            "#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i",
            "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>",
            $ret
        );

    	return substr($ret, 1);

    }
    

    public function canCreate($member = null, $context = [])
    {
        return false;
    }    

}
