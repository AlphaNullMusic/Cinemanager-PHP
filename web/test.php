<?php
	require('../config.inc.php');
	function get_page_content($reference=null) {
		global $mysqli;
		db_direct();
		/*if (!isset($mysqli)) {
			return false;
		}*/
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
	$result = get_page_content('venue-hire');
	//print_r($result);
	echo $result[content];
?>