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
					m.custom_poster,
                    c.classification
                FROM movies m
                LEFT JOIN classifications c
                    ON c.classification_id = m.classification_id
                WHERE movie_id = '{$_REQUEST['movie_id']}'
            ";
            $movie_res = query($sql);
            $original_movie_data = $movie_res->fetch_assoc();
            // For the following, only use the post data if it differs from the original movie data
			$title = (!isset($_POST['title']) || $original_movie_data['title'] === $_POST['title']) ? '' : $_POST['title'];
            $synopsis = (!isset($_POST['synopsis']) || $original_movie_data['synopsis'] === $_POST['synopsis']) ? '' : $_POST['synopsis'];
            $classification_id = (!isset($_POST['classification_id'])) ? '1' : $_POST['classification_id'];
            $runtime  = (!isset($_POST['duration'])) ? '0' : stringtomins($_POST['duration']);
            $trailer  = (!isset($_POST['trailer'])) ? '' : $_POST['trailer'];
			$custom_poster  = (!isset($_POST['custom_poster']) || $_POST['custom_poster'] != 1) ? 0 : $_POST['custom_poster'];
			
			$extra_sql = '';
			if (!isset($_POST['title_edit'])) {
				$extra_sql .= "title = '".$mysqli->real_escape_string($title)."',";
			}
			if (!isset($_POST['synopsis_edit'])) {
				$extra_sql .= "synopsis = '".$mysqli->real_escape_string($synopsis)."',";
			}
			if (!isset($_POST['classification_edit'])) {
				$extra_sql .= "classification_id = '".$mysqli->real_escape_string($classification_id)."',";
			}
			if (!isset($_POST['duration_edit'])) {
				$extra_sql .= "runtime = '".$mysqli->real_escape_string($runtime)."',";
			}
			if (!isset($_POST['trailer_edit'])) {
				$extra_sql .= "trailer = '".$mysqli->real_escape_string($trailer)."',";
			}
			
			$sql = "
				UPDATE movies
				SET
					release_date = '".$mysqli->real_escape_string($release_date)."',
					$extra_sql
					comments = '".$mysqli->real_escape_string(isset($_POST['comments']) ? $_POST['comments'] : '')."',
					custom_poster = '".$mysqli->real_escape_string($custom_poster)."'
				WHERE movie_id = '".$mysqli->real_escape_string($_POST['movie_id'])."'
			";
			query($sql);
        }
		
        // Upload image
		if (isset($_FILES['poster']['error']) && ($_FILES['poster']['error'] == 'UPLOAD_ERR_OK' || $_FILES['poster']['error'] == 0)) {
			if (isset($_POST['custom_poster']) && $_POST['custom_poster'] == 1) {
				$target_dir = $config['tmp_poster_dir'];
				$target_file = $target_dir . basename($_FILES["poster"]["name"]);
				$tmp_file = $_FILES["poster"]["tmp_name"];
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
				// Check if image file is a actual image or fake image
				$check = getimagesize($_FILES["poster"]["tmp_name"]);
				// Check if file already exists
				if (file_exists($target_file)) {
					$location = "movie_edit_details.php?movie_id=".$_POST['movie_id']."&er=Sorry,+the+file+already+exists.";
					header("Location: $location");
					$uploadOk = 0;
				}
				// Check file size
				if ($_FILES["poster"]["size"] > 500000) {
					$location = "movie_edit_details.php?movie_id=".$_POST['movie_id']."&er=File+is+too+large,+must+be+under+500MB.";
					header("Location: $location");
					$uploadOk = 0;
				}
				// Allow certain file formats
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif" ) {
					$location = "movie_edit_details.php?movie_id=".$_POST['movie_id']."&er=Sorry,+only+JPG,+JPEG,+PNG+%26+GIF+files+are+allowed.";
					header("Location: $location");
					$uploadOk = 0;
				}
				// Convert PNG
				if ($imageFileType == "png") {
					$tmp_file = png2jpg($tmp_file);
					$target_file = explode(".png",$target_file)[0].".jpg";
				}
				// Convert GIF
				else if ($imageFileType == "gif") {
					$tmp_file = gif2jpg($tmp_file);
					$target_file = explode(".gif",$target_file)[0].".jpg";
				}
				// Check if $uploadOk is set to 0 by an error
				if ($uploadOk == 0) {
					$location = "movie_edit_details.php?movie_id=".$_POST['movie_id']."&er=Something+went+wrong+uploading+the+poster,+please+try+again.";
					header("Location: $location");
				// if everything is ok, try to upload file
				} else {
					if (rename($tmp_file, $target_file)) {
						if (save_poster($target_file, $_POST['movie_id'], true)) {
							@unlink($target_file);
						}
					} else {
						echo "Sorry, there was an error uploading your file.";
					}
				}
			}
		}
        
        // Tidy up
		smarty_clear_cache($_POST['movie_id'], NULL, NULL, false, false);
        
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
				m.custom_poster,
				m.poster_url,
                m.runtime, 
                m.trailer,
				m.comments,
				m.status,
				m.release_date,
                c.classification
            FROM movies m
            LEFT JOIN classifications c
                ON c.classification_id=m.classification_id
            WHERE movie_id='" . $mysqli->real_escape_string($_REQUEST['movie_id']) . "'
        ";
        $movie_res = query($sql);
        $movie_data = $movie_res->fetch_assoc();
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
    } else {
		header("Location: movies.php?er=Can't+find+movie.");
	}
}

?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="inc/js/generic.js" type="text/javascript"></script>
    <title><?php echo $title_prefix; ?><?php echo (check_cinema()) ? "Movie Lists &amp; Sessions" : "Website Content Management For Cinemas"; ?></title>
    <script src="inc/js/generic.js" type="text/javascript"></script>
	<script src="inc/js/jquery-3.4.1.min.js" type="text/javascript"></script>
	<script src="inc/js/movieEdit.js" type="text/javascript"></script>
	<link href="inc/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="inc/css/dashboard.css" rel="stylesheet">
  </head>
  <body>
  <?php include("inc/header.inc.php"); ?>
 <div class="container-fluid">
    <div class="row">
    <?php include("inc/nav.inc.php");
	if (check_cinema()) { ?>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <h1 class="h2">Edit Movie Details</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group mr-2">
                            <?php button_1("< Back To Movie List", "movies.php", "back", "right");?>
                        </div>
                    </div>
                </div>
				<?php echo check_msg(); ?>
                <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" enctype="multipart/form-data"> 
					<h3><?php echo $movie_data['title'];?></h3>
					<ul>
						<li>Please use the boxes below to change the details of this movie.</li>
						<li>If you have no confirmed release date please select the TBC box.</li>
					</ul>
					<table border="0" cellspacing="0" cellpadding="1">
  <?php if (has_permission('sessions')) {?>
						<tr>
                            <td align="right" nowrap><strong>Title</strong></td>
                            <td nowrap>&nbsp;</td>
                            <td>
                                <input 
									name="title" 
									type="text" 
									id="title" 
									value="<?php echo $movie_data['title'];?>"
									<?php if (!empty($movie_data['title'])) { ?>disabled="disabled" <?php }; ?>
									size="46" 
									maxlength="100"
								>
								<label>
									<input 
										type="checkbox" 
										name="title_edit" 
										value="true" 
										class="edit_toggle" 
										data-inputid="title" 
										data-defaultvalue="<?php echo $movie_data['title']?>"
										<?php if (!empty($movie_data['title'])) { ?>checked="checked" <?php }; ?>
									>
									Default
								</label>
                            </td>
                        </tr>
						<tr>
                            <td align="right"><strong>Classification</strong></td>
                            <td nowrap>&nbsp;</td>
                            <td>
                                <select 
									name="classification_id" 
									id="classification_id"
									<?php echo (!empty($movie_data['classification_id'])) ? ' disabled="disabled"' : '';?>
								>
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
										name="classification_edit" 
										value="true" 
										class="edit_toggle" 
										data-inputid="classification_id" 
										data-defaultvalue="<?php echo $movie_data['classification_id'];?>"
										<?php if (!empty($movie_data['classification_id'])) { ?>checked="checked" <?php }; ?>
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
									<?php echo (!empty($movie_data['runtime'])) ? ' disabled="disabled"' : '';?> 
									size="46" 
									maxlength="100"
								>
								<label>
									<input 
										type="checkbox" 
										name="duration_edit" 
										value="true" 
										class="edit_toggle" 
										data-inputid="duration" 
										data-defaultvalue="<?php echo $movie_data['runtime']?>"
										<?php if (!empty($movie_data['runtime'])) { ?>checked="checked" <?php }; ?>
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
									<?php if (!empty($movie_data['trailer'])) { ?>disabled="disabled" <?php }; ?>
									size="46" 
									maxlength="100"
								>
								<label>
									<input 
										type="checkbox" 
										name="trailer_edit" 
										value="true" 
										class="edit_toggle" 
										data-inputid="trailer" 
										data-defaultvalue="<?php echo $movie_data['trailer']?>"
										<?php if (!empty($movie_data['trailer'])) { ?>checked="checked" <?php }; ?>
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
									<?php if (!empty($movie_data['synopsis'])) { ?>disabled="disabled" <?php }; ?>
								><?php echo $movie_data['synopsis'];?>
								</textarea>
								<label>
									<input 
										type="checkbox" 
										name="synopsis_edit" 
										value="true" 
										class="edit_toggle" 
										data-inputid="synopsis" 
										data-defaultvalue="<?php echo htmlspecialchars($movie_data['synopsis'])?>"
										<?php if (!empty($movie_data['synopsis'])) { ?>checked="checked" <?php }; ?>
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
							<td align="right" valign="top"><strong>Use Custom Poster</strong></td>
							<td>&nbsp;</td>
							<td>
								<div class="form-check">
									<label class="form-check-label">
										<input 
											type="checkbox" 
											class="form-check-input" 
											value="1" 
											name="custom_poster" 
											<?php echo ($movie_data['custom_poster'] == 1 || empty($movie_data['poster_url'])) ? ' checked="checked"' : ''; ?>
										>&nbsp;
									</label>
								</div>
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
								<br /><br />
								<?php button_3("Remove From List","movies.php?delete_movie=".$_REQUEST['movie_id'],"x","","y","Are you sure you want to remove this movie from your list?");?>
								<br /><br />
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