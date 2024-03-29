<?php
// Show all errors
ini_set('display_errors',1); 
error_reporting(E_ALL);

require('inc/web.inc.php');
require('inc/smarty_vars.inc.php');

$tpl_name = 'home.tpl';
$tpl = $config['site_dir'].'tpl/'.$tpl_name;
$cache_id = 'homepage';
$cache_id .= (isset($_GET['page'])) ? '|'.preg_replace('/[^a-z]/i','',$_GET['page']) : '';

if (!$smarty->isCached($tpl,$cache_id)) {
	// Assign page variables
	$smarty->assign('domain',$cinema_domain);
	$smarty->assign('name',$cinema_data['cinema_name']);
	$smarty->assign('city',$cinema_data['city']);
	$smarty->assign('tpl_name',$tpl_name);
	$smarty->assign('gacode',$config['ga_code']);

	// Check for homepage content
	if (has_permission('edit_pages')) {
		$smarty->assign('page',get_page_content('homepage'));
	}
	
	// Get full movie list (type, order by, number of sessions, date format, alt date format, limit, session start, movie array, days of sessions, get session labels, small poster)
	$movie_list = get_movie_list_full('ns','m.title',14,'%W %D','%e %b',100,'today',null,null,false,'small');
	shuffle($movie_list);
	$smarty->assign('now_showing', $movie_list);
	
	// Common functions
	include('inc/local.inc.php');
}

$smarty->display($tpl,$cache_id);

?>
