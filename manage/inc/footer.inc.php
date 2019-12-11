    </main>
  </div>
</div>
<script src="inc/js/jquery-3.4.1.min.js"></script>
<script src="inc/js/popper.min.js"></script>
<script src="inc/js/bootstrap.min.js"></script>
<script src="inc/js/generic.js"></script>
<script>
	window.addEventListener("resize", function(event) {
		if ($('body').width() >= 768) {
			$('.sidebar').css({left:0});
	   	} else {
			var w = $('.sidebar').width();
	   		$('.sidebar').css({left:-w});
		}
	});
	$(document).ready(function() {
		// Responsive Navbar
		if ($('body').width() >= 768) {
                        $('.sidebar').css({left:0});
                } else {
                        var w = $('.sidebar').width();
                        $('.sidebar').css({left:-w});
                }

		// Tooltips
		$('body').tooltip({ selector: '[data-toggle=tooltip]' });
	});
</script>
