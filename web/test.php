<?php
	require('inc/web.inc.php');
	/*function get_page_content($reference=null) {
		global $mysqli;
		db_direct();
		$sql = "
			SELECT page_id, reference, title, content
			FROM pages
			WHERE status = 'ok'
		";
		$sql .= ($reference) ? "AND (reference = '".$mysqli->real_escape_string($reference)."' OR page_id = '".$mysqli->real_escape_string($reference)."') " : "" ;
		$sql .= "LIMIT 1";
		$res = $mysqli->query($sql) or user_error("Gnarly: $sql");
		if ($res->num_rows == 1) {
			if ($reference) {
				$data = $res->fetch_assoc();
			} else {
				$data = array();
				while ($d = $res->fetch_assoc()) {
					$data[$d['reference']] = $d;
				}
			}
			return $data;
		} else {
			return false;
		}
	}
	$result = get_page_content('venue-hire');*/
	//print_r($result);
	//echo $result[content];
	//$res = get_movie_list_full('ns','m.title',14,'%W %D','%e %b',100,'today',null,null,false);
	
	//$raw_data = get_movie_sessions(2, ['2019-10-01','2024-10-01'], false, '%Y-%m-%d', '%l:%i%p', true);
	//print_r($raw_data);
	
	$raw_data = get_movie_list_full('ns','m.title',14,'%W %D','%e %b',100,'today',null,null,true);
	echo '-----get_movie_list_full()-----<br/>';
	print_r($raw_data);
	echo '<br/>-----end get_movie_list_full()-----<br/>';
	echo '<br/>';

	$res = get_movie_sessions([1], NULL, false, '%Y-%m-%d', '%l:%i%p', true);
	echo '-----get_movie_sessions()-----<br/>';
	print_r($res);
	echo '<br/>-----end get_movie_sessions()-----<br/>';
	echo '<br/>';
	
	$result = get_movie('2', false, NULL);
	echo '-----get_movie()-----<br/>';
	print_r($result);
	echo '<br/>-----end get_movie()-----<br/>';
	echo '<br/>';
	
	echo get_class_explanation('TBC');
	
?>