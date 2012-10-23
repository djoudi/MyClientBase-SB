<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class DB_Backup {

	public $CI;

	function DB_Backup() {

		$this->CI =& get_instance();

	}

	/**
	 * Returns the MCBSB database backup 
	 * 
	 * @access		public
	 * @param		array	$prefs  Array containing 'filename' (with no extension) and 'format' (ex: zip)			
	 * @return		boolean
	 * 
	 * @author 		Damiano Venturin
	 * @since		Nov 5, 2012
	 */
	function backup(array $prefs) {

		if(!isset($prefs['format']) || !isset($prefs['filename'])) return false;
		
		$this->CI->load->dbutil();
		$this->CI->load->helper('download');
		
		$download_filename = $prefs['filename'] . '.' . $prefs['format']; 
		$prefs['filename'] = $prefs['filename'] . '.sql'; 
		
		if($backup =& $this->CI->dbutil->backup($prefs)) {
			force_download($download_filename, $backup);
		} else {
			return false;
		}
	}

}

?>