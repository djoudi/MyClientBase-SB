<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_MCB_Modules extends MY_Model {

	//ALTER TABLE `mcb_modules` ADD `module_change_status` INT( 1 ) NOT NULL DEFAULT '1' AFTER `module_core` 
	
    public $core_modules = array();

    public $custom_modules = array();
    
    public $enabled_modules = array(
    									'all' => array(),
    									'core' => array(),
    									'custom' => array());

    public function __construct() {

        parent::__construct();

        /* Define table name */
        //$this->table_name = 'mcb_modules';

        /* Define primary key */
        //$this->primary_key = 'mcb_modules.module_id';

        /* Define default order by */
        //$this->order_by = 'mcb_modules.module_order, mcb_modules.module_path';

    }

/*     public function refresh() {

        $this->core_modules = array();

        $this->custom_modules = array();

        // Refresh database core module records 
        $this->refresh_core();

        // Refresh database custom module records 
        $this->refresh_custom();

        // Refresh module variables 
        $this->set_module_data();

    } */

/*     public function get_enabled(){
    	$this->refresh();
    	
    	foreach ($this->core_modules as $module) {
    		if($module->module_enabled) {
    			$this->enabled_modules['all'][] = $module->module_name;
    			$this->enabled_modules['core'][] = $module->module_name;
    		}
    	}
    	
    	foreach ($this->custom_modules as $module) {
    		if($module->module_enabled) {
    			$this->enabled_modules['all'][] = $module->module_name;
    			$this->enabled_modules['custom'][] = $module->module_name;
    		}
    	}
    	    	
    	return $this->enabled_modules;
    	
    } */
    

    /**
     * This method parses the config file of every custom modules and refreshes the database module records
     *
     * @access		private
     * @param		nothing
     * @var
     * @return		nothing
     * @example
     * @see
     *
     * @author 		Damiano Venturin
     * @copyright 	2V S.r.l.
     * @license		GPL
     * @link		http://www.squadrainformatica.com/en/development#mcbsb  MCB-SB official page
     * @since		Jun 21, 2012
     *
     * @todo refactor this method and refresh_core: they have duplicated code
     */
    private function refresh_custom_delme() {

        $this->load->helper('directory');

        /* Gather list of directories inside modules_custom */
        $custom_modules = directory_map(APPPATH . 'modules_custom/', TRUE);

        foreach ($custom_modules as $custom_module) {

            /* Use $where_not_in to delete orphaned module records in db */
            $where_not_in[] = $custom_module;

            /* This should be the location of the module's config file */
            $config_file = APPPATH . 'modules_custom/' . $custom_module . '/config/config.php';

            /* If the config file exists, insert or update the db record */
            if (file_exists($config_file)) {

                include($config_file);

                /*
				 * Custom module config required:
				 *		module_path			string
				 *		module_name			string
				 *		module_description	string
				 *		module_author		string
				 *		module_homepage		string
				 *		module_version		string
				 *
				 * Custom module config optional:
				 *		module_config		array
				 *
                */

                $db_array = array(
                    'module_core'				=>	0,
                    'module_path'				=>	$config['module_path'],
                    'module_name'				=>	$config['module_name'],
                    'module_description'		=>	$config['module_description'],
                    'module_author'				=>	$config['module_author'],
                    'module_homepage'			=>	$config['module_homepage'],
                    'module_available_version'	=>	$config['module_version']
                );

                /* Check for the optional module_config and serialize if exists*/
                if (isset($config['module_config'])) {

                    $db_array['module_config'] = serialize($config['module_config']);

                }

                $this->db->where('module_path', $config['module_path']);

                $this->db->where('module_core', 0);

                $query = $this->db->get('mcb_modules');

                if ($query->num_rows()) {

                    /* The db record exists, so update */
                    $result = $query->row();

                    if (!$result->module_enabled) {

                        $db_array['module_version'] = $config['module_version'];

                    }

                    $this->db->where('module_path', $config['module_path']);

                    $this->db->where('module_core', 0);

                    $this->db->update('mcb_modules', $db_array);

                }

                else {

                    /* The db record does not exist, so insert */
                    $db_array['module_version'] = $config['module_version'];

                    $this->db->insert('mcb_modules', $db_array);

                }

            }

        }

        /* Delete any orphaned custom module records from database */
        if (isset($where_not_in)) {

            $this->db->where_not_in('module_path', $where_not_in);

            $this->db->where('module_core', 0);

            $this->db->delete('mcb_modules');

        }

    }

    /* Set variables accessible through this class */
    /*    
    public function set_module_data_delme() {

         $modules = parent::get();

        $this->num_custom_modules_enabled = 0;

        foreach ($modules as $module) {

            if ($module->module_core == 1) {

                // Assign to core_modules array 
                $this->core_modules[$module->module_path] = $module;

            }

            else {

                // Assign to custom modules array 
                $this->custom_modules[$module->module_path] = $module;

                if ($module->module_enabled) {

                    $this->num_custom_modules_enabled++;

                }

            }

        }

        foreach ($this->core_modules as $core_module) {

            // Unserialize the core module_config arrays 
            $core_module->module_config = unserialize($core_module->module_config);

        }

        foreach ($this->custom_modules as $custom_module) {

            // Unserialize the custom module_config arrays 
            if ($custom_module->module_config) {

                $custom_module->module_config = unserialize($custom_module->module_config);

            }

        }

    }
    
 */    
    
/* 
    public function check_enable($module_path) {
    	// Is the $module_path module enabled?
    	
    	if(isset($this->custom_modules[$module_path]) and $this->custom_modules[$module_path]->module_enabled) return true;
    	
    	if(isset($this->core_modules[$module_path]) and $this->core_modules[$module_path]->module_enabled) return true;
    	
    	return false;
    }
 */
 


/*     public function module_upgrade_notice() {

        $module_upgrade_notice = FALSE;

        foreach ($this->mdl_mcb_modules->custom_modules as $module) {

            if ($module->module_version < $module->module_available_version) {

                $module_upgrade_notice = $this->lang->line('module_upgrade_available');

            }

        }

        return $module_upgrade_notice;

    } */

}

?>