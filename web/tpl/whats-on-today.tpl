{strip}

{include file="inc/tpl/header.tpl"}

<div class="information">
    <h1>What's on Today</h1>
	{if $sessions}
		{foreach from=$sessions item=s name=s}
		<div class="featured-poster item">
			<div style="clear:{cycle values="left,none"};">
				<a href="/movies/{$s.movie_id}/"><img src="{$s.poster_url}" alt="{$s.title}" height="279" border="0"></a>
	      <h2><a href="/movies/{$s.movie_id}/">{$s.title}</a> <span class="details">({$s.classification})</span></h2>
	      <p><strong>
	        {foreach from=$s.sessions item=st name=st}
	          {if !$smarty.foreach.st.first}, {/if}{$st.time}
	          {if $st.comment} ({$st.comment}){/if}
	          {if $st.label} <em>({$st.label})</em>{/if}
	        {/foreach}</strong></p>
				<a class="btn dark" href="/movies/{$s.movie_id}/">More details</a>	
			</div>
		</div>
		{/foreach}
	{else}
		<p>
			Currently we don't have any session times listed.<br />
			Please <a href="/contact-us/"><strong><em>contact us</em></strong></a> or check back later.
		</p>
	{/if}
</div>

{include file="inc/tpl/footer.tpl"}

{/strip}