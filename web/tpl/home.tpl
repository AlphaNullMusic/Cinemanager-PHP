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
                    <iframe title="Shoreline Cinema on the map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3016.7729275756215!2d175.06186241584493!3d-40.87685717931432!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6d40a25f6ef06be5%3A0x2d166093f029d9a9!2sShoreline+Cinema!5e0!3m2!1sen!2sus!4v1541431553996" width="100%" height="250" style="border:0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    {include file="inc/tpl/footer.tpl" home=true}

{/strip}