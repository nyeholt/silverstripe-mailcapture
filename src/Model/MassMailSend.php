<?php

namespace Symbiote\MailCapture\Model;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_Base;
use SilverStripe\ORM\DataObject;
use Symbiote\MailCapture\Form\ViewEmailButton;
use Symbiote\MailCapture\Model\CapturedEmail;

/**
 * A way of grouping many emails under a single banner
 *
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class MassMailSend extends DataObject
{

    private static $table_name = 'MassMailSend';

    private static $db = array(
        'Title'  => 'Varchar(128)',
    );

    private static $has_many = array(
        'Emails' => CapturedEmail::class,
    );

    private static $default_sort = 'ID DESC';

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->removeByName('Emails');
        $config = GridFieldConfig_Base::create();
        $config->addComponent(new ViewEmailButton());

        $gf = GridField::create('Emails', 'Sent emails', $this->Emails(), $config);

        $fields->addFieldToTab('Root.Main', $gf);

        return $fields;
    }
}
