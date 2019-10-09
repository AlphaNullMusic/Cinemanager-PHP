<?php
require("inc/manage.inc.php");

if (check_cinema()) {
    
    $sessions_displayed   = 21;
    $friendly_date_format = "l jS F";
    
    // Save changes
    if (isset($_POST['submit']) && isset($_POST['movie_id'])) {
        // Update movie details
        if (isset($_POST['tba'])) {
            $release_date = "0000-00-00";
        } else {
            $release_date = $_POST['y'] . "-" . $_POST['m'] . "-" . $_POST['d'];
        }
        if (has_permission('sessions')) {
            // Get generic movie data
            $sql = "
                SELECT 
                    m.movie_id, 
                    m.title, 
                    m.synopsis, 
                    m.classification_id, 
                    m.runtime, 
                    m.trailer,
                    c.classification,
                    c.class_explanation
                FROM movies m
                LEFT JOIN classifications c
                    ON c.classification_id = m.classification_id
                WHERE movie_id = '{$_REQUEST['movie_id']}'
            ";
            $movie_res           = query($sql);
            $original_movie_data = $movie_res->fetch_assoc();
            
			/*function hrtomins($string) {
				if (!is_numeric($string)) {
					$string_clean = strtolower(str_replace(' ','',$string));
					$hr_arr = explode('hr', $string);
					$min_arr = explode('min', $hr_arr[1]);
					if (empty($hr_arr) && empty($min_arr) || empty($min_arr)) {
						// Wrong format
						$_REQUEST['er'] = "The duration is in the wrong format, please try 0hr 0min.";
						return false;
					}
					if (is_numeric($hr) && is_numeric($min)) {
						
					}
					$hr = $hr_arr[0];
					$min = $min_arr[0];
					$res = $hr * 60 + $min;
					return $hr;
				}
				return $string;
			}*/
			
            // for the following, only use the post data if it differs from the original movie data
            $synopsis = (!isset($_POST['synopsis']) || $original_movie_data['synopsis'] === $_POST['synopsis']) ? '' : $_POST['synopsis'];
            $classification_id = (!isset($_POST['classification_id']) || $original_movie_data['classification_id'] === $_POST['classification_id']) ? '1' : $_POST['classification_id'];
            $runtime  = (!isset($_POST['duration']) || mintohr($original_movie_data['runtime']) === $_POST['duration']) ? '71' : stringtomins($_POST['duration']);
            $trailer  = (!isset($_POST['trailer']) || $original_movie_data['trailer'] === $_POST['trailer']) ? '' : $_POST['trailer'];
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
            /*$sql = sprintf("
                UPDATE movies 
                SET release_date=%s, 
                    synopsis=%s,
                    classification_id=%s,
                    comments=%s, 
                    runtime=%s,
                    trailer=%s
                    $alias_sql
                WHERE movie_id=%s
                ", dbv($release_date), dbv($synopsis), dbv($classification_id), dbv(isset($_POST['comments']) ? $_POST['comments'] : ''), dbv($runtime), dbv($trailer), dbv($_POST['movie_id']));*/
			$sql = "
				UPDATE movies
				SET
					release_date = '".$mysqli->real_escape_string($release_date)."',
					synopsis = '".$mysqli->real_escape_string($synopsis)."',
					classification_id = '".$mysqli->real_escape_string($classification_id)."',
					comments = '".$mysqli->real_escape_string(isset($_POST['comments']) ? $_POST['comments'] : '')."',
					runtime = '".$mysqli->real_escape_string($runtime)."',
					trailer = '".$mysqli->real_escape_string($trailer)."'
					$alias_sql
				WHERE movie_id = '".$mysqli->real_escape_string($_POST['movie_id'])."'
			";
        /*} else {
            $sql = sprintf("
                UPDATE movies 
                SET release_date=%s 
                WHERE movie_id=%s
                ", dbv($release_date), dbv($_POST['movie_id']));*/
        }
		query($sql);
        die(print_r($_POST));
		//die(print_r($original_movie_data));
		//die($runtime);
		
        // Upload image
        if (isset($_FILES['poster']['error']) && ($_FILES['poster']['error'] == 'UPLOAD_ERR_OK' || $_FILES['poster']['error'] == 0)) {
            if (isset($_POST['movie_id'])) {
                $dir           = $config['poster_dir'];
                $rand_chars    = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $tmp_name      = 'tmp-' . substr(str_shuffle($permitted_chars), 0, 16) . '.jpg';
                $tmp_path      = $dir . $tmp_name;
                $ok            = 1;
                $check         = getimagesize($_FILES['poster']['tmp_name']);
                $imageFileType = strtolower(pathinfo($_FILES['poster']['tmp_name'], PATHINFO_EXTENSION));
                if ($check !== false) {
                    // File is an image
                    if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg") {
                        if (move_uploaded_file($_FILES['poster']['tmp_name'], $tmp_path)) {
                            // Uploaded
                            save_poster($tmp_path, $_POST['movie_id'], true);
                        } else {
                            // Error
                        }
                    }
                } else {
                    // Not an image
                }
                
                @unlink($dir . $tmp_name);
            }
            //require($config['libs_dir'].'images.inc.php');
            
            /*$title = (isset($original_movie_data['title'])) ? $original_movie_data['title'] : 'custom_cinema_image' ;
            $image_name = valid_file_name($title)."_".$image_id;
            $new_image_path    = $global['movie_image_dir'].$image_id.'/raw.jpg';
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
            }*/
        }
        
        // Tidy up
        smarty_clear_cache($_SESSION['cinema_data']['cinema_id'], $_POST['movie_id']);
        
        // Redirect
        if (isset($_POST['redir']) && $_POST['redir'] == 'edit_sessions') {
            $location = "movie_edit_sessions.php?movie_id=" . $_POST['movie_id'];
        } else {
            $location = "movies.php?changed_id=" . $_POST['movie_id'];
        }
        header("Location: $location");
        exit;
    }
    
    // Get movie info
    elseif (isset($_REQUEST['movie_id'])) {
        
        // Create array of dates for next two weeks
        $days = 24 * 3600;
        for ($n = 0; $n < $sessions_displayed; $n++) {
            $session_dates[$n]['friendly'] = date("D j M", time() + $days * $n);
            $session_dates[$n]['mysql']    = date("Y-n-j", time() + $days * $n);
        }
        
        // Get the list of classifications
        $sql = "
            SELECT 
                c.classification_id, 
                c.classification
            FROM classifications c
            ORDER BY c.priority ASC
        ";
        $class_res = query($sql);
        while ($r = $class_res->fetch_assoc()) {
            $class_data[] = $r;
        }
        
        // Get generic movie data
        $sql = "
            SELECT 
                m.movie_id, 
                m.imdb_id,
                m.title, 
                m.synopsis, 
                m.classification_id, 
                c.class_explanation, 
                m.runtime, 
                m.trailer,
				m.status,
				m.release_date,
                c.classification
            FROM movies m
            LEFT JOIN classifications c
                ON c.classification_id=m.classification_id
            WHERE movie_id='" . $mysqli->real_escape_string($_REQUEST['movie_id']) . "'
        ";
        
        $movie_res                   = query($sql);
        $movie_data                  = $movie_res->fetch_assoc();
        $movie_data['custom_poster'] = get_custom_poster($_REQUEST['movie_id']);
		if ($movie_data['status'] == 'exp') {
			header("Location: movies.php?er=This+movie+has+been+deleted.");
		} elseif (!isset($movie_data['status'])) {
			header("Location: movies.php?er=Can't+find+movie.");
		}
		
        // Split up the date
        $release_date = explode('-', $movie_data['release_date']);
        $d            = $release_date[2];
        $m            = $release_date[1];
        $y            = $release_date[0];
    }
}

?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="includes/generic.js" type="text/javascript"></script>
    <title><?php echo $title_prefix; ?><?php echo (check_cinema()) ? "Movie Lists &amp; Sessions" : "Website Content Management For Cinemas"; ?></title>
    <link href="inc/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="inc/css/dashboard.css" rel="stylesheet">
  </head>
  <body>
  <?php include("inc/header.inc.php"); ?>
 <div class="container-fluid">
    <div class="row">
    <?php include("inc/nav.inc.php");
	if (check_cinema()) {
		if (!empty($error)) { ?>
			<div class="notice error">
				<?php echo $error;?>TEST
			</div>
  <?php } ?>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <h1 class="h2">Edit Movie Details</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group mr-2">
                            <?php button_1("< Back To Movie List", "movies.php", "back", "right");?>
                        </div>
                    </div>
                </div>
                <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data"> 
					<h3><?php echo $movie_data['title'];?></h3>
					<ul>
						<li>Please use the boxes below to change the details of this movie.</li>
						<li>If you have no confirmed release date please select the TBC box.</li>
					</ul>
					<table border="0" cellspacing="0" cellpadding="1">
  <?php if (has_permission('sessions')) {?>
                        <?php echo check_msg(); ?>
						<tr>
                            <td align="right"><strong>Classification</strong></td>
                            <td nowrap>&nbsp;</td>
                            <td>
                                <select 
									name="class_id" 
									id="class_id"<?php echo (empty($movie_data['classification_id'])) ? ' disabled="disabled"' : '';?>
								><?php print_r($class_data); ?>
								<?php $selected_class_id = $movie_data['classification_id'];
								foreach ($class_data as $key => $class) {?>
									<option value="<?php echo $class['classification_id'];?>" <?php
									if ($class['classification_id'] == $selected_class_id) {
										echo "selected";
									}?>>
										<?php echo $class['classification'];?>
									</option>
  <?php } ?>
								</select>
                                <label>
									<input 
										type="checkbox" 
										name="class_explanation_edit" 
										value="true" 
										class="edit_toggle" 
										data-inputid="class_id" 
										data-defaultvalue="<?php echo $movie_data['class_id'];?>"<?php echo (empty($movie_data['classification_id'])) ? ' checked="checked"' : '';?>
									>
									Default
								</label>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" nowrap><strong>Explanation</strong></td>
                            <td nowrap>&nbsp;</td>
                            <td>
                                <input 	
									name="class_explanation" 
									type="text" 
									id="class_explanation" 
									value="<?php echo $movie_data['class_explanation'];?>"
									<?php echo (empty($movie_data['class_explanation'])) ? ' disabled="disabled"' : '';?> 
									size="46" 
									maxlength="100"
								>
                                <label>
									<input 
										type="checkbox" 
										name="class_explanation_edit" 
										value="true" 
										class="edit_toggle" 
										data-inputid="class_explanation" 
										data-defaultvalue="<?php echo $movie_data['class_explanation'];?>"
										<?php echo (empty($movie_data['class_explanation'])) ? ' checked="checked"' : '';?>
									>
									Default
								</label>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" nowrap><strong>Duration (0hr 0min)</strong></td>
                            <td nowrap>&nbsp;</td>
                            <td>
                                <input 
									name="duration" 
									type="text" 
									id="duration" 
									value="<?php echo mintohr($movie_data['runtime']);?>"
									<?php echo (empty($movie_data['runtime'])) ? ' disabled="disabled"' : '';?> 
									size="46" 
									maxlength="100"
								>
                                <label>
									<input 
										type="checkbox" 
										name="class_explanation_edit" 
										value="true" 
										class="edit_toggle" 
										data-inputid="duration" 
										data-defaultvalue="<?php echo mintohr($movie_data['runtime']);?>"
										<?php echo (empty($movie_data['runtime'])) ? ' checked="checked"' : '';?>
									>
									Default
								</label>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="top"><strong>Comments</strong></td>
                            <td>&nbsp;</td>
                            <td>
								<input 
									type="text" 
									name="comments" 
									id="comments" 
									value="<?php echo $movie_data['comments'];?>" 
									size="46"
								>
							</td>
                        </tr>
						<tr>
                            <td align="right" nowrap><strong>YouTube Trailer</strong></td>
                            <td nowrap>&nbsp;</td>
							<td>
                                <input 
									name="trailer" 
									type="text" 
									id="trailer" 
									value="<?php echo $movie_data['trailer'];?>"
									<?php echo (empty($movie_data['trailer'])) ? ' disabled="disabled"' : '';?> 
									size="46" 
									maxlength="100"
								>
                                <label>
									<input 
										type="checkbox" 
										name="class_explanation_edit" 
										value="true" 
										class="edit_toggle" 
										data-inputid="trailer" 
										data-defaultvalue="<?php echo $movie_data['trailer'];?>"
										<?php echo (empty($movie_data['trailer'])) ? ' checked="checked"' : '';?>
									>
									Default
								</label>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" valign="top"><strong>Synopsis</strong></td>
                            <td>&nbsp;</td>
                            <td>
                                <textarea 
									name="synopsis" 
									id="synopsis" 
									cols="60" 
									rows="6"
									<?php echo (empty($movie_data['synopsis'])) ? ' disabled="disabled"' : '';?>
								>
									<?php echo $movie_data['synopsis'];?>
								</textarea>
                                <label>
									<input 
										type="checkbox" 
										name="class_explanation_edit" 
										value="true" 
										class="edit_toggle" 
										data-inputid="synopsis" 
										data-defaultvalue="<?php echo htmlspecialchars($movie_data['synopsis']);?>"
										<?php echo (empty($movie_data['synopsis'])) ? ' checked="checked"' : '';?>
									>
									Default
								</label>
                            </td>
                        </tr>

                        <tr>
                            <td align="right" nowrap><strong>Release Date</strong></td>
                            <td nowrap>&nbsp;</td>
                            <td nowrap>
                                <select 
									name="d" 
									id="d" 
									<?php if ($d == "00") { echo " disabled"; } ?>
								>
							  <?php for ($n = 1; $n <= 31; $n++) {?>
									<option 
										value="<?php echo $n;?>" 
										<?php if ($d == $n) { echo "selected"; } ?>
									>
										<?php echo $n;?>
									</option>
							  <?php } ?>
                                </select>
                                <select 
									name="m" 
									id="m" 
									<?php if ($m == "00") { echo " disabled"; } ?>
								>
								<?php
									$months = array(
										1 => "January",
										"February",
										"March",
										"April",
										"May",
										"June",
										"July",
										"August",
										"September",
										"October",
										"November",
										"December"
									);
								for ($n = 1; $n <= count($months); $n++) { ?>
									<option 
										value="<?php echo $n;?>" 
										<?php if ($m == $n) { echo "selected"; } ?>
									>
										<?php echo $months[$n];?>
									</option>
						  <?php } ?>
                                </select>
                                <select 
									name="y" 
									id="y" 
									<?php if ($y == "00") { echo " disabled"; } ?>
								>
								<?php
								for ($n = date('Y') - 1; $n <= date('Y') + 1; $n++) {?>
									<option 
										value="<?php echo $n;?>" 
										<?php if ($n == $y) { echo "selected"; } ?>
									>
										<?php echo $n;?>
									</option>
						  <?php } ?>
                                </select>
                                <input 
									name="tba" 
									type="checkbox" 
									id="tba" 
									value="y" 
									<?php if ($movie_data['release_date'] == "0000-00-00") { echo "checked"; } ?> 
									onClick="disableDate(this.form)"
								>
								<label for="tba">TBC</label>
							</td>
						</tr>
						<tr class="padTop">
							<td align="right" valign="top"><strong>Change Poster</strong></td>
							<td>&nbsp;</td>
							<td>
								<input type="file" name="poster"><br>
							  <?php if ($movie_data['custom_poster'] == 1) { ?>
									<img src="<?php echo $config['poster_url'].$movie_data['movie_id'];?>-medium-custom.jpg" height="118" />
							  <?php } elseif ($movie_data['custom_poster'] == 0) { ?>
									<img src="<?php echo $config['poster_url'].$movie_data['movie_id'];?>-medium-default.jpg" height="118" />
							  <?php } else { ?>
									<em>This movie has no poster</em>
							  <?php } ?>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>
								<p>
									<strong>Where to next?</strong><br>
									<input 
										name="redir" 
										type="radio" 
										id="redir_2" 
										value="movie_list" 
										checked
									> 
									<label for="redir_2">Back to movie list</label><br>
									<input 
										name="redir" 
										type="radio" 
										id="redir_3" 
										value="edit_sessions"
									> 
									<label for="redir_3">Edit sessions</label><br>
								</p>
								<input 
									name="movie_id" 
									type="hidden" 
									value="<?php echo $_REQUEST['movie_id'];?>"
								>
								<input name="submit" type="submit" class="btn btn-success submit" value="Save Changes">
									<br /><br /><br /><br />
									<?php button_3("Remove From List","movies.php?delete_movie=".$_REQUEST['movie_id'],"x","","y","Are you sure you want to remove this movie from your list?");?>
							</td>
						</tr>
					</table>
                </form>
  <?php }
	} else { ?>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Edit Movie Details</h1>
            </div>
            <p>
				<?php check_notice("Either you are not logged in or you do not have permission to use this feature.");?>
			</p>
<?php
	} ?>
<?php include("inc/footer.inc.php");?> 
</body>
</html>