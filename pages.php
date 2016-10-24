<?php
if (!defined('OCMS') || OCMS != true) { exit(); }

$pages = array(
	'index'		=> file_get_contents('pages/index.html'),
	'404'			=> file_get_contents('pages/404.html')
);

?>
