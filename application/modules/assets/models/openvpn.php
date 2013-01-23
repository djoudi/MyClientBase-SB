<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Openvpn extends CI_Model {
	
	public $conf = null;
	private $caData = null;
	private $caKey = null;
	private $caCrt = null;
	
	public function __construct() {
		
		parent::__construct();
		
		//loads openvpn configuration
		$this->conf = $this->load->config('openvpn');
		
		
		//checks
		if(!$this->conf) {
			log_message('error','openvpn config file can not be read. Aborted');
			return false;
		}
		
		if(!isset($this->conf['server_caKeyPath']) || !isset($this->conf['server_caCrtPath'])) {
			log_message('error','server_caKeyPath or server_caCrtPath is not defined in config file. Aborted');
			return false;
		}
		
		if(!is_file($this->conf['server_caKeyPath'])) {
			log_message('error', $this->conf['server_caKeyPath']. ' is not a file');
			return false;
		}

		if(!is_file($this->conf['server_caCrtPath'])) {
			log_message('error', $this->conf['server_caCrtPath']. ' is not a file');
			return false;
		}
		
		
		//loads server private key
		$fp=fopen($this->conf['server_caKeyPath'],"r");
		$this->caData = fread($fp,8192);
		fclose($fp);
		$this->caKey = openssl_get_privatekey($this->caData);
		
		
		
		//loads server certificate
		$fp=fopen($this->conf['server_caCrtPath'],"r");
		$this->caCrt = fread($fp,8192);
		fclose($fp);
	}

	
	//deviceName can be considered as commonName
	public function create_certificate($deviceName = null, $password = null){
		
		//checks
		if(is_null($deviceName)) $deviceName = 'test';
		
		if(!is_string($deviceName)) return false;
		
		if(!empty($password) && !is_string($password)) return false;
		
		if(is_null($password)) $password = $this->conf['privkeypass'];
		
		
		//creates the private key for the device. Result stored in $devicePrivateKey
		$privkey = openssl_pkey_new();
		openssl_pkey_export($privkey, $devicePrivateKey, $password);
		
		
		
		//makes the certificate request for the device. Result stored in $csrStr
		$csr = openssl_csr_new($this->conf['certificate'], $devicePrivateKey);
		openssl_csr_export($csr, $csrStr);
		
		
		
		//signs the certificate request with the CA key. Result stored in $devicePublicKey
		$sscert = openssl_csr_sign($csrStr, $this->caCrt, $this->caKey, $this->conf['numberofdays']);
		openssl_x509_export($sscert, $devicePublicKey);
		
		
		return $this->write_device_certificate($deviceName, $devicePrivateKey, $devicePublicKey, $this->conf['verbose']);
		
	}
	
	
	private function write_device_certificate($deviceName, $devicePrivateKey, $devicePublicKey, $verbose = false, $create_zip = true){

		if(!is_string($deviceName) || empty($deviceName)) {
			log_message('error','The variable $deviceName is not set');
			return false;
		}
		
		if(!is_string($devicePrivateKey) || empty($devicePrivateKey)) {
			log_message('error','The variable $devicePrivateKey is not set');
			return false;
		}

		if(!is_string($devicePublicKey) || empty($devicePublicKey)) {
			log_message('error','The variable $devicePublicKey is not set');
			return false;
		}
		
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
		
		//this will stored on the server side
		if(!$this->write_file($this->conf['server_keys_directory'] . '/' . $deviceName . '.key', $devicePrivateKey)) return false;

		
		
		
		
		//writes the device certificate		
		if($verbose && !$this->conf['download_zip']) {

			echo "<br/><hr/><br/>";
			echo "writing certificate...<br/>";
			echo $devicePublicKey;
		}
	
		//this will be included in the zip file		
		if(!$this->write_file($this->conf['tmpDir'] . '/' . $deviceName.'.crt', $devicePublicKey)) return false;
		
		//this will stored on the server side
		if(!$this->write_file($this->conf['server_keys_directory'] . '/' . $deviceName . '.crt', $devicePublicKey)) return false;
		
		
		
		
		
		
		//copies the server certificate
		if(isset($this->conf['server_caCrtPath'])) {
			if(!copy($this->conf['server_caCrtPath'], $this->conf['tmpDir'].'/server_ca.crt')){
				log_message('error','The file '. $this->conf['server_caCrtPath'] . ' can not be copied to ' . $this->conf['tmpDir'].'/server_ca.crt');
				return false;
			}
		} 
				
		//copies the server ta.key
		if(isset($this->conf['server_taKeyPath']) && !empty($this->conf['server_taKeyPath'])) {

			if(!is_file($this->conf['server_taKeyPath'])) {
				log_message('error', $this->conf['server_taKeyPath']. ' is not a file');
				return false;
			}			
			
			if(!copy($this->conf['server_taKeyPath'], $this->conf['tmpDir'].'/server_ta.key')){
				log_message('error','The file '. $this->conf['server_taKeyPath'] . ' can not be copied to ' . $this->conf['tmpDir'].'/server_ta.key');
				return false;
			}		
		}			
		
		//writes the client config file
		$content = $this->conf['client_config_header'];
		$content .= 'ca /etc/openvpn/keys/server_ca.crt'."\n";
		$content .= 'cert /etc/openvpn/keys/' . $deviceName . '.crt'."\n";
		$content .= 'key /etc/openvpn/keys/' . $deviceName . '.key'."\n";
		if(isset($this->conf['server_taKeyPath']) && !empty($this->conf['server_taKeyPath'])) {
			$content .= 'tls-auth /etc/openvpn/keys/server_ta.key 1';
		}
		
		if(!$this->write_file($this->conf['tmpDir'] .'/'. $this->conf['client_config_filename'], $content)) return false;
		
		if($create_zip) {
			
			if($this->conf['save_zip_locally']){
				
				$zip_file = $this->conf['zip_dir'] . $deviceName . '.zip';
				
			} else {
				
				$zip_file = '/tmp/' . $deviceName . '.zip';
				
			}
			
			if(!$zip_file_path = $this->create_certificate_zip_file($zip_file, $deviceName)) return false;

		}		
		
		return true;
	}	
	
	
	private function write_file($filePath, $content){

		if(!file_put_contents($filePath, $content)){
			log_message('error','The file '. $filePath .' can not be written');
			return false;
		}		
		
		return true;
	}

	private function create_certificate_zip_file($zip_file, $deviceName){
		
		
		$error = false;
		
		if(!$this->load->library('zip')) $error = true;
			
		//adds files to zip archive
		$files = array(
				$this->conf['tmpDir'] . '/' . $deviceName.'.key',
				$this->conf['tmpDir'] . '/' . $deviceName.'.crt',
				$this->conf['tmpDir'] . '/server_ca.crt',
				$this->conf['tmpDir'] . '/server_ta.key',
				$this->conf['tmpDir'] . '/' . $this->conf['client_config_filename']
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
			$this->zip->download('vpn_certificate_' . $deviceName . '.zip');
		} 

		return true;
		
	}
}
