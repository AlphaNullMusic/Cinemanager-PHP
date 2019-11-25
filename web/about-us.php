<?php
require('inc/web.inc.php');
require('inc/smarty_vars.inc.php');

$tpl_name = 'about-us.tpl';
$tpl = $config['site_dir'].'tpl/generic.tpl';
$cache_id = 'about-us';

if(!$smarty->isCached($tpl,$cache_id)) {

	// Assign page variables
	$smarty->assign('domain',$cinema_domain);
	$smarty->assign('name',$cinema_data['cinema_name']);
	$smarty->assign('city',$cinema_data['city']);
	$smarty->assign('movie_image_url',$global['movie_image_url']);
	$smarty->assign('movie_image_url_secure',$global['movie_image_url_secure']);
	$smarty->assign('tpl_name',$tpl_name);
	
	if (has_permission('edit_pages')) {
		$smarty->assign('page',get_page_content('about-us'));
	}

	// Common functions
	include('inc/local.inc.php');
	
	// Register functions / filters
	$smarty->registerPlugin("function", "summary", "smarty_summary");
	$smarty->registerFilter("pre", "edit_image_path");

}

// Include template
$smarty->display($tpl,$cache_id);

?>
