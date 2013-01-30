<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Object class for Code Igniter capable to create or revoke an openvpn certificate for openvpn clients
 * 
 * @author 		Damiano Venturin
 * @since		Jan 30, 2013
 */
class Openvpn extends CI_Model {
	
	public $conf = null;
	protected $caData = null;
	protected $caKey = null;
	protected $caCrt = null;
	
	public function __construct() {
		
		parent::__construct();
		
		$this->load->helper('file');
		
		//loads openvpn.php configuration file
		$this->conf = $this->load->config('openvpn');
		
	}
	
	
	

	/**
	 * Loads the openvpn configuration file and performs checks on the configuration variables
	 * 
	 * @access		private
	 * @param		none
	 * @return		boolean
	 * 
	 * @author 		Damiano Venturin
	 * @since		Jan 30, 2013
	 */
	private function load_config(){

		//checks
		if(!$this->conf) {
			log_message('error','openvpn config file can not be read. Aborted');
			return false;
		}
		
		
		//are files present and readable?
		$mandatory_config_files = array('server_caKey_path','server_caCrt_path','serial_file_path', 'database_file_path');
		
		foreach ($mandatory_config_files as $config_file) {
				
			if(!isset($this->conf[$config_file])) {
				log_message('error', $config_file . ' is not defined in config file. Aborted');
				return false;
			}
		
			if(!is_file($this->conf[$config_file]) || !is_readable($this->conf[$config_file])) {
				log_message('error', $this->conf[$config_file]. ' is not a readable file');
				return false;
			}
		}
		
		
		
		//are paths writable?
		$writable_paths = array('server_keys_directory', 'serial_file_path', 'database_file_path');
		
		foreach ($writable_paths as $writable_path) {
				
			if(!is_writable($this->conf[$writable_path])) {
				log_message('error', $this->conf[$writable_path]. ' is not writable');
				return false;
			}
				
		}
		
		
		//loads server private key
		$fp=fopen($this->conf['server_caKey_path'],"r");
		$this->caData = fread($fp,8192);
		fclose($fp);
		$this->caKey = openssl_get_privatekey($this->caData);
		
		
		
		//loads server certificate
		$fp=fopen($this->conf['server_caCrt_path'],"r");
		$this->caCrt = fread($fp,8192);
		fclose($fp);

		return true;
		
	}
	
	
	
	
	/**
	 * Creates an OpenVPN certificate and writes it on disk calling the write_certificate method
	 * 
	 * @access		public
	 * @param		string $deviceName	Name of the device for which the certificate is generated. It will be used as "CommonName" in the certificate creation
	 * @param		string $password	Password for the certificate
	 * @param		array  $certificate_params Array overwriting the $config['openvpn']['certificate'] array set in the config file
	 * @return		boolean
	 * 
	 * @author 		Damiano Venturin
	 * @since		Jan 30, 2013
	 */
	public function create_certificate($deviceName, $password = null, array $certificate_params){
		
		//checks
		if(!$this->load_config()) return false;
		if(!is_string($deviceName) || empty($deviceName)) return false;
		
		if(!empty($password) && !is_string($password)) return false;
		if(!is_null($password) && !is_string($password)) return false;
		if(!is_array($certificate_params)) return false;
		
		
		foreach ($this->conf['certificate'] as $key => $value){
			
			//checks that the $certificate_params array has the same keys set in the configuration file
			//and sets the given value in the $this->conf attribute
			if(isset($certificate_params[$key]) && !empty($certificate_params[$key])){
				$this->conf['certificate'][$key] = $certificate_params[$key];
			} else {
				return false;
			}
			
		}
	
		
		//creates the private key for the device. Result stored in $devicePrivateKey
		$privkey = openssl_pkey_new();
		if(is_resource($privkey)){
			openssl_pkey_export($privkey, $devicePrivateKey,$password);
		} else {
			return false;
		}
		
		
		
		//makes the certificate request for the device. Result stored in $csrStr
		$csr = openssl_csr_new($this->conf['certificate'], $devicePrivateKey);
		if(is_resource($csr)) {
			openssl_csr_export($csr, $csrStr,false);
		} else {
			return false;
		}
		
		
		
		//signs the certificate request with the CA key. Result stored in $devicePublicKey
		$configargs = array(
				//'config' => '/etc/ssl/openssl.cnf',
				'dir' => '/etc/openvpn',
				'certs' => '/etc/openvpn/keys',
				'crl_dir' => '/etc/openvpn/keys',
				'database' => '/etc/openvpn/keys/index.txt',
				'new_certs_dir' => '/etc/openvpn/keys',
				'certificate' => '/etc/openvpn/keys/ca.crt',
				'serial' => '/etc/openvpn/keys/serial',
				'crl' => '/etc/openvpn/keys/crl.pem',
				'private_key' => '/etc/openvpn/keys/ca.key',
				'RANDFILE' => '/etc/openvpn/keys/.rand',
				'x509_extensions' => 'usr_cert',
		);
		
		$configargs = array();
		
		$serial = $this->format_serial(read_file($this->conf['serial_file_path']),'read');
				
		$sscert = openssl_csr_sign($csrStr, $this->caCrt, $this->caKey, $this->conf['numberofdays'],$configargs,$serial);
		if(is_resource($sscert)) {
			openssl_x509_export($sscert, $devicePublicKey,false);
		} else {
			return false;
		}
		
		
		return $this->write_certificate($deviceName, $devicePrivateKey, $devicePublicKey, $this->format_serial($serial, 'write'), $this->conf['verbose']);
		
	}
	
	
	
	
	/**
	 * The serial is a progressive ID number for the certificate. It is saved in the $config['openvpn']['serial_file_path'] file.
	 * For ID numbers between 1 and 9 OpenVPN adds a 0 in front => 01, 02, 03 ...
	 * Given the serial read from the $config['openvpn']['serial_file_path'] file, this method returns always an integer when $mode is 'read'.
	 * On $mode == 'write' it returns the correct value to store in the $config['openvpn']['serial_file_path'] file. (01, 02, 03, ... , 11, 12)
	 * 
	 * @access		private
	 * @param		string $serial	
	 * @return		
	 * 
	 * @author 		Damiano Venturin
	 * @since		Jan 30, 2013
	 */
	private function format_serial($serial, $mode){
		
		if((!is_string($serial) && !is_integer($serial) ) || empty($serial)) return false;
		if(!is_string($mode) || empty($mode)) return false;
		if($mode != 'read' && $mode != 'write') return false;
		
		if(strlen($serial) > 2){
			return (int) $serial;
		}
		
		if($mode == 'read'){
			return (int) $serial;
 		}
 		
 		if($mode == 'write'){
 			$serial = (int) $serial;
 			
 			if(strlen($serial) == 1) {
 				//adds a 0 in front of [1-9] to obtain 01, 02 ..
 				return '0'.$serial;
 			} 
 			
 			return $serial;
 		} 		
	}
	
	
	
	/**
	 * Writes in the temporary directory $config['openvpn']['tmpDir']: 
	 * - the client private and public key
	 * - the server certificate
	 * - the server ta.key if ssl is used
	 * - the openvpn client configuration file for linux
	 * - the openvpn client configuration file for windows
	 * It can also make a zip package of the files listed above
	 * 
	 * It also writes in the OpenVpn server directory $config['openvpn']['server_keys_directory'] the following files
	 * - the client private and public key
	 * - the $serial.pem file (copy of the client public key)
	 * - updates the OpenVpn database file $config['openvpn']['database_file_path'] with the created certificates
	 * - updates $config['openvpn']['serial_file_path'] with the next $serial 
	 * 
	 * @access		private
	 * @param		string $deviceName			Name of the device for which the certificate is generated. It will be used as "CommonName" in the certificate creation
	 * @param		string $devicePrivateKey	Content of the newly created private key
	 * @param		string $devicePublicKey		Content of the newly created public key
	 * @param		string $serial				Serial number as string ($mode = 'write')
	 * @param		boolean $verbose			For debug purposes
	 * @param		boolean $create_zip			If true creates a zip package which can also be automatically downloaded by setting $config['openvpn']['download_zip'] to TRUE
	 * @return		boolean
	 * 
	 * @author 		Damiano Venturin
	 * @since		Jan 30, 2013
	 */
	private function write_certificate($deviceName, $devicePrivateKey, $devicePublicKey, $serial, $verbose = false, $create_zip = true){

		//checks
		$args = array('deviceName', 'devicePrivateKey', 'devicePublicKey', 'serial');
		
		foreach ($args as $arg) {
			
			if(!is_string($$arg) || empty($$arg)) {
				log_message('error','The variable $' . $arg . ' is not set');
				return false;
			}
			
		}
		
		if(!is_bool($verbose)) {
			log_message('error','The variable '. $verbose . ' is not boolean');
			return false;
		}
				
		if(!is_bool($create_zip)) {
			log_message('error','The variable '. $verbose . ' is not boolean');
			return false;
		}
		
		
		
		
		
		
		//=================== TEMPORARY FOLDER ======================
		
		
		//creates a tmp dir
		if(!mkdir($this->conf['tmpDir'])){
			log_message('error','The directory '.$this->conf['tmpDir'].' can not be created');
			return false;
		}
		
		
		
		//writes the device private key
		if($verbose && !$this->conf['download_zip']) {
			echo "writing private key...<br/><br/>";
			echo $devicePrivateKey;
		}
		//this will be included in the zip file
		if(!$this->write_file($this->conf['tmpDir'] . '/' . $deviceName.'.key', $devicePrivateKey)) return false;
		
		
	
		
		
		//writes the device certificate		
		if($verbose && !$this->conf['download_zip']) {
			echo "<br/><hr/><br/>";
			echo "writing certificate...<br/>";
			echo $devicePublicKey;
		}
	
		//this will be included in the zip file		
		if(!$this->write_file($this->conf['tmpDir'] . '/' . $deviceName.'.crt', $devicePublicKey)) return false;
		
		
		
		//copies the server certificate
		if(isset($this->conf['server_caCrt_path'])) {
			if(!copy($this->conf['server_caCrt_path'], $this->conf['tmpDir'].'/server_ca.crt')){
				log_message('error','The file '. $this->conf['server_caCrt_path'] . ' can not be copied to ' . $this->conf['tmpDir'].'/server_ca.crt');
				return false;
			}
		} 
				
		
		
		
		//copies the server ta.key
		if(isset($this->conf['server_taKey_path']) && !empty($this->conf['server_taKey_path'])) {

			if(!is_file($this->conf['server_taKey_path'])) {
				log_message('error', $this->conf['server_taKey_path']. ' is not a file');
				return false;
			}			
			
			if(!copy($this->conf['server_taKey_path'], $this->conf['tmpDir'].'/server_ta.key')){
				log_message('error','The file '. $this->conf['server_taKey_path'] . ' can not be copied to ' . $this->conf['tmpDir'].'/server_ta.key');
				return false;
			}		
		}			
		
		
		
		
		
		//writes the openvpn client config file
		$content = $this->conf['client_config_header'];
		$content .= 'ca server_ca.crt'."\n";
		$content .= 'cert ' . $deviceName . '.crt'."\n";
		$content .= 'key ' . $deviceName . '.key'."\n";
		if(isset($this->conf['server_taKey_path']) && !empty($this->conf['server_taKey_path'])) {
			$content .= 'tls-auth server_ta.key 1';
		}
		
		//config file for linux
		$config_file_lin = $this->conf['client_config_filename'].'_lin.conf';
		if(!$this->write_file($this->conf['tmpDir'] .'/'. $config_file_lin, $content)) return false;
		
		//config file for windows
		$config_file_win = $this->conf['client_config_filename'].'_win.ovpn';
		if(!$this->write_file($this->conf['tmpDir'] .'/'. $config_file_win, $content)) return false;
		
		
		
		
		
		
		
		//=================== SERVER FOLDER ======================
		
		if(!ends_with($this->conf['server_keys_directory'], '/')) $this->conf['server_keys_directory'] = $this->conf['server_keys_directory'] . '/';
		
		//this will stored on the server side
		if(!$this->write_file($this->conf['server_keys_directory'] . $deviceName . '.key', $devicePrivateKey)) return false;
		
		//this will stored on the server side
		if(!$this->write_file($this->conf['server_keys_directory'] . $deviceName . '.crt', $devicePublicKey)) return false;
		
		//serial.pem
		if(!$this->write_file($this->conf['server_keys_directory'] . $serial . '.pem', $devicePublicKey)) return false;
		
		//updates openvpn datababse file
		if(!$this->update_db($devicePublicKey, $serial)) return false;
		
		//updates the serial file
		$next_serial = $this->format_serial(($this->format_serial($serial, 'read') + 1),'write');
		if(!$this->write_file($this->conf['serial_file_path'], $next_serial)) return false;
		
		
		
		
		
		
		
		//=================== ZIP PACKAGE ======================
		
		if($create_zip) {
			
			if($this->conf['save_zip_locally']){
				
				if(!ends_with($this->conf['zip_dir'], '/')) $this->conf['zip_dir'] . '/';
				 
				$zip_file = $this->conf['zip_dir'] . $deviceName . '.zip';
				
			} else {
				
				$pieces = explode('/', $this->conf['tmpDir']);
				
				unset($pieces[count($pieces) - 1]);
				
				$parent_folder = implode('/', $pieces);
				
				if(!ends_with($parent_folder, '/')) $parent_folder . '/';
				$zip_file = $parent_folder . $deviceName . '.zip';
				
			}
			
			if(!$zip_file_path = $this->create_certificate_zip_file($zip_file, $deviceName)) return false;

		}		
		
		return true;
	}	
	
	
	
	/**
	 * Updates the OpenVpn database file $config['openvpn']['database_file_path'] with the newly created certificate
	 * 
	 * @access		private
	 * @param		string $devicePublicKey		Content of the newly created public key
	 * @param		string $serial				Serial number as string ($mode = 'write')
	 * @return		boolean
	 * 
	 * @author 		Damiano Venturin
	 * @since		Jan 30, 2013
	 */
	private function update_db($devicePublicKey,$serial){
		
		if(!is_string($devicePublicKey) || empty($devicePublicKey)) return false;
		if(!is_string($serial) || empty($serial)) return false;
		
		//reads the expiration date from the header of the public key: ex. Jan 28 16:40:02 2023
		$not_after = trim(trim(strstr(strstr(strstr($devicePublicKey, 'Not After :'),'GMT',true),':'),':'));
		
		//formats $not_after in ASN1_TIME format: ex "Jan 28 16:40:02 2023" becomes 230128164002Z
		$not_after = explode(' ', $not_after);
		
		if(count($not_after) != 4) {
			log_message('error', 'Wrong expiration date format in public certificate.');
			return false;	
		}
		
		$ed = array();
		$ed['month'] = strtolower($not_after[0]);
		$ed['day'] = $not_after[1];
		$ed['time'] = $not_after[2];
		$ed['year'] = $not_after[3];
		
		$months = array(
				'jan' => '01',
				'feb' => '02',
				'mar' => '03',
				'apr' => '04',
				'may' => '05',
				'jun' => '06',
				'jul' => '07',
				'aug' => '08',
				'sep' => '09',
				'oct' => '10',
				'nov' => '11',
				'dec' => '12',
		);
		
		$expiration_date = substr($ed['year'],2,3) . $months[$ed['month']] . $ed['day'] . str_replace(":", "",$ed['time']) . 'Z';
		
		
		//reads the certificate DN from the header of the public key and formats it for the database file
		$subject = "/" . str_replace(",","/",str_replace(":","", str_replace(" ", "", strstr(strstr(strstr($devicePublicKey, 'Subject'),"\n",true),": "))));
		
		//updates the database file
		$db_content = read_file($this->conf['database_file_path']);
		$record = "V\t" . $expiration_date . "\t\t" . $serial . "\tunknown\t" .$subject. "\n";
		$content = $db_content . $record;	
		
		if(!$this->write_file($this->conf['database_file_path'], $content)) return false;
		
		return true;	
	}
	
	
	/**
	 * Writes content on file and logs errors
	 * 
	 * @access		private
	 * @param		string	$filePath
	 * @param		string 	$content
	 * @return		boolean
	 * 
	 * @author 		Damiano Venturin
	 * @since		Jan 31, 2013
	 */
	private function write_file($filePath, $content){

		if(!write_file($filePath, $content)){
			log_message('error','The file '. $filePath .' can not be written');
			return false;
		}		
		
		return true;
	}

	
	
	/**
	 * Creates the zip package containing the certificate and the required files for the OpenVpn client
	 * 
	 * @access		private
	 * @param		string 	$zip_file		Absolute path of the zip file
	 * @param		string $deviceName		Name of the device for which the certificate is generated. It will be used as "CommonName" in the certificate creation
	 * @return		boolean
	 * 
	 * @author 		Damiano Venturin
	 * @since		Jan 31, 2013
	 */
	private function create_certificate_zip_file($zip_file, $deviceName){
		
		if(!is_string($zip_file) || empty($zip_file)) return false;
		if(!is_string($deviceName) || empty($deviceName)) return false;
		
		$error = false;
		
		if(!$this->load->library('zip')) $error = true;
			
		//adds files to zip archive
		$config_file_lin = $this->conf['client_config_filename'].'_lin.conf';
		$config_file_win = $this->conf['client_config_filename'].'_win.ovpn';
		$files = array(
				$this->conf['tmpDir'] . '/' . $deviceName.'.key',
				$this->conf['tmpDir'] . '/' . $deviceName.'.crt',
				$this->conf['tmpDir'] . '/server_ca.crt',
				$this->conf['tmpDir'] . '/server_ta.key',
				$this->conf['tmpDir'] . '/' . $config_file_lin,
				$this->conf['tmpDir'] . '/' . $config_file_win,
		);
			
		foreach ($files as $file) {
			if(!$this->zip->read_file($file)) $error = true;
		}
		
			
		//writes zip archive
		if(!$this->zip->archive($zip_file)) $error = true;
			
		//deletes original files	
		remove_dir($this->conf['tmpDir']);
		
		if($error) {
			log_message('error','Zip file can be created');
			return false;	
		}
			
		if($this->conf['download_zip']){
			//CI will modify the header and push it to the browser so there won't be any return: the script stops here. 
			$this->zip->download('vpn_certificate_' . $deviceName . '.zip');
		} 

		return true;
		
	}
	
	/**
	 * Revokes a given certificate using the system script defined in $config['openvpn']['revoke_script']  
	 * 
	 * @access		public
	 * @param		string $deviceName		Name of the device for which the certificate is generated
	 * @return		boolean
	 * 
	 * @author 		Damiano Venturin
	 * @since		Jan 31, 2013
	 * 
	 * @todo  Maybe I can avoid the system script.
	 */
	public function revoke_certificate($deviceName) {
	
		if(!$this->load_config()) return false;
		if(!is_string($deviceName) || empty($deviceName)) return false;
	
		$script = $this->conf['revoke_script'];
	
		if(!is_file($script) || !is_executable($script)) {
			log_message('error', 'The script: ' . $script . ' does not exist or is not executable');
			return false;
		}
	
		$command = $script . ' ' . $deviceName . ' mute';
		system($command,$return);
		return $return === 0 ? true : false;
	
	}	
}
