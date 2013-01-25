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
$config['openvpn']['client_config_filename'] = 'tooljar_vpn_client.conf';
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

$config['openvpn']['certificate']['countryName'] = 'US';
$config['openvpn']['certificate']['stateOrProvinceName'] = 'IL';
$config['openvpn']['certificate']['localityName'] = 'Chicago';
$config['openvpn']['certificate']['organizationName'] = 'Example';
$config['openvpn']['certificate']['organizationalUnitName'] = 'RD';
$config['openvpn']['certificate']['emailAddress'] = 'user@example.com';
