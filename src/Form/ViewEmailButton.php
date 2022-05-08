<?php

namespace Symbiote\MailCapture\Form;

use SilverStripe\Control\Controller;
use SilverStripe\Forms\GridField\GridField_ColumnProvider;
use SilverStripe\View\ArrayData;

class ViewEmailButton implements GridField_ColumnProvider
{

    public function augmentColumns($field, &$cols) {
        if(!in_array('Actions', $cols)) $cols[] = 'Actions';
    }

    public function getColumnsHandled($field) {
        return array('Actions');
    }

    public function getColumnContent($field, $record, $col) {
        if($record->canView()) {
            $data = new ArrayData(array(
                'Link' => Controller::join_links('CapturedEmailController', 'view', $record->ID)
            ));
            return $data->renderWith('ViewEmailButton');
        }
        return '';
    }

    public function getColumnAttributes($field, $record, $col) {
        return array('class' => 'col-buttons');
    }

    public function getColumnMetadata($gridField, $col) {
        return array('title' => null);
    }

}
