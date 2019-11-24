<?

include_once("../local.inc.php");

//set up for today and tomorrow
if ($display_day=='tomorrow') {
	$session_date_sql="DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
	$day_name='tomorrow';
} else {
	$session_date_sql="CURDATE()";
	$day_name='today';
}

//find cinemas with XML permission
$cinema_res=mysql_query("SELECT c.cinema_id,c.cinema_name,c.city,c.homepage_url FROM cinemas c, cinema_permissions cp WHERE cp.export_xml=1 AND cp.cinema_id=c.cinema_id");
while ($cinema_data=mysql_fetch_assoc($cinema_res)) {
	$file_name="nzcinema.sessions.{$day_name}.{$cinema_data['cinema_id']}.rss100.xml";
	
	////////////////
	// CREATE XML //
	////////////////
	
	//begin XML doc
	$xml="<?xml version=\"1.0\" encoding=\"windows-1252\"?>\r";
	$xml.="<Document CinemaID='{$cinema_data['cinema_id']}'>\r";
	//feed data
	$xml.="<Info>\r";
	$xml.="  <DocumentName>{$cinema_data['cinema_name']} ({$cinema_data['city']}) Session Times</DocumentName>\r";
	$xml.="  <DocumentDescription>Movie screening times for {$cinema_data['cinema_name']} ({$cinema_data['city']}) provided by www.NZCinema.co.nz</DocumentDescription>\r";
	$xml.="  <DocumentLink>{$base_url}</DocumentLink>\r";
	$xml.="  <DocumentURI>{$base_url}rss/{$file_name}</DocumentURI>\r";
	$xml.="  <CinemaName>{$cinema_data['cinema_name']}</CinemaName>\r";
	$xml.="  <CinemaCity>{$cinema_data['city']}</CinemaCity>\r";
	$xml.="  <CinemaURL>{$cinema_data['homepage_url']}</CinemaURL>\r";
	$xml.="  <Author>NZ Cinema</Author>\r";
	$xml.="  <PublishDate>".date('r')."</PublishDate>\r";
	$xml.="</Info>\r";
	//today's sessions
	$sql="SELECT m.movie_id, m.title, m.synopsis, m.cast, m.trailer, m.official_site, st.sessions, i.image_name 
					FROM movies m, session_times st, images i 
				 WHERE m.movie_id=st.movie_id 
					 AND st.cinema_id='{$cinema_data['cinema_id']}' 
					 AND st.session_date=$session_date_sql 
					 AND st.movie_id=i.movie_id 
			GROUP BY m.movie_id 
			ORDER BY m.title";
	$session_res=mysql_query($sql, $db) or user_error("Gnarly: $sql");
	$xml.="<SessionsToday>\r";
	while ($movie_data=mysql_fetch_assoc($session_res)) {
		$link=$base_url."movies/".$movie_data['movie_id'].".php";
		$remove=array('<!--R-->','<!--B-->');
		$xml.="<Movie>\r";
		$xml.="  <Title>".htmlspecialchars($movie_data['title'])."</Title>\r";
		$xml.="  <Sessions>".str_replace($remove,'',$movie_data['sessions'])."</Sessions>\r";
		$xml.="  <Synopsis>".htmlspecialchars(summary(str_replace("\r\n",' ',$movie_data['synopsis']),240))."</Synopsis>\r";
		$xml.="  <Cast>".htmlspecialchars(str_replace("\r\n",', ',$movie_data['cast']))."</Cast>\r";
		$xml.="  <Link>".$link."</Link>\r";
		$xml.="  <Image>\r";
		$xml.="    <ImageTitle>".htmlspecialchars($movie_data['title'])."</ImageTitle>\r";
		$xml.="    <ImageLink>".$link."</ImageLink>\r";
		$xml.="    <ImageURL>".htmlspecialchars($movie_image_url.$movie_data['image_name']."_medium.jpg")."</ImageURL>\r";
		$xml.="  </Image>\r";
		$xml.="</Movie>\r";
	}
	$xml.="</SessionsToday>\r";
	$xml.="</Document>\r";
	
	//remove illegal characters
	$xml=str_replace($illegal_find,$illegal_replace,$xml);
	
	////////////////////////
	// APPLY XSL TEMPLATE //
	////////////////////////

	$xslt_file = "http://www.nzcinema.co.nz/rss/nzcinema.rss100.xsl";
	$xslt_string = file_get_contents($xslt_file);
	$xml_string=$xml;
	// create the XSLT processor
  $xp = xslt_create() or die("Could not create XSLT processor");
	// read in the XSLT data
  $xslt_string = join("", file($xslt_file));
	// set up buffers
  $arg_buffer = array("/xml" => $xml_string, "/xslt" => $xslt_string);
	// process the two files to get the desired output
  if ($result = xslt_process($xp, "arg:/xml", "arg:/xslt", NULL, $arg_buffer)){

		//////////////////////////////
		// WRITE RSS FILE TO SERVER //
		//////////////////////////////
		
		$file_uri="{$base_dir}rss/{$file_name}";
		$file_handle=fopen($file_uri,'w') or die("Can't open file.");
		fwrite($file_handle,$result);
		fclose($file_handle);
  } else {
		// else display error
		echo "An error occurred: " . xslt_error($xp) . "(error code " . xslt_errno($xp) . ")";
	}
	// free the resources occupied by the handler
	xslt_free($xp);	
}

?>