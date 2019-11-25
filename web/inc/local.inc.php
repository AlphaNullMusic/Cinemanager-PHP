<?php

//
// Common Smarty assignments which should be executed on all pages
//

if (has_permission('edit_pages', $cinema_id)) {
	$smarty->assign('page_global', get_page_content('global'));
}

?>