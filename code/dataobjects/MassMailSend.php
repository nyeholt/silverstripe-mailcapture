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
	
	public static $default_sort = 'ID DESC';
	
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

class ViewEmailButton implements GridField_ColumnProvider {
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
	}

	public function getColumnAttributes($field, $record, $col) {
		return array('class' => 'col-buttons');
	}

	public function getColumnMetadata($gridField, $col) {
		return array('title' => null);
	}
}