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
{include file="inc/css/email.css"}
ul.sessions {
	min-height: 0!important;
}
</style>
</head>
<body>
    <table class="full" cellspacing="0" cellpadding="0">
		<thead>
			<tr class="small-logo">
				<td>
					<a class="logo hide-med" href="#">
					<img src="https://shoreline.nz/tpl/inc/img/sl_logo.png" class="sl-logo-sm" height="25">
					</a>
				</td>
			</tr>
			<tr class="large-logo">
				<td class="header-container">
					<img src="https://shoreline.nz/tpl/inc/img/sl_logo.png" class="sl-logo">
				</td>
			</tr>
		</thead>
	</table>
    
    <table class="full wrapper" cellspacing="0" cellpadding="0">
		<tbody>
			{if $editorial}
			<tr class="featured">
				<td>
					{$editorial}
				</td>
			</tr>
			{/if}

			{if $ns}
			<tr class="information">
				<td>
					<h1>Weekly Session Times</h1>
				</td>
			</tr>
			<tr>
				<td>
					<table class="movie-times" cellspacing="0" cellpadding="0">
						<tbody>
							{foreach from=$ns item=n name=n}
							<tr class="movie-item">
								<td class="movie-poster">
									<a href="https://shoreline.nz/movies/{$n.movie_id}/">
										<img src="{$n.poster_url}" alt="{$n.title}" width="190" border="0">
									</a>
								</td>
								<td class="movie-text">
									<table class="content" cellspacing="0" cellpadding="0">
										<tr>
											<td style="vertical-align: top;">
												<h2>
													<a href="https://shoreline.nz/movies/{$n.movie_id}/">{$n.title}</a>
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
											</td>
										</tr>
									</table>
									<hr>
								</td>
							</tr>
							{/foreach}
						</tbody>
					</table>
				</td>
			</tr>
			{/if}
		</tbody>
		<tfoot>
			<tr>
				<td class="footer">
					<p style="text-align:center;">
						To unsubscribe from this email newsletter, <a href="<!--unsub-->" id="unsubscribe">click here</a>.<br>
						While every attempt is made to ensure this website is accurate,<br>
						we are not liable for any omissions or errors.<br>
						Web design and content &copy; {'Y'|date}, Shoreline Cinema Waikanae, New Zealand.
					</p> 
				</td>
			</tr>
		</tfoot>
	</table>
</body>
</html>
{/if}

{/strip}
