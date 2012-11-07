<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['organization_show_fields'] = array('o','oType','description','category','businessCategory','businessActivity','businessAudience','vatNumber','codiceFiscale','street','postalCode','l','st','c','postOfficeBox','physicalDeliveryOfficeName','preferredDeliveryMethod','oMobile','telephoneNumber','facsimileTelephoneNumber','oURL','omail','enabled');
$config['organization_attributes_aliases'] = array(
				'o' => 'organization',
				'oType' => 'organization_type',
				'description' => 'description',
				'businessCategory' => 'business_category',
				'businessActivity' => 'activity',
				'businessAudience' => 'client_base',
				'vatNumber' => 'vat_number',
				'codiceFiscale' => 'codice_fiscale',
				'street' => 'address',
				'postalCode' => 'zip',
				'l' => 'city',
				'st' => 'state_-_province',
				'c' => 'country',
				'postOfficeBox' => 'po-box',
				'physicalDeliveryOfficeName' => 'delivery_office',
				'oMobile' => 'mobile',
				'telephoneNumber' => 'telephone',
				'facsimileTelephoneNumber' => 'fax',
				'oURL' => 'website',
				'omail' => 'e-mail',
);
$config['organization_hidden_fields'] = array('oid');
$config['organization_default_values'] = array(
				'telephoneNumber' => '+39031',
				'st' => 'Como',
				'facsimileTelephoneNumber' => '+39031',
				'c' => 'Italia',
				'category' => 'company',
				'enabled' => 'TRUE',
				'entryCreatedBy' => 'unknown',
				'o' => 'unknown',
				'oid' => '',
);
