<?php

//
// Common Smarty assignments which should be executed on all pages
//

if (has_permission('edit_pages', $cinema_id)) {
	$smarty->assign('page_global', get_page_content('global'));
	if ($cinema_id >= 1199) {
		$smarty->assign('page_list', get_page_list($cinema_id));
	}
	if ($cinema_id=='1016') {
		$smarty->assign('pagetwo',get_page_content(525));
	}
}

?>