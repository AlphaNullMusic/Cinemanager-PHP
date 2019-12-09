<?php

// Server settings
session_start();
$root_dir = dirname(dirname(dirname(__FILE__))).'/';
require($root_dir."config.inc.php");
require($root_dir."functions.inc.php");
$base_url = $config['public_url'];
$base_dir = $config['public_dir'];
require("web_functions.inc.php");

// Query string varibles that affect page display (cache will be cleared when these variables are encountered)
$uncache_flags = array(
	'er',
	'ok',
	'conf',
	'confirm',
	'showpage',
	'day'
);

// Identify cinema by url unless $bypass_cid is already set (can not be parsed via get or post)
unset($_GET['bypass_cid'],$_POST['bypass_cid']);
if (!isset($bypass_cid)) {
	identify_cinema();
}

$er = false;

register_shutdown_function("fatal_handler");

function fatal_handler() {
	$error = error_get_last();
	if ($error !== NULL && $error['type'] == E_ERROR) {
		error_log("Fatal error ({$error['type']}) in {$error['file']} on line {$error['line']}: {$error['message']}",3,"web-bad-errors.log");
		exit;
	} else if ($error !== NULL && $error['type'] == E_WARNING) {
		error_log("Warning");
		exit;
	} else {
		error_log("Check");
	}
}

?>
