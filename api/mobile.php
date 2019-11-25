<?

// Setup

ini_set('display_errors',1); 
error_reporting(E_ALL);

$global_root_dir = dirname(dirname(__FILE__));
$use_pdo = true;
require($global_root_dir."/global_settings.inc.php");
require($global_root_dir."/global_functions.inc.php");
$pdo = new db;
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
db_direct();

// Authentication

$required = array('token', 'format');
if (empty($_REQUEST['token'])) {
	failed('Access denied');
}

$sql = "
	SELECT a.*, p.*
	FROM apps a
	INNER JOIN pages p
		ON p.page_id = a.about_page_id
	WHERE a.token = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array($_REQUEST['token']));
if ($stmt->rowCount() != 1) {
	failed('Invalid token');
}

// Fetch some data

$appData = $stmt->fetch();
$stmt = null;
$feed = extractFields($appData, 'name, image, content|about, about_page_title');

$sql = "
	SELECT ac.*, c.*, co.country_name
	FROM app_cinemas ac
	INNER JOIN cinemas c
		ON ac.cinema_id = c.cinema_id
	INNER JOIN countries co
		ON co.country_id = c.country_id
	WHERE ac.app_id = :app_id
		AND c.status = 'ok'
	ORDER BY ac.priority ASC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(
	'app_id' => $appData['app_id']
));
if ($stmt->rowCount() > 0) {
	$cinemaData = $stmt->fetchAll();
	$stmt = null;
	$cinemaIds = array();
	foreach ($cinemaData as $row) {
		if (!empty($row['homepage_url'])) {
			$row['homepage_url'] = 'http://' . str_replace('http://', '', $row['homepage_url']);
		}
		$feed['cinemas'][] = extractFields($row, 'cinema_id, cinema_name, homepage_url, email, phone, physical_address|address, city, country_name|country, timezone, geo_long|longitude, geo_lat|latitude');
		$cinemaIds[] = $row['cinema_id'];
	}
	$cinema_data = array(
		'api_fetch' => true,
		'homepage_url' => $feed['cinemas'][0]['homepage_url']
	);
	$feed['movies'] = get_movie_list_full_mobile(($_REQUEST['data'] == 'upcoming')?'cs':'ns', ($_REQUEST['data'] == 'upcoming')?'tbc,ml.priority,ml.release_date,m.title':'ml.priority,m.title', 7, '%W %e %b', '%e %b', 100, 'today', $cinemaIds);
	function imageNameToUrl(&$item, $key) {
		global $global;
		if ($key == 'image_name') {
			$item = $global['movie_image_url'] . $item . '_appposter.jpg';
		}
	}
	array_walk_recursive($feed['movies'], 'imageNameToUrl');
	function cleanupText(&$item, $key) {
		global $global;
		if ($key == 'manager_comments' || $key == 'synopsis') {
			$item = trim(strip_tags($item));
		}
	}
	array_walk_recursive($feed['movies'], 'cleanupText');
	if (empty($feed['image'])) {
		$feed['image'] = $feed['movies'][0]['image_name'];
	}
} else {
	failed('No cinemas');
}

if ($_REQUEST['data'] == 'updated') {
	$feed['updated'] = date('Y-m-d H:i:s');
	unset($feed['cinemas'], $feed['movies']);
}

// Output the data

switch($_REQUEST['format']) {
	case 'xml':
		header("Content-type: text/xml");
		array2xml($feed);
		die;
	case 'json':
	default:
		header('Content-Type: application/json');
		echo json_encode($feed);
		die;
}

// Helpers

function d($vars, $fatal = true) {
	echo '<pre>';
	print_r($vars);
	echo '</pre>';
	if ($fatal) {
		die;
	}
}

function failed($message) {
	header('HTTP/1.0 403 Forbidden');
	echo $message;
	die;
}

function extractFields($array, $keys) {
	$keys = explode(',', $keys);
	$return = array();
	foreach($keys as $key) {
		$key = trim($key);
		$k = explode('|', $key);
		if (isset($array[$k[0]])) {
			$return[(!empty($k[1]))?$k[1]:$k[0]] = $array[$k[0]];
		}
	}
	return $return;
}

function array2xml($array, $node_name="root") {
  $_xml = $xml;
  if ($_xml === null) {
    $_xml = new SimpleXMLElement($rootElement !== null ? $rootElement : '<root/>');
  }
  foreach ($array as $k => $v) {
    if (is_array($v)) { //nested array
      array2xml($v, $k, $_xml->addChild($k));
    } else {
      $_xml->addChild($k, $v);
    }
  }
  return $_xml->asXML();
}

//get_movie_list_full()
//gets now_showing or coming_soon list for current cinema
//get_movie_list_full('ns','ml.priority, m.title',14,'%W %D','%e %b',60,'today',null,null,null,true)
function get_movie_list_full_mobile($type='ns', $order_by='ml.priority,m.title', $num_sessions='7', $date_format='%e %b', $date_format2='%e %b', $limit=100, $session_start='today', $cinema_id_parsed=NULL, $movie_array=NULL, $days_of_sessions=NULL, $group_by_cinema=NULL, $features_only=NULL, $get_session_labels=NULL, $session_label_filter=NULL, $event_filter=NULL, $session_comment_filter=NULL, $vista_attribute_filter=NULL, $session_screen_filter=NULL) {
	global $db, $cinema_id, $session_flags, $cinema_data;
	$extra_conditions="";
	$extra_select="";
	$having="";
	
	if ($limit>0) {
	
		//allow cinema id override
		if (isset($cinema_id_parsed) && !empty($cinema_id_parsed)) {
			$this_cinema_id = $cinema_id_parsed;
		} elseif (!empty($cinema_data['children'])) {
			$this_cinema_id = $cinema_data['children'];
			array_push($this_cinema_id, $cinema_data['cinema_id']);
		} elseif (isset($cinema_id) && !empty($cinema_id)) {
			$this_cinema_id = $cinema_id;
		} elseif (isset($_SESSION['cinema_data']['cinema_id']) && !empty($_SESSION['cinema_data']['cinema_id'])) {
			$this_cinema_id = $_SESSION['cinema_data']['cinema_id'];
		}
		
		//timezone
		$timezoneOffset = (!empty($cinema_data['timezoneOffset'])) ? $cinema_data['timezoneOffset'] : 17 ;

		//$cinema_data['image_cat_id'] will be absent if using $bypass_cid
		if (!isset($cinema_data['image_cat_id'])) {
			$cinema_data['image_cat_id']=2;
		}
		
		//get relative dates
		$today=date('j M');
		$tomorrow_time=mktime(0,0,0,date('m'),date('d')+1,date('Y'));
		$tomorrow=date("j M",$tomorrow_time);
		if ($session_start=='today') {
			$session_start=date('Y-m-d');
		}
		$session_end = (date('Y')+5).date('-m-d');
		if (isset($days_of_sessions) && !empty($days_of_sessions)) {
			$temp = explode('-',$session_start);
			$session_end = date('Y-m-d', mktime(0, 0, 0, $temp[1], $temp[2]+$days_of_sessions-1, $temp[0])).' 23:59:59';
			$session_array = array($session_start.' 00:00:00',$session_end);
		}
		
		//additional selection conditions
		if ($type == 'ns' && $num_sessions == 0) {
			$extra_conditions .= " AND ml.release_date <= DATE_ADD(NOW(), INTERVAL $timezoneOffset HOUR) "; // Temporary fix, we need dynamic timezone entered here
		}
		
		//restrict movies shown
		if (is_array($movie_array)) {
			$extra_conditions.=" AND ( ";
			foreach ($movie_array as $m) {
				if (isset($movies_started)) {
					$extra_conditions.=" OR ";
				} else {
					$movies_started=true;
				}
				$extra_conditions.=" m.movie_id='$m' ";
			}
			$extra_conditions.=" ) ";
		}
		
		//some cinemas display multiple cinemas
		$cinema_sql = "ml.cinema_id='$this_cinema_id'";
		if (!$cinema_id_parsed && ($cinema_id == 1044 || $cinema_id == 1045)) {
			$cinema_ids = array(1045,1044);
			$cinema_sql = "(ml.cinema_id=1045 OR ml.cinema_id=1044)";
			$extra_select .= ", GROUP_CONCAT(DISTINCT ml.cinema_id ORDER BY ml.cinema_id DESC SEPARATOR '|') AS cinema_ids";
		} elseif (!empty($cinema_data['api_fetch']) || !empty($cinema_data['children'])) {
			if (!is_array($this_cinema_id)) {
				$cinema_ids = array($this_cinema_id);
			} else {
				$cinema_ids = $this_cinema_id;
			}
			$cinema_sql = "( ";
			foreach ($cinema_ids as $key => $cids) {
				if ($key > 0) {
					$cinema_sql .= "OR ";
				}
				$cinema_sql .= "ml.cinema_id=$cids ";
			}
			$cinema_sql .= ") ";
			$extra_select .= ", GROUP_CONCAT(DISTINCT ml.cinema_id ORDER BY ml.cinema_id DESC SEPARATOR '|') AS cinema_ids";
		} else {
			$cinema_ids = array($this_cinema_id);
		}
		
		/*
		//exclude 3D films if we are merging sessions
		if (has_permission('merge_3d')) {
			$extra_conditions .= " AND RIGHT(LOWER(m.title), 5) != ' (3d)' AND RIGHT(LOWER(m.title), 3) != ' 3d'";
		}
		*/

		//tidy up the session label filter
		if (isset($session_label_filter) && !empty($session_label_filter) && !is_array($session_label_filter)) {
			$session_label_filter = explode('|', $session_label_filter);
		}
		
		//filter by event
		if (isset($event_filter) && !empty($event_filter)) {
			if ($event_filter[0] == 'v') {
				$event_filter = preg_replace('/[^0-9]/', '', $event_filter);
				$extra_conditions .= " AND ml.event_id = '".mysql_real_escape_string($event_filter)."' ";
			} else {
				$extra_conditions .= " AND st.event_id = '".mysql_real_escape_string($event_filter)."' ";
			}
		}
		
		//filter by session comments
		if (!empty($session_comment_filter)) {
			$extra_select .= ", GROUP_CONCAT(DISTINCT LOWER(st.comments) SEPARATOR '|') AS session_comments ";
			$having .= " HAVING session_comments LIKE '%".mysql_real_escape_string($session_comment_filter)."%' ";
		} elseif (!empty($vista_attribute_filter)) {
			$extra_select .= ", GROUP_CONCAT(DISTINCT LOWER(st.vista_attributes) SEPARATOR '|') AS session_vista_attributes ";
			$having .= " HAVING session_vista_attributes LIKE '%".mysql_real_escape_string($vista_attribute_filter)."%' ";
		} elseif (!empty($session_screen_filter)) {
			$extra_select .= ", GROUP_CONCAT(DISTINCT LOWER(st.screen) SEPARATOR '|') AS screens ";
			$having .= " HAVING screens LIKE '%".mysql_real_escape_string($session_screen_filter)."%' ";
		}
		
		//only show featured movies
		if (isset($features_only) && !empty($features_only)) {
			$cinema_sql .= " AND ml.feature > 0 ";
		}
		
		//get a list of labels used by each movie's sessions
		if (isset($get_session_labels) && !empty($get_session_labels)) {
			$extra_select .= ", GROUP_CONCAT(DISTINCT st.session_preset_group_id ORDER BY st.session_preset_group_id DESC SEPARATOR '|') AS session_labels";
		}
		
		//exclude custom images if this is not a cinema site
		$extra_custom_cinema_image = (empty($cinema_data['api_fetch']) && !isset($cinema_data['cinema_id']) && !isset($_SESSION['cinema_data']['cinema_id'])) ? "AND ml.cinema_id = 0" : "" ;

		//now showing
		if ($type=='ns') {
			$sql="
				SELECT m.movie_id, m.master_movie_id, m.title, IF(ml.synopsis != '', ml.synopsis, m.synopsis) AS `synopsis`, m.country_id, IF(ml.trailer!='',ml.trailer,m.trailer) AS trailer, m.official_site, IF(ml.class_explanation != '', ml.class_explanation, m.class_explanation) AS `class_explanation`, m.subtitled,
					mc.trailers AS cache_trailers, mc.cast AS cache_cast,
					ml.comments, ml.release_date, ml.comments, ml.priority, COUNT(DISTINCT st.session_id) AS total_sessions, st.event_id, IF(ml.duration!='',ml.duration,m.duration) AS duration, IF(MAX(ml.runtime)>0,MAX(ml.runtime),m.runtime) AS runtime, MIN(ml.priority) as priority, DATE_FORMAT(ml.release_date,'$date_format') AS release_date_f1, DATE_FORMAT(ml.release_date,'$date_format2') AS release_date_f2, ml.event_id AS movie_event_id,
					GROUP_CONCAT(DISTINCT i.image_name ORDER BY IF(mi.cinema_id IN(" . implode(',', $cinema_ids) . "), 1, 0) DESC, i.priority DESC, i.image_id DESC SEPARATOR '|') AS image_names,
					IF(ml.class_id != 0, c2.class, c.class) AS `class`
					$extra_select
				FROM movies m
				INNER JOIN movie_lists ml
					ON ml.movie_id=m.movie_id
					$extra_custom_cinema_image
				INNER JOIN movie_cache mc
					ON mc.movie_id=m.movie_id
				INNER JOIN sessions st
					ON st.movie_id=m.movie_id
					AND ml.cinema_id=st.cinema_id
					AND st.time>='$session_start' 
					AND st.time<='$session_end'
				LEFT JOIN movie_images mi
					ON mi.movie_id=m.movie_id
				LEFT JOIN images i
					ON i.image_id=mi.image_id
					AND i.image_cat_id='{$cinema_data['image_cat_id']}'
					AND i.status='ok'
					AND (mi.cinema_id IN(" . implode(',', $cinema_ids) . ") OR (i.priority=1 AND i.exclusive!=1))
				LEFT JOIN classifications c
					ON c.class_id=m.class_id
				LEFT JOIN classifications c2
					ON c2.class_id=ml.class_id
				WHERE $cinema_sql 
					AND ml.status='ok'
					$extra_conditions
				GROUP BY m.movie_id 
				$having
				ORDER BY $order_by
				LIMIT $limit
		";
  
		//coming soon
		//timezone support here needs to be dynamic based on the cinema's location
		} else if ($type=='cs') {
			if (isset($orig_cinema_id)) {
				$this_cinema_id=$orig_cinema_id;
			}
			$sql="
				SELECT m.movie_id, m.master_movie_id, m.title, IF(ml.synopsis != '', ml.synopsis, m.synopsis) AS `synopsis`, m.country_id, IF(ml.trailer!='',ml.trailer,m.trailer) AS trailer, m.official_site,
					mc.trailers AS cache_trailers, mc.cast AS cache_cast,
					ml.comments, ml.release_date, ml.priority, ml.event_id, DATE_FORMAT(ml.release_date,'$date_format') AS release_date_f1, DATE_FORMAT(ml.release_date,'$date_format2') AS release_date_f2, ml.event_id AS movie_event_id,
					GROUP_CONCAT(DISTINCT i.image_name ORDER BY IF(mi.cinema_id IN(" . implode(',', $cinema_ids) . "), 1, 0) DESC, mi.cinema_id DESC, i.priority DESC, i.image_id DESC SEPARATOR '|') AS image_names,
					IF(ml.class_id != 0, c2.class, c.class) AS `class`,
					COUNT(DISTINCT s.session_id) AS total_sessions, s.event_id,
					IF(ml.release_date = '0000-00-00', 1, 0) AS tbc
					$extra_select
				FROM movies m
				INNER JOIN movie_cache mc
					ON mc.movie_id=m.movie_id
				INNER JOIN movie_lists ml
					ON ml.movie_id=m.movie_id
				LEFT JOIN sessions s
					ON s.cinema_id = ml.cinema_id
					AND s.movie_id = m.movie_id
					AND s.time >= NOW()
				LEFT JOIN movie_images mi
					ON mi.movie_id=m.movie_id
					$extra_custom_cinema_image
				LEFT JOIN images i
					ON i.image_id=mi.image_id
					AND i.image_cat_id='{$cinema_data['image_cat_id']}'
					AND i.status='ok'
					AND (mi.cinema_id IN(" . implode(',', $cinema_ids) . ") OR (i.priority=1 AND i.exclusive!=1))
				LEFT JOIN classifications c
					ON c.class_id=m.class_id
				LEFT JOIN classifications c2
					ON c2.class_id=ml.class_id
				WHERE $cinema_sql
					AND (ml.release_date > DATE_ADD(NOW(), INTERVAL $timezoneOffset HOUR) OR ml.release_date='0000-00-00') 
					AND ml.status='ok'
				GROUP BY m.movie_id 
				$having
				ORDER BY $order_by
				LIMIT $limit
			";
			if (isset($orig_cinema_id)) {
				$this_cinema_id=$new_cinema_id;
			}
		
		//any movies
		} else if ($type=='all') {
			$sql="
				SELECT m.movie_id, m.master_movie_id, m.title, IF(ml.synopsis != '', ml.synopsis, m.synopsis) AS `synopsis`, m.country_id, IF(ml.trailer!='',ml.trailer,m.trailer) AS trailer, m.official_site, IF(ml.class_explanation != '', ml.class_explanation, m.class_explanation) AS `class_explanation`, IF(ml.duration!='',ml.duration,m.duration) AS duration, IF(MAX(ml.runtime)>0,MAX(ml.runtime),m.runtime) AS runtime,
					mc.trailers AS cache_trailers, mc.cast AS cache_cast,
					GROUP_CONCAT(DISTINCT i.image_name ORDER BY IF(mi.cinema_id IN(" . implode(',', $cinema_ids) . "), 1, 0) DESC, i.priority DESC, i.image_id DESC SEPARATOR '|') AS image_names,
					IF(ml.class_id != 0, c2.class, c.class) AS `class`,
					COUNT(DISTINCT s.session_id) AS total_sessions,
					ml.comments, ml.release_date, ml.priority, ml.feature, ml.event_id, IF(ml.duration!='',ml.duration,m.duration) AS duration, DATE_FORMAT(ml.release_date,'$date_format') AS release_date_f1, DATE_FORMAT(ml.release_date,'$date_format2') AS release_date_f2, ml.event_id AS movie_event_id
					$extra_select
				FROM movies m
				INNER JOIN movie_cache mc
					ON mc.movie_id=m.movie_id
				LEFT JOIN movie_images mi
					ON mi.movie_id=m.movie_id
					$extra_custom_cinema_image
				LEFT JOIN images i
					ON i.image_id=mi.image_id
					AND i.image_cat_id='{$cinema_data['image_cat_id']}'
					AND i.status='ok'
					AND (mi.cinema_id IN(" . implode(',', $cinema_ids) . ") OR (i.priority=1 AND i.exclusive!=1))
				INNER JOIN movie_lists ml
					ON ml.movie_id=m.movie_id
				LEFT JOIN sessions s
					ON s.cinema_id = ml.cinema_id
					AND s.movie_id = m.movie_id
					AND s.time >= NOW()
				LEFT JOIN classifications c
					ON c.class_id=m.class_id
				LEFT JOIN classifications c2
					ON c2.class_id=ml.class_id
				WHERE $cinema_sql
					AND ml.status='ok'
					$extra_conditions
				GROUP BY m.movie_id 
				$having
				ORDER BY $order_by
				LIMIT $limit
			";
		
		//temporary sessions
		} else if ($type=='temp') {
			$sql="
				SELECT m.movie_id, m.title, m.synopsis, m.country_id
				FROM movies m
				INNER JOIN sessions_temp s
					ON s.movie_id=m.movie_id
				WHERE s.cinema_id='$this_cinema_id' 
				GROUP BY m.movie_id 
				ORDER BY $order_by
			";
		}

		$movie_list_res=query($sql);
		
		if (mysql_num_rows($movie_list_res)>0) {
			$n=0;
			unset($tmp,$movies);
			
			//if movies found, assign variables to $movies array
			while ($movie_list_data=mysql_fetch_assoc($movie_list_res)) {
				if (isset($movie_list_data['image_names'])) {
					$image_names = explode('|', $movie_list_data['image_names']);
					$movie_list_data['image_name'] = $image_names[0];
				}
				$movies[$n]['movie_id']						= (isset($movie_list_data['movie_id'])) ? $movie_list_data['movie_id'] : NULL;
				$movies[$n]['title']							= (isset($movie_list_data['title'])) ? $movie_list_data['title'] : NULL;
				$movies[$n]['priority']						= (isset($movie_list_data['priority'])) ? $movie_list_data['priority'] : NULL;
				$movies[$n]['feature']						= (isset($movie_list_data['feature'])) ? $movie_list_data['feature'] : NULL;
				$movies[$n]['country_id']					= (isset($movie_list_data['country_id'])) ? $movie_list_data['country_id'] : NULL;
				$movies[$n]['image_name']					= (isset($movie_list_data['image_name'])) ? $movie_list_data['image_name'] : NULL;
				$movies[$n]['class']							= (isset($movie_list_data['class'])) ? get_classification_translation($movie_list_data['class'], $cinema_data['country_code']) : NULL ;
				$movies[$n]['class_explanation']	= (isset($movie_list_data['class_explanation'])) ? $movie_list_data['class_explanation'] : NULL;
				$movies[$n]['duration']						= (isset($movie_list_data['duration'])) ? $movie_list_data['duration'] : NULL;
				$movies[$n]['runtime']						= (isset($movie_list_data['runtime'])) ? $movie_list_data['runtime'] : NULL;
				$movies[$n]['trailer']						= (isset($movie_list_data['trailer'])) ? $movie_list_data['trailer'] : NULL;
				$movies[$n]['trailers']						= (isset($movie_list_data['cache_trailers'])) ? unserialize($movie_list_data['cache_trailers']) : NULL;
				$movies[$n]['official_site']			= (isset($movie_list_data['official_site'])) ? $movie_list_data['official_site'] : NULL;
				$movies[$n]['event_id']						= (isset($movie_list_data['event_id'])) ? $movie_list_data['event_id'] : NULL;
				$movies[$n]['release_date_raw']		= (isset($movie_list_data['release_date'])) ? $movie_list_data['release_date'] : NULL;
				$movies[$n]['master']							= (!empty($movie_list_data['master_movie_id'])) ? get_movie_basics($movie_list_data['master_movie_id']) : null;
				if ($movies[$n]['release_date_raw']=='0000-00-00') {
					$movies[$n]['release_date']='TBC';
					$movies[$n]['release_date2']='TBC';
				} else {
					$movies[$n]['release_date']=$movie_list_data['release_date_f1'];
					$movies[$n]['release_date2']=$movie_list_data['release_date_f2'];
				}
				$movies[$n]['comments']						= (isset($movie_list_data['comments'])) ? $movie_list_data['comments'] : NULL;
				$movies[$n]['manager_comments']		= (isset($movie_list_data['manager_comments'])) ? $movie_list_data['manager_comments'] : NULL;
				$movies[$n]['synopsis']						= (isset($movie_list_data['synopsis'])) ? $movie_list_data['synopsis'] : NULL;
				$movies[$n]['total_sessions']			= (isset($movie_list_data['total_sessions'])) ? $movie_list_data['total_sessions'] : NULL;
				if ($movies[$n]['release_date_raw']>date('Y-m-d') || $movies[$n]['release_date_raw']=='0000-00-00') { 
					$status='cs'; 
				} else { 
					$status='ns'; 
				}
				$movies[$n]['status']			= $status;
				$movies[$n]['cast'] 			= get_cast($movie_list_data['movie_id']);
				if (has_permission('merge_3d')) {
					if (substr(strtolower($movies[$n]['title']), -3) == ' 3d' || substr(strtolower($movies[$n]['title']), -5) == ' (3d)') {
						$sql = "
							SELECT m.movie_id
							FROM movies m
							INNER JOIN movie_lists ml
								ON ml.movie_id = m.movie_id
								AND ml.cinema_id IN (" . implode(',', $cinema_ids) . ")
							WHERE LOWER(m.title) = '" . mysql_real_escape_string(str_replace(array(' 3d',' (3d)'), array('',''), strtolower($movies[$n]['title']))) . "'
							LIMIT 1
						";
						$root_movie_res = query($sql);
						if (mysql_num_rows($root_movie_res) > 0) {
							$root_movie = mysql_fetch_assoc($root_movie_res);
							$movies[$n]['3D'] = $root_movie['movie_id'];
						}
					} else {
						$sql = "
							SELECT m.movie_id
							FROM movies m
							INNER JOIN movie_lists ml
								ON ml.movie_id = m.movie_id
								AND ml.cinema_id IN (" . implode(',', $cinema_ids) . ")
							WHERE LOWER(m.title) = '" . mysql_real_escape_string(strtolower($movies[$n]['title'])) . " 3d' OR LOWER(m.title) = '" . mysql_real_escape_string(strtolower($movies[$n]['title'])) . " (3d)'
							LIMIT 1
						";
						$d3_movie_res = query($sql);
						if (mysql_num_rows($d3_movie_res) > 0) {
							$d3_movie = mysql_fetch_assoc($d3_movie_res);
							$movies[$n]['2D'] = $d3_movie['movie_id'];
						}
					}
				}
				if (isset($movie_list_data['cinema_ids']) && !empty($movie_list_data['cinema_ids'])) {
					foreach (explode('|',$movie_list_data['cinema_ids']) as $temp) {
						if (!empty($temp)) {
							$movies[$n]['cinema_ids'][$temp] = true;
						}
					}
				}
				if (isset($get_session_labels) && !empty($get_session_labels)) {
					unset($check_for_movie_removal,$keep_movie);
					foreach (explode('|',$movie_list_data['session_labels']) as $temp) {
						if (isset($session_label_filter) && !empty($session_label_filter)) {
							$check_for_movie_removal = true;
							if (!empty($temp) && in_array($temp, $session_label_filter)) {
								$keep_movie = true;
							}
						}
						if (!empty($temp)) {
							$movies[$n]['labels'][$temp] = true;
						}
					}
					if (isset($check_for_movie_removal) && !isset($keep_movie)) {
						unset($movies[$n]);
					}
				}
				//add sessions
				if ($num_sessions > 0 && isset($movies[$n])) {
					$session_array = NULL;
					$raw_data = get_movie_sessions($cinema_ids,$movie_list_data['movie_id'],$session_array,false,'%Y-%m-%d','%l:%i%p',$session_comment_filter,true,$vista_attribute_filter,$session_screen_filter);

          foreach ($cinema_ids as $c) {
            if ($group_by_cinema && isset($raw_data['cinemas'][$c]['movies'])) {
              foreach ($movies[$n] as $m) {
                $tmp[$c][$n] = $movies[$n];
                $tmp[$c][$n]['sessions'] = $raw_data['cinemas'][$c]['movies'][$movie_list_data['movie_id']]['sessions'];
              }
            } else {
              $movies[$n]['sessions'][$c] = isset($raw_data['cinemas'][$c]['movies']) ? $raw_data['cinemas'][$c]['movies'][$movie_list_data['movie_id']]['sessions'] : NULL;
            }
          }
				}
				$n++;
			}
			if (isset($tmp)) {
				$movies = $tmp;
			}
			return $movies;
		}
	}
}
?>