jQuery(document).ready(function($) {

	/************************************************************************* 
	Image Uploader 
	************************************************************************/
	
	var inputField = ''; // global
	
	$('.upload_wp_image').click(function() {
	 inputField = $(this).prev('input');
	 formfield = inputField.attr('name');
	 // tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');	 
	 tb_show('', 'media-upload.php?type=image&amp;post_id=0&amp;TB_iframe=true');	 
	 // Change "Insert nto post button" text:
	 tbframe_interval = setInterval( function() { 
		$('#TB_iframeContent').contents().find('.savesend .button').val('Add to Montezuma'); 
		}, 2000);
	 return false;
	});
	
	window.send_to_editor = function(html) {
		// other files?
		// imgurl = jQuery(html).attr('href');
		imgurl = $('img',html).attr('src');
		imgwidth = $('img',html).attr('width');
		imgheight = $('img',html).attr('height');
		//$('#upload_image').val(imgurl);
		inputField.val(imgurl);
		inputField.parent()
			.find('.image-here')
			.css('background-image', 'url(' + imgurl + ')'); 
		// To display everything that WP returns:
		// $('...').html(html);
		tb_remove();
		$('#topsubmit').css('display', 'block');
	};
	
	$('.delete_wp_image').click(function() {
		$(this)
			.parent()
			.find('input[type=text]')
			.val('')
			.end()
			.find('.image-here')
			.css('background-image', 'none');
		$('#topsubmit').css('display', 'block');
		return false;
	});
	
});
		