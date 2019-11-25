<?php /* Smarty version Smarty-3.1.13, created on 2019-10-02 21:56:27
         compiled from "tpl\movies.tpl" */ ?>
<?php /*%%SmartyHeaderCode:179845d945933ee4a76-39154822%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '581c1dbff0125a000e16d50aecff57485b8e6da7' => 
    array (
      0 => 'tpl\\movies.tpl',
      1 => 1570006586,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '179845d945933ee4a76-39154822',
  'function' => 
  array (
  ),
  'cache_lifetime' => 600,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5d945933ee6122_15146748',
  'variables' => 
  array (
    'movie' => 0,
    'tpl_name' => 0,
    'sessions' => 0,
    'date' => 0,
    's' => 0,
    'st' => 0,
    'newDate' => 0,
    'mmDate' => 0,
    'cnt' => 0,
    'cast' => 0,
    'c' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5d945933ee6122_15146748')) {function content_5d945933ee6122_15146748($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'I:\\Cinemanager\\www\\Cinemanager/_deps/smarty/plugins\\modifier.date_format.php';
?><!DOCTYPE html>
<html lang="en-nz">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $_smarty_tpl->tpl_vars['movie']->value['title'];?>
 - Info</title>
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
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value=='home.tpl'){?>active<?php }?>" href="/home/">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value=='whats-on-today.tpl'){?>active<?php }?>" href="/whats-on-today/">WHAT&apos;S ON TODAY</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value=='whats-on.tpl'){?>active<?php }?>" href="/whats-on/">WHAT&apos;S ON</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value=='coming-soon.tpl'){?>active<?php }?>" href="/coming-soon/">COMING SOON</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value=='venue-hire.tpl'){?>active<?php }?>" href="/venue-hire/">VENUE HIRE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value=='about-us.tpl'){?>active<?php }?>" href="/about-us/">ABOUT US</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value=='contact-us.tpl'){?>active<?php }?>" href="/contact-us/">CONTACT US</a>
                </li>
            </ul>
        </div>
      </nav>
    </header>
    
    <div class="wrapper">
			


<div class="information">
    <h2><?php echo $_smarty_tpl->tpl_vars['movie']->value['title'];?>
</h2>
    <div class="content">
    	<div class="content-wrapper poster">
    		<img src="<?php echo $_smarty_tpl->tpl_vars['movie']->value['poster_url'];?>
" width="190" alt="<?php echo $_smarty_tpl->tpl_vars['movie']->value['title'];?>
 Poster" />
    	</div>
    	<div class="content-wrapper text"> 
    		<div class="content">
    		    <div class="content-wrapper text">
    		        <p><?php echo nl2br($_smarty_tpl->tpl_vars['movie']->value['synopsis']);?>
</p>
					<?php if ($_smarty_tpl->tpl_vars['movie']->value['comment']){?><br/><p>Manager's Comment: <?php echo $_smarty_tpl->tpl_vars['movie']->value['comment'];?>
</p><?php }?>
    		            <?php if ($_smarty_tpl->tpl_vars['sessions']->value){?>
                            <span class="h3">Upcoming Screening Times</span>
                            <ul class="sessions">
                              <?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
 $_smarty_tpl->tpl_vars['date'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['sessions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['s']->key => $_smarty_tpl->tpl_vars['s']->value){
$_smarty_tpl->tpl_vars['s']->_loop = true;
 $_smarty_tpl->tpl_vars['date']->value = $_smarty_tpl->tpl_vars['s']->key;
?>
                              	<?php $_smarty_tpl->tpl_vars["cnt"] = new Smarty_variable(0, null, 0);?>
                              	
                              	<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['s']['first']){?>
                              		<li>
                    		              <strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b');?>
</strong>
                              	<?php }?>
                                    <?php  $_smarty_tpl->tpl_vars['st'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['st']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['s']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['st']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['st']->key => $_smarty_tpl->tpl_vars['st']->value){
$_smarty_tpl->tpl_vars['st']->_loop = true;
 $_smarty_tpl->tpl_vars['st']->index++;
 $_smarty_tpl->tpl_vars['st']->first = $_smarty_tpl->tpl_vars['st']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['st']['first'] = $_smarty_tpl->tpl_vars['st']->first;
?>
                                    	<?php $_smarty_tpl->tpl_vars["newDate"] = new Smarty_variable((($_smarty_tpl->tpl_vars['date']->value).(' ')).($_smarty_tpl->tpl_vars['st']->value['time']), null, 0);?>
                    			<?php $_smarty_tpl->tpl_vars["mmDate"] = new Smarty_variable((($_smarty_tpl->tpl_vars['date']->value).(' ')).('02:00am'), null, 0);?>
                    			
                    			<?php if (smarty_modifier_date_format($_smarty_tpl->tpl_vars['newDate']->value,"%Y-%m-%d %H:%M:%S")<=smarty_modifier_date_format($_smarty_tpl->tpl_vars['mmDate']->value,"%Y-%m-%d %H:%M:%S")){?>
                    				<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['s']['first']){?>
                    					<?php $_smarty_tpl->tpl_vars["cnt"] = new Smarty_variable($_smarty_tpl->tpl_vars['cnt']->value+1, null, 0);?>
                    				<?php }else{ ?>
                    					<?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['st']['first']){?>, <?php }?><a href="/bookings/<?php echo $_smarty_tpl->tpl_vars['st']->value['id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
</a>
                    			                  <?php if ($_smarty_tpl->tpl_vars['st']->value['comment']){?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['comment'];?>
)<?php }?>
                    			                  <?php if ($_smarty_tpl->tpl_vars['st']->value['label']){?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['label'];?>
)<?php }?>
                    				<?php }?>
                    			<?php }else{ ?>
                    				<?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['s']['first']){?>
                    												
                    				<?php }else{ ?>
                    					<?php if ($_smarty_tpl->tpl_vars['cnt']->value==0){?>
                    						<li>
                    					              <strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b');?>
</strong>
                
                    					<?php }?>
                    				<?php }?>
                    				
                    				<?php $_smarty_tpl->tpl_vars["cnt"] = new Smarty_variable($_smarty_tpl->tpl_vars['cnt']->value+1, null, 0);?>
                    				
                    				<?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['st']['first']){?>, <?php }?><a href="/bookings/<?php echo $_smarty_tpl->tpl_vars['st']->value['id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
</a>
                    		                  <?php if ($_smarty_tpl->tpl_vars['st']->value['comment']){?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['comment'];?>
)<?php }?>
                    		                  <?php if ($_smarty_tpl->tpl_vars['st']->value['label']){?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['label'];?>
)<?php }?>
                                    			<?php }?>
                                    <?php } ?>
                              <?php } ?>
                               </li>
                            </ul>
                          	<p><i>To place a booking, click on the session time you are interested. Bookings must be made an hour before the film starts. Please wait for confirmation from us via phone or email.</i></p>
                        <?php }?>
    		    </div>
    		    <div class="content-wrapper details">
    		        <?php if ($_smarty_tpl->tpl_vars['movie']->value['classification']){?>
        			<p>
        				<strong>Rated:</strong> <?php echo $_smarty_tpl->tpl_vars['movie']->value['classification'];?>
<br />
        				<?php if ($_smarty_tpl->tpl_vars['movie']->value['class_explanation']){?> <em><?php echo $_smarty_tpl->tpl_vars['movie']->value['class_explanation'];?>
</em><?php }?>
        			</p>
        		    <?php }?>
        		    <p>
        			<?php if ($_smarty_tpl->tpl_vars['movie']->value['duration']){?>
              	        <strong>Duration:</strong><br />
        				<?php echo $_smarty_tpl->tpl_vars['movie']->value['duration'];?>
<br />
        			<?php }?>
        			<?php if ($_smarty_tpl->tpl_vars['movie']->value['official_site']){?>
        				<a class="btn dark" href="<?php echo $_smarty_tpl->tpl_vars['movie']->value['official_site'];?>
" target="_blank">Official Website</a><br />
        			<?php }?>
        			<?php if ($_smarty_tpl->tpl_vars['movie']->value['trailer']){?>
        				<a class="btn dark" href="<?php echo $_smarty_tpl->tpl_vars['movie']->value['trailer'];?>
" target="_blank">Official Trailer</a>
        			<?php }?>
        		    </p>
            		<?php if ($_smarty_tpl->tpl_vars['cast']->value){?>
            			<p>
            				<strong>Starring: </strong>
            				<?php  $_smarty_tpl->tpl_vars['c'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['c']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cast']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['c']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['c']->key => $_smarty_tpl->tpl_vars['c']->value){
$_smarty_tpl->tpl_vars['c']->_loop = true;
 $_smarty_tpl->tpl_vars['c']->index++;
 $_smarty_tpl->tpl_vars['c']->first = $_smarty_tpl->tpl_vars['c']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['c']['first'] = $_smarty_tpl->tpl_vars['c']->first;
?>
            					<?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['c']['first']){?>, <?php }?><?php echo $_smarty_tpl->tpl_vars['c']->value;?>

            				<?php } ?>
            			</p>
            		<?php }?>  
    		    </div>
    		</div>
    	</div>
      </div> 
    </div>

<?php echo $_smarty_tpl->getSubTemplate ("inc/tpl/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null, array(), 0);?>


<?php }} ?>