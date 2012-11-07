<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Settings extends MY_Model {

	/**
	 * Retrieves MCBSB settings stored in the mysql database 
	 * 
	 * @access		public
	 * @param		string $key Setting to retrieve			
	 * @return		boolean
	 * 
	 * @author 		Damiano Venturin
	 * @since		Nov 5, 2012
	 */
	public function get($key) {

		$this->db->select('mcb_value');

		$this->db->where('mcb_key', $key);

		$query = $this->db->get('mcb_data');

		if ($query->row()) {
			return $query->row()->mcb_value;
		} 

		return false;
	}

	/**
	 * Retrieves all the MCBSB settings stored in the database (table mcb_data)
	 * 
	 * @access		public
	 * @param		none
	 * @return		array containing all the settings stored in the database
	 * 
	 * @author 		Damiano Venturin
	 * @since		Oct 26, 2012		
	 */
	public function get_all() {
	
		$mcbsb_data = $this->db->get('mcb_data')->result();
		
		$settings = array();
		
		foreach ($mcbsb_data as $data) {
			$key = $data->mcb_key;
			$settings[$key] = $data->mcb_value;
			$this->{$data->mcb_key} = $data->mcb_value;
	
		}
		
		return $settings;
	}
	

	
	public function save($key, $value, $only_if_null = FALSE) {

		if (!is_null($this->get($key)) and !$only_if_null) {

			$this->db->where('mcb_key', $key);

			$db_array = array(
				'mcb_value'	=>	$value
			);

			$this->db->update('mcb_data', $db_array);

		}

		else {

			if ($only_if_null) {

				if (!is_null($this->get($key))) return;

			}

			$db_array = array(
				'mcb_key'	=>	$key,
				'mcb_value'	=>	$value
			);

			$this->db->insert('mcb_data', $db_array);
		}
	}

	public function delete($key) {

		$this->db->where('mcb_key', $key);

		$this->db->delete('mcb_data');

	}


    public function set_application_title() {

        $this->application_title = $this->get('application_title');

    }

	public function setting($key) {

		return (isset($this->$key)) ? $this->$key : NULL;

	}

    public function set_setting($key, $value) {

        $this->$key = $value;

    }

}

?>