<?php
require('inc/web.inc.php');
require('inc/smarty_vars.inc.php');

$tpl = $config['site_dir'].'tpl/generic.tpl';

	// Assign page variables
	$smarty->assign('domain',$cinema_domain);
	$smarty->assign('name',$cinema_data['cinema_name']);
	$smarty->assign('city',$cinema_data['city']);
	$smarty->assign('gacode',$config['ga_code']);

	if (get_page_content($_GET['page']) == null) {
		header("Location: /error.php?404");
		die();
	}

	if (has_permission('edit_pages')) {
		$smarty->assign('page',get_page_content($_GET['page']));
	}

	// Common functions
	include('inc/local.inc.php');

// Include template
$smarty->display($tpl);

?>
