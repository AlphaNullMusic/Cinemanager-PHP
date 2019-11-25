#!/usr/local/bin/php
<?

// Show all errors
ini_set('display_errors',1); 
error_reporting(E_ALL);

ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 180);

/////////////////////////////////////////////////////////////////
// Checks BookMyShow XML data and updates sessions accordingly //
/////////////////////////////////////////////////////////////////

$global_root_dir = dirname(dirname(__FILE__)).'/';
require($global_root_dir."global_settings.inc.php");
require($global_root_dir."global_functions.inc.php");
require($global_root_dir."_libs/simplexml.class.php");
$xml = new simplexml;

$booking_cutoff = 0;

//get the location of the xml files
$sql = "SELECT * FROM bms_xml";
$res = query($sql);
while ($data = mysql_fetch_assoc($res)) {
	$file_locations[$data['bms_xml_id']] = $data['url'];
}

//get the cinema ids for reference
$sql = "
	SELECT bc.*, c.booking_cutoff
	FROM bms_cinema bc
	INNER JOIN cinemas c
		ON bc.cinema_id = c.cinema_id
";
$res = query($sql);
while ($data = mysql_fetch_assoc($res)) {
	$cinema_translation[$data['bms_xml_id']][$data['bms_Venue_strCode']] = $data['cinema_id']; // Store the cinema ID with the BMS code as key
	$cinema_info[$data['cinema_id']]['booking_cutoff'] = $data['booking_cutoff']; // Store the booking_cutoff time with cinema_id as key (this array can be used for other cinema data)
	$cinema_list_tmp[$data['cinema_id']] = true;
}

//$cinema_list_tmp = array(1001 => true);

//get the cinemas' movie lists for reference
$cinema_list = array();
foreach ($cinema_list_tmp as $cinema_id => $value) {
	$sql = "
		SELECT movie_list_id, movie_id, status
		FROM movie_lists
		WHERE cinema_id = $cinema_id
	";
	$res = query($sql);
	while ($tmp = mysql_fetch_assoc($res)) {
		if ($tmp['status'] == 'ok') {
			$cinema_list[$cinema_id]['ok'][$tmp['movie_id']] = true;
		} else {
			$cinema_list[$cinema_id]['expired'][$tmp['movie_id']] = true;
		}
	}
}
unset($cinema_list_tmp);

//get the classifications for reference
$sql = "SELECT class_id, class FROM classifications";
$res = query($sql);
while ($data = mysql_fetch_assoc($res)) {
	$classification_translation[$data['class']] = $data['class_id'];
}

//get all cast members for reference
$sql = "SELECT cast_id, LOWER(name) AS name FROM cast";
$res = mysql_query($sql);
while ($data = mysql_fetch_assoc($res)) {
	$cast_translation[preg_replace('/[^a-z]+/i','',$data['name'])] = $data['cast_id'];
}

//get the movies for reference
$sql = "
	SELECT movie_id, bms_Event_strCode
	FROM movies
	WHERE bms_Event_strCode != ''
";
$res = query($sql);
while ($data = mysql_fetch_assoc($res)) {
	$bms_movie_translation[$data['bms_Event_strCode']] = $data['movie_id'];
}

//get the events for reference
$sql = "
	SELECT event_id, cinema_id, VistaEvent_strCode
	FROM events
	WHERE VistaEvent_strCode != ''
";
$res = query($sql);
while ($data = mysql_fetch_assoc($res)) {
	$bms_event_translation[$data['cinema_id'].$data['VistaEvent_strCode']] = $data['event_id'];
}

//loop through the xml files
foreach ($file_locations as $bms_xml_id => $file) {
	$data = $xml->xml_load_file($file,"array");
	$movies_updated = array();
	
	if (isset($data['Events']) && isset($data['Venues']) && isset($data['Sessions'])) {
		//check that all movies are in the database
		foreach ($data['Events']['Event'] as $movie) {
			$m = $movie['@attributes'];
			if (isset($m['Event_strCode'])) {
				//do we already have a reference to this movie
				//we should probaby update the classification and duration here too
				if (isset($bms_movie_translation[$m['Event_strCode']])) {
					$movie_id = $bms_movie_translation[$m['Event_strCode']];
				} else {
					//search for the movie by title
					$sql = "
						SELECT movie_id
						FROM movies
						WHERE bms_Event_strURLTitle_guess = '".mysql_real_escape_string(strtolower($m['Event_strURLTitle']))."'
					";
					$res = query($sql);
					if (mysql_num_rows($res) == 1) {
						$tmp = mysql_fetch_assoc($res);
						$movie_id = $tmp['movie_id'];
						$bms_movie_translation[$m['Event_strCode']] = $movie_id;
						//update bms_Event_strCode for faster searching next time
						$sql = "
							UPDATE movies
							SET bms_Event_strCode = '".mysql_real_escape_string($m['Event_strCode'])."'
							WHERE movie_id = {$tmp['movie_id']}
							LIMIT 1
						";
						query($sql);
					} else {
						$movie_info = "Information gathered from Book My Show:\n\n";
						//movie could not be found so add it to the database
						if (strlen(preg_replace('/[^a-z0-9]/i','',$m['Event_strCensor'])) == 0) {
							$m['Event_strCensor'] = 'TBC';
							$movie_info .= "Classification: ".$m['Event_strCensor']."\n";
						}
						$title = trim(str_replace('('.$m['Event_strCensor'].')','',$m['Event_strTitle']));
						$sql = "
							INSERT INTO movies 
							SET status = 'ok', 
								date_listed = CURDATE()
						";
						$insert = array(
							'title' => $title,
							'synopsis' => (isset($m['Event_strSynopsis']) && !empty($m['Event_strSynopsis'])) ? $m['Event_strSynopsis'] : "A synopsis for $title will be added soon.",
							'release_date' => $m['Event_dtmReleaseDate'],
							'class_id' => $classification_translation[$m['Event_strCensor']],
							'duration' => duration_tidy($m['Event_intDuration']),
							'runtime' => $m['Event_intDuration'],
							'bms_Event_strCode' => $m['Event_strCode'],
							'bms_Event_strURLTitle_guess' => strtolower($m['Event_strURLTitle'])
						);
						$movie_info .= "Duration: ".$m['Event_intDuration']."\n";
						$movie_info .= "Release Date: ".$m['Event_dtmReleaseDate']."\n";
						$movie_info .= "Synopsis: ".$m['Event_strSynopsis']."\n";
						$movie_info .= (isset($m['Event_strCensor']) && !empty($m['Event_strCensor'])) ? "Classification: ".$m['Event_strCensor'] : '' ;
						foreach ($insert as $key => $value) {
							if ($key == 'release_date') {
								$value = date('Y-m-d', strtotime($value));
							}
							$sql .= ", $key = '".mysql_real_escape_string($value)."' ";
						}
						query($sql);
						$movie_id = mysql_insert_id();
						$bms_movie_translation[$m['Event_strCode']] = $movie_id;
						//add cast if necessary
						unset($cast);
						if (strlen($m['Event_strStarring'])>0) {
							foreach (explode(',',$m['Event_strStarring']) as $actor) {
								$cast[] = array(
									'name' => trim($actor),
									'role' => 'actor'
								);
							}
						}
						if (strlen($m['Event_strDirector'])>0) {
							foreach (explode(',',$m['Event_strDirector']) as $director) {
								$cast[] = array(
									'name' => trim($director),
									'role' => 'actor'
								);
							}
						}
						if (isset($cast)) {
							foreach ($cast as $priority => $c) {
								//get cast id
								$compare = strtolower(preg_replace('/[^a-z]+/i','',$c['name']));
								if (isset($cast_translation[$compare])) {
									$cast_id = $cast_translation[$compare];
								} else {
									$sql = "
										INSERT INTO cast
										SET name='".mysql_real_escape_string($c['name'])."'
									";
									query($sql);
									$cast_id = mysql_insert_id();
								}
								//assign cast to movie
								$sql = "
									INSERT INTO movie_cast
									SET movie_id = $movie_id,
										cast_id = $cast_id,
										relationship = '{$c['role']}',
										priority = $priority
								";
								query($sql);
								if (isset($cast_string)) {
									$cast_string .= ", ";
								} else {
									$cast_string = "Cast: ";
								}
								$cast_string .= $c['name'];
							}
							$movie_info .= $cast_string;
						}
						//add any last movie info to the string
						$movie_info .= "\n";
						$movie_info .= "Movie Manager Movie ID: ".$movie_id."\n";
						if (isset($cinema_translation[$bms_xml_id][$v['Venue_strCode']])) {
							$movie_info .= "Movie Manager Cinema ID: ".$cinema_translation[$bms_xml_id][$v['Venue_strCode']]."\n";
						}
						$movie_info .= "Book My Show Movie ID: ".$m['Event_strCode']."\n";
						$movie_info .= "Book My Show Venue Code: ".$v['Venue_strCode']."\n";
						$movie_info .= "Book My Show Link: http://www.bookmyshow.co.nz/Movies/Movie/".$v['Event_strCode']."\n";
						//notify of the update
						$subject = 'New Movie Added by BMS Import for Cinema (BMS Code ' . $v['Venue_strCode'] . ')';
						if (isset($cinema_translation[$bms_xml_id][$v['Venue_strCode']])) {
							$temp_cinema_id = $cinema_translation[$bms_xml_id][$v['Venue_strCode']];
							if (has_permission('sessions_own', $temp_cinema_id)) {
								$subject = 'New Movie Added by BMS Import for Paying Cinema #' . $temp_cinema_id;
							} else {
								$subject = 'New Movie Added by BMS Import for Non-Paying Cinema #' . $temp_cinema_id;
							}
						}
						$to = 'sessions@moviemanager.biz';
						$message = "$title:\n {$global['admin_url']}admin/movies.php?action=edit&movie_id=$movie_id";
						$message .= "\n\n";
						$message .= $movie_info;
						mail($to,$subject,$message);
					}
				}
				//record what we just did, using the $insert variable means we can call these variables when updating movie_lists later on
				$movies_updated[$movie_id] = (isset($insert)) ? $insert : true ;
			}
		}
		
		//remove all existing sessions for this cinema from today onwards (which have no presets)
		$sessions_not_updated = array();
		foreach ($data['Venues']['Venue'] as $venue) {
			unset($v);
			if (isset($venue['@attributes'])) {
				$v = $venue['@attributes'];
			} else {
				$v = $venue;
			}
			if (isset($v)) {
				if (isset($cinema_translation[$bms_xml_id][$v['Venue_strCode']])) {
					$cinema_id = $cinema_translation[$bms_xml_id][$v['Venue_strCode']];
					$booking_cutoff = $cinema_info[$cinema_id]['booking_cutoff'];
					//we are not updating movies with presets
					//so first make a list of BMS Session ID's for movies with presets
					//this will prevent double-ups when we import the times
					$sql = "
						SELECT bms_Session_lngSessionId
						FROM sessions
						WHERE cinema_id = $cinema_id
							AND session_preset_group_id != 0
							AND time >= CURDATE()
					";
					$res = query($sql);
					while ($tmp = mysql_fetch_assoc($res)) {
						$sessions_not_updated[$cinema_id][] = $tmp['bms_Session_lngSessionId'];
					}
					//now delete previously imported movies without presets (leave all manually entered sessions alone)
					$sql = "
						DELETE FROM sessions
						WHERE cinema_id = $cinema_id
							AND bms_Session_lngSessionId != 0
							AND session_preset_group_id = 0
							AND time >= DATE_ADD(CURDATE(), INTERVAL $booking_cutoff MINUTE)
					";
					query($sql);
				}
			} else {

				echo '<pre>';
				echo '<b>$venue[\'@attributes\'] was missing, here\'s the contents of $venue...</b>';
				print_r($venue);
				echo '</pre>';

			}
		}
		
		//loop through and insert new sessions
		foreach ($data['Sessions']['Session'] as $session) {
			//tidy up the useful variables
			$s = $session['@attributes'];
			if (isset($cinema_translation[$bms_xml_id][$s['Venue_strCode']])) {
				$cinema_id = $cinema_translation[$bms_xml_id][$s['Venue_strCode']];
				$movie_id = $bms_movie_translation[$s['Event_strCode']];
				$session = $s['Session_dtmRealShow'];
				$session_timestamp = strtotime($session);
				$session_comments = (strlen($s['Session_strComments'])==0 && $s['Venue_strCode']=='RLPN') ? 'Rialto' : $s['Session_strComments'] ;
				$bms_session_id = $s['Session_lngSessionId'];
				$bms_event_id = $s['VistaEvent_strCode'];
				$bms_event_name = $s['VistaEvent_strName'];
				if ($s['Film_strCode'] != 'PRIVATE' && $s['Session_strAllowSales'] == 'Y' && (!isset($sessions_not_updated[$cinema_id]) || !in_array($bms_session_id, $sessions_not_updated[$cinema_id]))) {
					//make sure the movie is in the cinema's movie list, add it if not
					//should probably update duration and classification here just in case it's not logged for this cinema
					if (isset($cinema_list[$cinema_id]['expired'][$movie_id])) {
						$sql = "
							UPDATE movie_lists
							SET status = 'ok'
							WHERE cinema_id = $cinema_id
								AND movie_id = $movie_id
							LIMIT 1
						";
						query($sql);
						$cinema_list[$cinema_id]['ok'][$movie_id] = true;
					} elseif (!isset($cinema_list[$cinema_id]['ok'][$movie_id])) {
						//see if we can get the movie basics from the insert earlier, otherwise get it from the movies table
						if (isset($movies_updated[$movie_id]['release_date']) && $movies_updated[$movie_id]['release_date']!='') {
							$tmp = array(
								'release_date' => $movies_updated[$movie_id]['release_date'],
								'duration' => $movies_updated[$movie_id]['duration']
							);
						} else {
							$sql = "
								SELECT release_date, duration
								FROM movies
								WHERE movie_id = $movie_id
							";
							$res = query($sql);
							$tmp = mysql_fetch_assoc($res);
						}
						$sql = "
							INSERT INTO movie_lists
							SET cinema_id = $cinema_id,
								movie_id = $movie_id,
								release_date = '".mysql_real_escape_string($tmp['release_date'])."',
								duration = '".mysql_real_escape_string($tmp['duration'])."',
								status = 'ok'
						";
						query($sql);
						$cinema_list[$cinema_id]['ok'][$movie_id] = true;
					}
					//is this session associated with an event?
					$event_id = 0;
					if (isset($bms_event_id) && !empty($bms_event_id)) {
						if (isset($bms_event_translation[$cinema_id.$bms_event_id])) {
							$event_id = $bms_event_translation[$cinema_id.$bms_event_id];
						} else {
							$sql = "
								INSERT INTO events
								SET cinema_id = $cinema_id,
									name = '".mysql_real_escape_string($bms_event_name)."',
									VistaEvent_strCode = '".mysql_real_escape_string($bms_event_id)."',
									VistaEvent_strName = '".mysql_real_escape_string($bms_event_name)."'
							";
							query($sql);
							$event_id = mysql_insert_id();
							$bms_event_translation[$cinema_id.$bms_event_id] = $event_id;
						}
					}
					//insert the session
					$sql = "
						INSERT INTO sessions
						SET cinema_id = $cinema_id,
							movie_id = $movie_id,
							event_id = $event_id,
							time = FROM_UNIXTIME($session_timestamp),
							comments = '".mysql_real_escape_string($session_comments)."',
							bms_Session_lngSessionId = '".mysql_real_escape_string($bms_session_id)."',
							bms_Venue_strCode = '".mysql_real_escape_string($s['Venue_strCode'])."',
							VistaEvent_strCode = '".mysql_real_escape_string($bms_event_id)."',
							status = '" . mysql_real_escape_string(($s['Session_strSeatsAvail'] == 'Y') ? 'public' : 'soldout') . "'
					";
					query($sql);
				}
			}
		}
	}

	//update caches
	update_site_stats();
	foreach ($movies_updated as $movie_id => $tmp) {
		update_movie_cache($movie_id);
		update_movie_list_cache($movie_id, $cinema_id);
	}
	foreach ($cinema_list as $cinema_id => $tmp) {
		smarty_clear_cache($cinema_id);
	}

}

?>