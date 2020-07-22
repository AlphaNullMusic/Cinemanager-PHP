<?php
/* Smarty version 3.1.33, created on 2020-07-21 11:17:18
  from '/var/www/Cinemanager/web/tpl/bookings.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f1625fe9c3b35_69744165',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ce221c853473b19e1b87a9dc89e74186ccb01129' => 
    array (
      0 => '/var/www/Cinemanager/web/tpl/bookings.tpl',
      1 => 1595287036,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:inc/tpl/footer.tpl' => 1,
  ),
),false)) {
function content_5f1625fe9c3b35_69744165 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/Cinemanager/_deps/smarty/plugins/modifier.date_format.php','function'=>'smarty_modifier_date_format',),1=>array('file'=>'/var/www/Cinemanager/_deps/smarty/plugins/function.html_options.php','function'=>'smarty_function_html_options',),));
?>
<!DOCTYPE html>
<html lang="en-nz">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Book for <?php echo $_smarty_tpl->tpl_vars['movie']->value['title'];?>
</title>
<meta name="description" content="Shoreline Cinema is Waikanae's premiere cinema complex. Find out who we are, what's on, what's coming up, how to contact us and much more.">
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
			



<?php echo '<script'; ?>
 language="JavaScript" type="text/JavaScript">
<!--
function YY_checkform() { //v4.71
//copyright (c)1998,2002 Yaromat.com
  var a=YY_checkform.arguments,oo=true,v='',s='',err=false,r,o,at,o1,t,i,j,ma,rx,cd,cm,cy,dte,at;
  for (i=1; i<a.length;i=i+4){
    if (a[i+1].charAt(0)=='#'){r=true; a[i+1]=a[i+1].substring(1);}else{r=false}
    o=MM_findObj(a[i].replace(/\[\d+\]/ig,""));
    o1=MM_findObj(a[i+1].replace(/\[\d+\]/ig,""));
    v=o.value;t=a[i+2];
    if (o.type=='text'||o.type=='password'||o.type=='hidden'){
      if (r&&v.length==0){err=true}
      if (v.length>0)
      if (t==1){ //fromto
        ma=a[i+1].split('_');if(isNaN(v)||v<ma[0]/1||v > ma[1]/1){err=true}
      } else if (t==2){
        rx=new RegExp("^[\\w\.=-]+@[\\w\\.-]+\\.[a-zA-Z]{2,4}$");if(!rx.test(v))err=true;
      } else if (t==3){ // date
        ma=a[i+1].split("#");at=v.match(ma[0]);
        if(at){
          cd=(at[ma[1]])?at[ma[1]]:1;cm=at[ma[2]]-1;cy=at[ma[3]];
          dte=new Date(cy,cm,cd);
          if(dte.getFullYear()!=cy||dte.getDate()!=cd||dte.getMonth()!=cm){err=true};
        }else{err=true}
      } else if (t==4){ // time
        ma=a[i+1].split("#");at=v.match(ma[0]);if(!at){err=true}
      } else if (t==5){ // check this 2
            if(o1.length)o1=o1[a[i+1].replace(/(.*\[)|(\].*)/ig,"")];
            if(!o1.checked){err=true}
      } else if (t==6){ // the same
            if(v!=MM_findObj(a[i+1]).value){err=true}
      }
    } else
    if (!o.type&&o.length>0&&o[0].type=='radio'){
          at = a[i].match(/(.*)\[(\d+)\].*/i);
          o2=(o.length>1)?o[at[2]]:o;
      if (t==1&&o2&&o2.checked&&o1&&o1.value.length/1==0){err=true}
      if (t==2){
        oo=false;
        for(j=0;j<o.length;j++){oo=oo||o[j].checked}
        if(!oo){s+='* '+a[i+3]+'\n'}
      }
    } else if (o.type=='checkbox'){
      if((t==1&&o.checked==false)||(t==2&&o.checked&&o1&&o1.value.length/1==0)){err=true}
    } else if (o.type=='select-one'||o.type=='select-multiple'){
      if(t==1&&o.selectedIndex/1==0){err=true}
    }else if (o.type=='textarea'){
      if(v.length<a[i+1]){err=true}
    }
    if (err){s+='* '+a[i+3]+'\n'; err=false}
  }
  if (s!=''){alert('The required information is incomplete or contains errors:\n\n'+s)}
  document.MM_returnValue = (s=='');
}
//-->
<?php echo '</script'; ?>
>



<div class="information">
    <h2><?php echo $_smarty_tpl->tpl_vars['movie']->value['title'];?>
 - Ticket Request</h2>
    <div class="content">
    	<ul class="content-wrapper poster">
    		<img src="<?php echo $_smarty_tpl->tpl_vars['movie']->value['poster_url'];?>
" width="190" alt="<?php echo $_smarty_tpl->tpl_vars['movie']->value['title'];?>
 Poster" />
    	</ul>
    	<div class="content-wrapper text">
    	    <div class="content">
    	        <div class="content-wrapper text">
    	            <p><i>Bookings must be made an hour before the film starts. Please wait for confirmation from us via phone or email.</i></p>
    	            			<form action="/bookings/<?php echo $_smarty_tpl->tpl_vars['booking_id']->value;?>
/" method="post" name="bookings" onSubmit="YY_checkform('bookings','c_name','#q','0','Please enter your name.','c_email','#S','2','Please enter your email address.','c_phone','#q','0','Please enter your phone number.');return document.MM_returnValue">
                        <?php if (!$_smarty_tpl->tpl_vars['booking']->value) {?>
							<span class="h3">Screening Details</span><br />
							<p>Movie: <strong><?php echo $_smarty_tpl->tpl_vars['movie']->value['title'];?>
</strong></p>
							<p>Date: <strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['session']->value['session_timestamp'],'%A %e %b');?>
</strong></p>
							<p>Time: <strong><?php echo $_smarty_tpl->tpl_vars['session']->value['session_time'];?>
</strong></p><br />
						<?php }?>
						<?php if ($_smarty_tpl->tpl_vars['er']->value == 'incomplete') {?>
                            <?php if ($_smarty_tpl->tpl_vars['er_msg']->value) {?>
                                <?php echo '<script'; ?>
>alert('Request incomplete:\r\n<?php echo $_smarty_tpl->tpl_vars['er_msg']->value;?>
');<?php echo '</script'; ?>
>
                                <h4 class="booking-failed"><i>Request incomplete:<br /><?php echo $_smarty_tpl->tpl_vars['er_msg']->value;?>
</i></h4><br />
                            <?php } else { ?>
							    <?php echo '<script'; ?>
>alert('Request incomplete.\r\nPlease check your details and try again.');<?php echo '</script'; ?>
>
                                <h4 class="booking-failed"><i>Request incomplete:<br />Please try again.</i></h4><br />
                            <?php }?>
						<?php }?>
						<?php if ($_smarty_tpl->tpl_vars['session']->value['label_name']) {?>
						<h4 class="booking-failed"><i>Note, this session is labelled as: </i><br><strong><?php echo $_smarty_tpl->tpl_vars['session']->value['label_name'];?>
<strong></h4><br />
						<?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['booking']->value == 'complete') {?>
							<?php echo '<script'; ?>
>alert('Request completed.');<?php echo '</script'; ?>
>
                            <span class="booking-succeeded"><br>Tickets Requested!<br />
                            <i>We will call you to confirm your booking.</i></span><hr />
							<span><strong>Your Session Details</strong></span><br />
							<p>Movie: <strong><?php echo $_smarty_tpl->tpl_vars['movie']->value['title'];?>
</strong></p>
							<p>Date: <strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['session']->value['session_timestamp'],'%A %e %b');?>
</strong></p>
							<p>Time: <strong><?php echo $_smarty_tpl->tpl_vars['session']->value['session_time'];?>
</strong></p><hr />
                            <i>To give feedback on the new website, please <a href="/contact-us/"><strong>click here</strong></a>.</i>
                            <?php if ($_smarty_tpl->tpl_vars['t_adults']->value) {?>
                              <p>Adults: <strong><?php echo $_smarty_tpl->tpl_vars['t_adults']->value;?>
</strong></p>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['t_children']->value) {?>
                              <p>Children: <strong><?php echo $_smarty_tpl->tpl_vars['t_children']->value;?>
</strong></p>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['t_seniors']->value) {?>
                              <p>Seniors: <strong><?php echo $_smarty_tpl->tpl_vars['t_seniors']->value;?>
</strong></p>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['t_students']->value) {?>
                              <p>Students: <strong><?php echo $_smarty_tpl->tpl_vars['t_students']->value;?>
</strong></p>
                            <?php }?>
                        <?php } elseif ($_smarty_tpl->tpl_vars['booking']->value == 'failed') {?>
							<?php echo '<script'; ?>
>alert('Request failed.\r\nPlease try again soon.');<?php echo '</script'; ?>
>
                            <span class="booking-failed"><br>Booking Failed<br />
                            <i>Please try booking again later, or <a href="/contact-us/"><strong><em>contact us</em></strong></a>.</i></span>
						<?php } else { ?>
                            <tr><td colspan="3"><span class="h3">Tickets Required</span><br /></table></td></tr>
                            <table>
                            <tr>
                              <td align="right">Adults</td>
                              <td>&nbsp;</td>
                              <td><select name="t_adults" id="t_adults"><?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['ticket_nums']->value,'output'=>$_smarty_tpl->tpl_vars['ticket_nums']->value,'selected'=>$_smarty_tpl->tpl_vars['t_adults']->value),$_smarty_tpl);?>
</select></td>
                            </tr>
                            <tr>
                              <td align="right">Children</td>
                              <td>&nbsp;</td>
                              <td><select name="t_children" id="t_children"><?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['ticket_nums']->value,'output'=>$_smarty_tpl->tpl_vars['ticket_nums']->value,'selected'=>$_smarty_tpl->tpl_vars['t_children']->value),$_smarty_tpl);?>
</select></td>
                            </tr>
                            <tr>
                              <td align="right">Seniors</td>
                              <td>&nbsp;</td>
                              <td><select name="t_seniors" id="t_seniors"><?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['ticket_nums']->value,'output'=>$_smarty_tpl->tpl_vars['ticket_nums']->value,'selected'=>$_smarty_tpl->tpl_vars['t_seniors']->value),$_smarty_tpl);?>
</select></td>
                            </tr>
                            <tr>
                              <td align="right">Students</td>
                              <td>&nbsp;</td>
                              <td><select name="t_students" id="t_students"><?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['ticket_nums']->value,'output'=>$_smarty_tpl->tpl_vars['ticket_nums']->value,'selected'=>$_smarty_tpl->tpl_vars['t_students']->value),$_smarty_tpl);?>
</select></td>
                            </tr>
                            <tr>
                              <td colspan="3"><span class="h3"><br>
                                Personal Details</span></td>
                            </tr>
                            <tr>
                              <td align="right">Your Name </td>
                              <td>&nbsp;</td>
                              <td><input name="c_name" type="text" id="c_name" value="<?php echo $_smarty_tpl->tpl_vars['c_name']->value;?>
" size="15" maxlength="50"></td>
                            </tr>
                            <tr>
                              <td align="right">Email</td>
                              <td>&nbsp;</td>
                              <td><input name="c_email" type="text" id="c_email" value="<?php echo $_smarty_tpl->tpl_vars['c_email']->value;?>
" size="15" maxlength="50"></td>
                            </tr>
                            <tr>
                              <td align="right">Phone</td>
                              <td>&nbsp;</td>
                              <td><input name="c_phone" type="text" id="c_phone" value="<?php echo $_smarty_tpl->tpl_vars['c_phone']->value;?>
" size="15" maxlength="50"></td>
                            </tr>
							<tr>
                              <td align="right">Request Wheelchair Access?</td>
                              <td>&nbsp;</td>
                              <td><input name="c_wheelchair" type="checkbox" id="c_wheelchair" <?php echo $_smarty_tpl->tpl_vars['c_wheelchair']->value;?>
></td>
                            </tr>
                            <tr>
                              <td align="right">Sign up for Weekly Session Timetable</td>
                              <td>&nbsp;</td>
                              <td><input name="c_newsletter_signup" type="checkbox" id="c_newsletter_signup" <?php echo $_smarty_tpl->tpl_vars['c_newsletter_signup']->value;?>
></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td><br>
                                <input type="hidden" name="m" value="<?php echo $_smarty_tpl->tpl_vars['m']->value;?>
">
                                <input type="hidden" name="d" value="<?php echo $_smarty_tpl->tpl_vars['d']->value;?>
">
                                <input type="hidden" name="s" value="<?php echo $_smarty_tpl->tpl_vars['s']->value;?>
">
                                <input type="hidden" name="action" value="place_booking">
				                                <button class="btn green" type="submit">Request Ticket</button></td>
                            </table>
                        <?php }?>
                		</form>
										    	        </div>
    	    </div> 	
      </div> 
      <div class="content-wrapper details">
    		        <?php if ($_smarty_tpl->tpl_vars['movie']->value['class']) {?>
        			<p>
        				<strong>Rated:</strong> <?php echo $_smarty_tpl->tpl_vars['movie']->value['class'];?>
<br />
        				<?php if ($_smarty_tpl->tpl_vars['movie']->value['class_explanation']) {?> <?php echo $_smarty_tpl->tpl_vars['movie']->value['class_explanation'];
}?>
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
    		</p>
    	</div>
	</div>
</div>
<?php $_smarty_tpl->_subTemplateRender("file:inc/tpl/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php }
}
