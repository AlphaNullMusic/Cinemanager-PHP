<?php
// Show all errors
ini_set('display_errors',1); 
error_reporting(E_ALL);

require('inc/web.inc.php');
require('inc/smarty_vars.inc.php');

$tpl_name = 'index.tpl';
$tpl = $config['site_dir'].'tpl/'.$tpl_name;
$cache_id = "homepage";
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
	// Default
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
		
	$type = 'ns';
	$limit = 100;
	$days_of_sessions = 14;
	$get_session_labels = false;
	$session_label_filter = NULL;
	$event_id = NULL;
	$session_comment_filter = NULL;
	$order = 'm.title';
	if (isset($_GET['session_label_filter']) && !empty($_GET['session_label_filter'])) {
		$get_session_labels = true;
		$session_label_filter = $_GET['session_label_filter'];
		$order = 'ml.priority, IF(ml.release_date = 0000-00-00, 3000-01-01, ml.release_date), m.title';
		$smarty->assign('label_info',get_label_info($_GET['session_label_filter'], $cinema_id));
	} elseif (isset($_GET['session_comment_filter']) && !empty($_GET['session_comment_filter'])) {
		$session_comment_filter = strtolower(preg_replace('/[^a-z0-9]+/i', '', $_GET['session_comment_filter']));
		$smarty->assign('session_comment_filter', $session_comment_filter);
	}
		
	$smarty->assign('now_showing',get_movie_list_full($type,$order,$days_of_sessions,'%W %D','%e %b',$limit,'today',null,null,null,null,null,$get_session_labels,$session_label_filter,$event_id,$session_comment_filter));
		
	// Common functions
	include('inc/local.inc.php');
		
	// Register functions / filters
	$smarty->registerPlugin("function", "summary", "smarty_summary");
	$smarty->registerFilter("pre", "edit_image_path");

}

$smarty->display($tpl,$cache_id);

?>