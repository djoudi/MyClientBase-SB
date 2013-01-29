<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['openvpn']['tmpDir'] = '/tmp/openvpn-'.time();
$config['openvpn']['server_caKeyPath'] = '/var/www/vpn/keys/ca.key';	//no trailing slash
$config['openvpn']['server_caCrtPath'] = '/var/www/vpn/keys/ca.crt';
$config['openvpn']['server_taKeyPath'] = '/var/www/vpn/keys/ta.key';
$config['openvpn']['server_keys_directory'] = '/var/www/vpn/keys'; //no trailing slash
$config['openvpn']['privkeypass'] = null;
$config['openvpn']['numberofdays'] = 3650;
$config['openvpn']['verbose'] = false;
$config['openvpn']['save_zip_locally'] = true;
$config['openvpn']['zip_dir'] = '/var/www/vpn/zip/';
$config['openvpn']['download_zip'] = false;
$config['openvpn']['client_keys_directory'] = '/var/www/vpn/keys/';
$config['openvpn']['client_config_filename'] = 'tooljar_vpn_client';  //do not add the extension
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
