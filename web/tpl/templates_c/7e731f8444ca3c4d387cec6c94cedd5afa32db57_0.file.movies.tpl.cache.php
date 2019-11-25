<?php
/* Smarty version 3.1.33, created on 2019-11-25 17:12:30
  from '/var/www/Cinemanager/web/tpl/movies.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ddb54ae9f2524_25043983',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7e731f8444ca3c4d387cec6c94cedd5afa32db57' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/movies.tpl',
      1 => 1574633434,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:inc/tpl/footer.tpl' => 1,
  ),
),false)) {
function content_5ddb54ae9f2524_25043983 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/Cinemanager/_deps/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
$_smarty_tpl->compiled->nocache_hash = '7984289425ddb54ae9c7492_74312749';
?>
<!DOCTYPE html>
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
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'home.tpl') {?>active<?php }?>" href="/home/">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'whats-on-today.tpl') {?>active<?php }?>" href="/whats-on-today/">WHAT&apos;S ON TODAY</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'whats-on.tpl') {?>active<?php }?>" href="/whats-on/">WHAT&apos;S ON</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'coming-soon.tpl') {?>active<?php }?>" href="/coming-soon/">COMING SOON</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'venue-hire.tpl') {?>active<?php }?>" href="/venue-hire/">VENUE HIRE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'about-us.tpl') {?>active<?php }?>" href="/about-us/">ABOUT US</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['tpl_name']->value == 'contact-us.tpl') {?>active<?php }?>" href="/contact-us/">CONTACT US</a>
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
					<?php if ($_smarty_tpl->tpl_vars['movie']->value['comment']) {?><br/><p>Manager's Comment: <?php echo $_smarty_tpl->tpl_vars['movie']->value['comment'];?>
</p><?php }?>
    		            <?php if ($_smarty_tpl->tpl_vars['sessions']->value) {?>
                            <span class="h3">Upcoming Screening Times</span>
                            <ul class="sessions">
                              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['sessions']->value, 's', false, 'date');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['date']->value => $_smarty_tpl->tpl_vars['s']->value) {
?>
                              	<?php $_smarty_tpl->_assignInScope('cnt', 0);?>
                              	
                              	<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first'] : null)) {?>
                              		<li>
                    		              <strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b');?>
</strong>
                              	<?php }?>
                                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['s']->value, 'st', false, NULL, 'st', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['st']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['index'];
?>
                                    	<?php $_smarty_tpl->_assignInScope('newDate', (($_smarty_tpl->tpl_vars['date']->value).(' ')).($_smarty_tpl->tpl_vars['st']->value['time']));?>
                    			<?php $_smarty_tpl->_assignInScope('mmDate', (($_smarty_tpl->tpl_vars['date']->value).(' ')).('02:00am'));?>
                    			
                    			<?php if (smarty_modifier_date_format($_smarty_tpl->tpl_vars['newDate']->value,"%Y-%m-%d %H:%M:%S") <= smarty_modifier_date_format($_smarty_tpl->tpl_vars['mmDate']->value,"%Y-%m-%d %H:%M:%S")) {?>
                    				<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first'] : null)) {?>
                    					<?php $_smarty_tpl->_assignInScope('cnt', $_smarty_tpl->tpl_vars['cnt']->value+1);?>
                    				<?php } else { ?>
                    					<?php if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first'] : null)) {?>, <?php }?><a href="/bookings/<?php echo $_smarty_tpl->tpl_vars['st']->value['id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
</a>
                    			                  <?php if ($_smarty_tpl->tpl_vars['st']->value['comment']) {?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['comment'];?>
)<?php }?>
                    			                  <?php if ($_smarty_tpl->tpl_vars['st']->value['label']) {?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['label'];?>
)<?php }?>
                    				<?php }?>
                    			<?php } else { ?>
                    				<?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_s']->value['first'] : null)) {?>
                    												
                    				<?php } else { ?>
                    					<?php if ($_smarty_tpl->tpl_vars['cnt']->value == 0) {?>
                    						<li>
                    					              <strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b');?>
</strong>
                
                    					<?php }?>
                    				<?php }?>
                    				
                    				<?php $_smarty_tpl->_assignInScope('cnt', $_smarty_tpl->tpl_vars['cnt']->value+1);?>
                    				
                    				<?php if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_st']->value['first'] : null)) {?>, <?php }?><a href="/bookings/<?php echo $_smarty_tpl->tpl_vars['st']->value['id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
</a>
                    		                  <?php if ($_smarty_tpl->tpl_vars['st']->value['comment']) {?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['comment'];?>
)<?php }?>
                    		                  <?php if ($_smarty_tpl->tpl_vars['st']->value['label']) {?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['label'];?>
)<?php }?>
                                    			<?php }?>
                                    <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                              <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                               </li>
                            </ul>
                          	<p><i>To place a booking, click on the session time you are interested. Bookings must be made an hour before the film starts. Please wait for confirmation from us via phone or email.</i></p>
                        <?php }?>
    		    </div>
    		    <div class="content-wrapper details">
    		        <?php if ($_smarty_tpl->tpl_vars['movie']->value['classification']) {?>
        			<p>
        				<strong>Rated:</strong> <?php echo $_smarty_tpl->tpl_vars['movie']->value['classification'];?>
<br />
        				<?php if ($_smarty_tpl->tpl_vars['movie']->value['class_explanation']) {?> <em><?php echo $_smarty_tpl->tpl_vars['movie']->value['class_explanation'];?>
</em><?php }?>
        			</p>
        		    <?php }?>
        		    <p>
        			<?php if ($_smarty_tpl->tpl_vars['movie']->value['duration']) {?>
              	        <strong>Duration:</strong><br />
        				<?php echo $_smarty_tpl->tpl_vars['movie']->value['duration'];?>
<br />
        			<?php }?>
        			<?php if ($_smarty_tpl->tpl_vars['movie']->value['official_site']) {?>
        				<a class="btn dark" href="<?php echo $_smarty_tpl->tpl_vars['movie']->value['official_site'];?>
" target="_blank">Official Website</a><br />
        			<?php }?>
        			<?php if ($_smarty_tpl->tpl_vars['movie']->value['trailer']) {?>
        				<a class="btn dark" href="<?php echo $_smarty_tpl->tpl_vars['movie']->value['trailer'];?>
" target="_blank">Official Trailer</a>
        			<?php }?>
        		    </p>
            		<?php if ($_smarty_tpl->tpl_vars['cast']->value) {?>
            			<p>
            				<strong>Starring: </strong>
            				<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['cast']->value, 'c', false, NULL, 'c', array (
  'first' => true,
  'index' => true,
));
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['c']->value) {
$_smarty_tpl->tpl_vars['__smarty_foreach_c']->value['index']++;
$_smarty_tpl->tpl_vars['__smarty_foreach_c']->value['first'] = !$_smarty_tpl->tpl_vars['__smarty_foreach_c']->value['index'];
?>
            					<?php if (!(isset($_smarty_tpl->tpl_vars['__smarty_foreach_c']->value['first']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_c']->value['first'] : null)) {?>, <?php }
echo $_smarty_tpl->tpl_vars['c']->value;?>

            				<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            			</p>
            		<?php }?>  
    		    </div>
    		</div>
    	</div>
      </div> 
    </div>

<?php $_smarty_tpl->_subTemplateRender("file:inc/tpl/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php }
}
