{strip}

<!DOCTYPE html>
<html lang="en-nz">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
    {if $tpl_name == 'index.tpl'}{$name} {$city}{/if}
    {if $tpl_name == 'session_times_today.tpl'}What's On Today{/if}
    {if $tpl_name == 'session_times.tpl'}What's On{/if}
    {if $tpl_name == 'coming_soon.tpl'}Coming Soon{/if}
    {if $tpl_name == 'venue-hire.tpl'}Venue Hire{/if}
    {if $tpl_name == 'about-us.tpl'}About Us{/if}
    {if $tpl_name == 'contact-us.tpl'}Contact Us{/if}
</title>
<meta name="description" content="Shoreline Cinema is Waikanae's premiere cinema complex. Find out who we are, what's on, what's coming up, how to contact us and much more.">
<link href="/tpl/inc/css/editor.css" rel="stylesheet" type="text/css" />
<link href="/tpl/inc/css/styles.css" rel="stylesheet" type="text/css" />
<link href="/tpl/inc/css/print.css" rel="stylesheet" type="text/css" media="print" />
{if $js_checkform}
	<script language="JavaScript" src="../includes/checkform.js"></script>
{/if}
{if $home}
	<link rel="stylesheet" type="text/css" href="/tpl/inc/css/slick.css"/>
	<link rel="stylesheet" type="text/css" href="/tpl/inc/css/slick-theme.css"/>
{/if}
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
                    <a class="nav-link {if $tpl_name == 'index.tpl'}active{/if}" href="/index.php">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {if $tpl_name == 'session_times_today.tpl'}active{/if}" href="/session_times_today.php">WHAT&apos;S ON TODAY</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {if $tpl_name == 'session_times.tpl'}active{/if}" href="/session_times.php">WHAT&apos;S ON</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {if $tpl_name == 'coming_soon.tpl'}active{/if}" href="/coming_soon.php">COMING SOON</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {if $tpl_name == 'venue-hire.tpl'}active{/if}" href="/page-venue-hire.php">VENUE HIRE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {if $tpl_name == 'about-us.tpl'}active{/if}" href="/page-about-us.php">ABOUT US</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {if $tpl_name == 'contact-us.tpl'}active{/if}" href="/page-contact-us.php">CONTACT US</a>
                </li>
            </ul>
        </div>
      </nav>
    </header>
    
    <div class="wrapper">
			
{/strip}