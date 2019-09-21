{strip}

{include file="inc/tpl/header.tpl"}

<div class="information">
    <h1>Session Times</h1>
	<ul class="movie-times">
		{foreach from=$now_showing item=n name=n}
		<li>
			    <div class="content">
			        <div class="content-wrapper poster">
			            <a href="movies/{$n.movie_id}.php">
			                <img src="{$n.poster}" alt="{$n.title}" width="190" border="0">
			            </a>
			        </div>
			        <div class="content-wrapper text">
			            <h2><a href="movies/{$n.movie_id}.php">{$n.title}</a>
				        <span class="details">&nbsp;&nbsp;[<strong>{$n.class}</strong>
				        {if $n.class_explanation} ({$n.class_explanation}){/if}
				        {if $n.duration} - {$n.duration}{/if}]</span>
				        </h2>
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
    											{if !$smarty.foreach.st.first}, {/if}<a href="/bookings.php?s={$st.id}">{$st.time}</a>
    											{if $st.comment} ({$st.comment}){/if}
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
    										
    										{if !$smarty.foreach.st.first}, {/if}<a href="/bookings.php?s={$st.id}">{$st.time}</a>
    										{if $st.label} <i>({$st.label})</i>{/if}
    									{/if}
    								{/foreach}
    					{/foreach}
					    </li>
				    </ul>
			        </div>
			    </div>
			</li>
		{/foreach}
	</ul>
</div>

{include file="inc/tpl/footer.tpl"}

{/strip}