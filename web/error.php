<?php
// Show all errors
ini_set('display_errors',1); 
error_reporting(E_ALL);

require('inc/web.inc.php');
require('inc/smarty_vars.inc.php');

if (isset($_GET['c'])) {
	$_SESSION['c'] = $_GET['c'];
}

if (isset($_GET['403'])) {
	$er = '403';
	$desc = 'Forbidden';
} else if (isset($_GET['404'])) {
	$er = '404';
	$desc = 'File Not Found';
} else if (isset($_GET['410'])) {
	$er = '410';
	$desc = 'Gone';
} else {
	$er = '400';
	$desc = 'Bad Request';
}

$tpl_name = 'error.tpl';
$tpl = $config['cinema_dir'].'tpl/'.$tpl_name;

$smarty->setCaching(Smarty::CACHING_OFF);

// Assign page variables
$smarty->assign('id',$cinema_id);
$smarty->assign('domain',$cinema_domain);
$smarty->assign('name',$cinema_data['cinema_name']);
$smarty->assign('city',$cinema_data['city']);
$smarty->assign('tpl_name',$tpl_name);
$smarty->assign('gacode',$config['ga_code']);
$smarty->assign('er',$er);
$smarty->assign('desc',$desc);

// Common functions
include('inc/local.inc.php');

$smarty->display($tpl);

?>
