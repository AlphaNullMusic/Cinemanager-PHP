{if $plaintext}
{assign var=divider value='========================================'}
{$plain_editorial}

For more information on any of our movies please visit www.shorelinecinema.co.nz.

{section name=ns loop=$now_showing}
{$divider}

{$now_showing[ns].title}

{$now_showing[ns].class}{if $now_showing[ns].class_explanation} ({$now_showing[ns].class_explanation}){/if} - {$now_showing[ns].duration}

{* summary text='$now_showing[ns].synopsis' chars=400 *}

Visit this web page for more information:
http://www.shorelinecinema.co.nz/movies/{$now_showing[ns].movie_id}.php
{if $now_showing[ns].comments}

{$now_showing[ns].comments}
{/if}
{foreach from=$now_showing[ns].sessions item=s key=date name=s}

  {$date|date_format:'%A %e %b'}: {foreach from=$s item=st name=st}{if !$smarty.foreach.st.first}, {/if}{$st.time}{if $st.comment} ({$st.comment}){/if}{if $st.label} ({$st.label}){/if}{/foreach}
{/foreach}


{/section}
{else}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
{literal}
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
{/literal}
{include file="inc/css/styles.css"}
</style>
</HEAD>
<BODY bgcolor="#8B886C" text="#DFDCCD" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#9C997E">
  <tr>
    <td width="500"><a href="http://www.shorelinecinema.co.nz/" target="_blank"><img src="inc/img/sl_logo.png" alt="Shoreline Cinema" width="500" height="94" border="0"></a></td>
  </tr>
  <tr>
  	<td>
      {if $editorial}
      <table width="500" border="0" align="center" cellpadding="0" cellspacing="20">
        <tr>
          <td>{$editorial}</td>
        </tr>
      </table>
      {else}
        &nbsp;
      {/if}
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
						{section name=ns loop=$now_showing}
						{if !$smarty.section.ns.first}
							<hr size="1" noshade color="#B4B19D">
						{/if}
						<table border="0" cellspacing="0" cellpadding="4">
            <tr>
              <td valign="top"><a href="{$domain}movies/{$now_showing[ns].movie_id}.php" target="_blank"><img src="{$movie_image_url}{$now_showing[ns].image_name}_tiny.jpg" width="80" border="0"></a></td>
              <td><a href="{$domain}movies/{$now_showing[ns].movie_id}.php" class="movie" target="_blank"><b>{$now_showing[ns].title}</b></a><br>
								<b>{$now_showing[ns].class}{if $now_showing[ns].class_explanation} ({$now_showing[ns].class_explanation}){/if}</b> - {$now_showing[ns].duration}<br>
								{if $now_showing[ns].comments}
								<font color="#AD3914">{$now_showing[ns].comments}</font><br>
								{/if}
								{* summary text=`$now_showing[ns].synopsis` chars=195 *}<br><br>
                <table border="0" cellspacing="0" cellpadding="0">
                  {foreach from=$now_showing[ns].sessions item=s key=date name=s}
									<tr>
                    <td valign="top" nowrap><b>{$date|date_format:'%A %e %b'}</b></td>
										<td>&nbsp;</td>
                    <td valign="top">
											{strip}
											{foreach from=$s item=st name=st}
												{if !$smarty.foreach.st.first}, {/if}{$st.time}
												{if $st.comment} ({$st.comment}){/if}
												{if $st.label} ({$st.label}){/if}
											{/foreach}
											{/strip}
										</td>
                  </tr>
									{/foreach}
                </table>
							</td>
            </tr>
          </table>
					{/section}
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
{/if}