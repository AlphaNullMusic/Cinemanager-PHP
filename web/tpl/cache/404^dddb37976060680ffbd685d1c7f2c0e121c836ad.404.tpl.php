<?php /*%%SmartyHeaderCode:86115d94711060df17-46239643%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dddb37976060680ffbd685d1c7f2c0e121c836ad' => 
    array (
      0 => 'tpl\\404.tpl',
      1 => 1570009501,
      2 => 'file',
    ),
    '717357d2a229876ed218782ea98cbddfc6796dab' => 
    array (
      0 => 'I:\\Cinemanager\\www\\Cinemanager\\web\\tpl\\inc\\tpl\\footer.tpl',
      1 => 1569987668,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '86115d94711060df17-46239643',
  'cache_lifetime' => 600,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5da136732252a2_95286690',
  'has_nocache_code' => false,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5da136732252a2_95286690')) {function content_5da136732252a2_95286690($_smarty_tpl) {?><!DOCTYPE html>
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
	                <p><?php echo '<?'; ?>=($_REQUEST['er'])?'Error: '.$_REQUEST['er']:'';<?php echo '?>'; ?></p>
	                <p></p>
	                <p>Click the button below to go back home.</p>
	                <a class="btn dark" href="/home/">Home</a>
                </div>
            </div>    
        </div>
    </div>

    <footer><div id="text"><p>Web design and content &copy; 2019, Shoreline Cinema, Waikanae, New Zealand. <a id="improve-visibility">Improve Visibility.</a></p></div></footer></div><script src="/tpl/inc/js/jquery-3.4.1.min.js"></script><script src="/tpl/inc/js/js.cookie-2.2.1.min.js"></script><script src="/tpl/inc/js/scripts.js"></script></body></html>

<?php }} ?>