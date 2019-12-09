<?php
require("inc/manage.inc.php");
if (check_cinema() && has_permission('user_list')) {
	if (!isset($_GET['show'])) {
		$_GET['show'] = 'all';
	}
	// Get an array of all users	
	switch ($_GET['show']) {
		case 'yesterday':
			$from = 'DATE(DATE_SUB(NOW(), INTERVAL 1 DAY))';
			$to = 'DATE(DATE_SUB(NOW(), INTERVAL 1 DAY))';
			break;
		case 'week':
			$from = 'DATE_SUB(NOW(), INTERVAL 1 WEEK)';
			$to = 'NOW()';
			break;
		case 'month':
			$from = 'DATE_SUB(NOW(), INTERVAL 1 MONTH)';
			$to = 'NOW()';
			break;
		case 'year':
			$from = 'DATE_SUB(NOW(), INTERVAL 1 YEAR)';
			$to = 'NOW()';
			break;
		case 'all':
			$from = '0000-00-00';
			$to = 'NOW()';
			break;
		case 'today':
			$from = 'DATE(NOW())';
			$to = 'NOW()';
			break;
		default:
			$from = 'NOW()';
			$to = 'NOW()';
			break;
	}
	$sql = "SELECT u.* ";
	$sql .= "
			, DATE_FORMAT(u.last_updated, '%d-%m-%Y') AS joined
		FROM users u
		WHERE u.last_updated >= $from
			AND u.last_updated <= $to
			AND u.status = 'ok'
		ORDER BY u.last_updated DESC
	";
	$res = query($sql);
	
	// Only continue if we have data
	if ($res->num_rows > 0) {
		$ignored_fields = array('phone','post_code','status','last_updated','date_joined','plain_text');
		$users = array();
		while ($data = $res->fetch_assoc()) {
			foreach ($ignored_fields as $if) {
				unset($data[$if]);
			}
			$users[] = $data;
		}
		
		// Generate output
		$output = array();
		$n = 0;
		foreach ($users as $user) {
			// Create headers
			if ($n == 0) {
				foreach ($user as $key => $value) {
					$output[$n][$key] = ucwords(str_replace('_', ' ', $key));
					/*if ($key == 'alternate_list1') {
						$output[$n][$key] = 'Mailing List';
					}*/
				}
			}
			// Create content
			$n++;
			foreach ($user as $key => $value) {
				if ($key == 'alternate_list1') {
					$user[$key] = ($value == 1) ? $_SESSION['cinema_data']['alt_mailing_list_name'] : "Default" ;
				} elseif ($key == 'birth_date' && $value == '0000-00-00') {
					$user[$key] = '';
				}
			}
			$output[$n] = $user;
		}
		
		// Output
		$filename = $config['cinema_name'] . " Subscribers";
		switch ($_GET['show']) {
			case 'yesterday':
				$filename .= ' Yesterday';
				break;
			case 'today':
				$filename .= ' Today';
				break;
			case 'week':
				$filename .= ' from the Last Week';
				break;
			case 'month':
				$filename .= ' from the Last Month';
				break;
			case 'year':
				$filename .= ' from the Last Year';
				break;
			case 'all':
				break;
			default:
				$filename .= " by " . ucfirst($_GET['show']);
				break;
		}
		$filename .= " as at " . date('d-m-Y');
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename="'.$filename.'.csv"');
		$fp = fopen('php://output', 'w');
		foreach($output as $fields) {
			fputcsv($fp, $fields);
		}
		fclose($fp);
		
	} else {
		header("Location: users.php?er=No+subscribers+found+for+".$_GET['show']);
	}
} else {
	header("Location: users.php");
}
?>
