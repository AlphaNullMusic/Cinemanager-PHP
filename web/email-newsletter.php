<?php

require('inc/web.inc.php');
require('inc/smarty_vars.inc.php');

if (!empty($_REQUEST['static']) && !empty($_REQUEST['newsletter_id'])) {
	$sql = "
		SELECT message_html
		FROM newsletters
		WHERE newsletter_id = '" . mysql_real_escape_string($_REQUEST['newsletter_id']) . "'
	";
	$res = $mysqli->query($sql);
	$data = $res->fetch_assoc();
	echo $data['message_html'];
	die;
}

$smarty->caching = 0;
$smarty->compile_check = true;

// Assign page variables
$smarty->assign('domain',$cinema_domain);
$smarty->assign('name',$cinema_data['cinema_name']);
$smarty->assign('city',$cinema_data['city']);
$smarty->assign('gacode',$config['ga_code']);
if (isset($_REQUEST['plaintext'])) { $smarty->assign('plaintext','y'); }

// Ordering
$movie_order='ml.priority,m.title';

// Get template info
if (isset($_REQUEST['newsletter_id'])) {
	$now_showing = false;
	$newsletter_id=$_REQUEST['newsletter_id'];
	$sql="SELECT t.*, n.editorial, n.subject, n.sessions_start_date, n.sessions_num, n.movies 
				FROM newsletter_templates t, newsletters n 
				WHERE n.template_id=t.template_id 
					AND n.newsletter_id='$newsletter_id'";
	$template_res=$mysqli->query($sql);
	$template_data=$template_res->fetch_assoc();
	if ($template_data['editorial'] && $template_data['editorial']!="<P>&nbsp;</P>") { 
		$smarty->assign('editorial',$template_data['editorial']); 
	}
	$plain_editorial=strip_tags($template_data['editorial']);
	$plain_editorial=str_replace("&nbsp;"," ",$plain_editorial);
	$smarty->assign('plain_editorial',$plain_editorial);
	
	// Get movie list
	$movies=$template_data['movies'];
	$movies=explode(", ",$movies);
	if (!empty($movies)) {
		$movie_list = get_movie_list_full('ns','m.title',$template_data['sessions_num'],'%W %D','%e %b',20,$template_data['sessions_start_date'],null,null,true,'medium');
		$ns = array();
		foreach($movies as $m) {
			foreach($movie_list as $key => $movie) {
				if($m == $movie['movie_id']) {
					$ns[$key] = $movie;
				}
			}
		}
	} else {
		$ns = get_movie_list_full('ns','m.title',$template_data['sessions_num'],'%W %D','%e %b',20,$template_data['sessions_start_date'],null,null,true,'medium');
	}
	$smarty->assign('ns',$ns);
} else {
	die("<a href='".$config['cinema_url']."'>Shoreline Cinema</a>");
}

date_default_timezone_set('Pacific/Auckland');
$smarty->assign('date_today',date('d m Y'));

// Include template
$smarty->display($config['template_dir'].$template_data['template_url']);

?>
