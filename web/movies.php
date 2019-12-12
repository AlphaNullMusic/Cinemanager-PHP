<?php
require('inc/web.inc.php');
require('inc/smarty_vars.inc.php');

$movie_id=(isset($_REQUEST['movie']))?$_REQUEST['movie']:null;
$tpl_name = 'movies.tpl';
$tpl = $config['site_dir'].'tpl/'.$tpl_name;
$cache_id = 'movie-'.$movie_id;

if(!$smarty->isCached($tpl,$cache_id)) {

	// Assign page variables
	$smarty->assign('domain',$cinema_domain);
	$smarty->assign('name',$cinema_data['cinema_name']);
	$smarty->assign('city',$cinema_data['city']);
	$smarty->assign('gacode',$config['ga_code']);

	if (isset($_REQUEST['movie'])) {
		// Get movie data (movie ID, get sessions, extra conditions)
		$movie_data = get_movie($movie_id, true, NULL, 'medium');

		// Redirect alias
		if (!empty($movie_data['movie']['alias'])) {
			header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: {$movie_data['movie']['alias']}");
			die;
		}

		// 410 if movie doesn't exist.
		if (empty($movie_data['movie'])) {
			header("Location: error.php?410");
			exit;
		}
		
		if (is_array($movie_data)) {
			$smarty->assign($movie_data);
		} else {
			// Movie data could not be found
			// header("Location: $cinema_domain");
			exit;
		}
		
		if (isset($movie_data['status']) && $movie_data['status']=='cs') {
			$smarty->assign('movie_list',get_movie_list_full('cs','m.release_date,m.title',0,'%M %e','%e %b',9,'today',null,null,false,'medium'));
		} else {
			$smarty->assign('movie_list',get_movie_list_full('ns','m.title',0,'%W %D','%e %b',9,'today',null,null,true,'medium'));
		}
	} else {
		// No movie id sent
		header("Location: $cinema_domain");
		exit;
	}
	
	// Common functions
	include('inc/local.inc.php');
}

// Include template
$smarty->display($tpl,$cache_id);

?>
