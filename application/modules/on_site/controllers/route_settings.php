<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Controller for the On Site Module settings 
 * 
 * @access		public
 * @author 		Damiano Venturin
 * @since		Nov 5, 2012
 */
class Route_Settings extends Admin_Controller {
	
	public function __construct() {
	
		parent::__construct();
	
		
	
	}
	
	/**
	 * This controller method (function) is defined as calledback in the config.php and is called by MCB when the System Settings Panel is displayed.
	 * MCB provide a specific tab for the module Contact. This function is called only once, when the System Settings is loaded.
	 * After that, during the accordion operations etc, this function is no more called.
	 * The aim of the function is to get, populate and return the html of several tpl files. The html returned will populate the "setting tab"
	 *
	 * @access		public
	 * @param		none
	 * @return		nothing
	 *
	 * @author 		Damiano Venturin
	 * @since		Feb 6, 2012
	 */	
	public function display_routes()
	{
		$data = array();
		
		$this->load->model('on_site/route','route');
		
		$locations = array(
'Fenegró',
'Figino',
'Fino',
'Garzeno',
'Gera',
'Gironico',
'Grandate',
'Grandola',
'Gravedona',
'Griante',
'Guanzate',
'Inverigo',
'Laglio',
'Laino',
'Lambrugo',
'Lanzo',
'Lasnigo',
'Lenno',
'Lezzeno',
'Limido',
'Lipomo',
'Livo',
'Locate',
'Lomazzo',
'Longone',
'Luisago',
'Lurago',
'Lurago',
'Lurate',
'Magreglio',
'Mariano',
'Maslianico',
'Menaggio',
'Merone',
'Mezzegra',
'Moltrasio',
'Monguzzo',
'Montano',
'Montemezzo',
'Montorfano',
'Mozzate',
'Musso',
'Nesso',
'Novedrate',
'Olgiate',
'Oltrona',
'Orsenigo',
'Ossuccio',
'Paré',
'Peglio',
'Pellio',
'Pianello',
'Pigra',
'Plesio',
'Pognana',
'Ponna',
'Ponte',
'Porlezza',
'Proserpio',
'Pusiano',
'Ramponio',
'Rezzago',
'Rodero',
'Ronago',
'Rovellasca',
'Rovello',
'Sala',
'Saronno',
'Saronno',
'Saronno',
'Saronno',
'Saronno',
'Saronno',
'Saronno',
'Saronno',
'Saronno',
'Saronno',
'Schignano',
'Senna',
'Solbiate',
'Sorico',
'Sormano',
'Stazzona',
'Tavernerio',
'Torno',
'Tremezzo',
'Trezzone',
'Turate',
'Uggiate-Trevano',
'Val',
'Valbrona',
'Valmorea',
'Valsolda',
'Veleso',
'Veniano',
'Vercana',
'Vertemate',
'Villa',
'Zelbio',	
		);
		
		$t = 0;
		while ($t < 5) {
			$route = new Route();
			$route->city = $locations[array_rand($locations)];
			$route->route_name = 'route_' . $t;
			//$a = $route->create();
			$t++;
		}
		
		$sql= 'select id,route_name,city from routes order by route_name,city';

		$data['routes'] = $routes = $route->readAll($sql);

		$route = new Route();
		$data['buttons'][] = $route->magic_button();
				
 		$this->load->view('settings_route.tpl', $data, false, 'smarty','on_site');
	}	

}