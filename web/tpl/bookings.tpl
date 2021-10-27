<!DOCTYPE html>
<html lang="en-nz">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Book for {$movie.title}</title>
<meta name="description" content="Shoreline Cinema is Waikanae's premiere cinema complex. Find out who we are, what's on, what's coming up, how to contact us and much more.">
<link href="/tpl/inc/css/styles.css" rel="stylesheet" type="text/css" />
<link href="/tpl/inc/css/print.css" rel="stylesheet" type="text/css" media="print" />
</head>
<body>
    <header>  
      <div class="box-auto hide show-med"><img src="/tpl/inc/img/sl_logo.png" class="img-responsive mc-auto show"></div>
      
      <nav>
        <a class="logo hide-med" href="#"><img src="/tpl/inc/img/sl_logo.png" height="25"></a>
        <button class="hide-med" type="button">
            <span class="icon-text">Menu&nbsp;</span>
            <span class="icon"></span>
        </button>
        
        
        <div class="collapse jc-center" id="navbar">
            <ul class="nav-links">
                <li class="nav-item">
                    <a class="nav-link {if $tpl_name == 'home.tpl'}active{/if}" href="/home/">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {if $tpl_name == 'whats-on-today.tpl'}active{/if}" href="/whats-on-today/">WHAT&apos;S ON TODAY</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {if $tpl_name == 'whats-on.tpl'}active{/if}" href="/whats-on/">WHAT&apos;S ON</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {if $tpl_name == 'coming-soon.tpl'}active{/if}" href="/coming-soon/">COMING SOON</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {if $tpl_name == 'venue-hire.tpl'}active{/if}" href="/venue-hire/">VENUE HIRE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {if $tpl_name == 'about-us.tpl'}active{/if}" href="/about-us/">ABOUT US</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {if $tpl_name == 'contact-us.tpl'}active{/if}" href="/contact-us/">CONTACT US</a>
                </li>
            </ul>
        </div>
      </nav>
    </header>
    
    <div class="wrapper">
			
{/strip}

{literal}
<script language="JavaScript" type="text/JavaScript">
<!--
function YY_checkform() { //v4.71
//copyright (c)1998,2002 Yaromat.com
  var a=YY_checkform.arguments,oo=true,v='',s='',err=false,r,o,at,o1,t,i,j,ma,rx,cd,cm,cy,dte,at;
  for (i=1; i<a.length;i=i+4){
    if (a[i+1].charAt(0)=='#'){r=true; a[i+1]=a[i+1].substring(1);}else{r=false}
    o=MM_findObj(a[i].replace(/\[\d+\]/ig,""));
    o1=MM_findObj(a[i+1].replace(/\[\d+\]/ig,""));
    v=o.value;t=a[i+2];
    if (o.type=='text'||o.type=='password'||o.type=='hidden'){
      if (r&&v.length==0){err=true}
      if (v.length>0)
      if (t==1){ //fromto
        ma=a[i+1].split('_');if(isNaN(v)||v<ma[0]/1||v > ma[1]/1){err=true}
      } else if (t==2){
        rx=new RegExp("^[\\w\.=-]+@[\\w\\.-]+\\.[a-zA-Z]{2,4}$");if(!rx.test(v))err=true;
      } else if (t==3){ // date
        ma=a[i+1].split("#");at=v.match(ma[0]);
        if(at){
          cd=(at[ma[1]])?at[ma[1]]:1;cm=at[ma[2]]-1;cy=at[ma[3]];
          dte=new Date(cy,cm,cd);
          if(dte.getFullYear()!=cy||dte.getDate()!=cd||dte.getMonth()!=cm){err=true};
        }else{err=true}
      } else if (t==4){ // time
        ma=a[i+1].split("#");at=v.match(ma[0]);if(!at){err=true}
      } else if (t==5){ // check this 2
            if(o1.length)o1=o1[a[i+1].replace(/(.*\[)|(\].*)/ig,"")];
            if(!o1.checked){err=true}
      } else if (t==6){ // the same
            if(v!=MM_findObj(a[i+1]).value){err=true}
      }
    } else
    if (!o.type&&o.length>0&&o[0].type=='radio'){
          at = a[i].match(/(.*)\[(\d+)\].*/i);
          o2=(o.length>1)?o[at[2]]:o;
      if (t==1&&o2&&o2.checked&&o1&&o1.value.length/1==0){err=true}
      if (t==2){
        oo=false;
        for(j=0;j<o.length;j++){oo=oo||o[j].checked}
        if(!oo){s+='* '+a[i+3]+'\n'}
      }
    } else if (o.type=='checkbox'){
      if((t==1&&o.checked==false)||(t==2&&o.checked&&o1&&o1.value.length/1==0)){err=true}
    } else if (o.type=='select-one'||o.type=='select-multiple'){
      if(t==1&&o.selectedIndex/1==0){err=true}
    }else if (o.type=='textarea'){
      if(v.length<a[i+1]){err=true}
    }
    if (err){s+='* '+a[i+3]+'\n'; err=false}
  }
  if (s!=''){alert('The required information is incomplete or contains errors:\n\n'+s)}
  document.MM_returnValue = (s=='');
}
//-->
</script>
{/literal}


<div class="information">
    <h2>{$movie.title} - Ticket Request</h2>
    <div class="content">
    	<ul class="content-wrapper poster">
    		<img src="{$movie.poster_url}" width="190" alt="{$movie.title} Poster" />
    	</ul>
    	<div class="content-wrapper text">
    	    <div class="content">
    	        <div class="content-wrapper text">
    	            <p><i>Bookings must be made an hour before the film starts. Please wait for confirmation from us via phone or email.</i></p>
    	            {*if $session.session_time|strtotime >= $smarty.now*}
			<form action="/bookings/{$booking_id}/" method="post" name="bookings" onSubmit="YY_checkform('bookings','c_name','#q','0','Please enter your name.','c_email','#S','2','Please enter your email address.','c_phone','#q','0','Please enter your phone number.');return document.MM_returnValue">
                        {if !$booking}
							<span class="h3">Screening Details</span><br />
							<p>Movie: <strong>{$movie.title}</strong></p>
							<p>Date: <strong>{$session.session_timestamp|date_format:'%A %e %b'}</strong></p>
							<p>Time: <strong>{$session.session_time}</strong></p><br />
						{/if}
						{if $er=='incomplete'}
                            {if $er_msg}
                                <script>alert('Request incomplete:\r\n{$er_msg}');</script>
                                <h4 class="booking-failed"><i>Request incomplete:<br />{$er_msg}</i></h4><br />
                            {else}
							    <script>alert('Request incomplete.\r\nPlease check your details and try again.');</script>
                                <h4 class="booking-failed"><i>Request incomplete:<br />Please try again.</i></h4><br />
                            {/if}
						{/if}
						{if $session.label_name}
						<h4 class="booking-failed"><i>Note, this session is labelled as: </i><br><strong>{$session.label_name}<strong></h4><br />
						{/if}
                        {if $booking=='complete'}
							<script>alert('Request completed.');</script>
                            <span class="booking-succeeded"><br>Tickets Requested!<br />
                            <i>We will call you to confirm your booking.</i></span><hr />
							<span><strong>Your Session Details</strong></span><br />
							<p>Movie: <strong>{$movie.title}</strong></p>
							<p>Date: <strong>{$session.session_timestamp|date_format:'%A %e %b'}</strong></p>
							<p>Time: <strong>{$session.session_time}</strong></p><hr />
                            <i>To give feedback on the new website, please <a href="/contact-us/"><strong>click here</strong></a>.</i>
                            {if $t_adults}
                              <p>Adults: <strong>{$t_adults}</strong></p>
                            {/if}
                            {if $t_children}
                              <p>Children: <strong>{$t_children}</strong></p>
                            {/if}
                            {if $t_seniors}
                              <p>Seniors: <strong>{$t_seniors}</strong></p>
                            {/if}
                            {if $t_students}
                              <p>Students: <strong>{$t_students}</strong></p>
                            {/if}
                        {elseif $booking=='failed'}
							<script>alert('Request failed.\r\nPlease try again soon.');</script>
                            <span class="booking-failed"><br>Booking Failed<br />
                            <i>Please try booking again later, or <a href="/contact-us/"><strong><em>contact us</em></strong></a>.</i></span>
						{else}
                            <tr><td colspan="3"><span class="h3">Tickets Required</span><br /></table></td></tr>
                            <table>
                            <tr>
                              <td align="right">Adults</td>
                              <td>&nbsp;</td>
                              <td><select name="t_adults" id="t_adults">{html_options values=$ticket_nums output=$ticket_nums selected=$t_adults}</select></td>
                            </tr>
                            <tr>
                              <td align="right">Children</td>
                              <td>&nbsp;</td>
                              <td><select name="t_children" id="t_children">{html_options values=$ticket_nums output=$ticket_nums selected=$t_children}</select></td>
                            </tr>
                            <tr>
                              <td align="right">Seniors</td>
                              <td>&nbsp;</td>
                              <td><select name="t_seniors" id="t_seniors">{html_options values=$ticket_nums output=$ticket_nums selected=$t_seniors}</select></td>
                            </tr>
                            <tr>
                              <td align="right">Students</td>
                              <td>&nbsp;</td>
                              <td><select name="t_students" id="t_students">{html_options values=$ticket_nums output=$ticket_nums selected=$t_students}</select></td>
                            </tr>
                            <tr>
                              <td colspan="3"><span class="h3"><br>
                                Personal Details</span></td>
                            </tr>
                            <tr>
                              <td align="right">Your Name </td>
                              <td>&nbsp;</td>
                              <td><input name="c_name" type="text" id="c_name" value="{$c_name}" size="15" maxlength="50"></td>
                            </tr>
                            <tr>
                              <td align="right">Email</td>
                              <td>&nbsp;</td>
                              <td><input name="c_email" type="text" id="c_email" value="{$c_email}" size="15" maxlength="50"></td>
                            </tr>
                            <tr>
                              <td align="right">Phone</td>
                              <td>&nbsp;</td>
                              <td><input name="c_phone" type="text" id="c_phone" value="{$c_phone}" size="15" maxlength="50"></td>
                            </tr>
							<tr>
                              <td align="right">Request Wheelchair Access?</td>
                              <td>&nbsp;</td>
                              <td><input name="c_wheelchair" type="checkbox" id="c_wheelchair" {$c_wheelchair}></td>
                            </tr>
                            <tr>
                              <td align="right">Sign up for Weekly Session Timetable</td>
                              <td>&nbsp;</td>
                              <td><input name="c_newsletter_signup" type="checkbox" {if $c_newsletter_signup != 'unchecked'} checked="checked" {/if} id="c_newsletter_signup" {$c_newsletter_signup}></td>
			    </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td><br>
                                <input type="hidden" name="m" value="{$m}">
                                <input type="hidden" name="d" value="{$d}">
                                <input type="hidden" name="s" value="{$s}">
                                <input type="hidden" name="action" value="place_booking">
				{* <span class="booking-failed">Sorry, bookings are temporarily disabled for maintenance.<br>Please <em><a href="/contact-us">contact us</a></em> to book.</span><br> *}
                                <button class="btn green" type="submit">Request Ticket</button></td>
                            </table>
				<em>By checking the above box, you agree to recieve weekly session times sent to your email address.</em>
                        {/if}
                		</form>
			{*else*}
				{*<span class="booking-failed"><em><strong>This session has already shown.</strong></em></span>*}
			{*/if*}
    	        </div>
    	    </div> 	
      </div> 
      <div class="content-wrapper details">
    		        {if $movie.class}
        			<p>
        				<strong>Rated:</strong> {$movie.class}<br />
        				{if $movie.class_explanation} {$movie.class_explanation}{/if}
        			</p>
        		    {/if}
        		    <p>
        			{if $movie.duration}
              	        <strong>Duration:</strong><br />
        				{$movie.duration}<br />
        			{/if}
        			{if $movie.official_site}
        				<a class="btn dark" href="{$movie.official_site}" target="_blank">Official Website</a><br />
        			{/if}
        			{if $movie.trailer}
        				<a class="btn dark" href="{$movie.trailer}" target="_blank">Official Trailer</a>
        			{/if}
        		    </p>
            		{if $cast}
            			<p>
            				<strong>Starring: </strong>
            				{foreach from=$cast item=c name=c}
            					{if !$smarty.foreach.c.first}, {/if}{$c}
            				{/foreach}
            			</p>
            		{/if} 
    		</p>
    	</div>
	</div>
</div>
{include file="inc/tpl/footer.tpl"}

{/strip}
