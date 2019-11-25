<?php
require('inc/web.inc.php');
require('inc/smarty_vars.inc.php');

$tpl_name = 'whats-on-today.tpl';
$tpl = $config['site_dir'].'tpl/'.$tpl_name;
$cache_id = 'whats-on-today';
$cache_id .= (!empty($_GET['day'])) ? '|'.$_GET['day'] : '';
$cache_id .= (!empty($_GET['date'])) ? '|'.$_GET['date'] : '';

if(!$smarty->isCached($tpl,$cache_id)) {
	
	// Assign page variables
	$smarty->assign('domain',$cinema_domain);
	$smarty->assign('name',$cinema_data['cinema_name']);
	$smarty->assign('city',$cinema_data['city']);
	$smarty->assign('tpl_name',$tpl_name);
	$smarty->assign('gacode',$config['ga_code']);
	
	// Get session data
	$smarty->assign('sessions',get_sessions_today($cinema_id,NULL,NULL,"s.time,m.title",true,false,'large'));
	$smarty->assign('day',$get_sessions_today_day);
	$smarty->assign('date',$get_sessions_today_date);
	
	// Get full movie list (type, order by, number of sessions, date format, alt date format, limit, session start, movie array, days of sessions, get session labels, large poster)
	$smarty->assign('now_showing',get_movie_list_full('ns','m.title',14,'%W %D','%e %b',20,'today',null,null,true,'large'));
	
	// Common functions
	include('inc/local.inc.php');
}

$smarty->display($tpl,$cache_id);

?>
