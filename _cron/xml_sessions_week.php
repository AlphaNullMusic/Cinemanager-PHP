<?

//get list of cinemas with XML export set

$sql = "
	SELECT c.cinema_id,c.cinema_name,c.city,c.homepage_url 
	FROM cinemas c, cinema_permissions cp 
	WHERE cp.export_xml_full=1 
		AND cp.cinema_id=c.cinema_id
";
$cinema_res = mysql_query($sql);

//set up XML basics

//loop through cinemas and get their sessions

while ($c = mysql_fetch_assoc($cinema_res)) {
	$sessions = get_movie_list_full('ns','priority,m.title',7,'%W %D','%e %b',50,'today',$c['cinema_id']);
}

?>