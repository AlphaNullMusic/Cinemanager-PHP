<nav class="navbar navbar-expand-md navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
  <!--<img src="images/movie_manager_logo_2012.gif" width="248" height="76" border="0">-->
  <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Cinemanager</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarDropdown" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
  <? if (check_cinema()) { 
    if (count($_SESSION['all_cinema_data']['cinemas'])) {?> &nbsp; 
    
      <select name="switch_cinema" class="form-control form-control-dark" OnChange="location.href='?switch_cinema='+this.value+'&<?=$_SERVER['QUERY_STRING']?>';">
        <? foreach ($_SESSION['all_cinema_data']['cinemas'] as $c) { ?>
          <option value="<?=$c['cinema_id']?>"<?
	  if ($_SESSION['cinema_data']['cinema_id'] == $c['cinema_id']) {
	    ?> selected<? 
	  } ?>><?=str_replace(', '.$c['city'], '', $c['name'])?>, <?=$c['city']?></option><? 
	 }
      ?></select><? } ?>
    
  <ul class="navbar-nav px-3">
    <li>
      <a class="nav-link" href="?action=logout">Logout</a>
    </li>
  </ul>
  <? } else { ?>
  <ul class="navbar-nav px-3">
    <li>
      <a class="nav-link" href="/" align="right">Login</a>
    </li>
  </ul>
  <? } ?>
  <ul class="navbar-nav px-3">
    <li class="nav-item text-nowrap">
      <a class="nav-link" href="help.php">Help</a>
    </li>
  </ul>
  
  <div class="d-md-none">
  <div class="collapse navbar-collapse" id="navbarDropdown">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Features</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Pricing</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown link
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
    </ul>
  </div>
  </div>
</nav>