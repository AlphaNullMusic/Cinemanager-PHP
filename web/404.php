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
	$smarty->assign('movie_image_url',$global['movie_image_url']);
	$smarty->assign('movie_image_url_secure',$global['movie_image_url_secure']);
	$smarty->assign('movie_trailer_url',$global['movie_trailer_url']);
	$smarty->assign('tpl_name',$tpl_name);
	
	// Common functions
	include('inc/local.inc.php');

	// Register functions / filters
	$smarty->registerPlugin("function", "summary", "smarty_summary");
	$smarty->registerFilter("pre", "edit_image_path");

}

$smarty->display($tpl,$cache_id);

?>