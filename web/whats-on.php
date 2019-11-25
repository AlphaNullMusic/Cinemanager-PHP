<?php
require('inc/web.inc.php');
require('inc/smarty_vars.inc.php');

$tpl_name = 'whats-on.tpl';
$tpl = $config['cinema_dir'].'tpl/'.$tpl_name;
$cache_id = 'whats-on';

if(!$smarty->isCached($tpl,$cache_id)) {
	
	// Assign page variables
	$smarty->assign('domain',$cinema_domain);
	$smarty->assign('name',$cinema_data['cinema_name']);
	$smarty->assign('city',$cinema_data['city']);
	$smarty->assign('tpl_name',$tpl_name);
	$smarty->assign('gacode',$config['ga_code']);

	// Get full movie list (type, order by, number of sessions, date format, alt date format, limit, session start, movie array, days of sessions, get session labels, medium poster)
	$smarty->assign('now_showing',get_movie_list_full('ns','m.title',14,'%W %D','%e %b',100,'today',null,null,true,'medium'));
	
	// Common functions
	include('inc/local.inc.php');
}

// Include template
$smarty->display($tpl,$cache_id);

?>
