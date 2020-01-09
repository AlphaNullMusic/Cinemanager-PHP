<!DOCTYPE html>
<html lang="en-nz">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$movie.title} - Info</title>
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

<div class="information">
    <h2>{$movie.title}</h2>
    <div class="content">
    	<div class="content-wrapper poster">
    		<img src="{$movie.poster_url}" width="190" alt="{$movie.title} Poster" />
    	</div>
    	<div class="content-wrapper text"> 
    		<div class="content">
    		    <div class="content-wrapper text">
    		        <p>{$movie.synopsis|nl2br}</p>
					{if $movie.comment}<br/><p>Manager's Comment: {$movie.comment}</p>{/if}
    		            {if $sessions}
                            <span class="h3">Upcoming Screening Times</span>
                            <ul class="sessions">
                              {foreach from=$sessions key=date item=s}
                              	{assign var="cnt" value=0}
                              	
                              	{if $smarty.foreach.s.first}
                              		<li>
                    		              <strong>{$date|date_format:'%A %e %b'}</strong>
                              	{/if}{strip}
                                    {foreach from=$s item=st name=st}
					{assign var="newDate" value=$date|cat:' '|cat:$st.time}
					{assign var="mmDate" value=$date|cat:' '|cat:'02:00am'}
					{if $newDate|date_format:"%Y-%m-%d %H:%M:%S" <= $mmDate|date_format:"%Y-%m-%d %H:%M:%S"}
						{if $smarty.foreach.s.first}
							{assign var="cnt" value=$cnt+1}
						{else}
							{if !$smarty.foreach.st.first}, {/if}<a href="/bookings/{$st.id}/">{$st.time}</a>
							{if $st.label} ({$st.label}){/if}
						{/if}
					{else}
						{if $smarty.foreach.s.first}
						{else}
							{if $cnt == 0}
								<li>
									<strong>{$date|date_format:'%A %e %b'}</strong>
							{/if}
						{/if}
						{assign var="cnt" value=$cnt+1}
						{if !$smarty.foreach.st.first}, {/if}<a href="/bookings/{$st.id}/">{$st.time}</a>{if $st.label} <i>({$st.label})</i>{/if}{/if}{/foreach}
				{/foreach}{/strip}
                               </li>
                            </ul>
                          	<p><i>To place a booking, click on the session time you are interested in. Bookings must be made an hour before the film starts. Please wait for confirmation from us via phone or email.</i></p>
                        {/if}
    		    </div>
    		    <div class="content-wrapper details">
    		        {if $movie.classification}
        			<p>
        				<strong>Rated:</strong> {$movie.classification}<br />
        				{if $movie.class_explanation} <em>{$movie.class_explanation}</em>{/if}
        			</p>
        		    {/if}
        		    <p>
        			{if $movie.duration}
              	        <strong>Duration:</strong><br />
        				{$movie.duration}<br /><br />
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
    		    </div>
    		</div>
    	</div>
      </div> 
    </div>

{include file="inc/tpl/footer.tpl"}

{/strip}
