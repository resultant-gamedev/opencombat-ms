<?php
define('OCMS', true);

include('config.php');
include('pages.php');
include('masterserver.php');

$ms = new OCMasterServer($config);

$do = (isset($_GET['do']) && $_GET['do'] != '') ? $_GET['do'] : 'index';

if ($do == 'index')
{
	echo $pages['index'];
	exit();
}

if ($do == 'lists')
{
	$servers = array("result" => array());
	$filter = isset($_GET['search']) ? $_GET['search'] : '';
	
	foreach ($ms->fetch_server($filter) as $item)
	{
		$servers['result'][] = array(
			'ip'		=> $item['ip'],
			'port'	=> $item['port'],
			'name'	=> $item['name']
		);
	}
	
	header('Content-Type: text/plain');
	echo json_encode($servers);
	
	exit();
}

// https://www.chriswiegman.com/2014/05/getting-correct-ip-address-php/

function get_ip() {
	//Just get the headers if we can or else use the SERVER global
	if ( function_exists( 'apache_request_headers' ) )
	{
		$headers = apache_request_headers();
	} else {
		$headers = $_SERVER;
	}
	
	//Get the forwarded IP if it exists
	if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) )
	{
		$the_ip = $headers['X-Forwarded-For'];
	}
	elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ))
	{
		$the_ip = $headers['HTTP_X_FORWARDED_FOR'];
	}
	else {
		$the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
	}
	
	return $the_ip;
}

if ($do == 'register')
{
	$ip		= get_ip();
	$port	= (isset($_GET['port'])) ? $_GET['port'] : '';
	$name	= (isset($_GET['name'])) ? urldecode($_GET['name']) : '';
	
	$ms->register_server($ip, $port, $name);
	
	exit();
}

echo $pages['404'];
exit();
?>
