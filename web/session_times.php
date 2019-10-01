<?
require('inc/web.inc.php');
require('inc/smarty_vars.inc.php');

$tpl_name = 'session_times.tpl';
$tpl = $config['site_dir'].'tpl/'.$tpl_name;
$cache_id = "session_times";

if (isset($_GET['session_label_filter'])) {
	$cache_id .= '|session_label_filter'.substr(preg_replace('/[^0-9]/i','',$_GET['session_label_filter']), 0, 10);
}
if (isset($_GET['session_comment_filter'])) {
	$cache_id .= '|session_comment_filter'.substr(preg_replace('/[^0-9]/i','',$_GET['session_comment_filter']), 0, 50);
}

if(!$smarty->isCached($tpl,$cache_id)) {
	
	// Assign page variables
	$smarty->assign('domain',$cinema_domain);
	$smarty->assign('name',$cinema_data['cinema_name']);
	$smarty->assign('city',$cinema_data['city']);
	$smarty->assign('movie_image_url',$global['movie_image_url']);
	$smarty->assign('movie_image_url_secure',$global['movie_image_url_secure']);
	$smarty->assign('movie_trailer_url',$global['movie_trailer_url']);
	$smarty->assign('tpl_name',$tpl_name);
	
	$type = 'ns';
	$limit = 100;
	$days_of_sessions = 14;
	$get_session_labels = false;
	$session_label_filter = NULL;
	$event_id = NULL;
	$session_comment_filter = NULL;
	$order = 'm.title';
	$smarty->assign('now_showing',get_movie_list_full($type,$order,$days_of_sessions,'%W %D','%e %b',$limit,'today',null,null,null,null,null,$get_session_labels,$session_label_filter,$event_id,$session_comment_filter));

	
	// Common functions
	include('inc/local.inc.php');

	// Register functions / filters
	$smarty->registerPlugin("function", "summary", "smarty_summary");
	$smarty->registerFilter("pre", "edit_image_path");
	
}

// Include template
$smarty->display($tpl,$cache_id);

?>
