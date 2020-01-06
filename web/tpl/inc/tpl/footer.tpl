{strip}

    <footer>
        <div id="text">
            <p>Web design and content &copy; {'Y'|date}, Shoreline Cinema Waikanae, New Zealand. <a id="improve-visibility">Improve Visibility.</a></p> 
        </div>
    </footer>
</div>

    <script src="/tpl/inc/js/jquery-3.4.1.min.js"></script>
    <script src="/tpl/inc/js/js.cookie-2.2.1.min.js"></script>
    <script src="/tpl/inc/js/scripts.js"></script>
    {if $home}
    <script src="/tpl/inc/js/slick.min.js"></script>
    <script>
        $('.featured-carousel').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          autoplay: true,
          autoplaySpeed: 10000,
        });
	window.onload = function() {
		document.getElementById("home_map").src = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3016.7729275756215!2d175.06186241584493!3d-40.87685717931432!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6d40a25f6ef06be5%3A0x2d166093f029d9a9!2sShoreline+Cinema!5e0!3m2!1sen!2sus!4v1541431553996";
	}
    </script>
    {/if}
    {if $gacode}
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id={$gacode}"></script>
	<script>
  	    window.dataLayer = window.dataLayer || [];
  	    function gtag(){ldelim}dataLayer.push(arguments);{rdelim}
  	    gtag('js', new Date());

  	    gtag('config', '{$gacode}');
	</script>
    {/if}
    {if $disable_btn}
    <script>
        $("form").submit(function () {
            $(this).submit(function () {
                return false;
            });
            $(this).find("button[type='submit']").attr('disabled', 'disabled').css('opacity', '0.8').text('Submiting...');
            return true;
        });
    </script>
    {/if}
</body>
</html>
{/strip}
