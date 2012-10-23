<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

$config['product_attributes_aliases'] = array(
				'due_date' => 'Due date',
				'start_date' => 'Start date',
);

$config['product_hidden_fields'] = array('id','contact_id_key','contact_id','contact_name');

$config['product_default_values'] = array(
				//'homePhone' => '+3902',
);

$config['product_never_display_fields'] = array('creation_date', 'created_by', 'creator', 'update_date', 'updated_by', 'editor');

$config['product_mandatory_fields'] = array('product');

$config['product_prototype'] = array(
				'id' => 1,
				'product' => 'This is my text',
				'details' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?',
				'code_number' => 'sateuoc1111885200ntoa snthaotnsoaeuntshoeasn stnhoasnthu',
				'brand' => 'sateuoc1111885200ntoa snthaotnsoaeuntshoeasn stnhoasnthu',
				'model' => 'sateuoc1111885200ntoa snthaotnsoaeuntshoeasn stnhoasnthu',
				'note' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?',
				'price' => 1000000099.08,
				'months_warranty' => 'trentasei',
				'salable' => 0,
				'creation_date' => 1354128792,
				'created_by' => 12345678901,
				'creator' => 'fernando castillo de la torres',
				'update_date' => 1354128792,
				'updated_by' => 12345678901,
				'editor' => 'fernando castillo de la torres',		
/*
		$device->category = 'lavatrice';
		$device->brand = 'Electrolux';
		$device->model = 'LI1400JE';
		$device->sn = uniqid();
		$device->registration_number = uniqid();
		$device->purchase_date = date('Y-m-d');
		$device->warranty = false;
		$device->insurance = 'Sed vitae nulla lorem. Nam sollicitudin suscipit laoreet. Cras eu arcu ac risus cursus convallis sed a nisi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Cras placerat rhoncus felis, vitae congue dolor suscipit tempus. Morbi tempor leo eu justo consectetur pretium. Nulla facilisi. Fusce commodo; tellus vitae fermentum rhoncus, lacus mauris auctor sem, at vulputate metus.';
		$device->uid = '89698077';
		$device->oid = '89698077';		
*/
);

