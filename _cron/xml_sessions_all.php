<?

include_once("../local.inc.php");

//find cinemas with Full XML permission
$cinema_res=mysql_query("SELECT c.cinema_id,c.cinema_name,c.city,c.homepage_url FROM cinemas c, cinema_permissions cp WHERE cp.export_xml_full=1 AND cp.cinema_id=c.cinema_id");
while ($cinema_data=mysql_fetch_assoc($cinema_res)) {
	$file_name="nzcinema.sessions.full.{$cinema_data['cinema_id']}.xml";
	
	////////////////
	// CREATE XML //
	////////////////
	
	//begin XML doc
	$xml="<?xml version=\"1.0\" encoding=\"windows-1252\"?>\r";
	$xml.="<Document CinemaID='{$cinema_data['cinema_id']}'>\r";
	//feed data
	$xml.="<Info>\r";
	$xml.="  <DocumentName>{$cinema_data['cinema_name']} ({$cinema_data['city']}) Complete Session Times</DocumentName>\r";
	$xml.="  <DocumentDescription>Movie screening times for {$cinema_data['cinema_name']} ({$cinema_data['city']}) provided by www.nzcinema.co.nz</DocumentDescription>\r";
	$xml.="  <DocumentLink>{$base_url}</DocumentLink>\r";
	$xml.="  <DocumentURI>{$base_url}rss/{$file_name}</DocumentURI>\r";
	$xml.="  <DocumentCredit>".htmlspecialchars("Sessions provided by <a href=\"{$cinema_data['homepage_url']}\" target=\"_blank\">{$cinema_data['cinema_name']}</a>. Movie information provided by <a href=\"http://www.nzcinema.co.nz/\" target=\"_blank\">NZ Cinema</a>")."</DocumentCredit>\r";
	$xml.="  <CinemaName>{$cinema_data['cinema_name']}</CinemaName>\r";
	$xml.="  <CinemaCity>{$cinema_data['city']}</CinemaCity>\r";
	$xml.="  <CinemaURL>{$cinema_data['homepage_url']}</CinemaURL>\r";
	$xml.="  <Author>NZ Cinema</Author>\r";
	$xml.="  <PublishDate>".date('r')."</PublishDate>\r";
	$xml.="</Info>\r";
	//today's sessions
	$sql="SELECT st.movie_id, m.title, m.synopsis, m.trailer, m.official_site, m.cast, c.class
					FROM session_times st, movie_lists ml, movies m, classifications c 
				 WHERE st.cinema_id='{$cinema_data['cinema_id']}' 
					 AND st.session_date>=CURDATE() 
					 AND st.movie_id=m.movie_id 
					 AND ml.cinema_id=st.cinema_id
					 AND ml.movie_id=m.movie_id
					 AND ml.status='ok'
					 AND m.class_id=c.class_id 
			GROUP BY st.movie_id 
			ORDER BY m.title";
	$movie_res=mysql_query($sql, $db) or user_error("Gnarly: $sql");
	$xml.="<SessionsFull>\r";
	while ($movie_data=mysql_fetch_assoc($movie_res)) {
		
		//main movie information
		$xml.="<Movie>\r";
		$xml.="  <Title>".htmlspecialchars($movie_data['title'])."</Title>\r";
		$xml.="  <Synopsis>".htmlspecialchars(str_replace("\r\n",' ',$movie_data['synopsis']))."</Synopsis>\r";
		$xml.="  <Website>".htmlspecialchars($movie_data['official_site'])."</Website>\r";
		$xml.="  <Trailer>".htmlspecialchars($movie_data['trailer'])."</Trailer>\r";
		//$xml.="  <Cast>".htmlspecialchars(str_replace("\r\n",', ',$movie_data['cast']))."</Cast>\r";
		
		//add images
		$xml.="  <Images>\r";
		$sql="SELECT image_name FROM images WHERE image_cat_id=1 AND movie_id='{$movie_data['movie_id']}' AND status='ok' LIMIT 4";
		$image_res=mysql_query($sql, $db) or user_error("Gnarly: $sql");
		while ($image_data=mysql_fetch_assoc($image_res)) {
			$xml.="    <ImageURL>".htmlspecialchars($movie_image_url.$image_data['image_name']."_medium.jpg")."</ImageURL>\r";
		}
		$xml.="  </Images>\r";
		
		//add sessions
		$xml.="  <Sessions>\r";
		$sql="SELECT sessions, DATE_FORMAT(session_date,'%d-%m-%Y') AS date FROM session_times WHERE cinema_id='{$cinema_data['cinema_id']}' AND movie_id='{$movie_data['movie_id']}' AND session_date>=CURDATE() ORDER BY session_date LIMIT 7";
		$session_res=mysql_query($sql, $db) or user_error("Gnarly: $sql");
		$remove=array('<!--R-->','<!--B-->');
		while ($session_data=mysql_fetch_assoc($session_res)) {
			$xml.="    <Session>\r";
			$xml.="      <SessionDate>{$session_data['date']}</SessionDate>\r";
			$xml.="      <SessionTimes>".str_replace($remove,'',$session_data['sessions'])."</SessionTimes>\r";
			$xml.="    </Session>\r";
		}
		$xml.="  </Sessions>\r";
		
		//close movie information
		$link="{$base_url}movies/{$movie_data['movie_id']}.php";
		$xml.="  <DataSource>".$link."</DataSource>\r";
		$xml.="</Movie>\r";
	}
	$xml.="</SessionsFull>\r";
	$xml.="</Document>\r";
	
	//remove illegal characters
	$xml=str_replace($illegal_find,$illegal_replace,$xml);
	
	//////////////////////////////
	// WRITE XML FILE TO SERVER //
	//////////////////////////////
	
	$file_uri="{$base_dir}rss/{$file_name}";
	$file_handle=fopen($file_uri,'w') or die("Can't open file.");
	fwrite($file_handle,$xml);
	fclose($file_handle);

}

?>