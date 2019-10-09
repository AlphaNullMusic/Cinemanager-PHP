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
	
	/*$raw_data = get_movie_list_full('ns','m.title',14,'%W %D','%e %b',100,'today',null,null,true);
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
	
	echo get_class_explanation('TBC');*/
	//echo get_class_id('R-13');
	function save_poster($url, $movie_id, $custom = false) {
		global $config, $mysqli;
		$dir = $config['poster_dir'];
		list($width_orig, $height_orig) = getimagesize($url);
		$ids = array();
		//$url_split = explode('SX300.jpg',$url);
		//$url_split1 = explode('.jpg',$url_split[0]);
		//$img_url = $url_split1[0].'.jpg';
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
			}
			
			$img = imagecreatetruecolor($dst_width,$dst_height);
			$tmp_img = imagecreatefromjpeg($img_url);
			imagecopyresampled($img, $tmp_img, 0, 0, 0, 0, $dst_width, $dst_height, $width_orig, $height_orig);
			
			$name = $movie_id.'-'.$size['name'].'-'.$status.'.jpg';
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
			imagejpeg($img,$dir.$name);
			imagedestroy($img);
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
		
		$sql = "
			UPDATE movies
			SET custom_poster = '0'
			WHERE movie_id = '".$mysqli->real_escape_string($movie_id)."'
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
	$_POST['duration'] = '1hr 20min';
	$d = $_POST['duration'];
	//save_poster('https://m.media-amazon.com/images/M/MV5BMTU2OTAxNjI2OV5BMl5BanBnXkFtZTgwNzc2NjUwODM@._V1_SX300.jpg','1',false);
	echo stringtomins($d);
	//delete_poster(1,'all');
?>