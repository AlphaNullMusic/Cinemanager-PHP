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
	/*$movies=explode(", ",$movies);
	if (count($movies)>0) {
		$sql="
			SELECT m.movie_id, m.title, IF(ml.synopsis != '', ml.synopsis, m.synopsis) AS `synopsis`, IF(ml.class_explanation != '', ml.class_explanation, m.class_explanation) AS `class_explanation`, m.duration, 
				mc.cast,
				ml.duration, ml.comments, DATE_FORMAT(ml.release_date,'%W %D') AS release_date, IF(ml.duration!='',ml.duration,m.duration) AS duration,
				IF(ml.class_id != 0, c2.class, c.class) AS `class`, 
				i.image_name
			FROM movies m
			INNER JOIN movie_lists ml
				ON ml.movie_id=m.movie_id
			INNER JOIN movie_images mi
				ON mi.movie_id=m.movie_id";
		$sql.= "
			INNER JOIN images i
				ON i.image_id=mi.image_id
				AND i.status='ok'";
		$sql.= "
			INNER JOIN movie_cache mc
				ON mc.movie_id = m.movie_id
			LEFT JOIN classifications c
				ON c.class_id=m.class_id
			LEFT JOIN classifications c2
				ON c2.class_id=ml.class_id
			WHERE ml.status='ok' 
				AND (
		";
		for ($i=0;$i<count($movies);$i++) {
			if ($i>0) {
				$sql.="OR ";
			} 
			$sql.="m.movie_id='$movies[$i]' ";
		}
		$sql.="
		) AND ml.cinema_id='$cinema_id'
			AND ml.status='ok'
			AND i.image_cat_id='{$cinema_data['image_cat_id']}'
			GROUP BY m.movie_id
			ORDER BY $movie_order
			LIMIT 20
		";
		//echo $sql;
		$movie_list_res=$mysqli->query($sql) or user_error("Gnarly: $sql");
		$n=0;
		while ($movie_list_data=$movie_list_res->fetch_assoc()) {
			$now_showing[$n]['movie_id']=$movie_list_data['movie_id'];
			$now_showing[$n]['title']=$movie_list_data['title'];
			$now_showing[$n]['country_id']=(isset($movie_list_data['country_id']))?$movie_list_data['country_id']:NULL;
			$now_showing[$n]['image_name']=$movie_list_data['image_name'];
			$now_showing[$n]['class']=$movie_list_data['class'];
			$now_showing[$n]['class_explanation']=$movie_list_data['class_explanation'];
			$now_showing[$n]['duration']=$movie_list_data['duration'];
			$now_showing[$n]['release_date']=$movie_list_data['release_date'];
			$now_showing[$n]['comments']=$movie_list_data['comments'];
			$now_showing[$n]['synopsis']=$movie_list_data['synopsis'];
			$now_showing[$n]['cast']=$movie_list_data['cast'];
			// Get sessions
			if ($template_data['sessions_num']>0) {
				$sql="
					SELECT s.session_id, s.comments, s.time, substring(s.time,1,10) AS session_date, LOWER(CAST(DATE_FORMAT(s.time,'%l:%i%p') AS CHAR(10))) AS time_format, s.bms_Session_lngSessionId, s.bms_Venue_strCode,
						spg.name AS label, spg.comments AS label_comments
					FROM sessions s
					LEFT JOIN session_preset_groups spg
						ON s.session_preset_group_id = spg.session_preset_group_id
					WHERE s.cinema_id='$cinema_id' 
						AND s.movie_id='{$movie_list_data['movie_id']}'
						AND s.time>='{$template_data['sessions_start_date']}'
						AND s.time<=DATE_ADD('{$template_data['sessions_start_date']} 23:59:59',INTERVAL ".($template_data['sessions_num']-1)." DAY)
					ORDER BY s.time 
				";
				//{$template_data['sessions_num']}
				$session_res=mysql_query($sql, $db) or user_error("Gnarly: $sql");
				while ($sd=mysql_fetch_assoc($session_res)) {
					$now_showing[$n]['sessions'][$sd['session_date']][] = array(
						'id' => $sd['session_id'],
						'comment' => $sd['comments'],
						'label'	=> $sd['label'],
						'label_comment'	=> $sd['label_comments'],
						'timestamp'	=> $sd['time'],
						'time' => $sd['time_format'],
						'bms_sid' => $sd['bms_Session_lngSessionId'],
						'bms_cid' => $sd['bms_Venue_strCode']
					);
				}
			}
			$n++;
		}
	}
	$smarty->assign('now_showing',$now_showing);*/
	//$smarty->assign('now_showing',get_movie_list('ns','m.title',$template_data['sessions_num'],'%W %D','%e %b',20,$template_data['sessions_start_date']));
	$smarty->assign('now_showing',$smarty->assign('now_showing',get_movie_list_full('ns','m.title',$template_data['sessions_num'],'%W %D','%e %b',20,$template_data['sessions_start_date'],null,null,true,'medium')));
} else {
	die("<a href='".$config['cinema_url']."'>Shoreline Cinema</a>");
}

date_default_timezone_set('Pacific/Auckland');
$smarty->assign('date_today',date('d m Y'));

// Include template
$smarty->display($config['cinema_dir'].$template_data['template_url']);

?>
