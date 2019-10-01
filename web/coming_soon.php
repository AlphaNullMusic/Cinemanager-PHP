<?

require('includes/local.inc.php');
require('includes/smarty_vars.inc.php');

$tpl_name = 'coming_soon.tpl';
$tpl = $config['site_dir'].'tpl/'.$tpl_name;
$cache_id = "coming_soon";
if(!$smarty->isCached($tpl,$cache_id)) {
				
	$smarty->assign('id',$cinema_id);
	$smarty->assign('domain',$cinema_domain);
	$smarty->assign('name',$cinema_data['cinema_name']);
	$smarty->assign('city',$cinema_data['city']);
	$smarty->assign('nzcinema_url',$global['public_url']);
	$smarty->assign('movie_image_url',$global['movie_image_url']);
	$smarty->assign('movie_image_url_secure',$global['movie_image_url_secure']);
	$smarty->assign('movie_trailer_url',$global['movie_trailer_url']);
	$smarty->assign('coming_soon',get_movie_list_full('cs','tbc, m.release_date,m.title',0,'%M %e','%e %b'));
	$smarty->assign('tpl_name',$tpl_name);
		
		if (isset($_GET['urloverride'])) { $smarty->assign('qs','urloverride='.$_GET['urloverride'].'&'); }
		if (isset($_GET['er'])) { $smarty->assign('er',$_GET['er']); }
		if (isset($_GET['ok'])) { $smarty->assign('ok',$_GET['ok']); }
		if (isset($_REQUEST['first_name'])) { $smarty->assign('first_name',$_REQUEST['first_name']); }
		if (isset($_REQUEST['last_name'])) { $smarty->assign('last_name',$_REQUEST['last_name']); }
		if (isset($_REQUEST['email'])) { $smarty->assign('email',$_REQUEST['email']); }
	
		if (has_permission('user_list',$cinema_id)) { $smarty->assign('user_list_ok','y'); }
		if (check_user()) { $smarty->assign('logged_in','y'); }

		//check for feature content
		if (has_permission('edit_pages',$cinema_id)) {
			$smarty->assign('page',get_page_content('coming_soon'));
		}
	
		//common functions
		include('allPages.inc.php');

	}
	
	//register functions / filters
	$smarty->registerPlugin("function", "summary", "smarty_summary");
	$smarty->registerFilter("pre", "edit_image_path");
	


//include template
$smarty->display($tpl,$cache_id);

?>
