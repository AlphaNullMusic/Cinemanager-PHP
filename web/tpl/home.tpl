{strip}

{include file="inc/tpl/header.tpl" home=true}
	
	{if $now_showing[1]}
		{include file="inc/tpl/featured.tpl"}
	{/if}
    <div class="information">
        <div class="content">
            <div class="content-wrapper">
                <div class="box">
                    {$page['content']}
                </div>
            </div>    
            <div class="content-wrapper">
                <div class="box">
					{$name}
                    <h2>Visit Us</h2>
                    <iframe title="Shoreline Cinema on the map" src="about:blank" width="100%" height="250" style="border:0" id="home_map" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    {include file="inc/tpl/footer.tpl" home=true}

{/strip}
