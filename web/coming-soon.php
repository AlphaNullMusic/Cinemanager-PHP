<?php
require('inc/web.inc.php');
require('inc/smarty_vars.inc.php');

$tpl_name = 'coming-soon.tpl';
$tpl = $config['site_dir'].'tpl/'.$tpl_name;
$cache_id = "coming-soon";

if(!$smarty->isCached($tpl,$cache_id)) {
	
	// Assign page variables
	$smarty->assign('domain',$cinema_domain);
	$smarty->assign('name',$cinema_data['cinema_name']);
	$smarty->assign('city',$cinema_data['city']);
	$smarty->assign('movie_image_url',$global['movie_image_url']);
	$smarty->assign('movie_image_url_secure',$global['movie_image_url_secure']);
	$smarty->assign('movie_trailer_url',$global['movie_trailer_url']);
	$smarty->assign('tpl_name',$tpl_name);
	
	// Get full movie list (type, order by, number of sessions, date format, alt date format, limit, session start, movie array, days of sessions, get session labels, large poster)
	$smarty->assign('coming_soon',get_movie_list_full('cs','tbc,m.release_date,m.title',0,'%M %e','%e %b',100,'today',null,null,false,'large'));
	
	// Check for feature content
	/*if (has_permission('edit_pages',$cinema_id)) {
		$smarty->assign('page',get_page_content('coming_soon'));
	}*/
	
	// Common functions
	include('inc/local.inc.php');
	
	// Register functions / filters
	//$smarty->registerPlugin("function", "summary", "smarty_summary");
	//$smarty->registerFilter("pre", "edit_image_path");
}
	
// Include template
$smarty->display($tpl,$cache_id);

?>
