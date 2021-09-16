<?php
require('inc/web.inc.php');
require('inc/smarty_vars.inc.php');

$tpl = $config['site_dir'].'tpl/generic.tpl';

	// Assign page variables
	$smarty->assign('domain',$cinema_domain);
	$smarty->assign('name',$cinema_data['cinema_name']);
	$smarty->assign('city',$cinema_data['city']);
	$smarty->assign('gacode',$config['ga_code']);

	$page_content = get_page_content($_GET['page']);

	if (has_permission('edit_pages') && $page_content != null) {
		$page_content = get_page_content($_GET['page']);
		$smarty->assign('page', $page_content);
		$smarty->assign('page_title', $page_content['title']);
	} else {
		header("Location: /error.php?404");
                die();
	}

	// Common functions
	include('inc/local.inc.php');

// Include template
$smarty->display($tpl);

?>
