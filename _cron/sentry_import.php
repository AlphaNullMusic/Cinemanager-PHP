#!/usr/local/bin/php
<?

// Show all errors
ini_set('display_errors',1); 
error_reporting(E_ALL);

/////////////////////////////////////////////////////////////
// Checks Sentry XML data and updates sessions accordingly //
/////////////////////////////////////////////////////////////

$global_root_dir = dirname(dirname(__FILE__)).'/';
require($global_root_dir."global_settings.inc.php");
require($global_root_dir."global_functions.inc.php");
require($global_root_dir."_libs/simplexml.class.php");
$xml = new simplexml;

//get the classifications for reference
$classification_translation = array(
	'G'  => 1,
	'PG' => 2,
	'M'  => 3,
	'13' => 4,
	'15' => 5,
	'16' => 6,
	'18' => 7,
	'Exempt' => 13	
);

//get the movies for reference
$sql = "
	SELECT sentry_cinema_id, movie_id, sentry_film_id
	FROM sentry_movies
";
$res = query($sql);
while ($data = mysql_fetch_assoc($res)) {
	$sentry_movie_translation[$data['sentry_cinema_id'].'|'.$data['sentry_film_id']] = $data['movie_id'];
}

//loop through the xml files
$sql = "SELECT * FROM sentry_cinema";
$xml_res = query($sql);
while ($data = mysql_fetch_assoc($xml_res)) {

	//get the cinemas' movie lists for reference
	$cinema_id = $data['cinema_id'];
	$sentry_cinema_id = $data['sentry_cinema_id'];
	$sql = "
		SELECT movie_list_id, movie_id, status
		FROM movie_lists
		WHERE cinema_id = $cinema_id
	";
	$res = query($sql);
	$cinema_list = array();
	while ($tmp = mysql_fetch_assoc($res)) {
		if ($tmp['status'] == 'ok') {
			$cinema_list['ok'][$tmp['movie_id']] = true;
		} else {
			$cinema_list['expired'][$tmp['movie_id']] = true;
		}
	}
	unset($tmp);

	//read the XML file
	$file = (substr($data['data_file'], 0, 7) === "http://") ? $data['data_file'] : $global['external_dir'] . $data['data_file'];
	$data = $xml->xml_load_file($file,"array");
	if (is_array($data)) {
		$movies_updated = array();
		if (isset($data['film_list']) && isset($data['session_list'])) {
			//check that all movies are in the database
			foreach ($data['film_list']['film'] as $movie) {
				$m = $movie['@attributes'];
				if (isset($m['film_id'])) {
					$title = ucwords(str_replace('3d', '3D', strtolower(urldecode($movie['title']))));
					//do we already have a reference to this movie?
					if (isset($sentry_movie_translation[$sentry_cinema_id.'|'.$m['film_id']])) {
						$movie_id = $sentry_movie_translation[$sentry_cinema_id.'|'.$m['film_id']];
						//update the duration as those often change
						$sql = "
							UPDATE movie_lists
							SET duration = '".mysql_real_escape_string($movie['length_mins'])."'
							WHERE cinema_id = '$cinema_id'
								AND movie_id = '$movie_id'
						";
						query($sql) or die(mysql_error());
						//update the title if this film was created just for this cinema
						$sql = "
							UPDATE movies
							SET title = '".mysql_real_escape_string($title)."'
							WHERE movie_id = '$movie_id'
								AND sentry_film_id = '{$sentry_cinema_id}|{$m['film_id']}'
						";
						query($sql) or die(mysql_error());
					} else {
						//search for the movie by title
						$sql = "
							SELECT movie_id
							FROM movies
							WHERE title LIKE '".mysql_real_escape_string($title)."%'
								AND master_movie_id = 0
							ORDER BY CHAR_LENGTH(title) ASC
							LIMIT 1
						";
						$res = query($sql);
						if (mysql_num_rows($res) == 1) {
							$tmp = mysql_fetch_assoc($res);
							$movie_id = $tmp['movie_id'];
							$sentry_movie_translation[$sentry_cinema_id.'|'.$m['film_id']] = $movie_id;
							//update sentry_movies table for faster searching next time
							$sql = "
								INSERT INTO sentry_movies
								SET sentry_cinema_id = '$sentry_cinema_id',
									sentry_film_id = '".mysql_real_escape_string($m['film_id'])."',
									movie_id = {$tmp['movie_id']}
							";
							query($sql) or die(mysql_error());
						} else {
							//movie could not be found so add it to the database
							$sql = "
								INSERT INTO movies 
								SET status = 'ok', 
									date_listed = CURDATE(),
									release_date = CURDATE()
							";
							$insert = array(
								'title' => $title,
								'synopsis' => (isset($movie['synopsis']) && strlen(preg_replace('/[^a-z0-9]/i','',urldecode($movie['synopsis']))) > 0) ? $movie['synopsis'] : "A synopsis for " . urldecode($title) . " will be added soon.",
								'class_id' => (isset($classification_translation[$movie['classification']])) ? $classification_translation[$movie['classification']] : 0,
								'duration' => duration_tidy($movie['length_mins']),
								'runtime' => $movie['length_mins'],
								'sentry_film_id' => $sentry_cinema_id.'|'.$m['film_id']
							);
							foreach ($insert as $key => $value) {
								$sql .= ", $key = '".mysql_real_escape_string($value)."' ";
							}
							query($sql) or die(mysql_error());
							$movie_id = mysql_insert_id();
							$sentry_movie_translation[$sentry_cinema_id.'|'.$m['film_id']] = $movie_id;
							//notify of the update
							$to = 'sessions@moviemanager.biz';
							$subject = 'New Movie Added by Sentry Import';
							$message = urldecode($title) . ":\n {$global['admin_url']}admin/movies.php?action=edit&movie_id=$movie_id";
							mail($to,$subject,$message);
						}
					}
					//record what we just did, using the $insert variable means we can call these variables when updating movie_lists later on
					$movies_updated[$movie_id] = (isset($insert)) ? $insert : true ;
				}
			}
			
			//remove all existing sessions for this cinema from today onwards (which have no presets)
			$sql = "
				SELECT sentry_session_id
				FROM sessions
				WHERE cinema_id = $cinema_id
				AND session_preset_group_id != 0
				AND time >= CURDATE()
			";
			$res = query($sql);
			$sessions_not_updated = array();
			while ($tmp = mysql_fetch_assoc($res)) {
				$sessions_not_updated[] = $tmp['sentry_session_id'];
			}
			$sql = "
				DELETE FROM sessions
				WHERE cinema_id = $cinema_id
				AND session_preset_group_id = 0
				AND time >= CURDATE()
			";
			query($sql);
			//loop through and insert new sessions
			foreach ($data['session_list']['session'] as $session) {
				//tidy up the useful variables
				$s = $session['@attributes'];
				$sentry_session_id = $s['session_id'];
				$movie_id = $sentry_movie_translation[$sentry_cinema_id.'|'.$session['movie_id']];
				$external_booking_url = $session['booking_link'];
				$session = $session['schedule_date'] . ' ' . $session['schedule_time'];
				$session_timestamp = strtotime($session);
				if (!in_array($sentry_session_id, $sessions_not_updated)) {
					//make sure the movie is in the cinema's movie list, add if not
					//should probably update duration and classification here just in case it's not logged for this cinema
					if (isset($cinema_list['expired'][$movie_id])) {
						$sql = "
							UPDATE movie_lists
							SET status = 'ok'
							WHERE cinema_id = $cinema_id
								AND movie_id = $movie_id
							LIMIT 1
						";
						query($sql);
						$cinema_list['ok'][$movie_id] = true;
					} elseif (!isset($cinema_list['ok'][$movie_id])) {
						//get some basic film info from our central database
						$sql = "
							SELECT release_date, duration
							FROM movies
							WHERE movie_id = $movie_id
						";
						$res = query($sql);
						$tmp = mysql_fetch_assoc($res);
						//override duration if possible
						if (isset($movies_updated[$movie_id]['duration']) && $movies_updated[$movie_id]['duration']!='') {
							$tmp['duration'] = $movies_updated[$movie_id]['duration'];
						}
						//update movie list
						$sql = "
							INSERT INTO movie_lists
							SET cinema_id = $cinema_id,
								movie_id = $movie_id,
								release_date = '".mysql_real_escape_string($tmp['release_date'])."',
								duration = '".mysql_real_escape_string($tmp['duration'])."',
								status = 'ok'
						";
						query($sql);
						$cinema_list['ok'][$movie_id] = true;
					}
					//insert the session
					$sql = "
						INSERT INTO sessions
						SET cinema_id = $cinema_id,
							movie_id = $movie_id,
							time = FROM_UNIXTIME($session_timestamp),
							sentry_session_id = '".mysql_real_escape_string($sentry_session_id)."',
							external_booking_url = '".mysql_real_escape_string($external_booking_url)."'
					";
					query($sql);
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
	} else {
		echo $data;
	}
}

?>