{strip}

{if $plaintext}

{assign var=divider value='<br><br>========================================<br><br>'}
{$plain_editorial}
<br><br>
For more information on any of our movies please visit www.shorelinecinema.co.nz.

{foreach from=$ns item=n name=n}
{$divider}

{$n.title}
<br>
{$n.classification}{if $n.class_explanation} ({$n.class_explanation}){/if}{if $n.duration} - {$n.duration}{/if}
<br><br>
{if $n.synopsis}
	Synopsis:
	<br>
	&emsp;{$n.synopsis}
{/if}
<br>

<br>
{if $n.comments}
	<em>{$n.comments}</em><br><br>
{/if}

{if $n.sessions}
Sessions:<br>
{foreach from=$n.sessions item=s key=date name=s}
	&emsp;
	{$date|date_format:'%A %e %b'}: {foreach from=$s item=st name=st}{if !$smarty.foreach.st.first}, {/if}{$st.time}{if $st.comment} ({$st.comment}){/if}{if $st.label} ({$st.label}){/if}{/foreach}
	<br>
{/foreach}
{/if}

<br>
Visit this web page for more information:&nbsp;
http://www.shorelinecinema.co.nz/movies/{$n.movie_id}
{/foreach}
{$divider}

{else}
<!DOCTYPE html>
<html lang="en-nz">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
{include file="inc/css/styles.css"}
ul.sessions {
	min-height: 0!important;
}
</style>
</head>
<body>
    <header>  
      <div class="box-auto hide show-med"><img src="https://shoreline.nz/tpl/inc/img/sl_logo.png" class="img-responsive mc-auto show"></div>
      
      <nav>
        <a class="logo hide-med" href="#"><img src="https://shoreline.nz/tpl/inc/img/sl_logo.png" height="25"></a>
      </nav>
    </header>
    
    <div class="wrapper">
	
	{if $editorial}
	<div class="featured">
        <div class="content">
            <div style="white-space: pre-wrap;">{$editorial}</div>
        </div>
    </div>
	{/if}

{if $ns}
	<div class="information">
    <h1>Weekly Session Times</h1>
	<ul class="movie-times">
		{foreach from=$ns item=n name=n}
		<li>
			    <div class="content">
			        <div class="content-wrapper poster">
			            <a href="https://shoreline.nz/movies/{$n.movie_id}/">
			                <img src="{$n.poster_url}" alt="{$n.title}" width="190" border="0">
			            </a>
			        </div>
			        <div class="content-wrapper text">
			            <h2><a href="https://shoreline.nz/movies/{$n.movie_id}/">{$n.title}</a>
				        <span class="details">&nbsp;&nbsp;[<strong>{$n.classification}</strong>
				        {if $n.class_explanation} ({$n.class_explanation}){/if}
				        {if $n.duration} - {$n.duration}{/if}]</span>
				        </h2>
						<strong><em>{if $n.comments} ({$n.comments}){/if}</em></strong>
						<p>{$n.synopsis}</p>
			            <ul class="sessions">
					    {foreach from=$n.sessions item=s key=date name=s}
    						{assign var="cnt" value=0}
    						
    						{if $smarty.foreach.s.first}
    							<li>
    								<strong>{$date|date_format:'%A %e %b'}</strong>
    						{/if}
    								{foreach from=$s item=st name=st}
    									{assign var="newDate" value=$date|cat:' '|cat:$st.time}
    									{assign var="mmDate" value=$date|cat:' '|cat:'02:00am'}
    									
    									{if $newDate|date_format:"%Y-%m-%d %H:%M:%S" <= $mmDate|date_format:"%Y-%m-%d %H:%M:%S"}
    										{if $smarty.foreach.s.first}
    											{assign var="cnt" value=$cnt+1}
    										{else}
    											{if !$smarty.foreach.st.first}, {/if}<a href="https://shoreline.nz/bookings/{$st.id}/">{$st.time}</a>
    											{* if $st.label} ({$st.label}){/if *}
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
    										
    										{if !$smarty.foreach.st.first}, {/if}<a href="https://shoreline.nz/bookings/{$st.id}/">{$st.time}</a>
    										{* if $st.label} <i>({$st.label})</i>{/if *}
    									{/if}
    								{/foreach}
    					{/foreach}
						</li>
				    </ul>
			        </div>
			    </div>
				<hr>
			</li>
		{/foreach}
	</ul>
	</div>
{/if}

    <footer>
        <div id="text">
            <p style="text-align:center;">To unsubscribe from this email newsletter, <a href="<!--unsub-->" id="unsubscribe">click here</a>.<br>
      While every attempt is made to ensure this website is accurate,<br>
			we are not liable for any omissions or errors.<br>
			Web design and content &copy; {'Y'|date}, Shoreline Cinema Waikanae, New Zealand.</p> 
        </div>
    </footer>
	
</div>
</body>
</html>
{/if}

{/strip}