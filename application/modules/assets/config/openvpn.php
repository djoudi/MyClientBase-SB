<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['openvpn']['tmpDir'] = '/tmp/openvpn-'.time();
$config['openvpn']['server_caKey_path'] = '/var/www/openvpn/keys/ca.key';
$config['openvpn']['server_caCrt_path'] = '/var/www/openvpn/keys/ca.crt';
$config['openvpn']['server_taKey_path'] = '/var/www/openvpn/keys/ta.key';
$config['openvpn']['server_keys_directory'] = '/var/www/openvpn/keys';
$config['openvpn']['serial_file_path'] = '/var/www/openvpn/keys/serial';
$config['openvpn']['database_file_path'] = '/var/www/openvpn/keys/index.txt';
$config['openvpn']['privkeypass'] = null;
$config['openvpn']['numberofdays'] = 3650;
$config['openvpn']['verbose'] = false;
$config['openvpn']['save_zip_locally'] = true;
$config['openvpn']['zip_dir'] = '/var/www/openvpn/zip/';
$config['openvpn']['download_zip'] = false;
$config['openvpn']['client_keys_directory'] = '/var/www/openvpn/keys/';
$config['openvpn']['client_config_filename'] = 'ovpn_client';  //do not add the extension (like .conf or .ovpn)
$config['openvpn']['client_config_header'] = '
## tooljar vpn client

client
tls-client
pull
dev tun
proto udp
remote ovpn.tooljar.biz 1194

cipher 			BF-CBC
comp-lzo
ns-cert-type 		server
script-security		2
verb			4

';

//There is no need to set this configuration manually because real parameters are sent to the create_certificate method.
//It's important to leave this array here though, because its keys are used to validate the the configuration array sent to the method.
$config['openvpn']['certificate']['countryName'] = '';
$config['openvpn']['certificate']['stateOrProvinceName'] = '';
$config['openvpn']['certificate']['localityName'] = '';
$config['openvpn']['certificate']['organizationName'] = '';
$config['openvpn']['certificate']['organizationalUnitName'] = '';
$config['openvpn']['certificate']['commonName'] = '';
$config['openvpn']['certificate']['emailAddress'] = '';

$config['openvpn']['revoke_script'] = '/bin/whatever.sh';

