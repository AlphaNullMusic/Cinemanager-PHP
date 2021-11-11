<?php

function fatal_handler() {
    $error = error_get_last();
    if($error['type'] === E_ERROR) {
        header("Location: movies.php?er=A+fatal+error+occured.+Please+check+your+formatting+and+try+again");
        die();
    }
}
register_shutdown_function("fatal_handler");

//////////
// AUTH //
//////////

// Check cinema
function check_cinema() {
    if (/*isset($_SESSION['cinema_data']) && 
		*/isset($_SESSION['all_cinema_data'])/* &&
        is_array($_SESSION['cinema_data']) && 
        count($_SESSION['cinema_data']) >= 4*/) {
	return true;
    } else {
        return false;
    }
}

// Check admin
function check_admin() {
    if (check_cinema()) {
	if (isset($_SESSION['all_cinema_data']['login_admin']) && $_SESSION['all_cinema_data']['login_admin'] == 1) {
		return true;
	} else {
		return false;
	}
    } else {
	return false;
    }
}

///////////////
// FUNCTIONS //
///////////////

// Delete movie
function delete_movie($movie_id) {
    global $mysqli, $config;
    
    // Delete images
    /*$sql = "
		SELECT i.image_id 
		FROM images i
		INNER JOIN film_images mi
			ON mi.image_id = i.image_id
			AND mi.film_id = '$film_id'
		LIMIT 20
	";
    $res = $mysqli->query($sql) or user_error("Error at: $sql");
    while ($data = $res->fetch_assoc()) {
        delete_movie_image($data['image_id']);
    }*/
    
    // Remove from database
    $sql = "DELETE FROM sessions WHERE movie_id = $movie_id";
    $mysqli->query($sql) or user_error("Error at: $sql");
    $sql = "DELETE FROM custom_images ci INNER JOIN movies m ON m.movie_id = ci.movie_id WHERE m.movie_id = $movie_id";
    $mysqli->query($sql) or user_error("Error at: $sql");
    $sql = "DELETE FROM movies WHERE movie_id=$movie_id LIMIT 1";
    $mysqli->query($sql) or user_error("Error at: $sql");
    
    // Clear Smarty cache for all cinemas using this movie
    smarty_clearCache(NULL, $movie_id);
    return true;
  }

// Delete movie images
//currently deletes from movie_images table completely
//should really just remove it's own entries and if the images are in use by other films then leave them on the server
//for now we'll leave it as it is because duplicate images are only used to clean up BMS imports
/*function delete_movie_image($image_id) {
    global $mysqli, $config;
    
    // Get the film id for this image
    $sql = "
		SELECT film_id
		FROM film_images
		WHERE image_id = '$image_id'
	";
    $res = $mysqli->query($sql);
    if ($res->num_rows > 0) {
        while ($data = $res->fetch_assoc()) {
            // Delete image files and directory
            $filedir = $global['movie_image_dir'] . $image_id;
            empty_directory($filedir, true);
            
            // Remove references from database
            $sql = "DELETE FROM image_cache WHERE image_id = '$image_id'";
            $mysqli->query($sql) or user_error("Error at: $sql");
            $sql = "DELETE FROM image_sizes WHERE image_id = '$image_id'";
            $mysqli->query($sql) or user_error("Error at: $sql");
            $sql = "DELETE FROM images WHERE image_id = '$image_id'";
            $mysqli->query($sql) or user_error("Error at: $sql");
            $sql = "DELETE FROM movie_images WHERE image_id = '$image_id'";
            $mysqli->query($sql) or user_error("Error at: $sql");
            
            // Update the movie table
            $sql = "
				UPDATE films
				SET updated = NOW()
				WHERE film_id = '{$data['film_id']}'
			";
           $mysqli->query($sql) or user_error("Error at: $sql");
            set_random_primary_image($data['film_id']);
            smarty_clear_cache(NULL, $data['film_id']);
        }
    }
}*/

function png2jpg($filePath) {
	global $config;
	$path = $config['tmp_poster_dir'];
	$image = imagecreatefrompng($filePath);
	$bg = imagecreatetruecolor(imagesx($image), imagesy($image));
	imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
	imagealphablending($bg, TRUE);
	imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
	imagedestroy($image);
	$quality = 100; // 0 = worst / smaller file, 100 = better / bigger file 
	$return = $path.basename($filePath).".jpg";
	imagejpeg($bg, $return, $quality);
	imagedestroy($bg);
	return $return;
}

function gif2jpg($filePath) {
	global $config;
	$path = $config['tmp_poster_dir'];
	$image = imagecreatefromgif($filePath);
	$bg = imagecreatetruecolor(imagesx($image), imagesy($image));
	imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
	imagealphablending($bg, TRUE);
	imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
	imagedestroy($image);
	$quality = 100; // 0 = worst / smaller file, 100 = better / bigger file 
	$return = $path.basename($filePath).".jpg";
	imagejpeg($bg, $return, $quality);
	imagedestroy($bg);
	return $return;
}

function save_poster($url, $movie_id, $custom = false) {
	global $config, $mysqli;
	$dir = $config['poster_dir'];
	list($width_orig, $height_orig) = getimagesize($url);
	$ids = array();
	$img_url = $url;
	
	if ($custom == false) {
		$status = 'default';
	} else {
		$status = 'custom';
	}
		
	// For each poster size needed
	foreach ($config['poster_sizes'] as $size) {
		if (isset($size['width']) && isset($size['height'])) {
			$dst_width = $size['width'];
			$dst_height = $size['height'];
		} elseif (isset($size['width'])) {
			$dst_width = $size['width'];
			$dst_height = ($dst_width/$width_orig)*$height_orig;
		} elseif (isset($size['height'])) {
			$dst_height = $size['height'];
			$dst_width = ($dst_height/$height_orig)*$width_orig;
		} elseif (!isset($size['height']) && !isset($size['width']) && $size['name'] == 'full') {
			$dst_width = $width_orig;
			$dst_height = $height_orig;
		}
		
		// Create blank image with correct dimensions
		if ($img = imagecreatetruecolor($dst_width,$dst_height)) {
			// Get poster image, with @ to ignore buggy/large JPEGs
			$tmp_img = @imagecreatefromjpeg($img_url);
			if (!$tmp_img) {
				$tmp_img = imagecreatefromstring(file_get_contents($img_url));
			}
			// Copy and resample poster onto blank with correct dimensions
			if (imagecopyresampled($img, $tmp_img, 0, 0, 0, 0, $dst_width, $dst_height, $width_orig, $height_orig)) {
				$name = $movie_id.'-'.$size['name'].'-'.$status.'.jpg';
				// Output image to file
				if (imagejpeg($img,$dir.$name)) {
					// Free poster from memory
					imagedestroy($img);
               				// Add data to posters table
               				$sql = "
                       				INSERT IGNORE INTO posters
                       				SET
                               				movie_id = '".$mysqli->real_escape_string($movie_id)."',
                              				size = '".$mysqli->real_escape_string($size['name'])."',
                               				name = '".$mysqli->real_escape_string($name)."',
                               				status = '".$mysqli->real_escape_string($status)."'
                			";
                			$mysqli->query($sql) or user_error("Error at ".$sql);
				} else {
					header("Location: ?movie_id={$movie_id}&er=Can't+save+image,+poster+likely+corrupt.+Please+try+again+with+a+new+poster."); 
					//return false; 
					exit;
				}
			} else { 
				header("Location: ?movie_id={$movie_id}&er=Can't+resize+image,+poster+likely+corrupt.+Please+try+again+with+a+new+poster.");
                                //return false;
                                exit; 
			}
		} else { 
			header("Location: ?movie_id={$movie_id}&er=Can't+create+blank+image,+poster+likely+corrupt.+Please+try+again+with+a+new+poster.");
                        //return false;
                        exit;
		}
	}
		
	// Set custom_poster to true in movies table
	if ($custom == true) {
		$sql = "
			UPDATE movies
			SET custom_poster = '1'
			WHERE movie_id = '".$mysqli->real_escape_string($movie_id)."'
		";
		$mysqli->query($sql) or user_error("Error at ".$sql);
	} else {
		$sql = "
			UPDATE movies
			SET custom_poster = '0'
			WHERE movie_id = '".$mysqli->real_escape_string($movie_id)."'
		";
		$mysqli->query($sql) or user_error("Error at ".$sql);
	}
	return true;
}
	
function delete_poster($movie_id, $type = 'custom') {
	global $config, $mysqli;
	$dir = $config['poster_dir'];

	if ($type == 'all') {
		foreach ($config['poster_sizes'] as $size) {
			$path = $dir.$movie_id.'-'.$size['name'].'-';
			@unlink($path.'default.jpg');
			@unlink($path.'custom.jpg');
		}
	} else {
		foreach ($config['poster_sizes'] as $size) {
			$path = $dir.$movie_id.'-'.$size['name'].'-custom.jpg';
			@unlink($path);
		}
	}
	
	// Set custom_poster to 0
	$sql = "
		UPDATE movies
		SET custom_poster = '0'
		WHERE movie_id = '".$mysqli->real_escape_string($movie_id)."'
	";
	$mysqli->query($sql) or user_error("Error at ".$sql);

	// Delete from posters table
	$sql = "
		DELETE FROM posters
		WHERE movie_id = '".$mysqli->real_escape_string($movie_id)."'
		AND status = '".$mysqli->real_escape_string($type)."'
	";
	$mysqli->query($sql) or user_error("Error at ".$sql);
	return true;
}
	
function get_custom_poster($movie_id) {
	global $mysqli;
	$sql = "
		SELECT custom_poster
		FROM movies
		WHERE movie_id = '".$mysqli->real_escape_string($movie_id)."'
	";
	$res = $mysqli->query($sql) or user_error("Error at ".$sql);
	if ($res->num_rows != 1) {
		return false;
	} else {
		$data = $res->fetch_assoc();
		if ($data['custom_poster'] == 1) {
			return true;
		} else {
			return false;
		}
	}
}	

// Remove custom posters, but only if it has not been moved to the common primary image
/*function remove_custom_images($film_id, $cinema_id) {
    global $mysqli;
    $sql = "
		SELECT mi.image_id
		FROM film_images mi
		INNER JOIN images i
			ON i.image_id = mi.image_id
			AND i.priority = 100
			AND image_cat_id IN (1,2)
		WHERE mi.film_id = '$film_id'
			AND mi.id = '$cinema_id'
	";
    $res = $mysqli->query($sql) or user_error("Error at: $sql");
    if ($res->num_rows > 0) {
        // This image is not used anywhere else, remove it completely
        while ($data = $res->fetch_assoc()) { delete_film_image($data['image_id']); }
        $sql = "
			DELETE fi
			FROM film_images fi
			INNER JOIN images i
				ON i.image_id = fi.image_id
				AND image_cat_id IN (1,2)
			WHERE film_id = '$film_id'
				AND cinema_id = '$cinema_id'
		";
        $mysqli->query($sql) or user_error("Error at: $sql");
    }
}*/

// Delete directory contents
function empty_directory($dirname, $rmdir = false) {
    if (is_dir($dirname)) { $dir_handle = opendir($dirname); }
    if (!isset($dir_handle)) { return false; }
    while ($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname . "/" . $file)) {
                unlink($dirname . "/" . $file);
            }
        }
    }
    closedir($dir_handle);
    if ($rmdir) { rmdir($dirname); }
    return true;
}

// Select a random image for this movie and set it as primary
/*function set_random_primary_image($film_id) {
    global $mysqli;
    $sql = "
		SELECT i.image_id
		FROM images i
		INNER JOIN film_images fi
			ON fi.image_id = i.image_id
			AND fi.movie_id = $film_id
		WHERE i.priority = 1
			AND i.image_cat_id = 2
	";
    $res = $mysqli->query($sql) or user_error("Error at: $sql");
    if ($res->num_rows == 0) {
        // Got one image
        $sql = "
			SELECT i.image_id
			FROM images i
			INNER JOIN film_images fi
				ON fi.image_id = i.image_id
				AND fi.movie_id = $film_id
			WHERE i.image_cat_id = 2
				AND i.status = 'ok'
			LIMIT 1
		";
        $res = $mysqli->query($sql) or user_error("Error at: $sql");
        if ($data = $res->fetch_assoc()) {
            $sql = "
				UPDATE images
				SET priority = 1
				WHERE image_id = '{$data['image_id']}'
			";
            $mysqli->query($sql) or user_error("Error at: $sql");
        }
    }
}*/

// Get file extension
function get_file_extension($filename) { return end(explode(".", $filename)); }

// Create page reference from title
function create_page_reference($title) { return strtolower(preg_replace('/[^a-z0-9_-]/i', '-', $title)); }

///////////
// ADMIN //
///////////

/*function notification_new_movie($film_id, $description = null, $movie_title = null, $synopsis = null, $distributor_id = null)
  {
    global $global, $default_email, $mysqli;
    //get title if necessary
    if (!$movie_title)
      {
        $sql = "SELECT title FROM films WHERE film_id='$film_id'";
        $res = $mysqli->query($sql) or user_error("Error at: $sql");
        $data        = $res->fetch_assoc();
        $movie_title = $data['title'];
      }
    //get distributor name if necessary
    if ($distributor_id)
      {
        $sql = "SELECT name FROM distributors WHERE distributor_id='$distributor_id'";
        $res = $mysqli->query($sql) or user_error("Error at: $sql");
        $data        = $res->fetch_assoc();
        $distributor = $data['name'];
      }
    //send email
    $message = "A new movie was just added to the NZ Cinema database:\r\n\r\n";
    $message .= "Movie Title = $movie_title\r\n\r\n";
    if ($synopsis)
      {
        $message .= "Synopsis = $synopsis\r\n\r\n";
      }
    if ($distributor)
      {
        $message .= "Distributor = $distributor\r\n\r\n";
      }
    if ($description)
      {
        $message .= "Notes = $description\r\n\r\n";
      }
    $message .= "{$global['admin_url']}admin/?action=edit&movie_id=$movie_id\r\n\r\n";
    $message .= "This request was made by:\r\n\r\n";
    $message .= "Cinema = {$_SESSION['cinema_data']['name']}\r\n";
    $message .= "Location = {$_SESSION['cinema_data']['city']}\r\n";
    $message .= "Email = {$_SESSION['cinema_data']['email']}\r\n";
    $subject = "New Movie Added by a Cinema";
    $headers = "From: {$_SESSION['cinema_data']['email']}\r\n";
    if (mail($default_email, $subject, $message, $headers))
      {
        return true;
      }
    else
      {
        return false;
      }
  }


function notification_no_poster($movie_id, $type = "poster")
  {
    global $global, $default_email, $mysqli;
    //get movie title
    $sql = "SELECT title FROM films WHERE movie_id='$movie_id'";
    $res = $mysqli->query($sql) or user_error("Error at: $sql");
    $data    = $res->fetch_assoc();
    //send email
    $message = "{$_SESSION['cinema_data']['name']} {$_SESSION['cinema_data']['city']} added the following movie to their website but no $type exists:";
    $message .= "\r\n\r\n";
    $message .= "Movie Title = {$data['title']}";
    $message .= "\r\n\r\n";
    $message .= "{$global['admin_url']}admin/?action=edit&movie_id={$movie_id}";
    $subject = "Movie Update - {$data['title']} is Missing a " . ucwords($type);
    $headers = "From: {$_SESSION['cinema_data']['email']}\r\n";
    if (mail($default_email, $subject, $message, $headers))
      {
        return true;
      }
    else
      {
        return false;
      }
  }*/

/////////////
// BUTTONS //
/////////////

function button_1($text,$link,$confirm = NULL, $confirm_msg = "Are you sure you want to delete?") {
    if ($confirm == 'y') { $confirm = " onClick=\"return confirm('$confirm_msg')\""; }
    echo "<a class=\"btn btn-sm btn-outline-success\" href=\"{$link}\"{$confirm}>{$text}</a>";
}

function button_2($text,$link) {
    echo "<a class=\"btn btn-sm btm-outline-secondary\" href=\"{$link}\"{$confirm}>{$text}</a>";
}

function button_3($text,$link,$confirm = NULL, $confirm_msg = "Are you sure you want to delete?") {
    if ($confirm == 'y') { $confirm = " onClick=\"return confirm('$confirm_msg')\""; }
    echo "<a class=\"btn btn-sm btn-outline-danger\" href=\"{$link}\"{$confirm}>{$text}</a>";
}
  
function button_4($text,$link,$confirm = NULL, $confirm_msg = "Are you sure you want to delete?") {
    if ($confirm == 'y') { $confirm = " onClick=\"return confirm('$confirm_msg')\""; }
    echo "<a class=\"btn btn-sm btn-outline-primary\" href=\"{$link}\"{$confirm}>{$text}</a>";
}

function confirm($msg = NULL, $type = 'ok') {
    global $er;
    if (!$msg) {
        if (!empty($_REQUEST['conf'])) {
            $msg  = $_REQUEST['conf'];
            $type = 'ok';
        } elseif (!empty($_REQUEST['er'])) {
            $msg  = $_REQUEST['er'];
            $type = 'er';
        } elseif (!empty($er)) {
            $msg  = (is_array($er)) ? implode('<br>', $er) : $er;
            $type = 'er';
        }
    }
    if ($msg) {
        if ($type == 'er') {
            $border_color     = "#F5CF17";
            $background_color = "#FFFFCA";
            $icon             = "icon_exclaim_onyellow.gif";
        } else {
            $border_color     = "#89B10C";
            $background_color = "#EDFBC4";
            $icon             = "icon_tick_greenbutton.gif";
        }
        $msg    = stripslashes($msg);
        $return = "<table border='0' cellpadding='6' cellspacing='1' bgcolor='{$border_color}'><tr><td bgcolor='{$background_color}'><img src='/images/{$icon}' width='15' height='15' align='absmiddle'> {$msg}</td></tr></table>";
        echo $return;
    } else {
        return false;
    }
}

/////////////////////
// MANAGE SESSIONS //
/////////////////////

class manage_sessions {
    var $session_date;
    var $movie_id;
    var $old_sessions = array();
    var $pattern = "/([0-2]{0,1}[0-9]{1})([:|\.|\-][0-6]{1}[0-9]{1})?\s*(am|pm|noon)?\s*(\(.+?\))?/i";
    
    function process_session_string($sessions) {
        $this->old_sessions = $this->day_sessions();
        preg_replace_callback($this->pattern, array($this,'insert_individual_session'), $sessions);
        $this->cleanup_day();
    }
    
    function insert_individual_session($matches) {
        global $mysqli;
        $hour           = $matches[1];
        $mins           = (isset($matches[2])) ? $matches[2] : NULL;
        $ampm           = (isset($matches[3])) ? $matches[3] : NULL;
        //$comment        = (isset($matches[4])) ? $matches[4] : NULL;
        
        // Ensure minutes exist and have : separator
        $bad_separators = array(
            '.',
            '-'
        );
        if (!$mins) {
            $mins = ":00";
	} else {
            $mins = str_replace($bad_separators, ':', $mins);
        }
        
        // 24 hour time
        if ($hour > 12) {
            $hour -= 12;
            $ampm = "pm";
        } else if ($ampm == "noon") {
            $ampm = "pm";
        } else if (!$ampm) {
            if ($hour >= 10 && $hour < 12) {
                $ampm = "am";
            } else {
                $ampm = "pm";
            }
        }

        // Comments without brackets
        //if ($comment) { $comment = preg_replace('/\((.+)\)/', '\1', $comment); }

        // Convert to timestamp
        $session_time = $this->session_date . ' ' . $hour . $mins . $ampm;
        $session_time = strtotime($session_time);
        // Check if session exists
        $sql = "
			SELECT session_id
			FROM sessions
			WHERE movie_id = " . $this->movie_id . "
				AND time = FROM_UNIXTIME($session_time)
		";
        $res = $mysqli->query($sql) or user_error("Error at: $sql");
        if ($res->num_rows == 1) {
		$data = $res->fetch_assoc();
        	// Session is already in database, so remove it from old_sessions
        	if (isset($this->old_sessions[$data['session_id']])) {
			unset($this->old_sessions[$data['session_id']]);
		}
        } else {
            // Insert new session into database
            $sql = "
				INSERT INTO sessions
				SET movie_id = " . $this->movie_id . ",
					time = FROM_UNIXTIME($session_time)
			";
            $mysqli->query($sql);
            $session_id = $mysqli->insert_id;
            if (isset($this->old_sessions[$session_id])) {
                unset($this->old_sessions[$session_id]);
            }
        }
        return true;
    }

	// Set label for a session
	function set_label($s,$id = NULL) {
		global $mysqli;
		if (isset($id)) {
			$sql = "
				UPDATE sessions
				SET label_id = $id
				WHERE session_id = $s
			";
		} else {
			$sql = "
				UPDATE sessions
				SET label_id = NULL
				WHERE session_id = $s
			";
		}
		$mysqli->query($sql) or user_error("Error at: ".$sql);
	}

    // Get a list of sessions for a particular day
    function day_sessions() {
        global $mysqli;
        $from   = $this->session_date . ' 00:00:00';
        $to     = $this->session_date . ' 23:59:59';
        $sql    = "
			SELECT session_id
			FROM sessions
			WHERE movie_id = " . $this->movie_id . "
				AND time>='$from'
				AND time<='$to'
		";
        $res    = $mysqli->query($sql) or user_error("Error at: $sql");
        $return = array();
        while ($data = $res->fetch_assoc()) { $return[$data['session_id']] = true; }
        return $return;
    }
    
    // Clear out any old sessions that may have been in the database for this date
    function cleanup_day() {
        foreach ($this->old_sessions as $s => $t) { $this->remove_single($s); }
      }
    
    // Remove a single session
    function remove_single($session_id) {
        global $mysqli;
        
        // Remove sessions
        $sql = "
			DELETE 
			FROM sessions
			WHERE session_id = $session_id
		";
        $mysqli->query($sql) or user_error("Error at: $sql");
    }
    
    // Remove all sessions for a day
    function clear_day($all_days = false) {
        global $mysqli;
        $from = $this->session_date . ' 00:00:00';
        $to   = $this->session_date . ' 23:59:59';
        
        // Remove sessions only
        $sql  = "
			DELETE FROM sessions
			WHERE movie_id = " . $this->movie_id . "
		";
        $sql .= (!$all_days) ? "
			AND time>='$from'
			AND time<='$to'
		" : "";
        $mysqli->query($sql) or user_error("Error at: $sql");
    }
    
    /*function insert_individual_session_temp($session_time) {
        $sql = "
			INSERT INTO sessions_temp
			SET movie_id = " . $this->movie_id . ",
				time = FROM_UNIXTIME($session_time)
		";
        $mysqli->query($sql) or user_error("Error at: $sql");
    }
    
    function clear_temporary_data() {
        $sql = "
			DELETE
			FROM sessions_temp
			WHERE cinema_id = '" . $this->cinema_id . "'
		";
        $mysqli->query($sql) or user_error("Error at: $sql");
    }*/
}

//////////
// MISC //
//////////

function getExtension($str) {
    $i = strrpos($str, ".");
    if (!$i) { return ""; }
    $l = strlen($str) - $i;
    $ext = substr($str, $i + 1, $l);
    return $ext;
}

function format_date($date) {
    $array_months = array(
        '0' => '',
        '1' => 'Jan',
        '2' => 'Feb',
        '3' => 'Mar',
        '4' => 'Apr',
        '5' => 'May',
        '6' => 'Jun',
        '7' => 'Jul',
        '8' => 'Aug',
        '9' => 'Sep',
        '10' => 'Oct',
        '11' => 'Nov',
        '12' => 'Dec'
    );
    
    $year  = substr($date, 0, 4);
    $month = substr($date, 5, 2);
    $day   = substr($date, 8, 2);
    
    if (substr($month, 0, 1) == "0") { $month = substr($month, 1, 1); }
    if (substr($day, 0, 1) == "0") { $day = substr($day, 1, 1); }
    $date = $day . " " . $array_months[$month] . " " . $year;
    return $date;
}

function get_homepage_url() {
    global $mysqli;
    $return = false;
    $sql = "SELECT url from cinemas WHERE id ='1'";
    $res = query($sql);
    
    while ($temp = $res->fetch_assoc($res)) {
        if ($temp['url'] != "") { $return = $temp['url']; }
    }
    return $return;
}

function get_date_6_months_ago() {
    // Today
    $d = date('j');
    $m = date('n');
    $y = date('Y');
    
    // Get date from 6 months ago
    if ($m < 7) {
        $m_former = $m + 6;
        $y_former = $y - 1;
    } else {
        $m_former = $m - 6;
        $y_former = $y;
    }
    $d = leading_zero($d);
    $m_former = leading_zero($m_former);
    $return = $y_former . "-" . $m_former . "-" . $d;
    return $return;
}

function leading_zero($number) {
    if (strlen($number) == 1) {
        $return = '0' . $number;
    } else {
        $return = $number;
    }
    return $return;
}

function makedate($day, $month, $year) {
    $day    = leading_zero($day);
    $month  = leading_zero($month);
    $return = $year . "-" . $month . "-" . $day;
    return $return;
}

function split_date($date) {
    $year  = substr($date, 0, 4);
    $month = substr($date, 5, 2);
    $day   = substr($date, 8, 2);

    if (substr($month, 0, 1) == "0") { $month = substr($month, 1, 1); }
    if (substr($day, 0, 1) == "0") {$day = substr($day, 1, 1); }
    $date = array(
        'day' => $day,
        'month' => $month,
        'year' => $year
    );
    return $date;
}

// Make a string numeric
function numeric($string) { return trim(preg_replace('/[^0-9\.]/i', '', $string)); }

// Convert a camel-case or underscore_separated string to Title Case
function valueToTitle($string) {
    $string = preg_replace('/(?!^)([[:upper:]][[:lower:]]+)/', ' $0', $string);
    $string = preg_replace('/(?!^)([[:lower:]])([[:upper:]])/', '$1 $2', $string);
    $string = str_replace('_', ' ', $string);
    $string = ucwords($string);
    return $string;
}

?>
