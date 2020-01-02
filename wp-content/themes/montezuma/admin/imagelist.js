jQuery(document).ready(function($) {

	/**************************************************************************
	Plupload
	multiple pluploads on the same page, created on the fly 
	when plupload button is hovered
	**************************************************************************/

	// Snyc image order after every drag & drop
	$.fn.reindex = function() {
		var o = $(this[0]),
		re = new RegExp(/\]\[list\]\[[0-9]+\]\[/); //  ][INDEX][
		// Loop through all LI items = all images
		o.find('li').each(function(index) {
			// Loop through all input fields of current image
			$(this).find('input').each(function() {
				// Replace the now possibly outdated index number with its actual index in the list
				var newName = $(this).attr('name').replace( re, '][list][' + index + '][' );
				$(this).attr('name', newName);
			});
		});				
	};


	// On page load, build an array with all image lists and their custom attributes
	$('.used-attr').each( function() {
		var containerID = $(this).parents('.plupcontainer').attr('id');
		var theseAttr = new Array();
		$(this).find('input').each( function() {
			theseAttr.push( $(this).val() )
		});
		usedAttr[containerID] = theseAttr;
	});


	// Add a new attribute field to (all images of) an image list
	$('.add-attr').on('click', 'a', function () {
		var containerID = $(this).parents('.plupcontainer').attr('id');
		var inputField = $(this).prev();
		var newAttr = inputField.val();
		$( inputField ).val('');
						
		if( newAttr == '' ) {
			alert('Please put a data name into the text field, i.e. "alt"');
		} else if( $.inArray( newAttr, usedAttr[containerID] ) != -1 ) {
			alert('Data name "' + newAttr + '" exists!');
		} else {
			var thisList = $('#' + containerID + ' .pluplist');
			var thisName = $('#' + containerID).attr('rel');
			var usedAttrContainer = $('#' + containerID + ' .used-attr');
			
			thisList.find('li').each( function(index) {
				var addThis = '<span class="attr-' + newAttr + '"><br><label>' + newAttr 
				+ ' <input class="regular-text code" type="text" '
				+ 'style="color:blue;width:700px;margin-bottom:5px" name="'
				+ thisName + '[list][' + index + '][' + newAttr + ']" value=""></label></span>';
				$(this).find('.imagelist-inner').append( addThis );
			});
			
			// Add to used attributes list
			var thisAttrInput = '<label><input type="checkbox" name="' 
			+ thisName + '[used-attr][]" checked="checked" value="' + newAttr + '" /> ' 
			+ newAttr + ' </label> '; 
			
			usedAttrContainer.append( thisAttrInput );
			usedAttr[containerID].push( newAttr );
		}
		$('#topsubmit').css('display', 'block');
		return false;
	});

	
	// Remove an attribute from (all images of) an image list
	$('.used-attr').on('click', 'input', function() {
		var containerID = $(this).parents('.plupcontainer').attr('id');
		var toBeRemoved = $(this).val();
		var indextoBeRemoved = usedAttr[containerID].indexOf( toBeRemoved ); // Find the index
		
		if( indextoBeRemoved != -1 ) { 
			usedAttr[containerID].splice( indextoBeRemoved, 1 ); // Remove from JS array
		}

		var thisList = $('#' + containerID + ' .pluplist');
		$( thisList ).find('li .attr-' + toBeRemoved).remove();
		
		// Remove from DOM
		$(this).parent().remove();
		$('#topsubmit').css('display', 'block');
	});

	
	// Display move and close handlers
	$('.pluplist').on( 'mouseover mouseout', '.imagelist-item', function(event) {
		if ( event.type == 'mouseover' ) { 
			$(this).find('.movehandle, .closehandle').css('display', 'block');
		}
		else if ( event.type == 'mouseout' ) { 
			$(this).find('.movehandle, .closehandle').css('display', 'none');
		}
	});	
	
	// .imagelist-item does not work for "live"-like late binding with "on" 
	// because the particular .imagelist-item did not exist (added with ajax). Looks
	// like the 'document' part (first selector, "parent") must have existed.
	// Use document or, for the sake of using the closest parent, ".pluplist"
	$('.pluplist').on('click', '.closehandle', function() {
		var imgInner = $(this).nextAll('.imagelist-inner:first');
		var thisData = 'path=' + imgInner.find('.thisPath').val();
		if ( $(imgInner).find('.thisThumbPath').length > 0 ) {
			thisData = thisData + '&tpath=' + $(imgInner).find('.thisThumbPath').val();
		};
		
		$.ajax({
			type: 'post',
			url: ajaxurl,
			data: thisData + '&action=delete_file&_ajax_nonce=' + plup_nonce,
			success: function(html){ 
				/*
				$("#bfa_ata4_deleted").html( html ).fadeIn("fast").fadeOut(3000); 
				window.location = window.location;
				*/
			}
		}); 

		var thisList = $(this).parent().parent();
		$(this).parent().remove();
		thisList.reindex(); // re-index image list
		$('#topsubmit').css('display', 'block');
		
		return false;
	});
		
	
	// Preview image popup
	$(document).on('click', '.imagelist-image', function() {
		var thisSrc = $(this).attr('href');
		$('<div id="imagelist-popup"></div>')
			.appendTo('body')
			.html('<img title="Click to close" src="' + thisSrc + '" />')
			.css('display', 'inline-block')
			.wrap('<div id="center-container"><div></div></div>');
		$('#center-container').css('display','table');
		return false;
	});

	
	// Close image popup
	$(document).on('click', '#imagelist-popup', function() {
		$('#center-container').remove();
	});

	
	// Image list drag & drop
	$('.pluplist').sortable({
		tolerance: 'pointer'
		,helper: 'clone'
		,handle: '.movehandle'
		,axis: 'y'
		,scrollSpeed: 50
		,scrollSensitivity: 50 
		,opacity: 1
		,start: function(event, ui) { 
			ui.placeholder.html( ui.item.html() );		
			ui.placeholder.css({
				'visibility': 'visible',
			});
			ui.placeholder.find('.movehandle, .closehandle').css('display','none'); 
			ui.helper.css({
				'box-shadow': '0 0 50px #000',
			});
		}
		,stop: function(event, ui) { 
			$(this).reindex();
			$('#topsubmit').css('display', 'block');
		}
	});

	
	$('.plupcontainer').on('click', '.thumb-check, .thumb-crop', function() {
		var containerID = $(this).parents('.plupcontainer').attr('id');
		plupSetThumbs( containerID );
	});
	$('.plupcontainer').on('blur', '.thumb-width, .thumb-height', function(event) {
		var containerID = $(this).parents('.plupcontainer').attr('id');
		plupSetThumbs( containerID );
	});
	$('.plupcontainer').on('click', '.resize-check', function() {
		var containerID = $(this).parents('.plupcontainer').attr('id');
		plupSetResize( containerID );
	});
	$('.plupcontainer').on('blur', '.resize-width, .resize-height, .resize-quality', function(event) {
		var containerID = $(this).parents('.plupcontainer').attr('id');
		plupSetResize( containerID );
	});		


	function createUploader( containerID ) {
		
		var buttonID = containerID + '-button';
		var plupParams = {
			"runtimes":				"html5,silverlight,flash,html4",
			"browse_button":		buttonID,
			"file_data_name":		"file-data",
			"multiple_queues":		true,
			"max_file_size":		"2000kb",
			"url":					ajaxurl,
			"flash_swf_url":		plup_flash_swf_url,
			"silverlight_xap_url":	plup_silverlight_xap_url,
			"filters":				[{"title":"Allowed Files","extensions":"*"}],
			"multipart":			true,
			"urlstream_upload":		true,
			"multipart_params":		{
				"_ajax_nonce":	plup_nonce,
				"action":		"bfa_plup_ajax",
				// A individual upload subdir for this uploader
				"upload_subdir":	containerID
			}
		};

		// Set unqiue button for this uploader
		// plupParams['browse_button'] = buttonID;
		
		// create the uploader and pass the config from above
		uploaders[containerID] = new plupload.Uploader( plupParams );
		
		// Adjust this uploaders settings according to current Upload settings		
		plupSetResize(containerID);
		plupSetThumbs(containerID);
		
		uploaders[containerID].init();
	
		// a file was added in the queue
		uploaders[containerID].bind('FilesAdded', function( up, files ) {
			up.refresh();
			up.start();
		});

		// a file was uploaded 
		uploaders[containerID].bind('FileUploaded', function( up, file, response ) {
			var name = $( '#' + containerID ).attr('rel');
			var image = $.parseJSON(response.response);
			var count = $( '#' + containerID + ' .pluplist li').length;
			
			var additionalAttr = '';
			$.each( usedAttr[containerID], function( index, value ) {
				additionalAttr += '<span class="attr-' + value + '"><br><label>' + value + ' <input name="' 
				+ name + '[list][' + count + '][' + value + ']" class="regular-text code" '
				+ 'style="color:blue;width:700px;margin-bottom:5px" type="text" value="" /></label></span>';
			});

			var imgData = '<input name="' + name + '[list][' + count + '][src]" type="hidden" value="' + image.url + '" />'
				+ '<input class="thisPath" name="' + name + '[list][' + count + '][path]" type="hidden" value="' + image.path + '" />'
				+ '<input name="' + name + '[list][' + count + '][size]" type="hidden" value="' + Math.round( file.size / 1024 ) + '"/>'
				+ '<input name="' + name + '[list][' + count + '][width]" type="hidden" value="' + image.width + '"/>'
				+ '<input name="' + name + '[list][' + count + '][height]" type="hidden" value="' + image.height + '">'		
				+ '<strong>src</strong> ' + image.url 
				+ '&nbsp;&nbsp;&nbsp;&nbsp;<strong>size</strong> ' + Math.round( file.size / 1024 ) 
				+ '&nbsp;&nbsp;&nbsp;&nbsp;<strong>width</strong> ' + image.width 
				+ '&nbsp;&nbsp;&nbsp;&nbsp;<strong>height</strong> ' + image.height;
				
			var thumbData = '';
			if (typeof image.thumb != 'undefined') {
				thumbData = '<input name="' + name + '[list][' + count + '][thumb]" type="hidden" value="' + image.thumb + '" />'
				+ '<input class="thisThumbPath" name="' + name + '[list][' + count + '][tpath]" type="hidden" value="' + image.tpath + '"/>'
				+ '<input name="' + name + '[list][' + count + '][twidth]" type="hidden" value="' + image.twidth + '"/>'
				+ '<input name="' + name + '[list][' + count + '][theight]" type="hidden" value="' + image.theight + '">'		
				+ '<br><strong>thumb</strong> ' + image.thumb 
				+ '&nbsp;&nbsp;&nbsp;&nbsp;<strong>twidth</strong> ' + image.twidth 
				+ '&nbsp;&nbsp;&nbsp;&nbsp;<strong>theight</strong> ' + image.theight;
			}
			
			$( '#' + containerID  + ' .pluplist').append(
				'<li class="imagelist-item"><a class="imagelist-image" title="Click to view full size image" href="' 
				+ image.url + '" style="background-image:url(' + image.url + ')"></a>'
				+ '<div title="Move this image up/down in list" class="movehandle"></div>'
				+ '<div title="Remove this image from list" class="closehandle"></div>'
				+ '<div class="imagelist-inner" style="padding:5px">'
				
				+ imgData
				+ thumbData
				+ additionalAttr
				+ '</div></li>'
			).sortable('refresh');
			$('#topsubmit').css('display', 'block');
			
			/*
			console.log(file);
			console.log(up);
			console.log(response);
			*/
		});
	}

	
	function plupSetResize(containerID) {
		if( typeof uploaders[containerID] != 'undefined' ){
			if( $('#' + containerID + ' .resize-check').is(':checked') ) {
				uploaders[containerID].settings.resize = new Array();
				if( $('#' + containerID + ' .resize-width').val() != '' ) 
					uploaders[containerID].settings.resize.width = $('#' + containerID + ' .resize-width').val();
				if( $('#' + containerID + ' .resize-height').val() != '' ) 
					uploaders[containerID].settings.resize.height = $('#' + containerID + ' .resize-height').val();
				if( $('#' + containerID + ' .resize-quality').val() != '' ) 
					uploaders[containerID].settings.resize.quality = $('#' + containerID + ' .resize-quality').val();				
			} else {
				uploaders[containerID].settings.resize = new Array();
			}
		}
	}		

	
	function plupSetThumbs(containerID) {
		if( typeof uploaders[containerID] != 'undefined' ){
			if( $('#' + containerID + ' .thumb-check').is(':checked') ) {
				if( $('#' + containerID + ' .thumb-width').val() != '' ) 
					uploaders[containerID].settings.multipart_params.thumbwidth = $('#' + containerID + ' .thumb-width').val();
				if( $('#' + containerID + ' .thumb-height').val() != '' ) 
					uploaders[containerID].settings.multipart_params.thumbheight = $('#' + containerID + ' .thumb-height').val();
				if( $('#' + containerID + ' .thumb-crop').is(':checked') ) 
					uploaders[containerID].settings.multipart_params.thumbcrop = 1;	
				if( $('#' + containerID + ' .thumb-width').val() != '' && $('#' + containerID + ' .thumb-height').val() != '' ) 
					uploaders[containerID].settings.multipart_params.thumbcheck = 1;		
			} else {
				uploaders[containerID].settings.multipart_params.thumbcheck = 0;
			}
		}
	}


	// Create new plupload instance, if it doesn't exist, when a plupload button is hovered
	$(document).on('mouseover', '.plupButton', function() {
		var containerID = $(this).parents('.plupcontainer').attr('id');
		if( typeof uploaders[containerID] == 'undefined' ) {
				createUploader( containerID );
		}
	});

});   
