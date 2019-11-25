<?

/*

//these includes are relative to pseudo-chron.inc.php (in the default includes dir)
include("../local.inc.php");
include("../email_elements.inc.php");
include("../phpmailer/class.phpmailer.php");
//check_referrer();

//list members using CriticWatch
$num_updates=0;
$days=1;
$sql="SELECT cw.user_id, u.first_name, u.last_name, u.email FROM critic_watch cw, users u WHERE u.user_id=cw.user_id GROUP BY cw.user_id";
$user_res=mysql_query($sql, $db) or user_error("Gnarly: $sql");
while ($user_data=mysql_fetch_assoc($user_res)) {
	//get list of movies rated or reviewed by this user's favourite critics
	$sql="SELECT m.movie_id, m.title, i.image_name 
				FROM movie_ratings rat, movie_reviews rev, movies m, critic_watch cw, images i 
				WHERE cw.user_id='{$user_data['user_id']}' 
				AND (
					(rat.movie_id=m.movie_id 
					AND rat.user_id=cw.critic_id 
					AND rat.timestamp>=(CURDATE() - INTERVAL $days DAY) 
					AND rat.movie_id=i.movie_id)
				OR 
					(rev.movie_id=m.movie_id 
					AND rev.user_id=cw.critic_id 
					AND rev.timestamp>=(CURDATE() - INTERVAL $days DAY) 
					AND rev.movie_id=i.movie_id)
				)
				GROUP BY m.movie_id
				ORDER BY m.title";
	$movie_res=mysql_query($sql, $db) or user_error("Gnarly: $sql");
	if (mysql_num_rows($movie_res)>0) {
		//generate email
		$html="<p>Hi {$user_data['first_name']}, the following movies have been<br>rated or reviewed by your favourite critics today:</p><hr size='1' noshade color='#CCCCCC'>";
		$text="Hi {$user_data['first_name']}, the following movies have been rated or reviewed by your favourite critics today:\r\n\r\n------------------------------\r\n";
		while ($movie=mysql_fetch_assoc($movie_res)) {
			$html.="<table border='0' cellspacing='0' cellpadding='0' style='$default_text_style'><tr><td width='100' valign='top'><a href='{$base_url}movies/{$movie['movie_id']}.php' target='blank'><img src='{$base_url}movies/images/{$movie['image_name']}_small.jpg' width='100' border='1' alt='{$movie['title']}' style='border-color:#000000'></a></td><td nowrap>&nbsp; &nbsp;</td>";
			$html.="<td valign='top'><font face='{$default_font}'><strong><a href='{$base_url}movies/{$movie['movie_id']}.php' target='blank'><font color='#3C99C5'>{$movie['title']}</font></a></strong>";
			$text.="{$movie['title']}\r\n";
			//add rating details
			$sql="SELECT mr.rating, u.nickname 
						FROM movie_ratings mr, users u, critic_watch cw 
						WHERE mr.movie_id={$movie['movie_id']} 
						AND mr.timestamp>=(CURDATE() - INTERVAL $days DAY)
						AND mr.user_id=u.user_id
						AND cw.user_id='{$user_data['user_id']}'
						AND mr.user_id=cw.critic_id";
			$rating_res=mysql_query($sql, $db) or user_error("Gnarly: $sql");
			while ($rating=mysql_fetch_assoc($rating_res)) {
				$uv=$rating['rating'];
				$altTag="Rated $uv/5";
				$s1=1; if ($uv<1) { $s1=0; }
				$s2=1; if ($uv<2) { $s2=0; }
				$s3=1; if ($uv<3) { $s3=0; }
				$s4=1; if ($uv<4) { $s4=0; }
				$s5=1; if ($uv<5) { $s5=0; }
				$html.="<br><img src='{$base_url}images/star_{$s1}_green.gif' width='14' height='15' border='0' alt='$altTag' align='absmiddle'><img src='{$base_url}images/star_{$s2}_green.gif' width='14' height='15' border='0' alt='$altTag' align='absmiddle'><img src='{$base_url}images/star_{$s3}_green.gif' width='14' height='15' border='0' alt='$altTag' align='absmiddle'><img src='{$base_url}images/star_{$s4}_green.gif' width='14' height='15' border='0' alt='$altTag' align='absmiddle'><img src='{$base_url}images/star_{$s5}_green.gif' width='14' height='15' border='0' alt='$altTag' align='absmiddle'><font color='#66CC33'> - {$rating['nickname']}</font>";
				$text.="\r\nRated {$rating['rating']}/5 by {$rating['nickname']}";
			}
			//add review details
			$sql="SELECT mr.review_title, mr.review, u.nickname 
						FROM movie_reviews mr, users u, critic_watch cw 
						WHERE mr.movie_id={$movie['movie_id']} 
						AND mr.timestamp>=(CURDATE() - INTERVAL $days DAY)
						AND mr.user_id=u.user_id
						AND cw.user_id='{$user_data['user_id']}'
						AND mr.user_id=cw.critic_id";
			$review_res=mysql_query($sql, $db) or user_error("Gnarly: $sql");
			while ($review=mysql_fetch_assoc($review_res)) {
				if ($review['review_title']) { $html.="<br><strong>{$review['review_title']}</strong>"; }
				$html.="<br>\"{$review['review']}\"<font color='#66CC33'> - {$review['nickname']}</font>";
				$text.="\r\n\r\n{$review['review_title']}\r\n\"{$review['review']}\" - {$review['nickname']}";
			}
			$html.="</font></td></tr></table><hr size='1' noshade color='#CCCCCC'>";
			$text.="\r\n\r\n{$base_url}movies/{$movie['movie_id']}.php\r\n------------------------------\r\n";
		}
		$html.="<br><table border='0' cellspacing='0' cellpadding='0' style='$default_text_style'><tr><td><font face='{$default_font}'><strong>Your CriticWatch currently includes:</strong></font></td></tr><tr><td><ul>";
		//list all critics in CriticWatch
		$sql="SELECT u.nickname, cw.critic_id FROM users u, critic_watch cw WHERE cw.critic_id=u.user_id AND cw.user_id={$user_data['user_id']} ORDER BY u.nickname";
		$critics_res=mysql_query($sql, $db) or user_error("Gnarly: $sql");
		while ($critics=mysql_fetch_assoc($critics_res)) {
			$html.="<li><font face='{$default_font}'><a href='{$base_url}cinema_critic.php?critic={$critics['critic_id']}' target='blank'><font color='#3C99C5'>{$critics['nickname']}</font></a></font></li>";
    }
		$html.="</ul></td></tr></table><table border=0 cellpadding=4 cellspacing=1 bgcolor='#FFCC00' style='$default_text_style'><tr><td bgcolor='#FFFADD'><font face='{$default_font}'><img src='{$base_url}images/icon_exclaim_onyellow.gif' width=15 height=15 align='absmiddle'> You can add or remove from your CriticWatch at any time at <a href='{$base_url}' target='blank'><font color='#3C99C5'>www.NZCinema.co.nz</font></a>.</font></td></tr></table><br><font face='{$default_font}'>Thanks for using the NZ Cinema CriticWatch feature!";
		$text.="\r\nYou can add or remove from your CriticWatch at any time at www.NZCinema.co.nz\r\n\r\nThanks for using the NZ Cinema CriticWatch feature!";
		$html=$default_html_header.$html.$default_html_footer;
		//send this as an email using phpmailer
		$mail = new PHPMailer();
		$mail->Subject	= "CriticWatch - New Reviews By Your Favourite Critics";
		$mail->From			= $noreply_email;
		$mail->FromName	= "NZ Cinema";
		$mail->Body			= $html;
		$mail->AltBody	= $text;
		$mail->AddAddress($user_data['email'],$user_data['first_name']." ".$user_data['last_name']);
		if(!$mail->Send()) {
			echo "Message was not sent";
			echo "Mailer Error: " . $mail->ErrorInfo;
		}
		$mail->ClearAddresses();
		$num_updates++;
		unset($html);
		unset($text);
	}
}
//log updages
$sql="INSERT INTO cron_log SET type='CriticWatch', updates='$num_updates', timestamp=NOW()";
mysql_query($sql, $db) or user_error("Gnarly: $sql");

*/

?>