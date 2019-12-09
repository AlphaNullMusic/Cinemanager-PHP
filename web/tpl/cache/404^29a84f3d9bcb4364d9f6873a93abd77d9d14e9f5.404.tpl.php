<?php
/* Smarty version 3.1.33, created on 2019-12-09 21:05:03
  from '/var/www/Cinemanager/web/tpl/404.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5dee002fedf006_58679006',
  'has_nocache_code' => true,
  'file_dependency' => 
  array (
    'cc9a9c9f7a0917a1a53ea197fc99e7d8293de378' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/404.tpl',
      1 => 1574633434,
      2 => 'file',
    ),
    '12f3ac630680bc6778753de8bb98e13015ec9d16' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/inc/tpl/footer.tpl',
      1 => 1574656539,
      2 => 'file',
    ),
  ),
  'cache_lifetime' => 600,
),true)) {
function content_5dee002fedf006_58679006 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="en-nz">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>404 - Page Not Found</title>
<meta name="description" content="Shoreline Cinema is Waikanae's premiere cinema complex. Find out who we are, what's on, what's coming up, how to contact us and much more.">
<link href="/tpl/inc/css/editor.css" rel="stylesheet" type="text/css" />
<link href="/tpl/inc/css/styles.css" rel="stylesheet" type="text/css" />
<link href="/tpl/inc/css/print.css" rel="stylesheet" type="text/css" media="print" />
</head>
<body>
    <header>  
      <div class="box-auto hide show-med"><img src="/tpl/inc/img/sl_logo.png" class="img-responsive mc-auto show"></div>
      
      <nav>
        <a class="logo hide-med" href="#"><img src="/tpl/inc/img/sl_logo.png" height="25"></a>
        <button class="hide-med" type="button">
            <span class="icon-text">Menu&nbsp;</span>
            <span class="icon"></span>
        </button>
        
        
        <div class="collapse jc-center" id="navbar">
            <ul class="nav-links">
                <li class="nav-item">
                    <a class="nav-link " href="/home/">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="/whats-on-today/">WHAT&apos;S ON TODAY</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="/whats-on/">WHAT&apos;S ON</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="/coming-soon/">COMING SOON</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="/venue-hire/">VENUE HIRE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="/about-us/">ABOUT US</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="/contact-us/">CONTACT US</a>
                </li>
            </ul>
        </div>
      </nav>
    </header>
    
    <div class="wrapper">
			

    
    <div class="information">
        <div class="content">
            <div class="content-wrapper">
                <div class="box">
                    <h2>Sorry... we couldn't find the page you were looking for.</h2>
	                <p><?php echo '<?=';?>($_REQUEST['er'])?'Error: '.$_REQUEST['er']:'';<?php echo '?>';?></p>
	                <p></p>
	                <p>Click the button below to go back home.</p>
	                <a class="btn dark" href="/home/">Home</a>
                </div>
            </div>    
        </div>
    </div>

    <footer><div id="text"><p>Web design and content &copy; 2019, Shoreline Cinema Waikanae, New Zealand. <a id="improve-visibility">Improve Visibility.</a></p></div></footer></div><script src="/tpl/inc/js/jquery-3.4.1.min.js"></script><script src="/tpl/inc/js/js.cookie-2.2.1.min.js"></script><script src="/tpl/inc/js/scripts.js"></script><!-- Global site tag (gtag.js) - Google Analytics --><script async src="https://www.googletagmanager.com/gtag/js?id=UA-137475424-2"></script><script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', 'UA-137475424-2');</script></body></html>

<?php }
}
