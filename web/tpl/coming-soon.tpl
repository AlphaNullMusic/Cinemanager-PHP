{strip}

{include file="inc/tpl/header.tpl"}

<div class="information">
	<h1>Coming Soon</h1>
	{if $coming_soon}
		{foreach from=$coming_soon item=n}
			{*if $n.image_name*}
		  <div class="item">
			<a href="/movies/{$n.movie_id}/">
			  <img src="{$n.poster_url}" alt="{$n.title} Poster" height="279">
			</a>
			<h2><a href="/movies/{$n.movie_id}/">{$n.title}</a></h2>
			<span class="details">{if $n.release_date2 != "TBC"}
			  {$n.release_date}
			{else}
			  Coming Soon
			{/if}</span>
		  </div>
		{*/if*}
		{/foreach}
	{else}
		<p>
			Currently we don't have any movies coming soon.<br />
			Please <a href="/contact-us/"><strong><em>contact us</em></strong></a> or check back later.
		</p>
	{/if}
</div>

{include file="inc/tpl/footer.tpl"}

{/strip}