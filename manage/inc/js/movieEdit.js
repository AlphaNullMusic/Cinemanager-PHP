$(document).ready(function() {
 
	// input toggle
	$(function(){
		$('.edit_toggle').change(function() {
			inputId = $(this).attr('data-inputid');
			inputDefault = $(this).attr('data-defaultvalue');
			if ($(this).is(':checked')) {
				// disable input box and apply default value
				$('#'+inputId).val(inputDefault);
				$('#'+inputId).attr('disabled', true);
			} else {
				// enable the input box
				$('#'+inputId).attr('disabled', false);
			}
		});
	});

	// reorder additional images
	/*$('ul.imageSort').sortable({
	  axis: "y",
	  placeholder: "placeholder",
	  update: function(event, ui){
		  var order = $("ul.imageSort").sortable("toArray");
		  $("input[name=additional_images_order]").val(order);
	  }
	}).disableSelection();*/

	// Set up the WYSIWYG editor
	/*
	$('textarea').redactor({
		buttons: ['bold', 'italic', '|', 'unorderedlist', 'orderedlist', 'outdent', 'indent', '|', 'image', 'video', 'file', 'link', '|', 'html'],
		imageUpload: 'includes/redactor/upload.php',
		iframe: true,
    css: ['/includes/redactor.css'],
    minHeight: 200
	});
	*/

});