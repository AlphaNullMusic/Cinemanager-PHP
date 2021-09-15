<?php

//
// Common Smarty assignments which should be executed on all pages
//

if (has_permission('edit_pages', $cinema_id)) {
	$smarty->assign('page_global', get_page_content('global'));
	if ($_GET['page']) {
		$smarty->assign('current_page', $_GET['page']);
	}
	$list = get_page_list();
	if ($list != null) {
		$smarty->assign('page_list', get_page_list());
	}
}

?>
