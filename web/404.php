<?php
// Show all errors
ini_set('display_errors',1); 
error_reporting(E_ALL);

require('inc/web.inc.php');
require('inc/smarty_vars.inc.php');

if (isset($_GET['c'])) {
	$_SESSION['c'] = $_GET['c'];
}

$tpl_name = '404.tpl';
$tpl = $global['cinema_dir'].'tpl/'.$tpl_name;
$cache_id = '404';
$cache_id .= (isset($_GET['alternate'])) ? '|alternate' : '' ;
$cache_id .= (isset($_GET['page'])) ? '|'.preg_replace('/[^a-z]/i','',$_GET['page']) : '';

if (!$smarty->isCached($tpl,$cache_id)) {

	// Assign page variables
	$smarty->assign('id',$cinema_id);
	$smarty->assign('domain',$cinema_domain);
	$smarty->assign('name',$cinema_data['cinema_name']);
	$smarty->assign('city',$cinema_data['city']);
	$smarty->assign('tpl_name',$tpl_name);
	$smarty->assign('gacode',$config['ga_code']);
	
	// Common functions
	include('inc/local.inc.php');
}

$smarty->display($tpl,$cache_id);

?>
