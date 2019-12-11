<nav class="navbar navbar-expand-md navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
	<a class="navbar-brand mr-0" href="#">Cinemanager</a>
<?php 
	if (check_cinema()) { 
		if (count($_SESSION['all_cinema_data']['cinemas'])) {?> 
			&nbsp; 
			<select 
				name="switch_cinema" 
				class="form-control form-control-dark d-none d-md-inline" 
				OnChange="location.href='?switch_cinema='+this.value+'&<?php echo $_SERVER['QUERY_STRING']?>';"
			>
        <?php 
			foreach ($_SESSION['all_cinema_data']['cinemas'] as $c) { ?>
				<option	selected>
					<?php echo str_replace(', '.$c['city'], '', $c['name'])?>, <?php echo $c['city']?>
				</option>
	  <?php } ?>
			</select>
  <?php } ?>
    
  <ul class="navbar-nav px-3 ml-auto">
    <li>
      <a class="nav-link" href="?action=logout">Logout</a>
    </li>
  </ul>
  <?php } else { ?>
  <ul class="navbar-nav px-3 ml-auto">
    <li>
      <a class="nav-link" href="/" align="right">Login</a>
    </li>
  </ul>
  <?php } ?>
	<button class="navbar-toggler" type="button" aria-label="Toggle navigation" onclick="sidebarToggle();">
                <span class="navbar-toggler-icon"></span>
        </button>
</nav>
