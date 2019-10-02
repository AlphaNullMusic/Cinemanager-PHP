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
	$smarty->assign('movie_image_url',$global['movie_image_url']);
	$smarty->assign('movie_image_url_secure',$global['movie_image_url_secure']);
	$smarty->assign('movie_trailer_url',$global['movie_trailer_url']);
	$smarty->assign('tpl_name',$tpl_name);

	// Now showing and coming soon movie lists
	$now_showing_sessions=0;
	$now_showing_movies=100;
	$coming_soon_movies=50;
	$ns_date_1='%W %D';
	$ns_date_2='%e %b';
	$cs_date_1='%M %e';
	$cs_date_2='%e %b';
	$days_of_sessions = NULL;
	$get_session_labels=false;
	$coming_soon_order='m.release_date, m.title';

	// Check for homepage content
	if (has_permission('edit_pages')) {
		$smarty->assign('page',get_page_content('homepage'));
	}
	
	// Get full movie list (type, order by, number of sessions, date format, alt date format, limit, session start, movie array, days of sessions, get session labels)
	$smarty->assign('now_showing',get_movie_list_full('ns','m.title',14,'%W %D','%e %b',100,'today',null,null,false));
	
	// Common functions
	include('inc/local.inc.php');
		
	// Register functions / filters
	$smarty->registerPlugin("function", "summary", "smarty_summary");
	$smarty->registerFilter("pre", "edit_image_path");

}

$smarty->display($tpl,$cache_id);

?>