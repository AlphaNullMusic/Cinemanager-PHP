<script type="text/javascript" src="inc/tiny_mce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		selector: 'textarea',
		plugins: 'advlist autolink link image lists charmap print preview table',
		toolbar: 'undo redo | styleselect | bold italic | link image | table tabledelete',
		content_css: "<?php echo $config['manage_url'].'inc/css/editor.css';?>",
		paste_preprocess : function(pl, o) {
			o.content = o.content.replace(/<(h1|h2|h3|h4|h5|h6|div)>/gi, '<p>'); // Convert some items into <p> tags
			o.content = o.content.replace(/<\/(h1|h2|h3|h4|h5|h6|div)>/gi, '</p>');
			o.content = o.content.replace(/<(?!p|br|strong|em)>/gi, ''); // Kill all but a small selection of other tags
    		},
		paste_word_valid_elements: "b,strong,i,em,span,p",
		style_formats : [ 
			{ title: 'Headings', items : [
				{ title: 'Heading 1', format: 'h1' },
				{ title: 'Heading 2', format: 'h2' },
				{ title: 'Heading 3', format: 'h3' }
			]},
			{ title: 'Inline', items: [
				{ title: 'Bold', format: 'bold' },
				{ title: 'Italic', format: 'italic' },
				{ title: 'Underline', format: 'underline' },
				{ title: 'Strikethrough', format: 'strikethrough' },
				{ title: 'Code', format: 'code' }
  			]},
			{ title: 'Blocks', items: [
				{ title: 'Paragraph', format: 'p' },
				{ title: 'Blockquote', format: 'blockquote' },
				{ title: 'Pre', format: 'pre' }
  			]},
  			{ title: 'Align', items: [
				{ title: 'Left', format: 'alignleft' },
				{ title: 'Center', format: 'aligncenter' },
				{ title: 'Right', format: 'alignright' },
				{ title: 'Justify', format: 'alignjustify' }
  			]}
		],
		images_upload_url: 'tiny_mce_upload.php',
		automatic_uploads: false,
		images_upload_handler : function(blobInfo, success, failure) {
			var xhr, formData;

			xhr = new XMLHttpRequest();
			xhr.withCredentials = false;
			xhr.open('POST', 'tiny_mce_upload.php');

			xhr.onload = function() {
				var json;

				if (xhr.status != 200) {
					failure('HTTP Error: ' + xhr.status);
					return;
				}

				json = JSON.parse(xhr.responseText);

				if (!json || typeof json.file_path != 'string') {
					failure('Invalid JSON: ' + xhr.responseText);
					return;
				}

				success(json.file_path);
			};

			formData = new FormData();
			formData.append('file', blobInfo.blob(), blobInfo.filename());

			xhr.send(formData);
		},
	});
</script>
<textarea name="<?php echo $editor_name?>" style="width:100%;height:300px;"><?php echo $editor_value?></textarea>
<div id="editor_tips">
	<h2>Editor Tips</h2>
	<p>If you are copying and pasting from Microsoft Word, please use the Paste From Word or Paste as Plain Text buttons, otherwise your formatting might get muddled.</p>
	<ul>
		<li><img src="inc/icons/icon-tinymce-pasteword.gif" width="16" height="16" align="absmiddle" alt="" /> <strong>Paste From Word</strong> - always use this button when copying and pasting from Word.</li>
		<li><img src="inc/icons/icon-tinymce-cleanup.gif" width="16" height="16" align="absmiddle" alt="" /> <strong>Remove Formatting</strong> - if text still pastes in weird press this to clean up the formatting.</li>
		<li><img src="inc/icons/icon-tinymce-link.gif" width="16" height="16" align="absmiddle" alt="" /> <strong>Insert Link</strong> - select the text you want to link from, then press this button to make it a link.</li>
		<li><img src="inc/icons/icon-tinymce-image.gif" width="16" height="16" align="absmiddle" alt="" /> <strong>Insert Image</strong> - you can insert images from your computer or other websites.</li>
	</ul>
</div>
