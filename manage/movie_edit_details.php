<? 
require("inc/manage.inc.php");

if (check_cinema()) {
	
	$sessions_displayed=21;
	$friendly_date_format="l jS F";
	
	// Save changes
	if (isset($_POST['submit']) && isset($_POST['movie_id'])) {
		// Update movie details
		if (isset($_POST['tba'])) {
			$release_date="0000-00-00";
		} else {
			$release_date=$_POST['y']."-".$_POST['m']."-".$_POST['d'];
		}
		if (has_permission('sessions')) {
			// Get generic movie data
			$sql="
				SELECT 
					m.movie_id, 
					m.title, 
					m.synopsis, 
					m.classification_id, 
					m.runtime, 
					m.trailer,
					c.classification,
					c.classification_explanation
				FROM movies m
				LEFT JOIN classifications c
					ON c.classification_id = m.classification_id
				WHERE movie_id = '{$_REQUEST['movie_id']}'
			"; 
			$movie_res = query($sql);
			$original_movie_data = $movie_res->fetch_assoc();
			
			// for the following, only use the post data if it differs from the original movie data
			$synopsis = (!isset($_POST['synopsis']) || $original_movie_data['synopsis'] === $_POST['synopsis']) ? '' : $_POST['synopsis'];
			$class_id = (!isset($_POST['classification_id']) || $original_movie_data['classification_id'] === $_POST['classification_id']) ? '1' : $_POST['classification_id'];
			$runtime = (!isset($_POST['runtime']) || $original_movie_data['runtime'] === $_POST['runtime']) ? '' : $_POST['runtime'];
			$trailer = (!isset($_POST['trailer']) || $original_movie_data['trailer'] === $_POST['trailer']) ? '' : $_POST['trailer'];
			if (!isset($_POST['class_explanation']) || $original_movie_data['class_explanation'] === $_POST['class_explanation']) {
				$class_explanation = '';
			} elseif (isset($_POST['class_explanation']) && $_POST['class_explanation'] == '') {
				$class_explanation = ' ';
			} else {
				$class_explanation = $_POST['class_explanation'];
			}
			if (isset($_POST['alias'])) {
				$alias_sql = ', alias = "' . $mysqli->real_escape_string($_POST['alias']) . '"';
			}
			$sql=sprintf("
				UPDATE movies 
				SET release_date=%s, 
					synopsis=%s,
					classification_id=%s,
					comments=%s, 
					runtime=%s,
					trailer=%s
					$alias_sql
				WHERE movie_id=%s
				",
				dbv($release_date),
				dbv($synopsis),
				dbv($class_id),
				dbv(isset($_POST['comments'])?$_POST['comments']:''),
				dbv($runtime),
				dbv($trailer),
				dbv($_POST['movie_id'])
			);
		} else {
			$sql=sprintf("
				UPDATE movies 
				SET release_date=%s 
				WHERE movie_id=%s
				",
				dbv($release_date),
				dbv($_POST['movie_id'])
			);
		}
		query($sql);
		
		// Upload image
		if (isset($_FILES['poster']['error']) && ($_FILES['poster']['error']=='UPLOAD_ERR_OK' || $_FILES['poster']['error']==0)) {
			require($config['libs_dir'].'images.inc.php');
			// Get an image_id
			$sql="
				INSERT INTO images 
				SET image_cat_id=2,
					status='tmp'
			";
			$mysqli->query($sql) or user_error("Gnarly: $sql");
			$image_id = $mysqli->insert_id;
			//store raw image
			$title = (isset($original_movie_data['title'])) ? $original_movie_data['title'] : 'custom_cinema_image' ;
			$image_name = valid_file_name($title)."_".$image_id;
			$new_image_path	= $global['movie_image_dir'].$image_id.'/raw.jpg';
			$resize = image_resize_auto($_FILES['poster']['tmp_name'], $new_image_path, 'raw');
			//if upload successful, remove any old images for this cinema and update db with new image name and status
			$raw_size = getimagesize($_FILES['poster']['tmp_name']);
			if (isset($resize)) {
				remove_custom_images($_POST['movie_id'], $_SESSION['cinema_data']['cinema_id']);
				$sql = "
					UPDATE images 
					SET image_name='$image_name', 
						width = '{$raw_size[0]}',
						height = '{$raw_size[1]}',
						priority = 100,
						status='ok' 
				";
				if (in_array($_SESSION['cinema_data']['cinema_id'], array(1191,1192,1193))) {
					$sql .= ", exclusive = 1 ";
				}
				$sql .= "WHERE image_id='$image_id' ";
				$mysqli->query($sql) or user_error("Gnarly: $sql");
				$sql = "
					INSERT INTO movie_images
					SET movie_id = '{$_POST['movie_id']}',
						image_id = '$image_id',
						cinema_id = '{$_SESSION['cinema_data']['cinema_id']}'
				";
				$mysqli->query($sql) or user_error("Gnarly: $sql");
				update_movie_image_sizes($image_id);
			}
		}

		//upload additional images
		if (isset($_SESSION['cinema_data']['cinema_id']) && !empty($_FILES['additional_images']['tmp_name'][0])) {
			require_once($global['libs_dir'].'images.inc.php');
			$error = null;
			foreach ($_FILES['additional_images']['error'] as $i) {
				if (!empty($i)) {
					$error = 'One or more of the images you were uploading had errors, please try again.';
					break;
				}
			}
			$allowed_mime_types = array('image/jpeg', 'image/pjpeg', 'image/gif', 'image/png');
			foreach ($_FILES['additional_images']['type'] as $i) {
				if (!in_array($i, $allowed_mime_types)) {
					$error = 'One or more of the images you were uploading had an unsupported file format, images must be in JPG or PNG format.';
					break;
				}
			}
			if (!$error) {
				foreach ($_FILES['additional_images']['tmp_name'] as $i) {
					//get an image_id
					$sql="
						INSERT INTO images 
						SET image_cat_id = 3,
							status = 'tmp'
					";
					$mysqli->query($sql) or user_error("Gnarly: $sql");
					$image_id = $mysqli->insert_id;
					//store raw image
					$image_name = 'ai_' . $image_id;
					$image_dest	= $global['movie_image_dir'] . $image_id . '/raw.jpg';
					$resize = image_resize_auto($i, $image_dest, 'raw');
					if (!empty($resize)) {
						$sql="
							UPDATE images 
							SET image_name = '" . $mysqli->real_escape_string($image_name) . "',
								priority = 200,
								status='ok'
							WHERE image_id = '" . $mysqli->real_escape_string($image_id) . "'
						";
						$mysqli->query($sql) or user_error("Gnarly: $sql");
						$sql = "
							INSERT INTO movie_images
							SET movie_id = '" . $mysqli->real_escape_string($_POST['movie_id']) . "',
								image_id = '" . $mysqli->real_escape_string($image_id) . "',
								cinema_id = '" . $mysqli->real_escape_string($_SESSION['cinema_data']['cinema_id']) . "'
						";
						$mysqli->query($sql) or user_error("Gnarly: $sql");
					}
				}
			}
		}
		
		//sort additional images
		if (!empty($_POST['additional_images_order'])) {
			$priority = 200;
			foreach (explode(',', $_POST['additional_images_order']) as $i) {
				$sql = "
					UPDATE images
					SET priority = '" . $mysqli->real_escape_string($priority) . "'
					WHERE image_id = '" . $mysqli->real_escape_string($i) . "'
				";
				$mysqli->query($sql) or user_error("Gnarly: $sql");
				$priority++;
			}
		}
		
		//delete additional images
		if (!empty($_POST['additional_image_delete'])) {
			foreach ($_POST['additional_image_delete'] as $i) {
				delete_movie_image($i);
			}
		}
		
		//tidy up
		smarty_clear_cache($_SESSION['cinema_data']['cinema_id'],$_POST['movie_id']);

		//redirect
		if (isset($_POST['redir']) && $_POST['redir']=='edit_sessions') {
			$location="movie_edit_sessions.php?movie_id=".$_POST['movie_id'];
		} else {
			$location="movies.php?changed_id=".$_POST['movie_id'];
		}
		header("Location: $location");
		exit;
		
	} 
	
	//get movie info
	elseif (isset($_REQUEST['movie_id'])) {
		
		//create array of dates for next two weeks
		$days=24*3600;
		for ($n=0; $n<$sessions_displayed; $n++) {
			$session_dates[$n]['friendly']=date("D j M", time()+$days*$n);
			$session_dates[$n]['mysql']=date("Y-n-j", time()+$days*$n);
		}
		
		//get the list of classifications
		$sql = "
			SELECT c.class_id, IFNULL(cc.class, c.class) AS class, cc.country, c.class AS root_class
			FROM classifications c
			LEFT JOIN classification_country cc
				ON c.class_id = cc.class_id
				AND cc.country = '{$_SESSION['cinema_data']['country_code']}'
			ORDER BY c.class_priority ASC
		";
		$class_res=query($sql);
		while ($r=$class_res->fetch_assoc()) {
			$class_data[] = $r;
		}
				
		//get generic movie data
		$sql="
			SELECT m.movie_id, m.title, m.synopsis, m.country_id, m.class_id, m.class_explanation, m.duration, m.trailer,
				c.class
			FROM movies m
			LEFT JOIN classifications c
				ON c.class_id=m.class_id
			WHERE movie_id='".$mysqli->real_escape_string($_REQUEST['movie_id'])."'
		"; 
		$movie_res=query($sql);
		$movie_data=$movie_res->fetch_assoc();
		
		//images
		$sql = "
			SELECT i.image_name, i.image_cat_id, i.priority, mi.cinema_id, IF(mi.cinema_id='{$_SESSION['cinema_data']['cinema_id']}', 1, 0) AS custom_image
			FROM images i
			INNER JOIN movie_images mi
				ON i.image_id=mi.image_id
			WHERE i.image_cat_id = 2
				AND mi.movie_id='".$mysqli->real_escape_string($_REQUEST['movie_id'])."'
				AND (mi.cinema_id=0 OR mi.cinema_id='{$_SESSION['cinema_data']['cinema_id']}' OR i.priority=1)
			ORDER BY custom_image DESC, i.priority DESC, i.image_id
			LIMIT 1
		";
		$poster_res=query($sql);
		$poster_data=$poster_res->fetch_assoc();
		
		//personalised movie data
		$sql="
			SELECT *
			FROM movie_lists 
			WHERE cinema_id='{$_SESSION['cinema_data']['cinema_id']}' 
				AND movie_id='".$mysqli->real_escape_string($_REQUEST['movie_id'])."'
		"; 
		$cinema_movie_res=query($sql);
		$cinema_movie_data=$cinema_movie_res->fetch_assoc();
		
		//split up the date
		$release_date=explode('-',$cinema_movie_data['release_date']);
		$d=$release_date[2];
		$m=$release_date[1];
		$y=$release_date[0];
	}

	// Get events
	$sql="
		SELECT e.*,
			COUNT(s.session_id) AS session_count
		FROM events e
		INNER JOIN sessions s
			ON e.event_id = s.event_id
		WHERE e.cinema_id = '{$_SESSION['cinema_data']['cinema_id']}'
		GROUP BY e.event_id
		HAVING session_count > 0
		ORDER BY e.event_id DESC
	";
	$event_res = $mysqli->query($sql) or user_error("Gnarly: $sql");
	$events = array();
	while ($e = $event_res->fetch_assoc()) {
		$events[$e['event_id']] = $e['name'];
	}

}

?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?
include('includes/meta_tags.inc.php');
?>
    <script src="includes/generic.js" type="text/javascript"></script>
    <title><?=$title_prefix?><?=(check_cinema())?"Movie Lists &amp; Sessions":"Website Content Management For Cinemas";?></title>
    <link href="includes/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!--<link href="includes/css/styles.css" rel="stylesheet" type="text/css">-->
    <link href="includes/css/dashboard.css" rel="stylesheet">
  </head>
  <body>
  <?
include("includes/header.inc.php");
?>
  <div class="container-fluid">
    <div class="row">
      <?
include("includes/nav.inc.php");
?>

						<? if (check_cinema()) { ?>
							<? if (!empty($error)) { ?>
								<div class="notice error"><?=$error?>TEST</div>
							<? } ?>
							<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
					          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
					            <h1 class="h2">Edit Movie Details</h1>
					            <div class="btn-toolbar mb-2 mb-md-0">
					              <div class="btn-group mr-2">
					                <? button_1("< Back To Movie List","movies.php","back","right") ?>
					              </div>
					            </div>
					          </div>
              <form name="form" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data"> 
	              <h3><?=$movie_data['title']?></h3>
	              <ul>
	              	<li>Please use the boxes below to change the details of this movie.</li>
	              	<li>If you have no confirmed release date please select the TBC box.</li>
	              </ul>
	              <table border="0" cellspacing="0" cellpadding="1">
									<? if (has_permission('sessions_own')) { ?>
									<tr>
										<td align="right"><strong>Classification</strong></td>
										<td nowrap>&nbsp;</td>
										<td>
											<select name="class_id" id="class_id"<?=(empty($cinema_movie_data['class_id'])) ? ' disabled="disabled"' : ''?>><?
												$selected_class_id=($cinema_movie_data['class_id'])?$cinema_movie_data['class_id']:$movie_data['class_id'];
												foreach($class_data as $key => $class) {
													?><option value="<?=$class['class_id']?>" <? if ($class['class_id']==$selected_class_id) { echo "selected"; } ?>><?=$class['class']?></option><? 
												}
											?></select>
											<label><input type="checkbox" name="class_explanation_edit" value="true" class="edit_toggle" data-inputid="class_id" data-defaultvalue="<?=$movie_data['class_id']?>"<?=(empty($cinema_movie_data['class_id'])) ? ' checked="checked"' : ''?>>Default</label>
										</td>
									</tr>
									<tr>
										<td align="right" nowrap><strong>Explanation</strong></td>
										<td nowrap>&nbsp;</td>
										<td>
											<input name="class_explanation" type="text" id="class_explanation" value="<?=(!empty($cinema_movie_data['class_explanation'])) ? $cinema_movie_data['class_explanation'] : $movie_data['class_explanation']?>"<?=(empty($cinema_movie_data['class_explanation'])) ? ' disabled="disabled"' : ''?> size="46" maxlength="100">
											<label><input type="checkbox" name="class_explanation_edit" value="true" class="edit_toggle" data-inputid="class_explanation" data-defaultvalue="<?=$movie_data['class_explanation']?>"<?=(empty($cinema_movie_data['class_explanation'])) ? ' checked="checked"' : ''?>>Default</label>
										</td>
									</tr>
									<tr>
										<td align="right" nowrap><strong>Duration</strong></td>
										<td nowrap>&nbsp;</td>
										<td>
											<input name="duration" type="text" id="duration" value="<?=(!empty($cinema_movie_data['duration'])) ? $cinema_movie_data['duration'] : $movie_data['duration']?>"<?=(empty($cinema_movie_data['duration'])) ? ' disabled="disabled"' : ''?> size="46" maxlength="100">
											<label><input type="checkbox" name="class_explanation_edit" value="true" class="edit_toggle" data-inputid="duration" data-defaultvalue="<?=$movie_data['duration']?>"<?=(empty($cinema_movie_data['duration'])) ? ' checked="checked"' : ''?>>Default</label>
										</td>
									</tr>
									<tr>
										<td align="right" valign="top"><strong>Comments</strong></td>
										<td>&nbsp;</td>
										<td><input type="text" name="comments" id="comments" value="<?=$cinema_movie_data['comments']?>" size="46"></td>
									</tr>
									<tr>
										<td align="right" nowrap><strong>YouTube Trailer</strong></td>
										<td nowrap>&nbsp;</td>
										<td>
											<input name="trailer" type="text" id="trailer" value="<?=(!empty($cinema_movie_data['trailer'])) ? $cinema_movie_data['trailer'] : $movie_data['trailer']?>"<?=(empty($cinema_movie_data['trailer'])) ? ' disabled="disabled"' : ''?> size="46" maxlength="100">
											<label><input type="checkbox" name="class_explanation_edit" value="true" class="edit_toggle" data-inputid="trailer" data-defaultvalue="<?=$movie_data['trailer']?>"<?=(empty($cinema_movie_data['trailer'])) ? ' checked="checked"' : ''?>>Default</label>
										</td>
									</tr>
									<tr>
										<td align="right" valign="top"><strong>Synopsis</strong></td>
										<td>&nbsp;</td>
										<td>
											<textarea name="synopsis" id="synopsis" cols="60" rows="6"<?=(empty($cinema_movie_data['synopsis'])) ? ' disabled="disabled"' : ''?>><?=(!empty($cinema_movie_data['synopsis'])) ? $cinema_movie_data['synopsis'] : $movie_data['synopsis']?></textarea>
											<label><input type="checkbox" name="class_explanation_edit" value="true" class="edit_toggle" data-inputid="synopsis" data-defaultvalue="<?=htmlspecialchars($movie_data['synopsis'])?>"<?=(empty($cinema_movie_data['synopsis'])) ? ' checked="checked"' : ''?>>Default</label>
										</td>
									</tr>
									<? /* if (has_permission('feature_movies')) { ?>
									<tr>
										<td align="right"><strong>Feature</strong></td>
										<td>&nbsp;</td>
										<td><label><input name="feature" type="checkbox" id="feature" value="1"<? if (isset($feature) && !empty($feature)) { echo ' checked'; } ?>> This is a featured movie</label></td>
									</tr>
									<? } */ } ?>
									<tr>
										<td align="right" nowrap><strong>Release Date</strong></td>
										<td nowrap>&nbsp;</td>
										<td nowrap>
											<select name="d" id="d" <? if ($d=="00") { echo " disabled"; } ?>><? 
												for($n=1;$n<=31;$n++) { ?><option value="<? echo $n ?>" <? if ($d==$n) { echo "selected"; } ?>><? echo $n ?></option><? } ?>
											</select>
											<select name="m" id="m" <? if ($m=="00") { echo " disabled"; } ?>><?
												$months=array(1=>"January","February","March","April","May","June","July","August","September","October","November","December");
												for($n=1;$n<=count($months);$n++) {
												?><option value="<? echo $n ?>" <? if ($m==$n) { echo "selected"; } ?>><? echo $months[$n] ?></option><? } ?>
											</select>
											<select name="y" id="y" <? if ($y=="00") { echo " disabled"; } ?>><?
												for($n=date('Y')-1;$n<=date('Y')+1;$n++) {
												?><option value="<?=$n?>" <? if ($n==$y) { echo "selected"; } ?>><?=$n?></option><? } ?>
											</select>
											<input name="tba" type="checkbox" id="tba" value="y" <? if ($cinema_movie_data['release_date']=="0000-00-00") { echo "checked"; } ?> onClick="disableDate(this.form)"><label for="tba">TBC</label>
										</td>
									</tr>
									<? if (!empty($events) || !empty($cinema_movie_data['alias'])) { ?>
										<tr>
											<td align="right" valign="top"><strong>Redirect to Event</strong></td>
											<td>&nbsp;</td>
											<td><select name="alias"><option value="">This film does not redirect anywhere</option><?
													foreach ($events as $event_id => $event_name) {
														$url = "/event-$event_id.php";
														$selected = ($cinema_movie_data['alias'] == $url) ? ' selected = "selected"' : '' ;
														?><option value="<?=$url?>"<?=$selected?>><?=$event_name?></select><?
													}
												?></select></td>
										</tr>
									<? } ?>
									<tr class="padTop">
										<td align="right" valign="top"><strong>Change Poster</strong></td>
										<td>&nbsp;</td>
										<td>
											<input type="file" name="poster"><br>
											<? if (isset($poster_data['image_name'])) { ?>
												<img src="<?=$global['movie_image_url'].$poster_data['image_name']?>_small.jpg" height="118" />
											<? } else { ?>
												<em>This movie has no poster</em>
											<? } ?>
										</td>
									</tr>
									<? if (has_permission('additional_images')) { ?>
										<tr class="padTop">
											<td align="right" valign="top"><strong>Additional Images</strong></td>
											<td>&nbsp;</td>
											<td><input type="file" name="additional_images[]" multiple="multiple"><br><?
												if ($additional_images = get_additional_images($_SESSION['cinema_data']['cinema_id'], $_REQUEST['movie_id'])) {
													?><ul class="imageSort"><?
													foreach	($additional_images as $i) {
														?><li id="<?=$i['image_id']?>"><img src="<?=$global['movie_image_url'] . $i['image_name']?>_aithumb.jpg" width="50" height="50" /> <label><input type="checkbox" name="additional_image_delete[]" value="<?=$i['image_id']?>"> Delete</label></li><?
													}
													?></ul><input type="hidden" name="additional_images_order" value=""><?
												}
											?></td>
										</tr>
									<? } ?>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>
											<p>
												<strong>Where to next?</strong><br>
												<input name="redir" type="radio" id="redir_2" value="movie_list" checked> <label for="redir_2">Back to movie list</label><br>
												<input name="redir" type="radio" id="redir_3" value="edit_sessions"> <label for="redir_3">Edit sessions</label><br>
											<p>
											<input name="movie_id" type="hidden" value="<?=$_REQUEST['movie_id']?>">
											<input name="submit" type="submit" class="submit" value="Save Changes">
											<br>
											<br>
											<br>
											<br>
											<? button_1("Remove From List","movies.php?delete_movie=".$_REQUEST['movie_id'],"x","","y","Are you sure you want to remove this movie from your list?") ?>
										</td>
									</tr>
								</table>
	            </form>
						<? } else { ?>
						    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
						          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
						            <h1 class="h2">Edit Movie Details</h1>
						          </div>
							  <p><? check_notice("Either you are not logged in or you do not have permission to use this feature.") ?></p>
						<? } ?>


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="includes/jquery-ui.min.js"></script>
<script type="text/javascript" src="includes/generic.js"></script>
<script type="text/javascript" src="includes/movieEdit.js"></script>

<? include("includes/footer.inc.php") ?> 
</body>
</html>
