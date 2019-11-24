<?php /* Smarty version Smarty-3.1.13, created on 2019-11-24 18:20:56
         compiled from "I:\Cinemanager\www\Cinemanager\web\tpl\email_newsletter.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3725dda12aba0f754-02390124%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '41fa86c58429a1c0fa3639dab9ac22d15ebb846b' => 
    array (
      0 => 'I:\\Cinemanager\\www\\Cinemanager\\web\\tpl\\email_newsletter.tpl',
      1 => 1574572855,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3725dda12aba0f754-02390124',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_5dda12abb5c2a7_27982339',
  'variables' => 
  array (
    'plaintext' => 0,
    'plain_editorial' => 0,
    'now_showing' => 0,
    'divider' => 0,
    'date' => 0,
    's' => 0,
    'st' => 0,
    'editorial' => 0,
    'domain' => 0,
    'movie_image_url' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5dda12abb5c2a7_27982339')) {function content_5dda12abb5c2a7_27982339($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'I:\\Cinemanager\\www\\Cinemanager/_deps/smarty/plugins\\modifier.date_format.php';
?><?php if ($_smarty_tpl->tpl_vars['plaintext']->value){?>
<?php $_smarty_tpl->tpl_vars['divider'] = new Smarty_variable('========================================', null, 0);?>
<?php echo $_smarty_tpl->tpl_vars['plain_editorial']->value;?>


For more information on any of our movies please visit www.shorelinecinema.co.nz.

<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['ns'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['ns']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['name'] = 'ns';
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['now_showing']->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['total']);
?>
<?php echo $_smarty_tpl->tpl_vars['divider']->value;?>


<?php echo $_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['title'];?>


<?php echo $_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['class'];?>
<?php if ($_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['class_explanation']){?> (<?php echo $_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['class_explanation'];?>
)<?php }?> - <?php echo $_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['duration'];?>




Visit this web page for more information:
http://www.shorelinecinema.co.nz/movies/<?php echo $_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['movie_id'];?>
.php
<?php if ($_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['comments']){?>

<?php echo $_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['comments'];?>

<?php }?>
<?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
 $_smarty_tpl->tpl_vars['date'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['sessions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['s']->key => $_smarty_tpl->tpl_vars['s']->value){
$_smarty_tpl->tpl_vars['s']->_loop = true;
 $_smarty_tpl->tpl_vars['date']->value = $_smarty_tpl->tpl_vars['s']->key;
?>

  <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b');?>
: <?php  $_smarty_tpl->tpl_vars['st'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['st']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['s']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['st']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['st']->key => $_smarty_tpl->tpl_vars['st']->value){
$_smarty_tpl->tpl_vars['st']->_loop = true;
 $_smarty_tpl->tpl_vars['st']->index++;
 $_smarty_tpl->tpl_vars['st']->first = $_smarty_tpl->tpl_vars['st']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['st']['first'] = $_smarty_tpl->tpl_vars['st']->first;
?><?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['st']['first']){?>, <?php }?><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
<?php if ($_smarty_tpl->tpl_vars['st']->value['comment']){?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['comment'];?>
)<?php }?><?php if ($_smarty_tpl->tpl_vars['st']->value['label']){?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['label'];?>
)<?php }?><?php } ?>
<?php } ?>


<?php endfor; endif; ?>
<?php }else{ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">

<!--
td, body {
	font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	line-height: 14px;
	color: #DFDCCD;
}
h1, .h1 {
	font-size: 22px;
	line-height: 24px;
	font-weight: normal;
	color: #967842;
}
h2, .h2 {
	font-size: 11px;
	line-height: 16px;
	font-weight: normal;
}
a:link, a:active, a:visited {
	color: #AD3914;
	text-decoration: none;
}
a:hover {
	text-decoration: underline;
}
.movie {
	font-size: 13px;
	font-weight: bold;
}
.small {
	font-size: 10px;
}
a.footer:link, a.footer:active, a.footer:visited, .footer {
	color: #AFAD99;
}
a.footer:hover {
	color: #ffffff;
}
a.nav:link, a.nav:active, a.nav:visited {
	color: #FFFFFF;
	text-decoration: none;
}
a.nav:hover {
	color: #FB9D00;
	text-decoration: none;
}
-->

<?php echo $_smarty_tpl->getSubTemplate ("inc/css/styles.css", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</style>
</HEAD>
<BODY bgcolor="#8B886C" text="#DFDCCD" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#9C997E">
  <tr>
    <td width="500"><a href="http://www.shorelinecinema.co.nz/" target="_blank"><img src="inc/img/sl_logo.png" alt="Shoreline Cinema" width="500" height="94" border="0"></a></td>
  </tr>
  <tr>
  	<td>
      <?php if ($_smarty_tpl->tpl_vars['editorial']->value){?>
      <table width="500" border="0" align="center" cellpadding="0" cellspacing="20">
        <tr>
          <td><?php echo $_smarty_tpl->tpl_vars['editorial']->value;?>
</td>
        </tr>
      </table>
      <?php }else{ ?>
        &nbsp;
      <?php }?>
   	</td>
  </tr>
  <tr>
    <td bgcolor="#9A9262">
			<table width="100%" border="0" cellpadding="0" cellspacing="20" bgcolor="#9A9262">
        <tr>
        	<td><img src="images/heading-session-times.gif" alt="Session Times" width="152" height="18" border="0"></td>
        </tr>
        <tr>
          <td>
						<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['ns'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['ns']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['name'] = 'ns';
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['now_showing']->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['loop'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['step'] = 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['start'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['step'] > 0 ? 0 : $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['loop']-1;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['total'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['loop'];
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['ns']['total']);
?>
						<?php if (!$_smarty_tpl->getVariable('smarty')->value['section']['ns']['first']){?>
							<hr size="1" noshade color="#B4B19D">
						<?php }?>
						<table border="0" cellspacing="0" cellpadding="4">
            <tr>
              <td valign="top"><a href="<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
movies/<?php echo $_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['movie_id'];?>
.php" target="_blank"><img src="<?php echo $_smarty_tpl->tpl_vars['movie_image_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['image_name'];?>
_tiny.jpg" width="80" border="0"></a></td>
              <td><a href="<?php echo $_smarty_tpl->tpl_vars['domain']->value;?>
movies/<?php echo $_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['movie_id'];?>
.php" class="movie" target="_blank"><b><?php echo $_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['title'];?>
</b></a><br>
								<b><?php echo $_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['class'];?>
<?php if ($_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['class_explanation']){?> (<?php echo $_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['class_explanation'];?>
)<?php }?></b> - <?php echo $_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['duration'];?>
<br>
								<?php if ($_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['comments']){?>
								<font color="#AD3914"><?php echo $_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['comments'];?>
</font><br>
								<?php }?>
								<br><br>
                <table border="0" cellspacing="0" cellpadding="0">
                  <?php  $_smarty_tpl->tpl_vars['s'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['s']->_loop = false;
 $_smarty_tpl->tpl_vars['date'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['now_showing']->value[$_smarty_tpl->getVariable('smarty')->value['section']['ns']['index']]['sessions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['s']->key => $_smarty_tpl->tpl_vars['s']->value){
$_smarty_tpl->tpl_vars['s']->_loop = true;
 $_smarty_tpl->tpl_vars['date']->value = $_smarty_tpl->tpl_vars['s']->key;
?>
									<tr>
                    <td valign="top" nowrap><b><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['date']->value,'%A %e %b');?>
</b></td>
										<td>&nbsp;</td>
                    <td valign="top">
											<?php  $_smarty_tpl->tpl_vars['st'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['st']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['s']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['st']->index=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['st']->key => $_smarty_tpl->tpl_vars['st']->value){
$_smarty_tpl->tpl_vars['st']->_loop = true;
 $_smarty_tpl->tpl_vars['st']->index++;
 $_smarty_tpl->tpl_vars['st']->first = $_smarty_tpl->tpl_vars['st']->index === 0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['st']['first'] = $_smarty_tpl->tpl_vars['st']->first;
?><?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['st']['first']){?>, <?php }?><?php echo $_smarty_tpl->tpl_vars['st']->value['time'];?>
<?php if ($_smarty_tpl->tpl_vars['st']->value['comment']){?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['comment'];?>
)<?php }?><?php if ($_smarty_tpl->tpl_vars['st']->value['label']){?> (<?php echo $_smarty_tpl->tpl_vars['st']->value['label'];?>
)<?php }?><?php } ?>
										</td>
                  </tr>
									<?php } ?>
                </table>
							</td>
            </tr>
          </table>
					<?php endfor; endif; ?>
					</td>
        </tr>
      </table>
  	</td>
  </tr>
  <tr>
  	<td><a href="http://www.shorelinecinema.co.nz/" target="_blank"><img height="61" border="0" width="480" alt="Shoreline Cinema Waikanae" src="images/email_footer.gif"/></a></td>
  </tr> 
  <tr bgcolor="#8B886C">
  	<td align="center" class="footer"><p>To unsubscribe from this email newsletter, <a href="<!--unsub-->" class="footer">click here</a>.<br>
      While every attempt is made to ensure this website is accurate,<br>
			we are not liable for any omissions or errors.<br>
			<a href="http://www.moviemanager.biz/" title="New Zealand Website Design &amp; Development" target="_blank" class="footer">Design</a> &amp; Content &copy; <a href="http://www.shorelinecinema.co.nz/" target="_blank" class="footer">Shoreline Cinema</a>, Powered by <a href="http://www.nzcinema.co.nz/" title="New Zealand Movie Database" target="_blank" class="footer">NZ Cinema</a>.</p>
    </td>
  </tr> 
</table>
</BODY>
</HTML>
<?php }?><?php }} ?>