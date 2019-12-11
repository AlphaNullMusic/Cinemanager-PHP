<?php
require("inc/manage.inc.php");

// For processing large posters
ini_set('memory_limit', '512M');

if (check_cinema() && (has_permission('sessions'))) {

    // Confirmation
    if (isset($_GET['changed_id'])) {
        $res = $mysqli->query("
			SELECT title 
			FROM movies 
			WHERE movie_id='{$_GET['changed_id']}'
		");
        $data         = $res->fetch_assoc();
        $_REQUEST['conf'] = "Sessions updated successfully for {$data['title']}.";
    }
    
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "listmovie") {
        // Format date
        if (isset($_POST['tba'])) {
            $release_date = "0000-00-00";
	} else {
            $r_date = $_POST['y'] . "-" . $_POST['m'] . "-" . $_POST['d'];
	    $release_date = date('Y-m-d', strtotime($r_date));
        }
        // Add a new movie
        if ($_REQUEST['movie_id'] && $_REQUEST['movie_id'] != 'new') {
            // Check if the submitted title already exists
            $sql = "
				SELECT title,
					   classification_id,
					   movie_id
				FROM movies 
				WHERE imdb_id='" . $mysqli->real_escape_string($_REQUEST['movie_id']) . "'
			";
            $res = $mysqli->query($sql) or user_error("Gnarly: $sql");
            if ($res->num_rows == 0) {
                // Movie does not exist, add to main database
                $movie_details = get_movie_basics($_REQUEST['movie_id']);
				if ($movie_details['runtime'] == 'N/A') {
					$movie_details['runtime'] = 0;
				}
                $sql = "
					INSERT INTO movies 
					SET title='" . $mysqli->real_escape_string($movie_details['title']) . "', 
					imdb_id='" . $mysqli->real_escape_string($movie_details['imdbID']) . "',
					status='ok', 
					synopsis = '" . $mysqli->real_escape_string($movie_details['synopsis']) . "',
					release_date='" . $mysqli->real_escape_string($release_date) . "', 
					poster_url='" . $mysqli->real_escape_string($movie_details['poster']) . "',
					runtime='" . $mysqli->real_escape_string($movie_details['runtime']) . "',
					classification_id='" . $mysqli->real_escape_string(get_class_id($movie_details['rated'])) . "'
				";
                $mysqli->query($sql) or user_error("Gnarly: $sql");
                $movie_id = $mysqli->insert_id;
		save_poster($movie_details['poster'], $movie_id, $custom = false);
            } else {
                // Movie exists, set movie_id to existing movie
                $data     = $res->fetch_assoc();
                $movie_id = $data['movie_id'];
                $class_id = $data['classification_id'];
		$sql = "
			UPDATE movies 
			SET status='ok'
			WHERE movie_id = '" . $mysqli->real_escape_string($movie_id) . "'
		";
                $mysqli->query($sql) or user_error("Gnarly: $sql");
            }
        } else if ($_REQUEST['movie_id'] && $_REQUEST['movie_id'] == 'new') {
            $sql = "
		INSERT INTO movies 
		SET title='" . $mysqli->real_escape_string($_POST['title']) . "', 
		status='ok', 
		release_date='" . $mysqli->real_escape_string(date($release_date)) . "' 
	    ";
            $mysqli->query($sql) or user_error("Gnarly: $sql");
        }

        // Clear smarty cache
        smarty_clear_cache($_SESSION['cinema_data']['cinema_id'], $movie_id);
        // Redirect
        if ($_POST['redir'] == "edit_details") {
            $redir = "movie_edit_details.php?movie_id=$movie_id";
        } else if ($_POST['redir'] == "edit_sessions") {
            $redir = "movie_edit_sessions.php?movie_id=$movie_id";
        } else {
            $redir = $_POST['redir'];
        }
        header("Location: $redir");
    }
    
    // Remove movie
    elseif (isset($_GET['delete_movie'])) {
        expire_movie($_GET['delete_movie']);
        header("Location: movies.php?conf=Movie deleted successfully.");
        exit;
    }
	
    // Delete multiple movies
    elseif (isset($_POST['delete_multiple_movies']) && isset($_POST['movie_ids']) && is_array($_POST['movie_ids'])) {
        foreach ($_POST['movie_ids'] as $m) {
            expire_movie($m);
        }
        header("Location: movies.php?conf=Movies deleted successfully.");
        exit;
    }
    
}

function expire_movie($movie_id) {
    global $mysqli;
    // Remove sessions and prices
    $sp               = new manage_sessions();
    $sp->session_date = "all";
    $sp->movie_id     = $movie_id;
    $sp->clear_day(true);
    // Remove movie data
    $sql = "
		UPDATE movies
		SET status = 'exp'
		WHERE movie_id = '$movie_id'
	";
    $mysqli->query($sql) or user_error("Gnarly: $sql");
	delete_poster($movie_id, $type = 'custom');
    // Clear smarty cache
    smarty_clear_cache($movie_id);
    return true;
}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Movie Lists &amp; Sessions</title>
		<script src="inc/js/jquery-3.4.1.min.js" type="text/javascript"></script>
		<script src="inc/js/movieEdit.js" type="text/javascript"></script>
		<link href="inc/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="inc/css/dashboard.css" rel="stylesheet">
	</head>
	<body>
		<?php include("inc/header.inc.php");?>
		<div class="container-fluid">
			<div class="row">
				<?php include("inc/nav.inc.php");
if (check_cinema() && (has_permission('sessions'))) {
	// Add movie
	if (isset($_REQUEST['action']) && $_REQUEST['action'] == "addmovie") {
        $movie_data = get_movie_basics($_REQUEST['movie_id']);
        if (strtotime($movie_data['year']) > strtotime(date('Y'))) {
            $d = date('j');
            $m = date('n');
            $y = $movie_data['year'];
        } else {
            $d = date('j');
            $m = date('n');
            $y = date('Y');
        }
?>
				<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
					<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
						<div class="btn-toolbar mb-2 mb-md-0">
							<div class="btn-group mr-2">
								<?php button_1("< Back To Movie List", "movies.php", "back", "right"); ?>
							</div>
						</div>
						<h1 class="h2">Add A Movie</h1>
					</div>
            
<?php // Check if movie already exists 
    $sql = "
		SELECT status 
		FROM movies 
		WHERE imdb_id='".$mysqli->real_escape_string($_REQUEST['movie_id'])."'
			AND status='ok'
	";
    $res = $mysqli->query($sql) or user_error("Gnarly: $sql");
    if (is_numeric($_REQUEST['movie_id']) && $res->num_rows > 0) { 
?>
					<p>This movie is already in your now showing list.</p>
<?php 
	} else { 
?>
					<form action="movies.php" method="post">
<?php 
	if ($_REQUEST['movie_id'] == "new") { 
?>
				    <p><strong>Movie Title:</strong><br><input name="title" type="text" size="30" maxlength="100"></p>
<?php 
	} else { 
?>
					<p>Add "<?php echo $movie_data['title']; ?>" to your movie list...</p>
<?php
    }
?>
					<p><strong>When do you start screening this movie?</strong><br>
					<em>Leave this as today's date if the movie is already showing.</em><br>
					<select name="d" id="d">
<?php
    for ($n = 1; $n <= 31; $n++) {
?>
						<option value="<?php echo $n;?>" <?php if ($d == $n){ echo "selected"; }?>>
							<?php echo $n;?>
						</option>
<?php 
    }
?>
					</select>
					<select name="m" id="m">
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
    for ($n = 1; $n <= count($months); $n++) {
?>
						<option value="<?php echo $n; ?>" <?php if ($m == $n){ echo "selected"; } ?>>
							<?php echo $months[$n];?>
						</option>
<?php 
	}
?>
					</select>
					<select name="y" id="y">
<?php 
    for ($n = date('Y') - 1; $n <= (date('Y') + 2); $n++) {
?>
						<option value="<?php echo $n ?>" <?php echo ($y == $n) ? 'selected' : '' ?>>
							<?php echo $n ?>
						</option>
<?php 
    }
?>
					</select>
					<input name="tba" type="checkbox" id="tba" value="y" onClick="disableDate(this.form)">TBA</p>
<?php
	if ($_REQUEST['movie_id'] != "new") { 
?>
					<p>
					    <strong>Where to next?</strong><br>
					    <input name="redir" type="radio" id="redir_1" value="edit_details" checked>
					    <label for="redir_1">Edit movie details</label><br>
					    <input name="redir" type="radio" id="redir_2" value="edit_sessions">
					    <label for="redir_2">Add movie sessions times</label><br>
					    <input name="redir" type="radio" id="redir_3" value="movies.php?action=findmovie">
					    <label for="redir_3">Add another movie</label><br>
					    <input name="redir" type="radio" id="redir_4" value="movies.php"> 
					    <label for="redir_4">Return to movie list</label>
					</p>
<?php
	}
?>
					<p>
						<input name="movie_id" type="hidden" value="<?php echo $_REQUEST['movie_id'];?>">
					    <input name="action" type="hidden" value="listmovie">
					    <input name="Submit" type="submit" class="btn btn-success submit" value="Add This Movie">
					</p>
					</form>
<?php
          }
	// Find movie to add
    } elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == "findmovie") {?>
	  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
		  <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                  <?php button_1("< Back To Movie List", "movies.php", "back", "right");?>
                </div>
          </div>
	      <h1 class="h2">Add A Movie</h1>
        </div>	
		<?php echo check_msg();?>
		<h2>Add Custom Film</h2>
		<a href="?action=addmovie&movie_id=new" class="btn btn-secondary">Add</a>
		<hr>
	    <h2>Search By Keyword</h2>
	    <form action="movies.php" method="get" enctype="multipart/form-data">
	      <input name="keyword" type="text" size="25" maxlength="50" value="<?php echo (isset($_GET['keyword'])) ? $_GET['keyword'] : '' ?>">
	      <input name="Search" type="submit" class="btn btn-sm btn-success submit" value="Search">
	      <input type="hidden" name="action" value="findmovie">
	    </form>
	    <hr>
	    <h2>Get With IMDB ID</h2>
		<p>E.G: 'tt8242340'</p>
	    <form action="movies.php" method="get" enctype="multipart/form-data">
	      <input name="imdbID" type="text" size="25" maxlength="50" value="<?php echo (isset($_GET['imdbID'])) ? $_GET['imdbID'] : '' ?>">
	      <input name="Search" type="submit" class="btn btn-sm btn-success submit" value="Search">
	      <input type="hidden" name="action" value="findmovie">
	    </form>
	    <?php
		// Get search with keyword
        if (isset($_REQUEST['keyword'])) {
            $keyword   = (isset($_REQUEST['keyword'])) ? trim($_REQUEST['keyword']) : '';
            if ($movie_list = get_movie_search($keyword)) {
                if ($movie_list['response'] == "True") { ?>
		<hr>
		<h2>Search Results</h2>
		<p class="subtle">Click a movie below to add it to your own movie list.</p>
		<table class="table table-striped table-sm">
		    <tbody>
				<?php foreach ($movie_list['result'] as $f) { ?>
		        <tr>
					<td class="title autowidth" bgcolor="#ffffff">
						<a href='?action=addmovie&movie_id=<?php echo $f['imdbID'] ?>'  class="search_poster">
							<img src="<?php echo (isset($f['poster']) ? $f['poster'] : '/inc/img/no_image_available.gif') ?>" width="30" height="44">
						</a>
						<a href='?action=addmovie&movie_id=<?php echo $f['imdbID'] ?>'><?php echo $f['title'] ?> (<?php echo $f['year']?>)</a>
					</td>
				</tr>
				<?php } ?>
		    </tbody>
		  </table>
		  <?php } else { ?>
        <hr>
        <h2>Error</h2>
	    <p class="subtle">No results found. Please <a href="?action=addmovie&movie_id=new">click here</a> if you wish to manually add it.</p>
		  <?php } ?>
	      <p>
	        <br>
			If you can't find the movie you wish to add in the list above,<br>
			<a href="?action=addmovie&movie_id=new">click here</a> to manually add it.
	      </p>
		  
		<?php }
		// Get movie with IMDB ID
		} elseif (isset($_REQUEST['imdbID'])) {
            $imdbID = (isset($_REQUEST['imdbID'])) ? trim($_REQUEST['imdbID']) : '';
            if ($movie_list = get_movie_basics($imdbID)) {
                if (!empty($movie_list['title'])/*$movie_list['response'] == "True"*/) { ?>
		<hr>
		<h2>Search Results</h2>
	    <p class="subtle">Click a movie below to add it to your own movie list.</p>
		<table class="table table-striped table-sm">
		    <tbody>
		        <tr>
					<td class="title autowidth" bgcolor="#ffffff">
						<a href='?action=addmovie&movie_id=<?php echo $movie_list['imdbID'] ?>'  class="search_poster">
							<img src="<?php echo (isset($movie_list['poster']) ? $movie_list['poster'] : '/inc/img/no_image_available.gif') ?>" width="30" height="44">
						</a>
						<a href='?action=addmovie&movie_id=<?php echo $movie_list['imdbID'] ?>'><?php echo $movie_list['title'] ?> (<?php echo $movie_list['year']?>)</a>
					</td>
				</tr>
		    </tbody>
		</table>
		  <?php } else { ?>
        <hr>
        <h2>Error</h2>
	    <p class="subtle">No results found. Please <a href="?action=addmovie&movie_id=new">click here</a> if you wish to manually add it.</p>
		  <?php }
                }
        }
	// Main view
	} else { ?>
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
	    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	        <h1 class="h2">Your Movies</h1>
	        <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                <?php button_1("+ Add a New Movie", "?action=findmovie", "plus", "right"); ?>
                </div>
            </div>
        </div>
        <?php echo check_msg(); ?>
            <p>Click a title to edit the film details, click the green numbers to prioritise your films.</p>	
	      <?php
        foreach (array(
            'all' => 'Current Movies',
            'cs' => 'Coming Soon'
        ) as $status => $name)
          {
?>
		<h1 class="h2"><?php echo $name ?></h1>
	        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
		  <table class="table table-striped table-sm">
		    <tbody>
		      <?php 
            if ($movie_list = get_movie_list_full($status, ($status == 'cs') ? 'tbc,m.release_date,m.title' : 'm.title', 7, '%e %b', '%e %b', 500, 'today', NULL, NULL, false, 'tiny'))
              {
                foreach ($movie_list as $movie_item)
                  {
                    date_default_timezone_set($_SESSION['cinema_data']['timezone']);
                    if ($status == 'cs' || (strtotime($movie_item['release_date_raw']) <= time() && $movie_item['release_date_raw'] != '0000-00-00'))
                      {
?>
			  	  
			    <tr id="imdbId_<?php echo $movie_item['imdb_id'] ?>">
			      <td class="title" nowrap>
				        <a href="movie_edit_details.php?movie_id=<?php echo $movie_item['movie_id'] ?>">
				            <img src="<?php echo $movie_item['poster_url'] ?>" width="30" height="44" border="0" alt="<?php echo $movie_item['title'] ?> Mini Poster" />
				        </a>
				
			      
				<a href="movie_edit_details.php?movie_id=<?php echo $movie_item['movie_id'] ?>" title="<?php echo $movie_item['title'] ?>"><?php echo summary($movie_item['title'], 36, 'char', '...', true) ?>
				</a>
				<?php 
                        if ($status == 'cs')
                          {
?>
				  <br>
				  <span class="subtle">Release Date: <?php echo (isset($movie_item['release_date']) && !empty($movie_item['release_date'])) ? $movie_item['release_date'] : 'TBC' ?></span>
				<?php 
                          }
?>
			      </td>
				
			      <td nowrap>
				<a href="movie_edit_sessions.php?movie_id=<?php echo $movie_item['movie_id'] ?>">
				  <img src="inc/icons/icon_mm_sessions.gif" width="19" height="13" border="0" align="absmiddle">Session Times
				</a>
				<span title="<?php echo $movie_item['total_sessions'] ?> sessions<?php echo ($movie_item['session_days_count']) ? " over {$movie_item['session_days_count']} days" : "" ?>">(<?php echo $movie_item['total_sessions'] ?>)</span>
			      </td>
			        
			        <td nowrap>
			          <?php
                            if ($movie_item['total_sessions'])
                              { 
?>
			            <a href="labels.php?movie_id_array[]=<?php echo $movie_item['movie_id'] ?>">
			              <img src="inc/icons/icon_mm_prices.gif" width="19" height="13" border="0" align="absmiddle">Session Labels
			            </a>
			          <?php 
                              }
?>
			        </td>
			      <?php 
                        if ($status != 'cs')
                          {
                            $ns_movies_present = true;
?>
				<td>
				  <input name="movie_ids[]" type="checkbox" value="<?php echo $movie_item['movie_id'] ?>"<?php echo (!$coming_soon && $movie_item['total_sessions'] == 0) ? " checked" : "" ?>>
				</td>
			      <?php 
                          }
?>
			    </tr>
			  <?php
                      }
                  }
              }
            else
              {
?>
		        <tr>
			  <td colspan="6">
			    <em>No movies currently listed.</em>
			  </td>
			</tr>
		      <?php 
              }
?>
		    </tbody>
		    <?php 
            if ($status != 'cs' && isset($ns_movies_present))
              {
?>
		      <tfoot>
			<td colspan="6" align="right">
			  <br>
			  <input type="hidden" name="delete_multiple_movies" value="true">
			  <input type="submit" class="btn btn-sm btn-outline-danger" value="Delete Ticked Movies &uarr;" onClick="return confirmDelete()">
			</td>
		      </tfoot>
		    <?php 
              }
?>
		  </table>
	        </form>
              <?php 
          }
		}
	} else { ?>
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Website Content Management For Cinemas</h1>
          </div>
	<p><?php check_notice("Either you are not logged in or you do not have permission to use this feature.");?></p>
	<p>This page allows cinemas to control their own website: modify and add pages, changes session times and movie details, maintain their upcoming features list and much more.</p>
	<p>This content management system has been built specifically for New Zealand cinema operators to streamline the website updating process.</p>				
      <?php 
  }
?>				
    <?php 
include("inc/footer.inc.php");
?>
  </body>
</html>
