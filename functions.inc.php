<?php
/////////////////////////
// DATATBASE FUNCTIONS //
/////////////////////////

// Query Database
function query($sql) {
    global $mysqli;
    $result = @$mysqli->query($sql);
    if ($mysqli->errno) {
        $error = '<p>mysqli ERROR #' . $mysqli->errno . ':<br><b>' . $mysqli->error . '</b><br>' . $sql . '</p>';
        echo ($error);
        return false;
    } else {
        return $result;
    }
}

// Check database variable
function dbv($value) {
    global $mysqli;
    if (!is_numeric($value)) {
        $value = "'" . $mysqli->real_escape_string($value) . "'";
    }
    return $value;
}

////////////////////////
// TIMEZONE FUNCTIONS //
////////////////////////

// Get difference between timezones
function differenceBetweenTimezones($zone1, $zone2 = null, $format = 'hours') {
    if (!$zone2) {
        $zone2 = ini_get('date.timezone');
    }
    $offset1 = timezone_offset_get(new DateTimeZone($zone1), new DateTime());
    $offset2 = timezone_offset_get(new DateTimeZone($zone2), new DateTime());
    $offset  = $offset1 - $offset2;
    if ($format == 'seconds') {
        return $offset;
    } else {
        return $offset / 60 / 60;
    }
}

// Get Local Timestamp
function localTimestamp($timezone) {
    $date = new DateTime(null, new DateTimeZone($timezone));
    return $date->getTimestamp();
}

//////////////////////////////
// GENERIC STRING FUNCTIONS //
//////////////////////////////

// Function summary()
// will shorten any text ($text) string to a specific number of characters ($chars)
// $round='char' -> round to nearest character, not word
// $suffix -> define a suffix to be appended to the string, default = "..."
function summary($text, $length, $round = 'word', $suffix = '...', $break_middle = false) {
    if (strlen($text) > $length) {
        $length -= min($length, strlen($suffix));
        if ($round == 'char' && !$break_middle) {
            $text = preg_replace('/\s+?(\S+)?$/', '', substr($text, 0, $length + 1));
        } elseif (!$break_middle) {
            $text = substr($text, 0, $length) . $suffix;
        } else {
            $text = substr($text, 0, $length / 2) . $suffix . substr($text, -$length / 2);
        }
    }
    return $text;
}

// Check the HTTP Referrer
function check_referrer($referrer = NULL, $compare = NULL) {
    global $config, $domain;
    if (!$referrer && isset($_SERVER['HTTP_REFERER'])) {
        $referrer = $_SERVER['HTTP_REFERER'];
    }
    // Since referrer can be disabled, skip if still missing
    if (!$referrer) {
        if (!$compare) {
            $compare  = (isset($cinema_domain)) ? $cinema_domain : $config['manage_url'];
            $compare2 = "http://" . $config['manage_url'];
        } else {
            $compare2 = $compare;
        }
        if (ereg("^" . $compare, $referrer) || ereg("^" . $compare2, $referrer)) {
            return true;
        } else {
            die("Bad Referrer");
        }
    }
}

// Check if $string is alphanumeric
function is_alphanumeric($string) { return (preg_match("/^[A-Za-z0-9]+$/i", $string)); }

// Check if $email is in valid email format
function is_email($email) {
    if (preg_match('/^[_a-z0-9+-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i', $email)) {
        return true;
    } else {
        return false;
    }
}

// Check if $name is a valid file name
function valid_file_name($name) {
    $name = str_replace(' ', '-', $name);
    $name = preg_replace('/[^a-zA-Z0-9_-]/', '', $name);
    return $name;
}

// Check if $url is in valid URL format
function valid_url($url) {
    $url = str_replace('http://', '', $url);
    $url = 'http://' . $url;
    return $url;
}

// Replace links with HTML links
function replace_urls($html, $target = NULL, $color = NULL) {
    if ($color) {
        $replace = "<a href='$0'><font color='$color'>$0</font></a>";
    } else {
        $replace = "<a href='$0'>$0</a>";
    }
    $pattern = "/(((http|ftp):\/\/)|mailto:)([a-z0-9\.\$\?\&\/]+)/i";
    $html    = preg_replace($pattern, $replace, $html);
    if ($target) {
        $html = str_replace("<a ", "<a target='$target' ", $html);
    }
    return $html;
}

// Trim to be used with array_walk
function trim_value(&$value, $key, $chars = NULL) { $value = (isset($chars)) ? trim($value, $chars) : trim($value); }

// Encode
function encode($num1, $num2) {
    global $config;
    $num = (($num1 + $num2) * $config['salt_int']) + ($num1 + $num2) + substr($config['salt_int'], 2, 8);
    $num = md5($num);
    $num = substr($num, 2, 8);
    return $num;
}

// Clean up weird characters
function clean_chars($string, $strip_tags = false) {
    
    // Strip tags
    $string = ($strip_tags) ? strip_tags($string) : $string;
    
    // Work with UTF-8
    $string = htmlentities($string, ENT_COMPAT, "UTF-8");
    
    // Convert smart quotes and m-dashes
    $search  = array(
        '&lsquo;',
        '&rsquo;',
        '&ldquo;',
        '&rdquo;',
        '&mdash;',
        '&mdash;'
    );
    $replace = array(
        "'",
        "'",
        '"',
        '"',
        '-',
        '-'
    );
    $string  = str_replace($search, $replace, $string);
    
    // Remove accents such as é Á ó ü
    $string = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde|cedil|ring);/', '$1', $string);
    
    // Add back to a decoded string
    $string = html_entity_decode($string);
    return $string;
}

// Convert 0hr 0min format to number of minutes
function stringtomins($string) {
    if (is_numeric($string)) {
        $mins = (int) $string;
    } else if (preg_match('/(([0-9])( )?(hours|hour|hrs|hr|h))?\s*(([0-9]{1,3})( )?(mins|min|m))?/i', $string, $matches)) {
		$mins = 0;
        $mins += (isset($matches[2])) ? $matches[2] * 60 : 0;
        $mins += (isset($matches[6])) ? $matches[6] : 0;
    }
    return $mins;
}

// Convert number of minutes to 0hr 0min format
function mintohr($runtime) {
    if (!$runtime == 0) {
        $hr = floor($runtime/60);
        $min = $runtime%60;
        $string = $hr.'hr '.$min.'min';
        return $string;
    } else {
        return '';
    }
}

// Validate and tidy up duration format
function duration_tidy($duration, $long_format = false) {
    // Split duration to hours and mins
    $mins = stringtomins($duration);
    $hrs = floor($mins / 60);
    $mins = $mins - $hrs * 60;
    // Convert to tidy format
    $suffix   = array(
        'long' => array(
            'hour',
            'minute'
        ),
        'short' => array(
            'hr',
            'min'
        )
    );
    $suffix   = ($long_format == true) ? $suffix['long'] : $suffix['short'];
    $duration = '';
    if ($hrs > 0) {
        $duration .= $hrs . $suffix[0];
        $duration .= ($hrs != 1) ? 's' : '';
    }
    if ($mins > 0) {
        $duration .= ($hrs > 0) ? ' ' : '';
        $duration .= $mins . $suffix[1];
        $duration .= ($mins != 1) ? 's' : '';
    }
    return $duration;
}

////////////////////////
// MOVIES AND RATINGS //
////////////////////////

// Gets list of movies
// Removes "The" from the begining of titles and reorder
function get_movie_list($sql, $reorder = true, $count_sessions = false) {
    global $mysqli;
    $res = query($sql);
    if ($res->num_rows < 1) {
        return false;
    } else {
        while ($data = $res->fetch_assoc()) {
            $movie_title = $data['title'];
            if ($new_title = preg_match('/^The /', $movie_title)) {
                $movie_title = preg_replace('/^The /', '', $movie_title) . ", The";
            }
            if (isset($count_sessions)) {
                $day_start = date('Y-m-d') . ' 00:00:00';
                // Count the total session times (from today)
                $sql       = "
                    SELECT COUNT(session_id) AS count
                    FROM sessions 
                    WHERE movie_id='{$data['movie_id']}' 
                        AND time>='$day_start'
                ";
                $tmp_res = query($sql);
                $tmp_data = $tmp_res->fetch_assoc();
                $session_count = $tmp_data['count'];
                $session_days_count = count_session_days($data['movie_id'], $day_start, (isset($_SESSION['cinema_data'])) ? $_SESSION['cinema_data']['cinema_id'] : null);
            }
            //$master = (!empty($data['master_movie_id'])) ? get_movie_basics($data['master_movie_id']) : null;
            $movie_list[] = array(
                'title' => $movie_title,
                'movie_id' => $data['movie_id'],
                //'master' => $master,
                'release_date' => (isset($data['release_date'])) ? $data['release_date'] : null,
                //'image_cat_id' => (isset($data['image_cat_id'])) ? $data['image_cat_id'] : null,
                //'priority' => (isset($data['priority'])) ? $data['priority'] : null,
                //'feature' => (isset($data['feature'])) ? $data['feature'] : null,
                //'image_name' => (isset($data['image_name'])) ? $data['image_name'] : null,
                //'cast' => (isset($data['cast'])) ? $data['cast'] : null,
                'distributor' => (isset($data['distributor'])) ? $data['distributor'] : null,
                'session_count' => ($count_sessions) ? $session_count : 0,
                'session_days_count' => ($count_sessions) ? $session_days_count : 0
            );
        }
        if ($reorder) {
            foreach ($movie_list as $value) {
                $sortedarray[] = $value['title'];
            }
            array_multisort($sortedarray, SORT_ASC, $movie_list);
        }
        return $movie_list;
    }
}

// Get list of movies with keyword
function get_movie_search($keyword) {
    $string              = str_replace(' ', '%20', $keyword);
    $content             = file_get_contents("https://www.omdbapi.com/?s=$string&type=movie&r=xml&apikey=cb84a757");
    $arr                 = array();
    $res                 = simplexml_load_string($content);
    $totalPages          = ceil($res['totalResults'] / 10);
    $arr['totalResults'] = $res['totalResults'];
    $arr['response']     = $res['response'];
    for ($i = 1; $i <= $totalPages; $i++) {
        $newContent = file_get_contents("https://www.omdbapi.com/?s=$string&page=$i&type=movie&r=xml&apikey=cb84a757");
        $newRes     = simplexml_load_string($newContent);
        for ($j = 1; $j <= 10; $j++) {
            if ($newRes->result[$j - 1]) {
                $num                           = ($i * 10) - 10 + $j;
                $arr['result'][$num]['title']  = $newRes->result[$j - 1]['title'];
                $arr['result'][$num]['year']   = $newRes->result[$j - 1]['year'];
                $arr['result'][$num]['imdbID'] = $newRes->result[$j - 1]['imdbID'];
                $arr['result'][$num]['type']   = $newRes->result[$j - 1]['type'];
                $arr['result'][$num]['poster'] = $newRes->result[$j - 1]['poster'];
            }
        }
    }
    return $arr;
}

// Get info about movie with IMDB ID
function get_movie_basics($imdbID) {
    $content         = file_get_contents("https://www.omdbapi.com/?i=$imdbID&plot=full&r=xml&apikey=cb84a757");
    $res             = simplexml_load_string($content);
    $arr             = array();
    $arr['imdbID']   = $imdbID;
    $arr['title']    = $res->movie['title'];
    $arr['year']     = $res->movie['year'];
    $arr['rated']    = $res->movie['rated'];
    $arr['released'] = $res->movie['released'];
	$arr['duration'] = $res->movie['duration'];
    $arr['runtime']  = explode(" min",$res->movie['runtime'])[0];
	$arr['cast']     = explode(", ",$res->movie['actors']);
    $arr['synopsis'] = $res->movie['plot'];
    $arr['poster']   = $res->movie['poster'];
	
    return $arr;
}

// Count number of days that have session from a specified date
function count_session_days($movie_id, $date/*, $cinema_id*/) {
    global $mysqli;
    $sql = "
        SELECT substring(time,1,10) AS date
        FROM sessions
        WHERE 
			movie_id='" . $mysqli->real_escape_string($movie_id) . "' 
            AND time>='" . $mysqli->real_escape_string($date) . "'
        GROUP BY date
    ";
    $res = query($sql);
    return $res->num_rows;
}

// Get all days that have sessions
function get_session_days($movie_id = false, $date = false) {
    if (!class_exists('db')) {
        db_pdo();
    }
    $db = new db;
    
    $sql_extra = "";
    if (empty($date)) {
        $date = date('Y-m-d');
    }
    
    $execute = array(
        'date' => $date
    );
    
    $sql = "SELECT substring(time,1,10) AS date ";
    $sql .= "FROM sessions ";
    $sql .= "WHERE time >= :date ";
    if (!empty($movie_id)) {
        $sql_extra .= "AND movie_id = :movie_id ";
        $execute['movie_id'] = $movie_id;
    }
    $sql .= "GROUP BY date ";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($execute);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = NULL;
    
    return $data;
}

// Get full movie list
// Gets now_showing or coming_soon list for current cinema
function get_movie_list_full($type = 'ns', $order_by = 'm.title', $num_sessions = '7', $date_format = '%e %b', $date_format2 = '%e %b', $limit = 100, $session_start = 'today', $movie_array = NULL, $days_of_sessions = NULL, $get_session_labels = false, $size = 'full') {
    global $mysqli, $session_flags, $cinema_data, $config;
    $extra_conditions = "";
    $extra_select     = "";
    $having           = "";
	
    if ($limit > 0) {
        // Timezone
        $timezoneOffset = (!empty($cinema_data['timezoneOffset'])) ? $cinema_data['timezoneOffset'] : 17;
        
        // $cinema_data['image_cat_id'] will be absent if using $bypass_cid
        if (!isset($cinema_data['image_cat_id'])) {
            $cinema_data['image_cat_id'] = 2;
        }
        
        // Get Relative Dates
        $today         = date('j M');
        $tomorrow_time = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y'));
        $tomorrow      = date("j M", $tomorrow_time);
        if ($session_start == 'today') {
            $session_start = date('Y-m-d');
        }
        $session_end = (date('Y') + 5) . date('-m-d');
        if (isset($days_of_sessions) && !empty($days_of_sessions)) {
            $temp          = explode('-', $session_start);
            $session_end   = date('Y-m-d', mktime(0, 0, 0, $temp[1], $temp[2] + $days_of_sessions - 1, $temp[0])) . ' 23:59:59';
            $session_array = array(
                $session_start . ' 00:00:00',
                $session_end
            );
        }
        
        // Additional selection conditions
        if ($type == 'ns' && $num_sessions == 0) {
            $extra_conditions .= " AND m.release_date <= DATE_ADD(NOW(), INTERVAL $timezoneOffset HOUR) "; // Temporary fix, we need dynamic timezone entered here
        }
        
        // Restrict movies shown
        if (is_array($movie_array)) {
            $extra_conditions .= " AND ( ";
            foreach ($movie_array as $m) {
                if (isset($movies_started)) {
                    $extra_conditions .= " OR ";
                } else {
                    $movies_started = true;
                }
                $extra_conditions .= " m.movie_id='$m' ";
            }
            $extra_conditions .= " ) ";
        }
		
        // Get a list of labels used by each movie's sessions
        if (isset($get_session_labels) && !empty($get_session_labels)) {
            $extra_select .= ", GROUP_CONCAT(DISTINCT st.label_id ORDER BY st.label_id DESC SEPARATOR '|') AS session_labels";
        }
		
        // Now Showing
        if ($type == 'ns') {
            $sql = "
                SELECT 
					m.movie_id, 
					m.imdb_id, 
					m.title, 
					m.synopsis, 
					m.trailer, 
					m.classification_id, 
					m.subtitled, 
					m.poster_url, 
					m.custom_poster,
					m.runtime, 
					m.comments, 
					m.release_date,
					COUNT(DISTINCT st.session_id) AS total_sessions, 
					DATE_FORMAT(m.release_date,'$date_format') AS release_date_f1,
					DATE_FORMAT(m.release_date,'$date_format2') AS release_date_f2,
					c.classification
                    $extra_select
                FROM movies m
                INNER JOIN sessions st
                    ON st.movie_id=m.movie_id
                    AND st.time>='$session_start' 
                    AND st.time<='$session_end'
                LEFT JOIN classifications c
                    ON c.classification_id=m.classification_id
                WHERE m.status='ok'
                    $extra_conditions
                GROUP BY m.movie_id 
                $having
                ORDER BY $order_by
                LIMIT $limit
        ";
            
        // Coming Soon
        // Timezone support here needs to be dynamic based on the cinema's location
        } else if ($type == 'cs') {
            $sql = "
                SELECT 
					m.movie_id, 
					m.imdb_id, 
					m.title, 
					m.synopsis, 
					m.trailer,
					m.classification_id, 
					m.subtitled, 
					m.poster_url, 
					m.custom_poster,
					m.runtime, 
					m.comments, 
					m.release_date,
					COUNT(DISTINCT s.session_id) AS total_sessions, 
                    DATE_FORMAT(m.release_date,'$date_format') AS release_date_f1, 
					DATE_FORMAT(m.release_date,'$date_format2') AS release_date_f2, 
                    c.classification,
                    IF(m.release_date = '0000-00-00', 1, 0) AS tbc
                    $extra_select
                FROM movies m
                LEFT JOIN sessions s
                    ON s.movie_id = m.movie_id
                    AND s.time >= NOW()
                LEFT JOIN classifications c
                    ON c.classification_id=m.classification_id
                WHERE (m.release_date > DATE_ADD(NOW(), INTERVAL $timezoneOffset HOUR) OR m.release_date='0000-00-00') 
					AND m.status='ok'
                GROUP BY m.movie_id 
                $having
                ORDER BY $order_by
                LIMIT $limit
            ";
            
        // Any Movies
        } else if ($type == 'all') {
            $sql = "
                SELECT 
					m.movie_id, 
					m.imdb_id, 
					m.title, 
					m.synopsis, 
					m.trailer, 
					m.classification_id, 
					m.subtitled, 
					m.poster_url, 
					m.custom_poster, 
					m.runtime, 
					m.comments,
					m.release_date,
                    COUNT(DISTINCT s.session_id) AS total_sessions,
                    DATE_FORMAT(m.release_date,'$date_format') AS release_date_f1, 
					DATE_FORMAT(m.release_date,'$date_format2') AS release_date_f2, 
                    c.classification
					$extra_select
                FROM movies m
                LEFT JOIN sessions s
                    ON s.movie_id = m.movie_id
                    AND s.time >= NOW()
                LEFT JOIN classifications c
                    ON c.classification_id=m.classification_id
                WHERE m.status='ok'
                    $extra_conditions
                GROUP BY m.movie_id 
                $having
                ORDER BY $order_by
                LIMIT $limit
            ";
        }
        $movie_list_res = query($sql);
        if ($movie_list_res->num_rows > 0) {
            $n = 0;
            unset($tmp, $movies);
            
            // If movies found, assign variables to $movies array
            while ($movie_list_data = $movie_list_res->fetch_assoc()) {
                $movies[$n]['movie_id']          = (isset($movie_list_data['movie_id'])) ? $movie_list_data['movie_id'] : NULL;
                $movies[$n]['imdb_id']           = (isset($movie_list_data['imdb_id'])) ? $movie_list_data['imdb_id'] : NULL;
                $movies[$n]['title']             = (isset($movie_list_data['title'])) ? $movie_list_data['title'] : NULL;
                $movies[$n]['classification']    = (isset($movie_list_data['classification'])) ? $movie_list_data['classification'] : NULL;
                $movies[$n]['class_explanation'] = (isset($movie_list_data['class_explanation'])) ? get_class_explanation($movies[$n]['classification']) : NULL;
                $movies[$n]['runtime']           = (isset($movie_list_data['runtime'])) ? $movie_list_data['runtime'] : NULL;
				$movies[$n]['duration']          = (isset($movie_list_data['runtime'])) ? mintohr($movie_list_data['runtime']) : NULL;
                $movies[$n]['poster_url']        = ($movie_list_data['custom_poster']==1 ? $config['poster_url'].$movie_list_data['movie_id'].'-'.$size.'-custom.jpg' : $config['poster_url'].$movie_list_data['movie_id'].'-'.$size.'-default.jpg');
				$movies[$n]['comments']           = (isset($movie_list_data['comments'])) ? $movie_list_data['comments'] : NULL;
                $movies[$n]['trailer']           = (isset($movie_list_data['trailer'])) ? $movie_list_data['trailer'] : NULL;
                $movies[$n]['release_date_raw']  = (isset($movie_list_data['release_date'])) ? $movie_list_data['release_date'] : NULL;
                if ($movies[$n]['release_date_raw'] == '0000-00-00') {
                    $movies[$n]['release_date']  = 'TBC';
                    $movies[$n]['release_date2'] = 'TBC';
                } else {
                    $movies[$n]['release_date']  = $movie_list_data['release_date_f1'];
                    $movies[$n]['release_date2'] = $movie_list_data['release_date_f2'];
                }
                $movies[$n]['synopsis']         = (isset($movie_list_data['synopsis'])) ? $movie_list_data['synopsis'] : NULL;
                $movies[$n]['total_sessions']   = (isset($movie_list_data['total_sessions'])) ? $movie_list_data['total_sessions'] : NULL;
                if ($movies[$n]['release_date_raw'] > date('Y-m-d') || $movies[$n]['release_date_raw'] == '0000-00-00') {
                    $status = 'cs';
                } else {
                    $status = 'ns';
                }
                $movies[$n]['status'] = $status;
                if (isset($get_session_labels) && !empty($get_session_labels)) {
                    unset($check_for_movie_removal, $keep_movie);
                    foreach (explode('|', $movie_list_data['session_labels']) as $temp) {
                        if (!empty($temp)) {
                            $movies[$n]['labels'][$temp] = true;
                        }
                    }
                    if (isset($check_for_movie_removal) && !isset($keep_movie)) {
                        unset($movies[$n]);
                    }
                }
                // Add sessions
                if ($num_sessions > 0 && isset($movies[$n])) {
                    $session_array = NULL;
                    $raw_data = get_movie_sessions([$movie_list_data['movie_id']], $session_array, false, '%Y-%m-%d', '%l:%i%p', true);
                    $movies[$n]['sessions'] = isset($raw_data['movies']) ? $raw_data['movies'][$movie_list_data['movie_id']]['sessions'] : NULL;
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

// Get cast
/*function get_cast($movie_id, $relationship = 'actor') {
    return false;
}*/

// Get movie
// $get_sessions can be true to show sessions for current cinema, or an array of cinema_id's from which sessions are taken
function get_movie($movie_id, $get_sessions = true, $extra_conditions = NULL, $size = 'full') {
    //db_direct();
    global $mysqli, $cinema_data, $config;
    $return = array();
	
    // Main movie info
    $sql = "
        SELECT 
			m.movie_id, 
			m.imdb_id, 
			m.title, 
			m.runtime,
			m.trailer, 
			m.subtitled, 
			m.last_updated, 
			m.poster_url, 
			m.custom_poster,   
            c.classification, 
			m.classification_id, 
			m.synopsis,  
            GROUP_CONCAT(DISTINCT m.comments SEPARATOR '') AS comments, 
			m.release_date, 
			DATE_FORMAT(m.release_date,'%M %e') AS release_date_format 
        FROM movies m
        LEFT JOIN classifications c
            ON c.classification_id = m.classification_id
        WHERE m.movie_id='".$mysqli->real_escape_string($movie_id)."'
			AND m.status = 'ok'
            $extra_conditions
        GROUP BY m.movie_id
        LIMIT 1
    ";
    $movie_res = $mysqli->query($sql);
    if ($movie_res->num_rows != 1) {
        return false;
    }
    $movie_data = $movie_res->fetch_assoc();
    if ($movie_data['release_date'] == '0000-00-00') {
        $movie_data['release_date_format'] = 'TBC';
    }
    if ($movie_data['release_date'] > date('Y-m-d') || $movie_data['release_date'] == '0000-00-00') {
        $status = 'cs';
    } else {
        $status = 'ns';
    }
    $return['movie_id'] = $movie_id;
    $return['movie'] = $movie_data;
    $return['status'] = $status;
    $return['movie']['classification'] = $movie_data['classification'];
	$return['movie']['class_explanation'] = get_class_explanation($movie_data['classification']);
	$return['movie']['poster_url'] = ($movie_data['custom_poster']==1 ? $config['poster_url'].$movie_data['movie_id'].'-'.$size.'-custom.jpg' : $config['poster_url'].$movie_data['movie_id'].'-'.$size.'-default.jpg');
    
    // Get sessions grouped by date
    if ($get_sessions) {
        if (is_array($get_sessions)) {
            $raw_data = get_movie_sessions([$movie_id],NULL,false,'%Y-%m-%d','%l:%i%p',true);
            foreach ($get_sessions as $s) {
                $return['sessions'][$s] = isset($raw_data['movies']) ? $raw_data['movies'][$movie_id]['sessions'] : NULL;
            }
        } else {
            $raw_data = get_movie_sessions([$movie_id],NULL,false,'%Y-%m-%d','%l:%i%p',true);
            $return['sessions'] = (isset($raw_data['movies'])) ? $raw_data['movies'][$movie_id]['sessions'] : NULL;
        }
    }
    return $return;
    
}

// Get a single random movie
/*function get_random_movie($cinema_id, $priority = null) {
    $sql = "
        SELECT ml.movie_id, ml.release_date,
            m.title,
            i.image_name
        FROM movie_lists ml
        INNER JOIN movies m
            ON m.movie_id = ml.movie_id
        INNER JOIN movie_images mi
            ON mi.movie_id=m.movie_id
        INNER JOIN images i
            ON i.image_id=mi.image_id
            AND i.image_cat_id = 1
            AND i.priority = 1
        WHERE ml.cinema_id = '" . $mysqli->real_escape_string($cinema_id) . "'
            AND ml.status = 'ok'
    ";
    $sql .= "
        ORDER BY RAND()
        LIMIT 1
    ";
    $top_movie_res = query($sql);
    return $top_movie_res->fetch_assoc();
}*/

// Get movie sessions
function get_movie_sessions($movie_id_array = NULL, $time_array = NULL, $get_image = false, $format_date = '%Y-%m-%d', $format_time = '%l:%i%p', $allow_session_duplicates = true) {
    global $config, $mysqli, $cinema_data;
    
    if (!isset($time_array)) {
        date_default_timezone_set($cinema_data['timezone']);
        $time_array = array(
            date('Y-m-d') . ' 00:00:00',
            date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y') + 5)) . ' 23:59:59'
        );
    }
    $max_sessions_per_day = 0;
    
    // Cinema info
    $sql = "
        SELECT se.name, se.city
        FROM settings se
        WHERE se.id='1'
    ";
    $res = query($sql);
    $data = $res->fetch_assoc();
    $session_array['cinema_data'] = array(
        'name' => $data['name'],
        'city' => $data['city']
    );
        
    // If no $movie_id_array specified, select all movies for this cinema with sessions >= today
    if (!$movie_id_array) {
        unset($movie_ids);
        $sql = "
            SELECT 
				DISTINCT(s.movie_id),
				session_id
            FROM sessions s
            WHERE s.time >= '" . $mysqli->real_escape_string($time_array[0]) . "' 
                AND s.time <= '" . $mysqli->real_escape_string($time_array[1]) . "'
                $extra_sql_conditions
        ";
        $res = query($sql);
        while ($data = $res->fetch_assoc()) {
            $movie_ids[] = $data['movie_id'];
        }
    } else {
        $movie_ids = $movie_id_array;
    }
        
    // Loop through each movie
    if (isset($movie_ids) && is_array($movie_ids)) {
        foreach ($movie_ids as $movie_id) {
            unset($num_sessions, $movie_session_summary);
                
            // Get sessions if they exist
            $sql = "
                SELECT 
					s.*, 
					DATE_FORMAT(s.time,
					'$format_date') AS session_date, 
					LOWER(
						CAST(
							DATE_FORMAT(s.time,
							'$format_time') AS CHAR(10)
						)
					) AS time_format,
                    sl.label_id, 
					sl.name AS label
                FROM sessions s
                LEFT JOIN session_labels sl
                    ON s.label_id = sl.label_id
                WHERE 
					s.movie_id = '" . $mysqli->real_escape_string($movie_id) . "'
                    AND s.time >= '" . $mysqli->real_escape_string($time_array[0]) . "' 
                    AND s.time <= '" . $mysqli->real_escape_string($time_array[1]) . "'
                    $extra_sql_conditions
                ORDER BY s.time
            ";
            $res = query($sql);
            if ($res->num_rows > 0) {
                $movie_data = get_movie($movie_id, false, NULL);
                $session_duplicates = array();
                while ($data = $res->fetch_assoc()) {
                    if ($allow_session_duplicates || !isset($session_duplicates[$movie_id][$data['time']])) {

                        // Prepare the session data
                        $session_array['movies'][$movie_id]['sessions'][$data['session_date']][$data['session_id']] = array(
                            'id' => $data['session_id'],
                            'timestamp' => $data['time'],
                            'time' => strtolower($data['time_format']),
                            'label' => (isset($data['label'])) ? $data['label'] : NULL,
                            'label_id' => (isset($data['label_id'])) ? $data['label_id'] : NULL,
                        );
                        $session_duplicates[$movie_id][$data['time']] = true;
                        $num_sessions = count($session_array['movies'][$movie_id]['sessions'][$data['session_date']]);
                        if (!isset($max_sessions_per_day) || $max_sessions_per_day < $num_sessions) {
                            $max_sessions_per_day = $num_sessions;
                        }
                        $movie_session_summary[] = $data['session_id'];
                    }
                }
                unset($session_duplicates);
                
                // Get basic movie details
                $session_array['movies'][$movie_id]['movie_data'] = array(
                    'movie_id' => $movie_id,
                    'title' => $movie_data['movie']['title'],
                    'classification' => $movie_data['movie']['classification'],
                    'class_explanation' => get_class_explanation($movie_data['movie']['classification']),
                    'runtime' => $movie_data['movie']['runtime'],
                    'synopsis' => $movie_data['movie']['synopsis'],
                    'cast' => $movie_data['movie']['cache_cast'],
                    'release_date_raw' => $movie_data['movie']['release_date'],
                    'status' => $movie_data['status'],
					'trailer' => $movie_data['trailer'],
                    'session_summary' => $movie_session_summary,
                );
            }
        }
    }
    if ($max_sessions_per_day > 0) {
        $session_array['array_data']['max_sessions_per_day'] = $max_sessions_per_day;
    }
    return $session_array;
}

// Get the details of a single session
// Get sessions
function get_session($session_id) {
    global $mysqli;
    $sql = "
        SELECT 
			s.label_id, 
			s.time, 
			DATE_FORMAT(s.time,'%l:%i%p') AS session_time, 
            sl.name AS label_name
        FROM sessions s
        LEFT JOIN session_labels sl
            ON s.label_id = sl.label_id
        WHERE s.session_id = '" . $mysqli->real_escape_string($session_id) . "'
    ";
    $res = query($sql);
    if ($res->num_rows != 1) {
        return false;
    }
    $data   = $res->fetch_assoc();
    $return = array(
        'session_timestamp' => $data['time'],
        'session_time' => strtolower($data['session_time']),
        'label_id' => (isset($data['label_id'])) ? $data['label_id'] : NULL,
        'label_name' => (isset($data['label_name'])) ? $data['label_name'] : NULL,
    );
    return $return;
}


// Generate a link to a movie page
function movie_link($movie_id, $title = NULL, $section = NULL, $prefix = NULL) {
    global $mysqli;
    if (!isset($db) || !is_object($db)) {
        $db = new db;
    }
    if (isset($movie_title)) {
        $title = valid_file_name($movie_title);
    } else {
        $db   = (!isset($db)) ? new db : $db;
        $sql  = "
            SELECT title 
            FROM movies 
            WHERE movie_id=? 
            LIMIT 1
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute(array(
            $movie_id
        ));
        $data  = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt  = NULL;
        $title = valid_file_name($data['title']);
    }
    $suffix = (isset($section)) ? '-' . $section : '';
    $prefix = (isset($prefix)) ? $prefix : '/';
    $link   = $prefix . '/movies/' . $title . '-' . $movie_id . $suffix . '/';
    return $link;
}

// Get flagged movie list
function get_movies_flagged($cinema_id) {
    $sql    = "
        SELECT m.movie_id, m.title 
        FROM movies m, sessions s
        WHERE s.cinema_id='" . $mysqli->real_escape_string($cinema_id) . "'
            AND s.movie_id=m.movie_id
            AND s.time>=CURDATE()
            AND s.session_preset_group_id != 0
        GROUP BY m.movie_id
        ORDER BY m.title
    ";
    $res    = query($sql);
    $return = array();
    while ($data = $res->fetch_assoc()) {
        $return[] = array(
            'movie_id' => $data['movie_id'],
            'title' => $data['title']
        );
    }
    return $return;
}

// Get sessions today
//default = today
//$day = 'tomorrow'
//$day = 'dd-mm-yyyy' (specific date)
//$add_day = int (increase day by specific number of days)
function get_sessions_today($cinema_id, $day = NULL, $add_day = NULL, $order_by = "m.title,s.time", $get_cast = false, $group_by_cinema = false, $size = 'full') {
    global $get_sessions_today_day, $get_sessions_today_date, $cinema_data, $mysqli, $config;
    date_default_timezone_set($cinema_data['timezone']);
    $session_data = false;
    
    // Use $_REQUEST passed vaiables if none were added in function call
    if (!isset($day) && isset($_REQUEST['day'])) {
        $day = $_REQUEST['day'];
    }
    if (!isset($add_day) && isset($_REQUEST['add_day'])) {
        $add_day = $_REQUEST['add_day'];
    }
    
    // Prepare query
    if ($day == 'tomorrow') {
        $time        = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
        $day         = date('Y-m-d', $time);
        $smarty_day  = "Tomorrow";
        $smarty_date = date('l jS F', $time);
    } elseif ($day) {
        $day         = date('Y-m-d', strtotime($day));
        $smarty_date = date('l jS F', $time);
    } elseif ($add_day) {
        $time        = mktime(0, 0, 0, date("m"), date("d") + $add_day, date("Y"));
        $day         = date('Y-m-d', $time);
        $smarty_date = date('l jS F', $time);
    } else {
        $day         = date('Y-m-d');
        $smarty_day  = "Today";
        $smarty_date = date('l jS F');
    }
	
    // Select data
    $from = $day . ' 00:00:00';
    // $to = $day.' 23:59:59';
    $to   = date('Y-m-d', strtotime($day . ' +1 day')) . ' 02:00:00';
    $sql  = "
        SELECT DISTINCT 
			m.movie_id,
			m.imdb_id,
			m.title,
			m.synopsis,
			m.trailer,
			m.classification_id,
			m.subtitled,
			m.poster_url,
			m.custom_poster,
            s.session_id, 
			s.time, 
			substring(s.time,1,10) AS session_date, 
			LOWER(
				CAST(
					DATE_FORMAT(s.time,
					'%l:%i%p') AS CHAR(10)
				)
			) AS time_format, 
            sl.name AS label, 
            c.classification 
        FROM movies m
        INNER JOIN sessions s
            ON s.movie_id=m.movie_id
        LEFT JOIN session_labels sl
            ON s.label_id=sl.label_id
        LEFT JOIN classifications c
            ON c.classification_id=m.classification_id
        WHERE s.time>='" . $mysqli->real_escape_string($from) . "'
            AND s.time<='" . $mysqli->real_escape_string($to) . "'
        GROUP BY s.session_id
        ORDER BY $order_by
    ";
    $session_res = query($sql);
    
    // Group session data by movie
    unset($tmp);
    while ($sd = $session_res->fetch_assoc()) {
        // Set up the film data if necessary
        if (!isset($session_data[$sd['movie_id']]['movie_id'])) {
            $session_data[$sd['movie_id']] = array(
                'movie_id' => $sd['movie_id'],
				'imdb_id' => $sd['imdb_id'],
                'title' => $sd['title'],
                'poster_url' => ($sd['custom_poster']==1 ? $config['poster_url'].$sd['movie_id'].'-'.$size.'-custom.jpg' : $config['poster_url'].$sd['movie_id'].'-'.$size.'-default.jpg'),
                'classification' => $sd['classification'],
				'class_explanation' => get_class_explanation($sd['classification']),
                'synopsis' => $sd['synopsis'],
                'trailer' => $sd['trailer'],
            );
            /*if ($get_cast) {
                $session_data[$sd['movie_id']]['cast'] = get_cast($sd['movie_id']);
            }*/
        }
        // Prepare the session data
        /*if (empty($sd['external_booking_url'])) {
            if (!empty($sd['bms_Session_lngSessionId']) && !empty($sd['bms_Venue_strCode'])) {
                $sd['external_booking_url'] = "{$global['bms_session_booking_url']}?cid={$sd['bms_Venue_strCode']}&sid={$sd['bms_Session_lngSessionId']}";
            } elseif (!empty($sd['vista_session_id'])) {
                $base_url                   = (!empty($cinema_data['homepage_url'])) ? rtrim($cinema_data['homepage_url'], '/') . '/' : main_cinema_domain($cinema_id);
                $sd['external_booking_url'] = $base_url . "/page_tickets.php?sessionId={$sd['vista_session_id']}&cinemaId={$sd['vista_cinema_id']}";
            }
        }*/
        $s = array(
            'id' => $sd['session_id'],
            'label' => $sd['label'],
            'timestamp' => $sd['time'],
            'time' => $sd['time_format'],
        );
        $session_data[$sd['movie_id']]['sessions'][] = $s;
    }
    if (isset($tmp)) {
        $session_data = $tmp;
    }
    
    // Assign final variables
    $get_sessions_today_day  = isset($smarty_day) ? $smarty_day : NULL;
    $get_sessions_today_date = isset($smarty_date) ? $smarty_date : NULL;
    return $session_data;
}

// Get list of genres for supplied $movie_id
/*function get_genres($movie_id, $link = false, $divider = 'array', $limit = 10) {
    global $base_url;
    $sql       = "
        SELECT g.* 
        FROM genres g
        INNER JOIN movie_genres mg 
            ON g.genre_id=mg.genre_id 
        WHERE mg.movie_id='" . $mysqli->real_escape_string($movie_id) . "'
        ORDER BY g.genre 
        LIMIT $limit";
    $genre_res = query($sql);
    if ($genre_res->num_rows == 0) {
        return false;
    } else {
        $n = 1;
        while ($genre_data = $genre_res->fetch_assoc()) {
            // Apply link
            if (isset($link)) {
                $genre = "<a href='/movies/genre-" . strtolower($genre_data['genre']) . "-" . $genre_data['genre_id'] . ".php'>" . $genre_data['genre'] . "</a>";
            } else {
                $genre = $genre_data['genre'];
            }
            // Create array
            if ($divider == 'array') {
                if (!isset($return)) {
                    $return = array();
                }
                $return[] = $genre;
                // Create string
            } else {
                if (!isset($return)) {
                    $return = '';
                }
                $return .= ($n > 1) ? $divider : '';
                $return .= $genre;
            }
            $n++;
        }
        return $return;
    }
}*/

function get_class_explanation($id) {
	global $mysqli;
	$sql = "
		SELECT class_explanation 
		FROM classifications 
		WHERE classification='".$mysqli->real_escape_string($id)."' 
		   OR classification_id='".$mysqli->real_escape_string($id)."'
		LIMIT 1
	";
    $res = $mysqli->query($sql) or user_error("Gnarly: $sql");
    if ($res->num_rows != 1) {
        return false;
    } else {
		$data = $res->fetch_assoc();
		return $data['class_explanation'];
    }
}

function get_class_id($input) {
	global $mysqli;
	$class = explode("-",$input);
	$sql = "
		SELECT classification_id 
		FROM classifications 
		WHERE classification='".$mysqli->real_escape_string($input)."' 
		   OR classification='".$mysqli->real_escape_string($class[0])."'
		LIMIT 1
	";
    $res = $mysqli->query($sql) or user_error("Gnarly: $sql");
    if ($res->num_rows != 1) {
        return 1;
    } else {
		$data = $res->fetch_assoc();
		return $data['classification_id'];
    }
}

$class = "PG";


function hrmin_convert($string, $return_pieces = false) {
    $pieces = str_split($string, 2);
    $ampm   = 'am';
    if ($pieces[0] > 12) {
        $pieces[0] -= 12;
        $ampm = 'pm';
    }
    if ($return_pieces) {
        return array(
            'hour' => $pieces[0],
            'mins' => $pieces[1],
            'ampm' => $ampm
        );
    } else {
        if (!isset($pieces[1])) {
            $pieces[1] = '00';
        }
        return $pieces[0] . ':' . $pieces[1] . $ampm;
    }
}

/////////////
// CACHING //
/////////////

// Update movie cache
/*function update_movie_cache($movie_id, $sessions_only = false) {
    if (!class_exists('db')) {
        db_pdo();
    }
    $db   = new db;
    // Sessions
    $sql  = "
        SELECT COUNT(session_id) AS count
        FROM sessions
        WHERE time>=NOW()
            AND movie_id=?
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute(array(
        $movie_id
    ));
    $sessions = $stmt->fetch();
    $stmt     = NULL;
    if ($sessions_only) {
        // Insert the new values
        $sql  = "
            UPDATE movie_cache
            SET session_count=:session_count
            WHERE movie_id=:movie_id
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute(array(
            ':session_count' => $sessions['count'],
            ':movie_id' => $movie_id
        ));
        $stmt = NULL;
    } else {
        // Trailers
        $sql  = "
            SELECT t.trailer_id, t.trailer_type_id,
                ttf.trailer_format_id, ttf.extension, ttf.size,
                tf.name AS format
            FROM trailers t
            INNER JOIN trailer_trailer_format ttf
                ON ttf.trailer_id = t.trailer_id
            INNER JOIN trailer_formats tf
                ON tf.trailer_format_id = ttf.trailer_format_id
            INNER JOIN movie_trailers mt
                ON mt.trailer_id = t.trailer_id
                AND mt.movie_id = ?
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute(array(
            $movie_id
        ));
        while ($t = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $trailers_raw[$t['trailer_id']][$t['trailer_format_id']]              = $t;
            $trailers_raw[$t['trailer_id']][$t['trailer_format_id']]['file_name'] = $t['trailer_id'] . '_' . $t['trailer_format_id'] . '.' . $t['extension'];
        }
        $stmt = NULL;
        if (isset($trailers_raw)) {
            foreach ($trailers_raw as $t) {
                $trailers[] = $t;
            }
        }
        // Insert the new values
        $sql  = "
            REPLACE INTO movie_cache
            SET movie_id=:movie_id,
                session_count=:session_count,
                trailers=:trailers
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute(array(
            ':movie_id' => $movie_id,
            ':session_count' => $sessions['count'],
            ':trailers' => (isset($trailers)) ? serialize($trailers) : ''
        ));
        $stmt = NULL;
    }
    return true;
}*/

// Update site stats
/*function update_site_stats() {
    if (!class_exists('db')) {
        db_pdo();
    }
    $db   = new db;
    // Count sessions
    $sql  = "
        SELECT COUNT(session_id) AS count
        FROM sessions
        WHERE time>NOW()
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $sessions           = $stmt->fetch();
    $stmt               = NULL;
    $fields['sessions'] = $sessions['count'];
    // Count cinemas
    $sql                = "
        SELECT COUNT(cinema_id) AS count
        FROM cinemas
        WHERE status='ok'
    ";
    $stmt               = $db->prepare($sql);
    $stmt->execute();
    $cinemas           = $stmt->fetch();
    $stmt              = NULL;
    $fields['cinemas'] = $cinemas['count'];
    // Count movies
    $sql               = "
        SELECT COUNT(movie_id) AS count
        FROM movies
        WHERE status='ok'
    ";
    $stmt              = $db->prepare($sql);
    $stmt->execute();
    $movies           = $stmt->fetch();
    $stmt             = NULL;
    $fields['movies'] = $movies['count'];
    // Count ratings
    $sql              = "
        SELECT COUNT(rating_id) AS count
        FROM movie_ratings
    ";
    $stmt             = $db->prepare($sql);
    $stmt->execute();
    $ratings           = $stmt->fetch();
    $stmt              = NULL;
    $fields['ratings'] = $ratings['count'];
    // Count reviews
    $sql               = "
        SELECT COUNT(review_id) AS count
        FROM movie_reviews
        WHERE status='ok'
    ";
    $stmt              = $db->prepare($sql);
    $stmt->execute();
    $reviews           = $stmt->fetch();
    $stmt              = NULL;
    $fields['reviews'] = $reviews['count'];
    // Count members
    $sql               = "
        SELECT COUNT(user_id) AS count
        FROM users
        WHERE status='ok'
            OR status='new'
    ";
    $stmt              = $db->prepare($sql);
    $stmt->execute();
    $members           = $stmt->fetch();
    $stmt              = NULL;
    $fields['members'] = $members['count'];
    // Count tags
    $sql               = "
        SELECT COUNT(tag_id) AS count
        FROM movie_tags
    ";
    $stmt              = $db->prepare($sql);
    $stmt->execute();
    $tags           = $stmt->fetch();
    $stmt           = NULL;
    $fields['tags'] = $tags['count'];
    // Update table
    $sql            = "
        UPDATE site_stats
        SET value=:value
        WHERE name=:name
    ";
    $stmt           = $db->prepare($sql);
    $stmt->bindParam(':value', $value);
    $stmt->bindParam(':name', $name);
    foreach ($fields as $name => $value) {
        $stmt->execute();
    }
    $stmt = NULL;
}*/

// Update the movie_list cache
// This adds an event_id against a movie_list if one is found against a session, otherwise clears the movie_list event_id
// Not good if we have set a movie-level event against the movie_list already
// Only useful when importing from BMS, dangerous otherwise
/*function update_movie_list_cache($movie_id, $cinema_id) {
    if (!class_exists('db')) {
        db_pdo();
    }
    $db   = new db;
    $sql  = "
        SELECT DISTINCT event_id
        FROM sessions
        WHERE movie_id = ?
            AND cinema_id = ?
        ORDER BY event_id DESC
        LIMIT 1
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute(array(
        $movie_id,
        $cinema_id
    ));
    $result   = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt     = NULL;
    $event_id = (isset($result['event_id']) && !empty($result['event_id'])) ? $result['event_id'] : 0;
    $sql      = "
        UPDATE movie_lists
        SET event_id = ?
        WHERE movie_id = ?
            AND cinema_id = ?
        LIMIT 1
    ";
    $stmt     = $db->prepare($sql);
    $stmt->execute(array(
        $event_id,
        $movie_id,
        $cinema_id
    ));
    $stmt = NULL;
}*/

function get_additional_images($cinema_id, $movie_id) {
    if (!class_exists('db')) {
        db_pdo();
    }
    $db   = new db;
    $sql  = "
        SELECT i.image_id, i.image_name, i.priority
        FROM images i
        INNER JOIN movie_images mi
            ON mi.image_id = i.image_id
            AND mi.cinema_id = :cinema_id
            AND mi.movie_id = :movie_id
        WHERE i.image_cat_id = 3
            AND i.status = 'ok'
        ORDER BY i.priority ASC
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute(array(
        ':cinema_id' => $cinema_id,
        ':movie_id' => $movie_id
    ));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt   = NULL;
    return $result;
}

/////////////
// CINEMAS //
/////////////

// Check cinema permissions
function has_permission($p, $c = NULL) {
    global $mysqli;
    db_direct();
    if (isset($p)) {
        $sql = "SELECT * FROM permissions WHERE id='1' AND $p=1";
        $tmp_res = $mysqli->query($sql) or user_error("Gnarly: $sql");
        if ($tmp_res->num_rows != 1) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

// Get an event name from an event_id
function get_event_info($event_id, $cinema_id) {
    $event_id = preg_replace('/[^0-9]/', '', $event_id);
    $sql      = "
        SELECT name, description
        FROM `events`
        WHERE event_id = '" . $mysqli->real_escape_string($event_id) . "'
            AND cinema_id = '$cinema_id'
        LIMIT 1
    ";
    $res      = query($sql);
    $data     = $res->fetch_assoc();
    return $data;
}

// Get an label name from a label_id
function get_label_info($label_id, $cinema_id) {
    $label_id = preg_replace('/[^0-9]/', '', $label_id);
    $sql      = "
        SELECT session_preset_group_id AS id, name, comments
        FROM `session_preset_groups`
        WHERE session_preset_group_id = '" . $mysqli->real_escape_string($label_id) . "'
            AND cinema_id = '$cinema_id'
        LIMIT 1
    ";
    $res      = query($sql);
    $data     = $res->fetch_assoc();
    return $data;
}

function main_cinema_domain($cinema_id) {
    $sql  = "
        SELECT url
        FROM cinema_domains
        WHERE cinema_id = '" . $mysqli->real_escape_string($cinema_id) . "'
            AND mode = 'w'
            AND `primary` = 1
        LIMIT 1
    ";
    $res  = query($sql);
    $data = $res->fetch_assoc();
    if (!empty($data['url'])) {
        $tidyUrl = 'http://' . rtrim(str_replace('http://', '', $data['url']), '/') . '/';
        return $tidyUrl;
    } else {
        return '/';
    }
}

//////////////////////
// SMARTY FUNCTIONS //
//////////////////////

function smarty_clear_cache($movie_id = NULL, $area = NULL, $user_id = NULL, $reset_smarty = false, $clear_all = false) {
    global $mysqli, $config, $smarty;
    if ((!isset($smarty) || $reset_smarty)) {
        include($config['cinema_dir']."inc/smarty_vars.inc.php");
    }
    // Create array of all pages that may contain movie data
    $areas = array(
        'coming_soon',
        'homepage',
        'whats_on',
        'whats_on_today'
    );

    // If $movie_id is passed, also clear data for that specific movie, plus all other generic pages
    if (isset($movie_id)) {
        $smarty->clearCache(NULL, "movie-".$movie_id);
        foreach ($areas as $a) {
            $smarty->clearCache(NULL, $a);
        }
    }
	
	// Reset all templates
	if ($clear_all) {
		$smarty->clearAllCache();
	} 
	
    // If $movie_id is passed with no $cinema_id, clear cache for all movies using this movie, plus all other generic pages for these cinemas
    if (isset($movie_id)) {
        $sql = "
            SELECT DISTINCT movie_id
            FROM movies
            WHERE movie_id = '" . $mysqli->real_escape_string($movie_id) . "'
                AND status = 'ok'
        ";
        $res = $mysqli->query($sql) or user_error("Gnarly: $sql");
        while ($data = $res->fetch_assoc()) {
            if (!isset($smarty)) {
                include($config['cinema_dir']."inc/smarty_vars.inc.php");
            }
            $smarty->clearCache(NULL, "movie_" . $movie_id);
            foreach ($areas as $a) {
                $smarty->clearCache(NULL, $a);
            }
        }
    }
    
    // Check for $area variable and clear accordingly
    else if (isset($area)) {
        $smarty->clearCache(NULL, $cinema_id . '|generic_page|' . $area);
        $smarty->clearCache(NULL, $cinema_id . '|' . $area);
        if (strstr($area, 'home')) {
            $smarty->clearCache(NULL, $cinema_id . '|homepage');
        }
        if (strstr($area, 'global')) {
            $smarty->clearCache(NULL, $cinema_id);
        }
    } else {
        return false;
    }
	
}

///////////////
// REPORTING //
///////////////

// Checks for both confirmation and error messages 
function check_msg($conf = null, $er = null) {
    if ($conf == null && isset($_REQUEST['conf'])) {
        $conf = $_REQUEST['conf'];
    }
    if ($er == null && isset($_REQUEST['er'])) {
        $er = $_REQUEST['er'];
    }
    if ($er = check_er($er, false)) {
        return $er;
    } else if ($conf = check_confirm($conf, false)) {
        return $conf;
    } else {
        return false;
    }
}

function check_confirm($msg, $echo_message = true) {
    global $global;
    if ($msg) {
        $msg = "
			<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\"><img src='".$config['manage_url']."inc/icons/icon_tick_greenbutton.gif' alt='ok' width='15' height='15' align='absmiddle'> 
			{$msg} 
			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
				<span aria-hidden=\"true\">&times;</span>
			</button>
			</div>
		";
		if ($echo_message) {
            echo $msg;
        } else {
            return $msg;
        }
    } else {
        return false;
    }
}

function check_er($msg, $echo_message = true) {
    global $global;
    if ($msg) {
        $msg = "
			<div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\"><img src='".$config['manage_url']."inc/icons/icon_exclaim_onyellow.gif' alt='ok' width='15' height='15' align='absmiddle'> 
			<strong>Error: </strong>
			{$msg} 
			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
				<span aria-hidden=\"true\">&times;</span>
			</button>
			</div>
		";
		if ($echo_message) {
            echo $msg;
        } else {
            return $msg;
        }
    } else {
        return false;
    }
}

function check_notice($msg, $echo_message = true) {
    if ($msg) {
        $msg = "
			<div class=\"alert alert-warning alert-dismissible fade show\" role=\"alert\"><img src='".$config['manage_url']."inc/icons/icon_exclaim_onyellow.gif' alt='ok' width='15' height='15' align='absmiddle'> 
			{$msg} 
			<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
				<span aria-hidden=\"true\">&times;</span>
			</button>
			</div>
		";
        if ($echo_message) {
            echo $msg;
        } else {
            return $msg;
        }
    } else {
        return false;
    }
}
?>