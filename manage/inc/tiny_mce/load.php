<script type="text/javascript" src="includes/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="includes/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php"></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		plugins : "safari,spellchecker,style,searchreplace,contextmenu,paste,spellchecker,table,youtubeIframe",
		theme_advanced_buttons1 : "styleselect,bold,italic,strikethrough,|,justifyleft,justifycenter,justifyright,|,bullist,numlist,|,link,unlink,image,insertfile,youtubeIframe,|,pasteword,pastetext,removeformat,code",
		theme_advanced_buttons2 : "tablecontrols,|,hr,spellchecker,undo,redo",
		theme_advanced_buttons3 : false,
		theme_advanced_styles : "Heading=h1;Subheading=h2;Emphasis=h3;Normal Text=normal",
		extended_valid_elements:"iframe[src|title|width|height|allowfullscreen|frameborder|class|id],object[classid|width|height|codebase|*],param[name|value|_value|*],embed[type|width|height|src|*]",
		spellchecker_languages : "+English=en",
		paste_auto_cleanup_on_paste : true,
		paste_strip_class_attributes : 'all',
		paste_remove_spans : true,
		paste_remove_styles : true,
		paste_preprocess : function(pl, o) {
    	o.content = o.content.replace(/<(h1|h2|h3|h4|h5|h6|div)>/gi, '<p>'); // Convert some items into <p> tags
    	o.content = o.content.replace(/<\/(h1|h2|h3|h4|h5|h6|div)>/gi, '</p>');
    	o.content = o.content.replace(/<(?!p|br|strong|em)>/gi, ''); // Kill all but a small selection of other tags
    },
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		external_link_list_url : "js/link_list.js",
		external_image_list_url : "js/image_list.js",
		file_browser_callback : "tinyBrowser",
		content_css : "<?=$global['cinema_url'].$_SESSION['cinema_data']['cinema_id']?>/<?="inc/css/editor.css"; ?>",
	});
</script>
<textarea name="<?=$tiny_mce_name?>" style="width:100%;height:300px;"><?=$tiny_mce_value?></textarea>
<p id="editor_tips_link"><a href="javascript:;" onClick="document.getElementById('editor_tips').style.display='block';document.getElementById('editor_tips_link').style.display='none';">Editor Tips</a></p>
<div id="editor_tips">
	<h2>Editor Tips</h2>
	<p>If you are copying and pasting from Microsoft Word, please use the Paste From Word or Paste as Plain Text buttons, otherwise your formatting might get muddled.</p>
	<ul>
		<li><img src="images/icon-tinymce-pasteword.gif" width="16" height="16" align="absmiddle" alt="" /> <strong>Paste From Word</strong> - always use this button when copying and pasting from Word.</li>
		<li><img src="images/icon-tinymce-cleanup.gif" width="16" height="16" align="absmiddle" alt="" /> <strong>Remove Formatting</strong> - if text still pastes in weird press this to clean up the formatting.</li>
		<li><img src="images/icon-tinymce-link.gif" width="16" height="16" align="absmiddle" alt="" /> <strong>Insert Link</strong> - select the text you want to link from, then press this button to make it a link.</li>
		<li><img src="images/icon-tinymce-image.gif" width="16" height="16" align="absmiddle" alt="" /> <strong>Insert Image</strong> - you can insert images from your computer or other websites.</li>
	</ul>
</div>