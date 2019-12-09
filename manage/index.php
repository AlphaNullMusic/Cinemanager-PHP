<?php
require("inc/manage.inc.php");

if (check_cinema()) {
	header("Location: movies.php");
	exit;
}

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="includes/generic.js" type="text/javascript"></script>
<title><?php echo $title_pre ?>a Specialised Content Management System for New Zealand Cinemas</title>
<link href="inc/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="inc/css/styles.css" rel="stylesheet" type="text/css">
<link href="inc/css/signin.css" rel="stylesheet">
</head>
<body class="text-center">
  <form name="login_form" class="form-signin" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="login_form">
    <h1 class="h3 mb-3 font-weight-normal">Welcome to Cinemanager</h3>
	          <p>
	          	Please log in to proceed.<br>
	     	  </p>
	          <input id="login" class="form-control login-box-input" type="text" name="login" size="14" maxlength="20" value="<?=(isset($_GET['login']))?$_GET['login']:''?>" placeholder="Username" />
	          <input id="password" class="form-control login-box-input" type="password" name="password" size="14" maxlength="20" placeholder="Password" />
	          <input class="login-box-submit-button" type="submit" name="Submit" value="Login" />
	          <input type="hidden" name="action" value="login" />
	        </form>
	      </div>
	    </div>
	    <div class="cell auto"></div>
	  </div>
	</div>
      </div>
</body>
</html>
