<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Sparks
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		CodeIgniter Reactor Dev Team
 * @author      Kenny Katzgrau <katzgrau@gmail.com>
 * @since		CodeIgniter Version 1.0
 * @filesource
 */

/**
 * Loader Class
 *
 * Loads views and files
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @author		CodeIgniter Reactor Dev Team
 * @author      Kenny Katzgrau <katzgrau@gmail.com>
 * @category	Loader
 * @link		http://codeigniter.com/user_guide/libraries/loader.html
 */

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {
	
	private $CI;

    /**
     * Keep track of which sparks are loaded. This will come in handy for being
     *  speedy about loading files later.
     *
     * @var array
     */
    var $_ci_loaded_sparks = array();

    /**
     * Is this version less than CI 2.1.0? If so, accomodate
     * @bubbafoley's world-destroying change at: http://bit.ly/sIqR7H
     * @var bool
     */
    var $_is_lt_210 = false;

    /**
     * Constructor. Define SPARKPATH if it doesn't exist, initialize parent
     */
    function __construct()
    {
        if(!defined('SPARKPATH'))
        {
            define('SPARKPATH', 'sparks/');
        }

        $this->_is_lt_210 = (is_callable(array('CI_Loader', 'ci_autoloader'))
                               || is_callable(array('CI_Loader', '_ci_autoloader')));

        parent::__construct();
    }

    /**
     * To accomodate CI 2.1.0, we override the initialize() method instead of
     *  the ci_autoloader() method. Once sparks is integrated into CI, we
     *  can avoid the awkward version-specific logic.
     * @return Loader
     */
    function initialize()
    {
        parent::initialize();

        if(!$this->_is_lt_210)
        {
            $this->ci_autoloader();
        }

        return $this;
    }

    /**
     * Load a spark by it's path within the sparks directory defined by
     *  SPARKPATH, such as 'markdown/1.0'
     * @param string $spark The spark path withint he sparks directory
     * @param <type> $autoload An optional array of items to autoload
     *  in the format of:
     *   array (
     *     'helper' => array('somehelper')
     *   )
     * @return <type>
     */
    function spark($spark, $autoload = array())
    {
        if(is_array($spark))
        {
            foreach($spark as $s)
            {
                $this->spark($s);
            }
        }

        $spark = ltrim($spark, '/');
        $spark = rtrim($spark, '/');

        $spark_path = SPARKPATH . $spark . '/';
        $parts      = explode('/', $spark);
        $spark_slug = strtolower($parts[0]);

        # If we've already loaded this spark, bail
        if(array_key_exists($spark_slug, $this->_ci_loaded_sparks))
        {
            return true;
        }

        # Check that it exists. CI Doesn't check package existence by itself
        if(!file_exists($spark_path))
        {
            show_error("Cannot find spark path at $spark_path");
        }

        if(count($parts) == 2)
        {
            $this->_ci_loaded_sparks[$spark_slug] = $spark;
        }

        $this->add_package_path($spark_path);

        if(is_array($autoload)){
        	foreach($autoload as $type => $read)        
	        {
	            if($type == 'library')
	                $this->library($read);
	            elseif($type == 'model')
	                $this->model($read);
	            elseif($type == 'config')
	                $this->config($read);
	            elseif($type == 'helper')
	                $this->helper($read);
	            elseif($type == 'view')
	                $this->view($read);
	            else
	                show_error ("Could not autoload object of type '$type' ($read) for spark $spark");
	        }
        }

        // Looks for a spark's specific autoloader
        $this->ci_autoloader($spark_path);

        return true;
    }

	/**
	 * Pre-CI 2.0.3 method for backward compatility.
	 *
	 * @param null $basepath
	 * @return void
	 */
	function _ci_autoloader($basepath = NULL)
	{
		$this->ci_autoloader($basepath);
	}

	/**
	 * Specific Autoloader (99% ripped from the parent)
	 *
	 * The config/autoload.php file contains an array that permits sub-systems,
	 * libraries, and helpers to be loaded automatically.
	 *
	 * @param array|null $basepath
	 * @return void
	 */
	function ci_autoloader($basepath = NULL)
	{
		//Dam bug fix (pd)
		if(! defined('EXT')) define('EXT', '.php');
		
        if($basepath !== NULL)
        {
            $autoload_path = $basepath.'config/autoload'.EXT;
        }
        else
        {
            $autoload_path = APPPATH.'config/autoload'.EXT;
        }

        if(! file_exists($autoload_path))
        {
            return FALSE;
        }

		include_once($autoload_path);

		if ( ! isset($autoload))
		{
			return FALSE;
		}
		
        if($this->_is_lt_210 || $basepath !== NULL)
        {
            // Autoload packages
            if (isset($autoload['packages']))
            {
                foreach ($autoload['packages'] as $package_path)
                {
                    $this->add_package_path($package_path);
                }
            }
        }

        // Autoload sparks
		if (isset($autoload['sparks']))
		{
			foreach ($autoload['sparks'] as $spark)
			{
				$this->spark($spark);
			}
		}

        if($this->_is_lt_210 || $basepath !== NULL)
        {
            if (isset($autoload['config']))
            {
                // Load any custom config file
                if (count($autoload['config']) > 0)
                {
                    $CI =& get_instance();
                    foreach ($autoload['config'] as $key => $val)
                    {
                        $CI->config->load($val);
                    }
                }
            }

            // Autoload helpers and languages
            foreach (array('helper', 'language') as $type)
            {
                if (isset($autoload[$type]) AND count($autoload[$type]) > 0)
                {
                    $this->$type($autoload[$type]);
                }
            }

            // A little tweak to remain backward compatible
            // The $autoload['core'] item was deprecated
            if ( ! isset($autoload['libraries']) AND isset($autoload['core']))
            {
                $autoload['libraries'] = $autoload['core'];
            }

            // Load libraries
            if (isset($autoload['libraries']) AND count($autoload['libraries']) > 0)
            {
                // Load the database driver.
                if (in_array('database', $autoload['libraries']))
                {
                    $this->database();
                    $autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
                }

                // Load all other libraries
                foreach ($autoload['libraries'] as $item)
                {
                    $this->library($item);
                }
            }       
            
            // Autoload models
            if (isset($autoload['model']))
            {
                $this->model($autoload['model']);
            }
        }
	}

	public function view($view, array $vars = array(), $return = false, $template_engine = 'php', $module_path = '')
	{
		$vars = $this->add_default_vars($vars);
		
		if($template_engine == 'smarty'){
			
 			$this->load->spark('pp/0.0.1');
 			$this->load->driver('pp');
 			
			$html = $this->pp->parse($view, $vars, $return, $template_engine, $module_path);
			
			if($return) {
				return $html;
				//$strip = array("\n","\t");
				//return str_replace($strip,'',$html);
			}
			
			return;
		} 
		
		return parent::view($view, $vars, $return);
		
	}
	
	private function add_default_vars(array $vars = array()){
		
		$mcbsb_settings = $this->mcbsb->settings->get_all();
		
		//TODO maybe including all the settings is too much. We'll see
		$vars = array_merge($vars, $mcbsb_settings);
		
		$vars['mcbsb_version'] = $this->mcbsb->_version;
		$vars['environment'] = ENVIRONMENT;  //development or production
		
		$vars['language'] = $this->mcbsb->_language;
		$vars['locale'] = $this->mcbsb->_locale.'.utf-8';
		
		$vars['fcpath'] = FCPATH;
		$vars['base_url'] = base_url();
		$vars['site_url'] = site_url($this->uri->uri_string());
		
		$vars['mcbsb_org_oid'] = $this->mcbsb->get_mcbsb_org_oid();
		$vars['enabled_modules'] = $this->mcbsb->_modules['enabled'];
		$vars['top_menu'] = $this->mcbsb->_modules['top_menu'];
		$vars['system_messages'] = $this->mcbsb->system_messages->get_all();
		
		$vars['user'] = $this->mcbsb->user;

		return $vars;
	}
}