{strip}
    <div class="featured">
        <div class="content">
            <div class="featured-carousel">
                {foreach from=$now_showing item=n name=n}
                <div>
                    <div class="content-wrapper poster">
                        <img class="mc-auto show" src="{$n.poster_url}" width="150" alt="{$n.title} Poster">
                    </div>
                    <div class="content-wrapper text">
                        <h1 class="featured-title">{$n.title}</h1>
                        <p>{$n.synopsis}</p>
                        <a class="btn light" href="/movies/{$n.movie_id}/">See More</a> 
                    </div>
                </div>
                {/foreach}
            </div>
        </div>
    </div>

{/strip}