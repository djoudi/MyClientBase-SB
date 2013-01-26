<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['location_show_fields'] = array('locDescription','locReference','locStreet','locZip','locCity','locState','locCountry','locPhone','locFax','locLatitude','locLongitude');
$config['location_attributes_aliases'] = array(
				'locDescription' => 'description',
				'locStreet' => 'address',
				'locZip' => 'zip',
				'locCity' => 'city',
				'locState' => 'state',
				'locCountry' => 'country',
				'locPhone' => 'land-line',
				'locFax' => 'fax',
				'locLatitude' => 'latitude',
				'locLongitude' => 'longitudine',
);
$config['location_hidden_fields'] = array('locId');
$config['location_never_display_fields'] = array(
		'entryCreatedBy',
		'entryCreationDate',
		'entryUpdateDate',
		'entryUpdatedBy',
		'locId',
		'objectClass',
);