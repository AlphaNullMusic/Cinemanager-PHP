{strip}

{if $plaintext}

Shoreline Cinema Weekly Newsletter
{"\n\n"}

{assign var=divider value='========================================'}
{if $plain_editorial}
	{$plain_editorial}
{/if}
{"\n\n"}
For more information on any of our movies please visit www.shorelinecinema.co.nz.

{foreach from=$ns item=n name=n}
{"\n\n"}
{$divider}
{"\n\n"}

{$n.title}
{"\n"}
{$n.classification}{if $n.class_explanation} ({$n.class_explanation}){/if}{if $n.duration} - {$n.duration}{/if}
{"\n\n"}
{if $n.synopsis}
	Synopsis:
	{"\n"}
	{$n.synopsis|indent:10}
{/if}
{"\n"}

{"\n"}
{if $n.comments}
	Comments:
	{"\n"}
	<em>{$n.comments|indent:10}</em>{"\n\n"}
{/if}

{if $n.sessions}
Sessions:{"\n"}
{foreach from=$n.sessions item=s key=date name=s}
	{$date|date_format:'%A %e %b'|indent:10}: {foreach from=$s item=st name=st}{if !$smarty.foreach.st.first}, {/if}{$st.time}{if $st.comment} ({$st.comment}){/if}{if $st.label} ({$st.label}){/if}{/foreach}
	{"\n"}
{/foreach}
{/if}

{"\n"}
Visit this web page for more information:&nbsp;
http://www.shorelinecinema.co.nz/movies/{$n.movie_id}
{/foreach}
{"\n\n"}
{$divider}
{"\n\n"}

{else}
<!DOCTYPE html>
<html lang="en-nz">
<head>
<title>Shoreline Cinema Newsletter</title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style type="text/css">
{include file="inc/css/email.css"}
ul.sessions {
	min-height: 0!important;
}
</style>
</head>
<body class="SL_body">
    <table class="full" cellspacing="0" cellpadding="0">
		<thead>
			<tr class="small-logo">
				<td>
					<a class="logo hide-med" href="https://shorelinecinema.co.nz/" style="margin:0 auto;">
					<img src="https://shoreline.nz/tpl/inc/img/sl_logo.png" class="sl-logo-sm" width="316">
					</a>
				</td>
			</tr>
		</thead>
	</table>
    
    <table class="full wrapper" cellspacing="0" cellpadding="0">
		<tbody>
			{if $editorial}
			<tr class="featured">
				<td style="color:#ffffff;">
					{$editorial}
				</td>
			</tr>
			{/if}

			{if $ns && !$no_movies}
			<tr class="information">
				<td>
					<h1 style="font-weight:400;">Weekly Session Times</h1>
				</td>
			</tr>
			<tr>
				<td>
					<table class="movie-times" cellspacing="0" cellpadding="10">
						<tbody>
							{foreach from=$ns item=n name=n}
							<tr class="movie-item">
								<td class="movie-poster">
									<a href="https://shoreline.nz/movies/{$n.movie_id}/">
										<img src="{$n.poster_url}" alt="{$n.title}" style="height:281px;" border="0">
									</a>
								</td>
								<td class="movie-text" style="border-bottom: 1px solid #ffffff;">
									<table class="content" cellspacing="0" cellpadding="0">
										<tr>
											<td style="vertical-align: top;">
												<h2>
													<a href="https://shoreline.nz/movies/{$n.movie_id}/" style="color:#ffffff;">{$n.title}</a>
													<span class="details">&nbsp;&nbsp;[<strong>{$n.classification}</strong>
													{if $n.class_explanation} ({$n.class_explanation}){/if}
													{if $n.duration} - {$n.duration}{/if}]</span>
												</h2>
												<b style="font-weight:bold;"><em style="font-style:italic;color:#ffffff;">{if $n.comments} ({$n.comments}){/if}</em></b>
												<p style="color:#ffffff;">{$n.synopsis}</p>
												<ul class="sessions">
												{foreach from=$n.sessions item=s key=date name=s}
													{assign var="cnt" value=0}
													{if $smarty.foreach.s.first}
														<li>
															<b style="color:#ffffff;display:inline-block;width:10.5em;">{$date|date_format:'%A %e %b'}</b>
													{/if}
													{foreach from=$s item=st name=st}
													{assign var="newDate" value=$date|cat:' '|cat:$st.time}
													{assign var="mmDate" value=$date|cat:' '|cat:'02:00am'}
													{if $newDate|date_format:"%Y-%m-%d %H:%M:%S" <= $mmDate|date_format:"%Y-%m-%d %H:%M:%S"}
														{if $smarty.foreach.s.first}
															{assign var="cnt" value=$cnt+1}
														{else}
															{if !$smarty.foreach.st.first}, {/if}<a href="https://shoreline.nz/bookings/{$st.id}/" style="color:#ffffff;">{$st.time}</a>
															{* if $st.label} ({$st.label}){/if *}
														{/if}
													{else}
														{if $smarty.foreach.s.first}
														{else}
															{if $cnt == 0}
																<li>
																	<b style="color:#ffffff;display:inline-block;width:10.5em;">{$date|date_format:'%A %e %b'}</b>
															{/if}
														{/if}
														{assign var="cnt" value=$cnt+1}
														{if !$smarty.foreach.st.first}, {/if}<a href="https://shoreline.nz/bookings/{$st.id}/" style="color:#ffffff;">{$st.time}</a>
														{if $st.label} <i>({$st.label})</i>{/if}
													{/if}
													{/foreach}
												{/foreach}
													</li>
												</ul>
											</td>
										</tr>
									</table>
									
								</td>
							</tr>
							{/foreach}

							<tr>
								<td>
									<br>
									<h1>Contact Us</h1>
									<p>Please use the information below to contact us if you have any questions about our cinema including venue hire enquiries.</p>
									<p><strong>Email</strong> for general enquiries:<br><a href="mailto:escape@shorelinecinema.co.nz">escape@shorelinecinema.co.nz</a></p>
									<p><strong>Phone</strong> for the cinema and reservations: <br>(04) 902 8070</p>
									<p><strong>Address</strong> for written correspondence:<br>PO Box 414, Waikanae 5036, New Zealand</p>
								</td>
				                        </tr>
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
						While every attempt is made to ensure this website is accurate, we are not liable for any omissions or errors.<br>
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
