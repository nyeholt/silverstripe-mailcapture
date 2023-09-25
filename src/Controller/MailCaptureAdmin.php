<?php

namespace Sunnysideup\MailCapture\Controller;

use SilverStripe\Admin\ModelAdmin;
use Sunnysideup\MailCapture\Form\ViewEmailButton;
use Sunnysideup\MailCapture\Model\CapturedEmail;

/**
 * @author marcus@silverstripe.com.au
 * @license BSD License http://silverstripe.org/bsd-license/
 */
class MailCaptureAdmin extends ModelAdmin
{
    private static $menu_title = 'Email Logs';
    private static $url_segment = 'emaillogs';
    private static $managed_models = array(
        CapturedEmail::class,
    );

    public function init()
    {
        parent::init();
        $this->showImportForm = false;
    }

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        if ($this->modelClass == CapturedEmail::class) {
            $grid = $form->Fields()->dataFieldByName($this->sanitiseClassName($this->modelClass));
            if ($grid) {
                $grid->getConfig()->removeComponentsByType('GridFieldEditButton');
                $grid->getConfig()->removeComponentsByType('GridFieldDeleteAction');
                $grid->getConfig()->addComponent(new ViewEmailButton());
            }
        }
        return $form;
    }

}
