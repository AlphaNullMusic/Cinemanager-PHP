<?php

//identify_cinema()
//default url can be overwritten by 'urloverride' query string
function identify_cinema() {
	global $mysqli, $cinema_domain, $cinema_id, $cinema_data, $default_login_url, $global;
	// Get domain name and clean up
	$cinema_domain=$_SERVER['HTTP_HOST'];
	$cinema_domain = str_replace('http://', '', rtrim($cinema_domain, '/'));
	
	// Look for cinema data
	$sql="
		SELECT p.*, s.*
		FROM settings s
		INNER JOIN permissions p
			ON s.id = p.id
		LIMIT 1
	";
	$cinema_res=$mysqli->query($sql) or user_error("Error at: $sql");
	if ($cinema_res->num_rows != 1) { 
		header("Location: 404.php?er=Cinema not found.");
		exit;
	} else {
		$res=$mysqli->query($sql) or user_error("Gnarly: $sql");
		$cinema_data=$res->fetch_assoc();
		$cinema_data['timezoneOffset'] = differenceBetweenTimezones($cinema_data['timezone']);
		$cinema_data['localTimestamp'] = localTimestamp($cinema_data['timezone']);
		$cinema_id=$cinema_data['cinema_id'];
		if (!isset($cinema_data['image_cat_id'])) {
			$cinema_data['image_cat_id'] = 2;
		}
		if (!empty($cinema_data['children'])) {
			$sql = "
				SELECT cinema_id, cinema_name
				FROM cinemas
				WHERE cinema_id IN ({$cinema_data['cinema_id']}, {$cinema_data['children']})
				ORDER BY family_order
			";
			$cinema_family_res = $mysqli->query($sql) or user_error("Gnarly: $sql");
			$cinema_data['family'] = array();
			while ($family = $cinema_family_res->fetch_assoc()) {
				$cinema_data['family'][$family['cinema_id']] = $family['cinema_name'];
			}
			$cinema_data['children'] = explode(',', $cinema_data['children']);
		}
		//change $cinema_domain to an absolute url for links
		//used to be: $global['cinema_url']
		if (isset($_GET['urloverride'])) {
			$cinema_domain='http://'.$cinema_data['url'].'/';
		} else {
			$cinema_domain="http://{$cinema_domain}/";
		}
	}
}


// Get the user-edited content for a page
function get_page_content($reference=null) {
	global $cinema_data, $mysqli;
	if (!isset($mysqli)) {
		return false;
	}
	$sql = "
		SELECT page_id, reference, content, title
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
		return embed_links($data);
	} else {
		return false;
	}
}


// Get a list of all the cinema's pages
function get_page_list($cinema_id) {
	$sql = "
		SELECT page_id, reference, content, title
		FROM pages
		WHERE cinema_id = '" . $mysqli->real_escape_string($cinema_id) . "'
			AND status = 'ok'
			AND public = 1
		ORDER BY priority ASC, title ASC
	";
	$res = $mysqli->query($sql) or user_error("Gnarly: $sql");
	$pages = array();
	while ($p = $res->fetch_assoc()) {
		$pages[] = $p;
	}
	return $pages;
}

////////////
// SMARTY //
////////////

//smarty_summary()
function smarty_summary($params) {
	if (empty($params['text']) || empty($params['chars'])) { 
		return false; 
	} else {
		$text=$params['text'];
		$chars=$params['chars'];
		if (empty($params['round'])) { $round='word'; } else { $round=$params['round']; }
		if (empty($params['suffix'])) { $suffix='...'; } else { $suffix=$params['suffix']; }

		if (strlen($text)<=$chars) { 
			$suffix=''; // No change if $text is less than $chars
		} else {
			if ($round=='char') {
				$chars=$chars-strlen($suffix); // Remove extra characters to account for suffix
				$text=substr($text,0,$chars); // Shorten text
			} else {
				$text=$text." "; // Add space (used for rounding to narest word)
				$text=substr($text,0,$chars); // Shorten text
				$text=substr($text,0,strrpos($text,' ')); // Round to nearest word
			}
		}
		$text=trim($text).$suffix; // Remove trailing spaces and append suffix
		return $text;
	}
}

//edit_image_path()
//smarty pre-filter to correct links
function edit_image_path($tpl_source, &$smarty) {
	global $cinema_domain,$cinema_id;
	$tpl_dir = (!empty($_GET['dev'])) ? $cinema_id.'dev' : $cinema_id ;
	$imagepath=$cinema_domain.$tpl_dir.'/';
	$change_from=array(
		"\"images/",
		"'images/",
		"\"/img/",
		"\"/css/",
		"\"/js/",
		"\"files/",
		"\"styles.css\"",
		"\"m_styles.css\"",
		"../includes/"
	);
	$change_to=array(
		//"\"$imagepath/banners/images/",
		"\"{$imagepath}images/",
		"'{$imagepath}images/",
		"\"{$imagepath}img/",
		"\"{$imagepath}css/",
		"\"{$imagepath}js/",
		"\"{$imagepath}files/",
		"\"{$imagepath}styles.css\"",
		"\"{$imagepath}m_styles.css\"",
		"{$cinema_domain}includes/"
	);
	$tpl_source=str_replace($change_from, $change_to, $tpl_source);
	return $tpl_source;
}

function format_date($date){
	$array_months = array(
		'0' => '',
		'1' => 'January',
		'2' => 'February',
		'3' => 'March',
		'4' => 'April',
		'5' => 'May',
		'6' => 'June',
		'7' => 'July',
		'8' => 'August',
		'9' => 'September',
		'10' => 'October',
		'11' => 'November',
		'12' => 'December'
	);
	$year = substr($date, 0, 4);
	$month = substr($date, 5, 2);
	$day = substr($date, 8, 2);
	if(substr($month, 0, 1) == "0")
		$month = substr($month, 1, 1);
	if(substr($day, 0, 1) == "0")
		$day = substr($day, 1, 1);
	$date = $day." ".$array_months[$month]." ".$year;
	return $date;
}
/*
function require_ssl() {
	global $cinema_data;
	if($_SERVER['HTTPS'] != 'on') {
		if (!empty($cinema_data['secure_url'])) {
			header('Location: ' . $cinema_data['secure_url'] . ltrim($_SERVER['REQUEST_URI'], '/'));
		}
		die();
	}
}

function errorLog($file_name, $message, $details) {
	if (is_array($details)) {
		$details = serialize($details);
	}
	$text = date('r') . "\t" . $message . "\t" . $details . "\n";
	file_put_contents($file_name, $text, FILE_APPEND | LOCK_EX);
}
*/
function embed_links($string) {
  // Youtube
  $string = preg_replace('~
	    https?://         # Required scheme. Either http or https.
	    (?:[0-9A-Z-]+\.)? # Optional subdomain.
	    (?:               # Group host alternatives.
	      youtu\.be/      # Either youtu.be,
	    | youtube\.com    # or youtube.com followed by
	      \S*             # Allow anything up to VIDEO_ID,
	      [^\w\-\s]       # but char before ID is non-ID char.
	    )                 # End host alternatives.
	    ([\w\-]{11})      # $1: VIDEO_ID is exactly 11 chars.
	    (?=[^\w\-]|$)     # Assert next char is non-ID or EOS.
	    (?!               # Assert URL is not pre-linked.
	      [?=&+%\w]*      # Allow URL (query) remainder.
	      (?:             # Group pre-linked alternatives.
	        [\'"][^<>]*>  # Either inside a start tag,
	      | </a>          # or inside <a> element text contents.
	      )               # End recognized pre-linked alts.
	    )                 # End negative lookahead assertion.
	    [?=&+%\w-]*       # Consume any URL (query) remainder.
	    ~ix', 
    '<div class="modifier_youtube"><iframe width="560" height="315" src="//www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe></div>',
    $string);
  return $string;
}

?>