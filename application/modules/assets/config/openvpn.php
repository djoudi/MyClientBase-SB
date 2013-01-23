<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['openvpn']['tmpDir'] = '/tmp/openvpn-'.time();
$config['openvpn']['server_caKeyPath'] = '/etc/openvpn/server/ca.key';	//no trailing slash
$config['openvpn']['server_caCrtPath'] = '/etc/openvpn/server/ca.crt';
$config['openvpn']['server_taKeyPath'] = '/etc/openvpn/server/ta.key';
$config['openvpn']['server_keys_directory'] = '/etc/openvpn/clients/keys'; //no trailing slash
$config['openvpn']['privkeypass'] = null;
$config['openvpn']['numberofdays'] = 3650;
$config['openvpn']['verbose'] = false;
$config['openvpn']['save_zip_locally'] = true;
$config['openvpn']['zip_dir'] = '/etc/openvpn/zip/';
$config['openvpn']['download_zip'] = false;
$config['openvpn']['client_keys_directory'] = '/etc/openvpn/keys/';
$config['openvpn']['client_config_filename'] = 'tooljar_vpn_client';
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

$config['openvpn']['certificate']['countryName'] = 'IT';
$config['openvpn']['certificate']['stateOrProvinceName'] = 'Varese';
$config['openvpn']['certificate']['localityName'] = 'Saronno';
$config['openvpn']['certificate']['organizationName'] = '2V';
$config['openvpn']['certificate']['organizationalUnitName'] = 'tooljar';
$config['openvpn']['certificate']['emailAddress'] = 'dam@tooljar.biz';