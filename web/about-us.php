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
	$smarty->assign('tpl_name',$tpl_name);
	$smarty->assign('gacode',$config['ga_code']);
	
	if (has_permission('edit_pages')) {
		$smarty->assign('page',get_page_content('about-us'));
	}

	// Common functions
	include('inc/local.inc.php');
}

// Include template
$smarty->display($tpl,$cache_id);

?>
