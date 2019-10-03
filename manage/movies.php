<?php
require("inc/manage.inc.php");

if (/*check_cinema() && (has_permission('sessions'))*/1==1) {

    // Confirmation
    if (isset($_GET['changed_id'])) {
        $res = $mysqli->query("SELECT title 
							   FROM movies 
							   WHERE movie_id='{$_GET['changed_id']}'
		");
        $data         = $res->fetch_assoc();
        $_GET['conf'] = "Sessions updated successfully for {$data['title']}.";
    }
    
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "listmovie") {
        // Format date
        if (isset($_POST['tba'])) {
            $release_date = "0000-00-00";
        } else {
            $release_date = $_POST['y'] . "-" . $_POST['m'] . "-" . $_POST['d'];
        }
        // Add a new movie
        if ($_REQUEST['movie_id']) {
            //check if the submitted title already exists
            $sql = "SELECT title,class_id FROM movies WHERE imdb_id='" . $mysqli->real_escape_string($_REQUEST['movie_id']) . "'";
            $res = $mysqli->query($sql) or user_error("Gnarly: $sql");
            if ($res->num_rows == 0)
              {
                //movie does not exist, add to main database
                $movie_details = get_movie_basics($_REQUEST['movie_id']);
                $sql = "
	  INSERT INTO movies 
	  SET title='" . $mysqli->real_escape_string($movie_details['title']) . "', 
	  imdb_id='" . $mysqli->real_escape_string($movie_details['movie_id']) . "',
	  status='new', 
	  synopsis = '" . $mysqli->real_escape_string($movie_details['synopsis']) . "',
	  release_date='" . $mysqli->real_escape_string($movie_details['released']) . "', 
	  poster='" . $mysqli->real_escape_string($movie_details['poster']) . "',
	  runtime='" . $mysqli->real_escape_string($movie_details['runtime']) . "',
	  country_id=15, 
	  trailer_format_id=1, 
	  date_listed=CURDATE()
	";
                $mysqli->query($sql) or user_error("Gnarly: $sql");
                $movie_id = $mysqli->insert_id;
                update_movie_cache($movie_id);
              }
            else
              {
                //movie exists, set movie_id to existing movie
                $data     = $res->fetch_assoc();
                $movie_id = $data['movie_id'];
                $class_id = $data['class_id'];
              }
            //notify staff of new movie addition
            notification_new_movie($movie_id, $_POST['description'], $_POST['title'], $_POST['synopsis'], $_POST['distributor_id']);
          }
        if (is_numeric($_REQUEST['movie_id']))
          {
            $movie_id = $_REQUEST['movie_id'];
          }
        //add movie to movie list
        $sql = "
      SELECT status 
      FROM movie_lists 
      WHERE cinema_id='{$_SESSION['cinema_data']['cinema_id']}' 
      AND movie_id='$movie_id'
    ";
        $res = $mysqli->query($sql) or user_error("Gnarly: $sql");
        $data                 = $res->fetch_assoc();
        $current_movie_status = $data['status'];
        //movie already exists (expired or ok), update movie list with release date specified
        if ($current_movie_status == "exp" || $current_movie_status == "ok")
          {
            $sql = "
	UPDATE movie_lists 
	SET release_date='$release_date', 
	status='ok' 
	WHERE cinema_id='{$_SESSION['cinema_data']['cinema_id']}' 
	AND movie_id='$movie_id'
      ";
            $mysqli->query($sql) or user_error("Gnarly: $sql");
            //movie is new, add to movie list
          }
        else
          {
            $sql = "
	INSERT INTO movie_lists 
	SET cinema_id='{$_SESSION['cinema_data']['cinema_id']}', 
	movie_id='$movie_id', 
	release_date='$release_date', 
	status='ok'
      ";
            $mysqli->query($sql) or user_error("Gnarly: $sql");
          }
        //check if this cinema requires posters
        $sql = "SELECT cinema_id FROM cinema_site_settings WHERE image_cat_id=2 AND cinema_id='{$_SESSION['cinema_data']['cinema_id']}' LIMIT 1";
        $tmp_res = $mysqli->query($sql) or user_error("Gnarly: $sql");
        if ($tmp_res->num_rows == 1)
          {
            //check if there is a poster for this movie
            $sql = "
	SELECT i.image_id 
	FROM images i
	INNER JOIN movie_images mi
	  ON mi.image_id = i.image_id
	  AND mi.movie_id='$movie_id' 
	WHERE i.image_cat_id=2 
	  AND i.status='ok' 
	LIMIT 1
      ";
            $tmp_res = $mysqli->query($sql) or user_error("Gnarly: $sql");
            if ($tmp_res->num_rows != 1)
              {
                //notify staff of new movie addition
                notification_no_poster($movie_id);
              }
          }
        //clear smarty cache
        smarty_clear_cache($_SESSION['cinema_data']['cinema_id'], $movie_id);
        //redirect
        if ($_POST['redir'] == "edit_details")
          {
            $redir = "movie_edit_details.php?movie_id=$movie_id";
          }
        else if ($_POST['redir'] == "edit_sessions")
          {
            $redir = "movie_edit_sessions.php?movie_id=$movie_id";
          }
        else
          {
            $redir = $_POST['redir'];
          }
        header("Location: $redir");
    }
    
    // Remove movie
    elseif (isset($_GET['delete_movie'])) {
        delete_cinema_movie($_GET['delete_movie'], $_SESSION['cinema_data']['cinema_id']);
        header("Location: movies.php?conf=Movie deleted successfully.");
        exit;
    }
	
    // Delete multiple movies
    elseif (isset($_POST['delete_multiple_movies']) && isset($_POST['movie_ids']) && is_array($_POST['movie_ids'])) {
        foreach ($_POST['movie_ids'] as $m)
          {
            delete_cinema_movie($m, $_SESSION['cinema_data']['cinema_id']);
          }
        header("Location: movies.php?conf=Movies deleted successfully.");
        exit;
    }
    
  }

function delete_cinema_movie($movie_id, $cinema_id)
  {
    global $mysqli;
    //remove sessions and prices
    $sp               = new manage_sessions();
    $sp->session_date = "all";
    $sp->movie_id     = $movie_id;
    $sp->cinema_id    = $cinema_id;
    $sp->clear_day(true);
    //remove movie data
    $sql = "
    UPDATE movie_lists
    SET status = 'exp'
    WHERE movie_id = '$movie_id'
      AND cinema_id = '$cinema_id'
  ";
    $mysqli->query($sql) or user_error("Gnarly: $sql");
    $mysqli->query("DELETE FROM vista_movie_matrix WHERE movie_id = '$movie_id' AND cinema_id = '$cinema_id'") or user_error("Gnarly: $sql");
    remove_custom_images($movie_id, $cinema_id);
    //clear smarty cache
    smarty_clear_cache($cinema_id, $movie_id);
    return true;
  }

?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="includes/generic.js" type="text/javascript"></script>
    <title><?=$title_prefix?> <?=(isset($_SESSION['cinema_data']))?"Movie Lists &amp; Sessions":"Website Content Management For Cinemas";?></title>
    <link href="inc/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!--<link href="includes/css/styles.css" rel="stylesheet" type="text/css">-->
    <link href="inc/css/dashboard.css" rel="stylesheet">
  </head>
  <body>
  <?
include("inc/header.inc.php");
?>
  <div class="container-fluid">
    <div class="row">
      <?
include("inc/nav.inc.php");
?>
      <?
if (/*check_cinema() && (has_permission('sessions'))*/1==1)
  {
?>
        <?
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "addmovie")
      {
        $movie_data = get_movie_basics($_REQUEST['movie_id']);
        if (strtotime($movie_data['year']) > strtotime(date('Y')))
          {
            $d = date('j');
            $m = date('n');
            $y = $movie_data['year'];
          }
        else
          {
            $d = date('j');
            $m = date('n');
            $y = date('Y');
          }
?>
	  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
              <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                  <? button_1("< Back To Movie List", "movies.php", "back", "right"); ?>
                </div>
              </div>
              <h1 class="h2">Add A Movie</h1>
            </div>
            
	    <? // Check if movie already exists 
        $sql = "
		SELECT status 
		FROM movie_lists 
		WHERE cinema_id='{$_SESSION['cinema_data']['cinema_id']}' 
		  AND movie_id='{$_REQUEST['movie_id']}'
		  AND status='ok'
	      ";
        $res = $mysqli->query($sql) or user_error("Gnarly: $sql");
        if (is_numeric($_REQUEST['movie_id']) && $res->num_rows > 0)
          {
?>
	      <p>This movie is already in your now showing list.</p>
	    <?
          }
        else
          {
?>
	      <form action="movies.php" method="post">
	        <?
            if ($_REQUEST['movie_id'] == "new")
              {
?>
		  <p><strong>Movie Title:</strong><br><input name="title" type="text" size="30" maxlength="100"></p>
		  <p><strong>Synopsis:</strong><br><textarea name="synopsis" cols="50" rows="6"></textarea></p>
		  <p><strong>IMDB ID:</strong><br><input name="imdb_id" type="text" size="30" maxlength="100"></input></p>
		  <p><strong>Distributor:</strong><br><select name="distributor_id"><option></option>
		  <?
                $sql = "
		    SELECT *
		    FROM distributors
		    ORDER BY name
		  ";
                $res = $mysqli->query($sql) or die($mysqli->error);
                while ($data = $res->fetch_assoc())
                  {
                    echo "<option value='{$data['distributor_id']}'>{$data['name']}</option>";
                  }
?>
		  <option></option><option>As specified below...</option></select></p>
		  <p><strong>Notes:</strong><br>
		    <em>Any additional information such as the cast, director or distributor (if not in the list above).</em><br>
		    <textarea name="description" cols="50" rows="6"></textarea>
		  </p>
	        <?
              }
            else
              {
?>
	          <p>Add "<?
                echo $movie_data['title'];
?>" to your movie list...</p>
	        <?
              }
?>
	        <p><strong>When do you start screening this movie?</strong><br>
	        <em>Leave this as today's date if the movie is already showing.</em><br>
	        <select name="d" id="d">
		  <?
            for ($n = 1; $n <= 31; $n++)
              {
?>
		    <option value="<?
                echo $n;
?>" <?
                if ($d == $n)
                  {
                    echo "selected";
                  }
?>><?
                echo $n;
?></option>
		  <?
              }
?>
	        </select>
	        <select name="m" id="m">
		  <?
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
            for ($n = 1; $n <= count($months); $n++)
              {
?>
		    <option value="<?
                echo $n;
?>" <?
                if ($m == $n)
                  {
                    echo "selected";
                  }
?>><?
                echo $months[$n];
?></option>
		  <?
              }
?>
	        </select>
	        <select name="y" id="y">
	          <?
            for ($n = date('Y') - 1; $n <= (date('Y') + 2); $n++)
              {
?>
	            <option value="<?= $n ?>" <?= ($y == $n) ? 'selected' : '' ?>><?= $n ?></option>
	          <?
              }
?>
	        </select>
	        <input name="tba" type="checkbox" id="tba" value="y" onClick="disableDate(this.form)">TBA
	        </p>
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
	        <p>
		  <input name="movie_id" type="hidden" value="<?
            echo $_REQUEST['movie_id'];
?>">
		  <input name="action" type="hidden" value="listmovie">
		  <input name="Submit" type="submit" class="submit" value="Add This Movie">
	        </p>
	      </form>
	    <?
          }
?>			
	<?
      }
    elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == "findmovie")
      {
?>
	  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
	    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	      <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                  <?
        check_msg();
?>
                  <?
        button_1("< Back To Movie List", "movies.php", "back", "right");
?>
                </div>
              </div>
	      <h1 class="h2">Add A Movie</h1>
            </div>	
	    <h2>Search By Keyword</h2>
	    <form action="movies.php" method="get" enctype="multipart/form-data">
	      <input name="keyword" type="text" size="25" maxlength="50" value="<?= (isset($_GET['keyword'])) ? $_GET['keyword'] : '' ?>">
	      <input name="Search" type="submit" class="btn btn-sm btn-success submit" value="Search">
	      <input type="hidden" name="action" value="findmovie">
	    </form>
	    <hr>
	    <h2>Search By IMDB ID</h2>
	    <form action="movies.php" method="get" enctype="multipart/form-data">
	      <input name="imdbID" type="text" size="25" maxlength="50" value="<?= (isset($_GET['imdbID'])) ? $_GET['imdbID'] : '' ?>">
	      <input name="Search" type="submit" class="btn btn-sm btn-success submit" value="Search">
	      <input type="hidden" name="action" value="findmovie">
	    </form>
	    <?
        if (isset($_REQUEST['alpha']) || isset($_REQUEST['keyword']))
          {

            $alpha     = (isset($_REQUEST['alpha'])) ? $_REQUEST['alpha'] : '';
            $keyword   = (isset($_REQUEST['keyword'])) ? trim($_REQUEST['keyword']) : '';
            $sql       = "
		SELECT m.movie_id, m.title, 
		  mc.cast,
		  i.image_name, 
		  d.name AS distributor
		FROM movies m 
		LEFT JOIN movie_cache mc
		  ON m.movie_id=mc.movie_id
		LEFT JOIN movie_images mi
		  ON mi.movie_id=m.movie_id
		LEFT JOIN images i
		  ON i.image_id=mi.image_id
		  AND i.image_cat_id=2
		  AND i.status='ok'
		  AND (i.priority=1 OR i.priority=100)
		LEFT JOIN distributors d
		  ON d.distributor_id = m.distributor_id
		WHERE 
	      ";
            $searching = true;
            if ($alpha == 'num')
              {
                $sql .= "( ";
                for ($n = 0; $n <= 9; $n++)
                  {
                    if ($n > 0)
                      {
                        $sql .= "OR ";
                      }
                    $sql .= "m.title LIKE '$n%' OR m.title LIKE 'The $n%'";
                  }
                $sql .= ") AND (m.status='ok' OR m.status='new')";
              }
            else if (strtolower($alpha) == "t")
              {
                $sql .= "((m.title LIKE '$alpha%' AND m.title NOT LIKE 'The %') OR m.title LIKE 'The $alpha%') AND (m.status='ok' OR m.status='new' OR m.status='hidden')";
              }
            else if ($alpha)
              {
                $sql .= "(m.title LIKE '$alpha%' OR m.title LIKE 'The $alpha%') AND (m.status='ok' OR m.status='new' OR m.status='hidden')";
              }
            else if (!empty($keyword))
              {
                $sql .= "m.title LIKE '%$keyword%' AND (m.status='ok' OR m.status='new' OR m.status='hidden')";
              }
            else
              {
                $searching = false;
              }
            if ($searching)
              {
                $sql .= "
		  AND m.master_movie_id = 0
		    GROUP BY m.movie_id 
		";
		
                if ($movie_list = get_movie_search($keyword))
                  {
                      if ($movie_list['response'] == "True") {
?>
		  <hr>
		  <h2>Search Results</h2>
	      <p class="subtle">Click a movie below to add it to your own movie list.</p>
		  <table class="table table-striped table-sm">
		    <tbody>
		      <?
                    foreach ($movie_list['result'] as $f)
                      {
?>
		        <tr>
			  <td class="title autowidth" bgcolor="#ffffff">
			    <a href='?action=addmovie&movie_id=<?= $f['imdbID'] ?>'>
			      <img src='<?= $f['poster'] ?>' width=30 height=44>
			    </a>
			  
			    <a href='?action=addmovie&movie_id=<?= $f['imdbID'] ?>'><?= $f['title'] ?> (<?= $f['year']?>)</a>
			  </td>
			</tr>
                  <?
                        }
?>
		    </tbody>
		  </table>
		<?
                  } else {
                      // get_movie_search()
                      ?>
                      <hr>
                      <h2>Error</h2>
	                  <p class="subtle">No results found. Please <a href="?action=addmovie&movie_id=new">click here</a> if you wish to manually add it.</p>
                  <? }
                  }
              }
?>
	      <p>
	        <br>
		If you can't find the movie you wish to add in the list above,<br>
		<a href="?action=addmovie&movie_id=new">click here</a> to manually add it.
	      </p>
	    <?
          }
?>
	    			
	<?
      }
    else
      {
?>
	  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
	    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
	      <h1 class="h2">Your Movies</h1>
	      <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                  <?
        button_1("+ Add a New Movie", "?action=findmovie", "plus", "right");
?>
                </div>
              </div>
            </div>
            <? check_msg(); ?>
            <p>Click a title to edit the film details, click the green numbers to prioritise your films.</p>	
	    <? // Get the cinema's primary domain
        $sql = "
		SELECT url
		FROM cinema_domains
		WHERE cinema_id = '{$_SESSION['cinema_data']['cinema_id']}'
		  AND mode = 'w'
		ORDER BY `primary` DESC
		LIMIT 1
	      ";
        $res = $mysqli->query($sql) or user_error("Gnarly: $sql");
        $domain = $res->fetch_assoc();
        if ($res->num_rows > 0)
          {
            // Get the events
            $sql = "
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
            if ($event_res->num_rows > 0)
              {
?>
		  <strong>The following events have been imported from Vista:</strong>
		  <ul>
		    <?
                while ($e = $event_res->fetch_assoc())
                  {
?>
		      <li><a href="http://<?= $domain['url'] ?>/event-<?= $e['event_id'] ?>.php" target="_blank"><?= $e['name'] ?></a> (<?= $e['session_count'] ?> sessions)</li>
		    <?
                  }
?>
		  </ul>
		<?
              }
          }
        else
          {
            // Does this cinema import from a Vista Web Ticketing Server
            $sql = "
		  SELECT vista_cinema_id
		  FROM vista_ticketing_servers
		  WHERE cinema_id = '{$_SESSION['cinema_data']['cinema_id']}'
		";
            $res = $mysqli->query($sql) or user_error("Gnarly: $sql");
            if ($res->num_rows > 0)
              {
                // Get events from the Vista ticketing server (movie-level)
                $sql = "
		    SELECT e.*
		    FROM events e
		    INNER JOIN movie_lists ml
		      ON ml.event_id = e.event_id
		      AND ml.cinema_id = e.cinema_id
		      AND ml.status = 'ok'
		    WHERE e.cinema_id = '{$_SESSION['cinema_data']['cinema_id']}'
		      GROUP BY e.event_id
		      ORDER BY e.name
		  ";
                $event_res = $mysqli->query($sql) or user_error("Gnarly: $sql");
                if ($event_res->num_rows > 0)
                  {
?>
		    <strong>The following events have been imported from Vista:</strong>
		    <ul>
		      <?
                    while ($e = $event_res->fetch_assoc())
                      {
?>
		        <li><a href="http://<?= $domain['url'] ?>/event-v<?= $e['event_id'] ?>.php" target="_blank"><?= $e['name'] ?></a></li>
		      <?
                      }
?>
		    </ul>
		  <?
                  }
              }
          }
?>
	      <?
        foreach (array(
            'all' => 'Current Movies',
            'cs' => 'Coming Soon'
        ) as $status => $name)
          {
?>
		<h1 class="h2"><?= $name ?></h1>
	        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
		  <table class="table table-striped table-sm">
		    <tbody>
		      <?
            if ($movie_list = get_movie_list_full($status, ($status == 'cs') ? 'tbc,ml.release_date,m.title' : 'm.title', 7, '%e %b', '%e %b', 500))
              {
                foreach ($movie_list as $movie_item)
                  {
                    date_default_timezone_set($_SESSION['cinema_data']['timezone']);
                    if ($status == 'cs' || (strtotime($movie_item['release_date_raw']) <= time() && $movie_item['release_date_raw'] != '0000-00-00'))
                      {
?>
			  	  
			    <tr id="imdbId_<?= $movie_item['imdb_id'] ?>">
			      <td class="title" nowrap>
				        <a href="movie_edit_details.php?movie_id=<?= $movie_item['movie_id'] ?>">
				            <img src="<?= $movie_item['poster'] ?>" width="30" height="44" border="0" alt="<?= $movie_item['title'] ?> Mini Poster" />
				        </a>
				
			      
				<a href="movie_edit_details.php?movie_id=<?= $movie_item['movie_id'] ?>" title="<?= $movie_item['title'] ?>"><?= summary($movie_item['title'], 36, 'char', '...', true) ?>
				</a>
				<?
                        if ($status == 'cs')
                          {
?>
				  <br>
				  <span class="subtle">Release Date: <?= (isset($movie_item['release_date']) && !empty($movie_item['release_date'])) ? $movie_item['release_date'] : 'TBC' ?></span>
				<?
                          }
?>
			      </td>
				
			      <td nowrap>
				<a href="movie_edit_sessions.php?movie_id=<?= $movie_item['movie_id'] ?>">
				  <img src="images/icon_mm_sessions.gif" width="19" height="13" border="0" align="absmiddle">Session Times
				</a>
				<span title="<?= $movie_item['total_sessions'] ?> sessions<?= ($movie_item['session_days_count']) ? " over {$movie_item['session_days_count']} days" : "" ?>">(<?= $movie_item['total_sessions'] ?>)</span>
			      </td>
			        
			      <?
                        if ($allow_presets)
                          {
?>
			        <td nowrap>
			          <?
                            if ($movie_item['total_sessions'])
                              {
?>
			            <a href="labels.php?movie_id_array[]=<?= $movie_item['movie_id'] ?>">
			              <img src="images/icon_mm_prices.gif" width="19" height="13" border="0" align="absmiddle">Session Labels
			            </a>
			          <?
                              }
?>
			        </td>
			      <?
                          }
                        if ($status != 'cs')
                          {
                            $ns_movies_present = true;
?>
				<td>
				  <input name="movie_ids[]" type="checkbox" value="<?= $movie_item['movie_id'] ?>"<?= (!$coming_soon && $movie_item['total_sessions'] == 0) ? " checked" : "" ?>>
				</td>
			      <?
                          }
?>
			    </tr>
			  <?
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
		      <?
              }
?>
		    </tbody>
		    <?
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
		    <?
              }
?>
		  </table>
	        </form>
              <?
          }
?>			
	<?
      }
?>				
      <?
  }
else
  {
?>
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Website Content Management For Cinemas</h1>
          </div>
	<p><?
    check_notice("Either you are not logged in or you do not have permission to use this feature.");
?></p>
	<p>This page allows cinemas to update their free NZ Cinema movie listing in minutes. Registered cinemas can also control their own website: modify and add pages, changes session times and movie details, maintain their upcoming features list and much more.</p>
	<p>This content management system has been built specifically for New Zealand cinema operators to streamline the website updating process. If you are a cinema operator and would like more information on any of our services, please don't hesitate to <a href="contact.php">contact us</a>.</p>				
      <?
  }
?>				
    <?
include("includes/footer.inc.php");
?>
  </body>
</html>
