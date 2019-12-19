<script type="text/javascript" src="inc/tiny_mce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		selector: 'textarea',
		plugins: 'print preview fullpage paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
		toolbar: 'undo redo | styleselect removeformat | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | image media link | outdent indent |  numlist bullist | forecolor backcolor | pagebreak | charmap emoticons | fullscreen  preview save print | ltr rtl',
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

				if (!json || typeof json.location != 'string') {
					failure('Invalid JSON: ' + xhr.responseText);
					return;
				}
				success(json.location);
			};

			formData = new FormData();
			formData.append('file', blobInfo.blob(), blobInfo.filename());

			xhr.send(formData);
		},
		urlconverter_callback: function(url,node,on_save,name) {
			url = encodeURI(url);
			return url;
		},
	});
	
	function uploadImagesTinyMCE() {
		tinymce.activeEditor.uploadImages(function(success) {
			// Upload ok, submit form
			document.forms[0].submit();
		});
	}
</script>
<textarea name="<?php echo $editor_name?>" style="width:100%;height:300px;"><?php echo $editor_value?></textarea>
<div id="editor_tips">
	<h2>Editor Tips</h2>
	<ul>
		<li><span class="tox-icon tox-tbtn__icon-wrap"><svg width="24" height="24"><path d="M13.2 6a1 1 0 0 1 0 .2l-2.6 10a1 1 0 0 1-1 .8h-.2a.8.8 0 0 1-.8-1l2.6-10H8a1 1 0 1 1 0-2h9a1 1 0 0 1 0 2h-3.8zM5 18h7a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2zm13 1.5L16.5 18 15 19.5a.7.7 0 0 1-1-1l1.5-1.5-1.5-1.5a.7.7 0 0 1 1-1l1.5 1.5 1.5-1.5a.7.7 0 0 1 1 1L17.5 17l1.5 1.5a.7.7 0 0 1-1 1z" fill-rule="evenodd"></path></svg></span> <strong>Clear Formatting</strong> - select some text and press this button to clean up the formatting.</li>
		<li><span class="tox-icon tox-tbtn__icon-wrap"><svg width="24" height="24"><path d="M5 15.7l3.3-3.2c.3-.3.7-.3 1 0L12 15l4.1-4c.3-.4.8-.4 1 0l2 1.9V5H5v10.7zM5 18V19h3l2.8-2.9-2-2L5 17.9zm14-3l-2.5-2.4-6.4 6.5H19v-4zM4 3h16c.6 0 1 .4 1 1v16c0 .6-.4 1-1 1H4a1 1 0 0 1-1-1V4c0-.6.4-1 1-1zm6 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" fill-rule="nonzero"></path></svg></span> <strong>Insert Image</strong> - you can insert images from your computer or other websites.</li>
		<li><span class="tox-icon tox-tbtn__icon-wrap"><svg width="24" height="24"><path d="M4 3h16c.6 0 1 .4 1 1v16c0 .6-.4 1-1 1H4a1 1 0 0 1-1-1V4c0-.6.4-1 1-1zm1 2v14h14V5H5zm4.8 2.6l5.6 4a.5.5 0 0 1 0 .8l-5.6 4A.5.5 0 0 1 9 16V8a.5.5 0 0 1 .8-.4z" fill-rule="nonzero"></path></svg></span> <strong>Insert Media</strong> - you can embed vidos from YouTube or other websites.</li>
		<li><span class="tox-icon tox-tbtn__icon-wrap"><svg width="24" height="24"><path d="M6.2 12.3a1 1 0 0 1 1.4 1.4l-2.1 2a2 2 0 1 0 2.7 2.8l4.8-4.8a1 1 0 0 0 0-1.4 1 1 0 1 1 1.4-1.3 2.9 2.9 0 0 1 0 4L9.6 20a3.9 3.9 0 0 1-5.5-5.5l2-2zm11.6-.6a1 1 0 0 1-1.4-1.4l2-2a2 2 0 1 0-2.6-2.8L11 10.3a1 1 0 0 0 0 1.4A1 1 0 1 1 9.6 13a2.9 2.9 0 0 1 0-4L14.4 4a3.9 3.9 0 0 1 5.5 5.5l-2 2z" fill-rule="nonzero"></path></svg></span> <strong>Insert Link</strong> - select the text you want to link from, then press this button to make it a link.</li>
		<li><span class="tox-icon tox-tbtn__icon-wrap"><svg width="24" height="24"><path d="M15 18h4l1-2v4h-6v-3.3l1.4-1a6 6 0 0 0 1.8-2.9 6.3 6.3 0 0 0-.1-4.1 5.8 5.8 0 0 0-3-3.2c-.6-.3-1.3-.5-2.1-.5a5.1 5.1 0 0 0-3.9 1.8 6.3 6.3 0 0 0-1.3 6 6.2 6.2 0 0 0 1.8 3l1.4.9V20H4v-4l1 2h4v-.5l-2-1L5.4 15A6.5 6.5 0 0 1 4 11c0-1 .2-1.9.6-2.7A7 7 0 0 1 6.3 6C7.1 5.4 8 5 9 4.5c1-.3 2-.5 3.1-.5a8.8 8.8 0 0 1 5.7 2 7 7 0 0 1 1.7 2.3 6 6 0 0 1 .2 4.8c-.2.7-.6 1.3-1 1.9a7.6 7.6 0 0 1-3.6 2.5v.5z" fill-rule="evenodd"></path></svg></span> <strong>Special Character</strong> - use this button to insert a special character.</li>
		<li><span class="tox-icon tox-tbtn__icon-wrap"><svg width="24" height="24"><path d="M15.3 10l-1.2-1.3 2.9-3h-2.3a.9.9 0 1 1 0-1.7H19c.5 0 .9.4.9.9v4.4a.9.9 0 1 1-1.8 0V7l-2.9 3zm0 4l3 3v-2.3a.9.9 0 1 1 1.7 0V19c0 .5-.4.9-.9.9h-4.4a.9.9 0 1 1 0-1.8H17l-3-2.9 1.3-1.2zM10 15.4l-2.9 3h2.3a.9.9 0 1 1 0 1.7H5a.9.9 0 0 1-.9-.9v-4.4a.9.9 0 1 1 1.8 0V17l2.9-3 1.2 1.3zM8.7 10L5.7 7v2.3a.9.9 0 0 1-1.7 0V5c0-.5.4-.9.9-.9h4.4a.9.9 0 0 1 0 1.8H7l3 2.9-1.3 1.2z" fill-rule="nonzero"></path></svg></span> <strong>Fullscreen</strong> - press this button to make the editor fullscreen, press again to go back.
		<li><span class="tox-icon tox-tbtn__icon-wrap"><svg width="24" height="24"><path d="M3.5 12.5c.5.8 1.1 1.6 1.8 2.3 2 2 4.2 3.2 6.7 3.2s4.7-1.2 6.7-3.2a16.2 16.2 0 0 0 2.1-2.8 15.7 15.7 0 0 0-2.1-2.8c-2-2-4.2-3.2-6.7-3.2a9.3 9.3 0 0 0-6.7 3.2A16.2 16.2 0 0 0 3.2 12c0 .2.2.3.3.5zm-2.4-1l.7-1.2L4 7.8C6.2 5.4 8.9 4 12 4c3 0 5.8 1.4 8.1 3.8a18.2 18.2 0 0 1 2.8 3.7v1l-.7 1.2-2.1 2.5c-2.3 2.4-5 3.8-8.1 3.8-3 0-5.8-1.4-8.1-3.8a18.2 18.2 0 0 1-2.8-3.7 1 1 0 0 1 0-1zm12-3.3a2 2 0 1 0 2.7 2.6 4 4 0 1 1-2.6-2.6z" fill-rule="nonzero"></path></svg></span> <strong>Preview</strong> - show a preview of the edited text in a pop-up.</li>
	</ul>
</div>
