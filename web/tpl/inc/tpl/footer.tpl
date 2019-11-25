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
    </script>
    {/if}
</body>
</html>
{/strip}